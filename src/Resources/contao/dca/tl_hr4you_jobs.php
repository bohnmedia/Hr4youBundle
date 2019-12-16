<?php

$GLOBALS['TL_DCA']['tl_hr4you_jobs'] = [
	'config' => [
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_hr4you_feeds',
		'enableVersioning'            => true,
		'sql' => [
			'keys' => [
				'id' => 'primary',
				'alias' => 'index',
				'pid,jobId' => 'unique'
			]
		]
	],
	'list' => [
		'sorting' => [
			'mode'                    => 4,
			'flag'                    => 12,
			'panelLayout'             => 'sort,filter;search,limit',
			'fields'                  => ['jobTitle'],
			'headerFields'			  => ['title','url','interval'],
			'child_record_callback'   => ['tl_hr4you_jobs', 'listJobs'],
			'disableGrouping'		  => true
		],
		'label' => [
			'fields'                  => ['jobTitle'],
			'showColumns'             => true
		],
		'global_operations' => [
			'all' => [
				'label'				  => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			]
		],
		'operations' => [
			'edit' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			],
			'copy' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			],
			'cut' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			],
			'delete' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			],
			'toggle' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_hr4you_jobs', 'toggleIcon')
			],
			'show' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			]
		]
	],
	'palettes' => [
		'__selector__' => ['setLongLat'],
		'default' => '{title_legend},jobTitle,alias,jobFulltext;{description_legend:hide},jobCompanyProfile,jobSummary,jobResponsibilities,jobRequirements,jobBenefits,jobOutro;{details_legend},jobWorkplace,jobWorkplaceZipcode,jobRegion,jobIndustry,jobCategory,jobEmploymentType;{contact_legend},contactCompany,contactGender,contactTitle,contactFirstname,contactLastname,contactDepartment,contactLocation,contactZipcode,contactPhone,contactEmail,contactWebsite;{coordinates_legend:hide},setLongLat;{links_legend:hide},jobOffer,applicationForm;{publish_legend},published,jobPublishingDateFrom,jobPublishingDateUntil;{import_legend:hide},jobId,disableUpdate'
	],
	'subpalettes' => [
		'setLongLat' => 'jobLongitude,jobLatitude'
	],
	'fields' => [
		'id' => [
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'pid' => array
		(
			'foreignKey'			  => 'tl_hr4you_feeds.title',
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => ['type'=>'belongsTo', 'load'=>'lazy']
		),
		'tstamp' => [
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		],
		'jobId' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobId'],
			'inputType'               => 'text',
			'eval'                    => ['tl_class'=>'w50', 'rgxp'=>'natural', 'unique'=>true, 'readonly'=>true],
			'sql'                     => "int(10) unsigned default NULL"
		],
		'jobTitle' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128),
			'save_callback' => array
			(
				array('tl_hr4you_jobs', 'generateAlias')
			),
			'sql'                     => "varchar(255) BINARY NOT NULL default ''"
		),
		'jobFulltext' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobFulltext'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class'=>'clr'],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobCompanyProfile' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobCompanyProfile'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobSummary' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobSummary'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobResponsibilities' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobResponsibilities'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobRequirements' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobRequirements'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobBenefits' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobBenefits'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobOutro' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobOutro'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NOT NULL default ''"
		],
		'jobWorkplace' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobWorkplace'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'jobWorkplaceZipcode' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobWorkplaceZipcode'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength' => 5, 'tl_class'=>'w50', 'rgxp'=>'natural'],
			'sql'                     => "INT(5) unsigned NOT NULL default 0"
		],
		'jobRegion' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobRegion'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'jobIndustry' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobIndustry'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'jobCategory' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobCategory'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'jobEmploymentType' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobEmploymentType'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'languageCode' => [
			'search'                  => true,
			'inputType'               => 'text',
			'sql'                     => "varchar(10) NOT NULL default ''"
		],
		'contactCompany' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactCompany'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactGender' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactGender'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options'                 => ['m'=>'Herr', 'f'=>'Frau'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(1) NOT NULL default ''"
		],
		'contactTitle' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactFirstname' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactFirstname'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactLastname' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactLastname'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactDepartment' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactDepartment'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactPhone' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactPhone'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactEmail' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactEmail'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'email'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactWebsite' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactWebsite'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'rgxp'=>'url', 'tl_class'=>'clr'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactLocation' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactLocation'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'contactZipcode' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['contactZipcode'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength' => 5, 'tl_class'=>'w50', 'rgxp'=>'natural'],
			'sql'                     => "INT(5) unsigned NOT NULL default 0"
		],
		'jobOffer' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobOffer'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'rgxp'=>'url'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'disableUpdate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['disableUpdate'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'jobPublishingDateFrom' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobPublishingDateFrom'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['tl_class'=>'clr w50 wizard', 'rgxp'=>'date', 'datepicker'=>true],
			'sql'                     => "varchar(10) NOT NULL default ''"
		],
		'jobPublishingDateUntil' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobPublishingDateUntil'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['tl_class'=>'w50 wizard', 'rgxp'=>'date', 'datepicker'=>true],
			'sql'                     => "varchar(10) NOT NULL default ''"
		],
		'applicationForm' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['applicationForm'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'rgxp'=>'url'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'setLongLat' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['setLongLat'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true, 'tl_class' => 'clr'],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'jobLongitude' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobLongitude'],
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'],
			'sql'                     => "decimal(11, 8) default NULL"
		],
		'jobLatitude' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_jobs']['jobLatitude'],
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'],
			'sql'                     => "decimal(10, 8) default NULL"
		]
	]
];

class tl_hr4you_jobs extends Contao\Backend
{	
	public function __construct()
	{
		parent::__construct();
		$this->import('Contao\BackendUser', 'User');
	}

	public function listJobs($arrRow) {
		return '<div class="tl_content_left">' . $arrRow['jobTitle'] . '</div>';
	}
	
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (Contao\Input::get('tid'))
		{
			$this->toggleVisibility(Contao\Input::get('tid'), (Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}
		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_hr4you_jobs::published', 'alexf'))
		{
			return '';
		}
		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);
		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}
		return '<a href="' . $this->addToUrl($href) . '" title="' . Contao\StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
	}

	public function toggleVisibility($intId, $blnVisible, Contao\DataContainer $dc=null)
	{
		if (!$this->User->hasAccess('tl_hr4you_jobs::published', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish news item ID ' . $intId . '.');
		}
		$time = time();
		$this->Database->prepare("UPDATE tl_hr4you_jobs SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);
	}
	
	public function generateAlias($varValue, Contao\DataContainer $dc)
	{
		$autoAlias = false;

		// Generiere einen Alias wenn es keinen gibt
		if ($varValue == '') {
			$autoAlias = true;
			$varValue = StringUtil::generateAlias($dc->activeRecord->jobTitle);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_hr4you_jobs WHERE alias=?")->execute($varValue);

		// Überprüfe ob der Alias bereits existiert.
		if ($objAlias->numRows > 1 && !$autoAlias) {
			throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// wenn alias bereits existiert, füge eine ID hinzu.
		if ($objAlias->numRows && $autoAlias) {
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}