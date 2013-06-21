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
 * Class ModuleIssuuLatest
 *
 * Front end module "issuu".
 * @copyright  Aleja soft d.o.o. 2011 - 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @license    LGPL
 */
class ModuleIssuuLatest extends Module
{
    /**
     * Folder with publications thumbnails
     * @var string
     */
    private $_thumbsFolder = '';
    
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_issuulatest';
    
    
    /**
     * Get thumbnail for Issuu publication
     *  either from Issuu page or local cache
     * 
     * @param  Std_Object     $publication
     * @return string|boolean
     */
    private function _getThumbnail($publication)
    {
        $thumbPath = $this->_thumbsFolder . $publication->documentId . '.jpg';
        if (!file_exists($thumbPath)) {
            $thumbUrl = 'http://image.issuu.com/' . $publication->documentId . '/jpg/page_1_thumb_small.jpg';
            $thumb = imagecreatefromjpeg($thumbUrl);
            if (!imagejpeg($thumb, $thumbPath)) {
                return false;
            }
        }
        
        return $thumbPath;
    }
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{	    
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ISSUU LATEST ###';
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
	 */
	protected function compile()
	{	    
		// Get category ID and number of items
		$issuuCats = ($this->issuu_categories) ?
                deserialize($this->issuu_categories) : array(1);
		$issuuItems = (int) $this->issuu_numberOfItems > 0 ?
                (int) $this->issuu_numberOfItems : 3;
        
        // set thumbnails folder
        $this->_thumbsFolder = $GLOBALS['TL_CONFIG']['issuu_thumbs_folder'];
        $username = $GLOBALS['TL_CONFIG']['issuu_username'];
        
        $arrPublications = array();
        $numItems = 0;
        foreach ($issuuCats as $category) {
            // Fetch data from the database
            $objPublications = $this->Database->prepare(
                "SELECT tstamp, headline, alias, documentId
                 FROM tl_issuu 
                 WHERE pid=?
                 ORDER BY sorting
                 LIMIT 0,?")
                                              ->execute(array($category,
                                                              $issuuItems-$numItems));

            while ($objPublications->next() && $numItems < 3) {
                
                $arrPublications[] = array (
                    'datetime'    => date('Y-m-d\TH:i:sP', $objPublications->tstamp),
                    'date'        => date($GLOBALS['TL_CONFIG']['dateFormat'], $objPublications->tstamp),
                    'tstamp'      => $objPublications->tstamp,
                    'title'       => $objPublications->headline,
                    'alias'       => $objPublications->alias,
                    'docId'       => $objPublications->documentId,
                    'url'         => 'http://issuu.com/' . $username . '/docs/'
                                   . $objPublications->alias
                                   . '?mode=embed[&]layout=http%3A%2F%2Fskin.issuu.com%2Fv%2Fdarkicons%2Flayout.xml[&]showFlipBtn=true',
                    'thumb'       => $this->_getThumbnail($objPublications)
                );
                $numItems++;
            }
            
            if ($numItems >= 3) {
                break;
            }
        }
		
        $this->Template->items = $arrPublications;
        
        $this->loadLanguageFile('tl_issuu');
        $this->Template->more  = $GLOBALS['TL_LANG']['tl_issuu']['more'];
	}
}
