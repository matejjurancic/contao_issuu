<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['issuucategory']    = '{title_legend},name,headline,type;{config_legend},issuu_categories';
$GLOBALS['TL_DCA']['tl_module']['palettes']['issuulatest']      = '{title_legend},name,headline,type;{config_legend},issuu_categories,issuu_numberOfItems;{redirect_legend},jumpTo;';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['issuu_categories'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['issuu_categories'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_module_issuu', 'getIssuuCategories'),
	'eval'                    => array('multiple' => true, 'mandatory' => true,
                                       'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['issuu_numberOfItems'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'],
	'default'                 => 3,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory' => true, 'rgxp' => 'digit',
                                       'tl_class' => 'w50')
);

/**
* Class tl_module_issuu
*
* Provide miscellaneous methods that are used by the data configuration array.
* @copyright  Aleja soft d.o.o. 2011-2013
* @author     Matej Jurančič <http://www.aleja-soft.si/>
* @package    Issuu
*/
class tl_module_issuu extends Backend
{
    /**
     * Get all issuu categories and return them as array
     * @return array
     */
    public function getIssuuCategories()
    {
        $arrCats = array();
        $objCats = $this->Database->execute("SELECT id, headline FROM tl_issuu_category ORDER BY headline");

        while ($objCats->next()) {
            $arrCats[$objCats->id] = $objCats->headline;
        }

        return $arrCats;        
    }    
}