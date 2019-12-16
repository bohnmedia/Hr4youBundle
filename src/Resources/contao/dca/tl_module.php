<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['joblist'] = '{title_legend},name,headline,type;{config_legend},jobFeeds,perPage;{template_legend:hide},jobTpl,customTpl;{expert_legend:hide},cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['jobreader'] = '{title_legend},name,type;{config_legend},jobFeeds;{template_legend:hide},jobTpl,customTpl;{expert_legend:hide},cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['jobFeeds'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jobFeeds'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_hr4you', 'getJobFeeds'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jobTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jobTpl'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Contao\Controller::getTemplateGroup('jobs_');
	},
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

class tl_module_hr4you extends Contao\Backend
{
	public function getJobFeeds()
	{
		$arrFeeds = array();
		$objFeeds = $this->Database->execute("SELECT id, title FROM tl_hr4you_feeds ORDER BY title");

		while ($objFeeds->next())
		{
			$arrFeeds[$objFeeds->id] = $objFeeds->title;
		}

		return $arrFeeds;
	}
}