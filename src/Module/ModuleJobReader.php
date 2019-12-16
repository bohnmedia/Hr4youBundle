<?php

namespace BohnMedia\Hr4youBundle\Module;

use BohnMedia\Hr4youBundle\Model\JobsModel;
use Contao\BackendTemplate;
use Contao\Config;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Environment;
use Contao\Input;
use Patchwork\Utf8;

class ModuleJobReader extends ModuleJob
{
    /**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_jobreader';
	
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['jobreader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}
		
		// Set auto_item to avoid 404
		if (!isset($_GET['item']) && Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			Input::setGet('item', Input::get('auto_item'));
		}

		return parent::generate();
	}
	
	protected function compile()
	{
		// Get alias from url
		$alias = Input::get('auto_item');

		// Search job by alias
		$objJob = JobsModel::findOneByAlias($alias);

		// Output 404 if no job was found
		if (!$objJob)
		{
			throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
		}

		// Generate Template
		$this->Template->jobs = $this->parseJob($objJob);
	}
}

?>