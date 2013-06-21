<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Issuu Component for Contao CMS
 * Copyright (C) 2011 Aleja soft d.o.o.
 *
 * PHP version 5
 * @copyright  Aleja soft d.o.o. 2011 - 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @package    Issuu
 * @license    LGPL
 * @filesource
 */

/*
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'issuu' => array
    (
		'tables'     => array('tl_issuu_category', 'tl_issuu'),
		'icon'       => 'system/modules/issuu/html/issuu.png',
		'import'	 => array('IssuuImport', 'importIssuu')
    )
));

/*
 * Front end modules
 */ 
array_insert($GLOBALS['FE_MOD']['miscellaneous'], 0, array
(
	'issuucategory' => 'ModuleIssuuCategory',
    'issuulatest'	=> 'ModuleIssuuLatest'
));