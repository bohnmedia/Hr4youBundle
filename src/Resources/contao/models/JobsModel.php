<?php

namespace BohnMedia\Hr4youBundle\Model;

use Contao\Model;
use Contao\Date;

class JobsModel extends Model
{
	protected static $strTable = 'tl_hr4you_jobs';
	
	public function findPublishedByPids($arrPids, $arrOptions=[])
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}
		
		$t = static::$strTable;
		$time = new Date();
		
		$arrColumns = [
			$t . ".pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")",
			"(" . $t . ".jobPublishingDateFrom='' OR " . $t . ".jobPublishingDateFrom<='" . $time->dayBegin . "')",
			"(" . $t . ".jobPublishingDateUntil='' OR " . $t . ".jobPublishingDateUntil>='" . $time->dayEnd . "')",
			$t . ".published='1'"
		];
		
		return static::findBy($arrColumns, null, $arrOptions);		
	}
}