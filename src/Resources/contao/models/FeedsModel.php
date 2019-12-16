<?php

namespace BohnMedia\Hr4youBundle\Model;

use Contao\Database;
use Contao\System;
use Contao\Model;
use Contao\StringUtil;

class FeedsModel extends Model
{
	protected static $strTable = 'tl_hr4you_feeds';
	
	private static function findUpdatable()
	{
		return static::find([
			"column" => ["(tl_hr4you_feeds.interval + tl_hr4you_feeds.lastImport) <= ?"],
			"value" => [time()]
		]);
	}
	
	public static function updateAll()
	{
		$objFeeds = static::findUpdatable();
		
		if($objFeeds)
		{
			foreach($objFeeds as $objFeed)
			{
				self::updateById($objFeed->id);
			}
		}
	}

	public static function updateById($id)
	{
		$database = Database::getInstance();
		$feedModel = static::findByPk($id);

		// Load content from url
		$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$content = file_get_contents($feedModel->url, false, $context);
		preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $match);
		
		// Check status code
		if ($match[1] !== "200")
		{
			return System::log('Response status "' . $http_response_header[0] . '" on "' . $feedModel->url . '"', 'HR4YOU', 'ERROR');
		}
		
		// Check if XML contains <HR4YOU_JOBS>
		if (!preg_match('/\s*<\?[^>]*\?>\s*<HR4YOU_JOBS>.*<\/HR4YOU_JOBS>\s*/s', $content))
		{
			return System::log('Check of file "' . $feedModel->url . '" failed', 'HR4YOU', 'ERROR');
		}
		
		// Check if XML could be parsed
		$xml = simplexml_load_string($content);
		if (!$xml)
		{
			return System::log('Could not parse "' . $feedModel->url . '"', 'HR4YOU', 'ERROR');
		}

		// Time before import
		$startTime = time();

		// Parse jobs
		foreach ($xml->job as $objJob)
		{
			// Generate job array
			$arrJob = array_map("strval", get_object_vars($objJob));
			$arrJob["pid"] = $id;
						
			// Get jobs model
			$jobModel = JobsModel::findOneBy('jobId', $arrJob["jobId"]) ?? new JobsModel();
			$firstImport = $jobModel->id ? false : true;
			
			// Skip if disableUpdate is enabled
			if ($jobModel->disableUpdate)
			{
				continue;
			}
						
			// Modify fields
			foreach ($arrJob as $key => $value)
			{
				if ($key === "jobPublishingDateUntil" || $key === "jobPublishingDateFrom")
				{
					$jobModel->{$key} = $value ? strtotime($value) : null;
				}
				else
				{
					$jobModel->{$key} = $value;
				}
			}
						
			// Set active on first import
			if ($firstImport)
			{
				$jobModel->published = "1";
			}
			
			// Save job
			$jobModel->tstamp = time();
			$jobModel->save();
			
			// Generate alias after firstImport
			if ($firstImport)
			{
				$alias = StringUtil::generateAlias($jobModel->jobTitle);
				while($database->prepare("SELECT id FROM tl_hr4you_jobs WHERE alias=?")->execute($alias)->numRows)
				{
					$alias .= "-" . $jobModel->id;
				}
				$jobModel->alias = $alias;
				$jobModel->save();
			}
		}
		
		// If not incremental delete old entries
		if (!$feedModel->incremental)
		{
			$database->prepare("DELETE FROM tl_hr4you_jobs WHERE pid=? AND tstamp<? AND jobId IS NOT NULL AND disableUpdate=''")->execute($id, $startTime);
		}
		
		// Save feed
		$feedModel->tstamp = time();
		$feedModel->lastImport = time();
		$feedModel->save();
			
		System::log('Feed "' . $feedModel->title . '" imported (ID: ' . $feedModel->id . ')', 'HR4YOU', 'GENERAL');
	}
}