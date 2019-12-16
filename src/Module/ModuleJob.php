<?php

namespace BohnMedia\Hr4youBundle\Module;

use Contao\Module;
use Contao\FrontendTemplate;
use Contao\PageModel;

abstract class ModuleJob extends Module
{
	private static $arrUrlCache = array();
	
	protected function generateUrl($objJob)
	{
		$arrUrlKey = "id_" . $objJob->pid;
		
		if(!self::$arrUrlCache[$arrUrlKey])
		{
			self::$arrUrlCache[$arrUrlKey] = PageModel::findByPk($objJob->getRelated('pid')->jumpTo);
		}
		
		return self::$arrUrlCache[$arrUrlKey]->getFrontendUrl("/" . $objJob->alias);
	}
	
	protected function parseJob($objJob, $strClass='')
	{
		$objTemplate = new FrontendTemplate($this->jobTpl ?: 'jobs_all');
		$objTemplate->setData($objJob->row());
		$objTemplate->href = $this->generateUrl($objJob);
		
		if ($objJob->cssClass != '')
		{
			$strClass = ' ' . $objJob->cssClass . $strClass;
		}
		
		$objTemplate->class = $strClass;
		
		return $objTemplate->parse();
	}
	
	protected function parseJobs($objJobs)
	{
		$limit = $objJobs->count();

		if ($limit < 1)
		{
			return array();
		}
		
		$count = 0;
		$arrJobs = array();
		
		$strClass = (++$count == 1) ? ' first' : '';
		$strClass .= ($count == $limit) ? ' last' : '';
		$strClass .= (($count % 2) == 0) ? ' odd' : ' even';
		
		while ($objJobs->next())
		{
			$objJob = $objJobs->current();
			$arrJobs[] = $this->parseJob($objJob, $strClass);
		}
		
		return $arrJobs;
		
	}

}