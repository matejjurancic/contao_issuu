<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Aleja soft d.o.o. 2011 - 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @package    Issuu
 * @license    LGPL
 * @filesource
 */


 /**
 * Class ModuleIssuuCategory
 *
 * Front end module "issuu".
 * @copyright  Aleja soft d.o.o. 2011 - 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @license    LGPL
 */
class ModuleIssuuCategory extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_issuucategory';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{	    
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ISSUU CATEGORY ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();		
	}
	
	
	/**
	 * Generate module
     * @return void
	 */
	protected function compile()
	{	    
        $username = $GLOBALS['TL_CONFIG']['issuu_username'];
        
		$arrPublications = array();

		// Get category ID
		$issuuCats = ($this->issuu_categories) ?
                        deserialize($this->issuu_categories) : array(1);
		
		// Fetch data from the database
		$objPublications = $this->Database->prepare(
			"SELECT headline, alias, file 
			 FROM tl_issuu 
			 WHERE pid IN (" . implode(',', array_map('intval', $issuuCats)) . ")
             ORDER BY sorting,headline")
                                          ->execute();
		
		// Put CDs of current category into array
		while ($objPublications->next())
		{
			$arrPublications[] = array
			(
				'title'       => $objPublications->headline,
				'alias'       => $objPublications->alias,
                'url'         => 'http://issuu.com/' . $username . '/docs/'
                               . $objPublications->alias
                               . '?mode=embed&layout=http%3A%2F%2Fskin.issuu.com%2Fv%2Flighticons%2Flayout.xml&showFlipBtn=true',
				'file'        => $objPublications->file
			);
		}
		
		// Assign data to the template
		$this->Template->publications = $arrPublications;
		
		// translations
		$this->loadLanguageFile('tl_issuu');
		$this->Template->lbl_view = $GLOBALS['TL_LANG']['tl_issuu']['viewText'];
		$this->Template->lbl_download = $GLOBALS['TL_LANG']['tl_issuu']['download'];		
	}
}