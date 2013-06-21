<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Add options to enter Issuu API Key and API Secret
 * 
 * Issuu Component for Contao CMS
 * Copyright (C) 2013 Aleja soft d.o.o.
 *
 * PHP version 5
 * @copyright  Aleja soft d.o.o. 2011 - 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @package    Issuu
 * @license    LGPL
 * @filesource
 */

/*
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{issuu_settings_legend:hide},issuu_api_key,issuu_api_secret,issuu_username,issuu_tag_universal,issuu_thumbs_folder;';


/**
 * Fields
 */
array_insert($GLOBALS['TL_DCA']['tl_settings']['fields'], 1, array
(
		'issuu_api_key' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['issuu_api_key'],
			'inputType'             => 'text',
			'eval'                  => array('submitOnChange' => false,
                                             'tl_class'       => 'w50',
                                             'nospace'        => true)
		),
		'issuu_api_secret' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['issuu_api_secret'],
			'inputType'             => 'text',
			'eval'                  => array('submitOnChange' => false,
                                             'tl_class'       => 'w50',
                                             'nospace'        => true)
		),
		'issuu_username' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['issuu_username'],
			'inputType'             => 'text',
			'eval'                  => array('submitOnChange' => false,
                                             'tl_class'       => 'w50',
                                             'nospace'        => true)
		),
		'issuu_tag_universal' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['issuu_tag_universal'],
			'inputType'             => 'text',
			'eval'                  => array('submitOnChange' => false,
                                             'tl_class'       => 'w50')
		),
        'issuu_thumbs_folder' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['issuu_thumbs_folder'],
            'inputType'             => 'fileTree',
            'eval'					=> array('fieldType'        => 'radio',
                                             'files'            => false,
                                             'trailingSlash'    => true,
                                             'submitOnChange'   => false)
        )
));