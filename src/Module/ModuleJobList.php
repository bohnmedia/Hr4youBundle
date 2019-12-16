<?php

namespace BohnMedia\Hr4youBundle\Module;

use Contao\BackendTemplate;
use Contao\StringUtil;
use Patchwork\Utf8;
use BohnMedia\Hr4youBundle\Model\JobsModel;

class ModuleJobList extends ModuleJob
{
    /**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_joblist';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['joblist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}

		return parent::generate();
	}

	protected function compile()
	{
		$arrOptions = [];
		
		if ($this->perPage > 0)
		{
			$arrOptions["limit"] = (int)$this->perPage;
		}
		
		if (!$this->jobFeeds)
		{
			return '';
		}
		
		$jobFeeds = StringUtil::deserialize($this->jobFeeds);
		$objJobs = JobsModel::findPublishedByPids($jobFeeds, $arrOptions);
		
		if ($objJobs !== null)
		{
			$this->Template->jobs = $this->parseJobs($objJobs);
		}
	}
}

?>