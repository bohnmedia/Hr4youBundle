<?php

$GLOBALS['TL_MODELS']['tl_hr4you_feeds'] = 'BohnMedia\Hr4youBundle\Model\FeedsModel';
$GLOBALS['TL_MODELS']['tl_hr4you_jobs'] = 'BohnMedia\Hr4youBundle\Model\JobsModel';

$GLOBALS['BE_MOD'] = array_slice($GLOBALS['BE_MOD'], 0, 1, true) + array('hr4you' => array()) + array_slice($GLOBALS['BE_MOD'], 1, count($GLOBALS['BE_MOD']), true);
$GLOBALS['BE_MOD']['hr4you']['jobs'] = array(
	'tables'      => ['tl_hr4you_feeds','tl_hr4you_jobs'],
	'table'       => ['Contao\TableWizard', 'importTable'],
	'list'        => ['Contao\ListWizard', 'importList']
);

array_insert($GLOBALS['FE_MOD'], 2, array
(
	'hr4you' => array
	(
		'joblist'    => 'BohnMedia\Hr4youBundle\Module\ModuleJobList',
		'jobreader'  => 'BohnMedia\Hr4youBundle\Module\ModuleJobReader'
	)
));

$GLOBALS['TL_CRON']['minutely'][] = ['BohnMedia\Hr4youBundle\Model\FeedsModel', 'updateAll'];

if ('BE' === TL_MODE) {
    $GLOBALS['TL_CSS'][] = 'bundles/hr4you/css/backend.css|static';
}