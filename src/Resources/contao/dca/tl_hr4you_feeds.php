<?php

$GLOBALS['TL_DCA']['tl_hr4you_feeds'] = [
	'config' => [
		'dataContainer'               => 'Table',
		'ctable'                      => ['tl_hr4you_jobs'],
		'enableVersioning'            => true,
		'sql' => [
			'keys' => [
				'id' => 'primary'
			]
		]
	],
	'list' => [
		'sorting' => [
			'mode'                    =>2,
			'fields'                  => ['title','lastImport'],
			'flag'                    => 1
		],
		'label' => [
			'fields'                  => ['title','lastImport','jobCount'],
			'showColumns'             => true,
			'label_callback'          => ['tl_hr4you_feeds','addJobCount']
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
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['edit'],
				'href'                => 'table=tl_hr4you_jobs',
				'icon'                => 'edit.svg'
			],
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.svg'
			),
			'delete' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			],
			'toggle' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['toggle'],
				'icon'                => 'system/modules/hr4you/assets/img/pause.svg',
				'button_callback'     => array('tl_hr4you_feeds', 'toggleIcon')
			],
			'show' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			]
		]
	],
	'palettes' => [
		'__selector__' => ['active'],
		'default' => '{title_legend},title,jumpTo;{import_legend},active'
	],
	'subpalettes' => [
		'active' => 'url,interval,incremental'
	],
	'fields' => [
		'id' => [
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'tstamp' => [
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		],
		'lastImport' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['lastImport'],
			'flag'                    => 6,
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		],
		'title' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'active' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['active'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true, 'tl_class' => 'w50'],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'incremental' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['incremental'],
			'inputType'               => 'checkbox',
			'eval'                    => ['tl_class' => 'w50'],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'url' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'url', 'tl_class' => 'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'interval' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['interval'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options'                 => [
				'60' => '1 Minute',
				'180' => '3 Minuten',
				'300' => '5 Minuten',
				'600' => '10 Minuten',
				'1800' => '30 Minuten',
				'3600' => '1 Stunde',
				'10800‬' => '3 Stunden',
				'11600‬' => '6 Stunden',
				'43200‬' => '12 Stunden',
				'86400' => '1 Tag'
			],
			'default'                 => '600',
			'eval'                    => ['tl_class'=>'w50'],
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		],
		'jobCount' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_hr4you_feeds']['jobCount']
		]
	]
];

class tl_hr4you_feeds extends Contao\Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Contao\BackendUser', 'User');
	}

	public function addJobCount($row, $label, $dc, $args)
	{
		$args[2] = \BohnMedia\Hr4youBundle\Model\JobsModel::countBy('pid',$row['id']);
		return $args;
	}

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if ($this->User->hasAccess('tl_hr4you_feeds::active', 'alexf'))
		{
			$tid = Contao\Input::get('tid');
			if ($tid)
			{
				$objFeed = \BohnMedia\Hr4youBundle\Model\FeedsModel::findByPk($tid);
				$objFeed->active = Contao\Input::get('state') ? '1' : '';
				$objFeed->save();
				$this->redirect($this->getReferer());
			}
		}
		else
		{
			return '';
		}
		$href .= 'tid=' . $row['id'] . '&amp;state=' . ($row['active'] ? '' : 1);
		if (!$row['active'])
		{
			$icon = 'system/modules/hr4you/assets/img/play.svg';
		}
		return '<a href="' . $this->addToUrl($href) . '" title="' . Contao\StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml($icon, $label, 'data-state="' . ($row['active'] ? 1 : 0) . '"') . '</a> ';
	}
}