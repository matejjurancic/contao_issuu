<?php

/**
 * Load tl_content language file
 */
$this->loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_issuu_category'] = array
(

    'config' => array
    (
        // dca config settings go here
        'dataContainer'	    => 'Table',
        'ctable'		    => array('tl_issuu'),
		'switchToEdit'      => true
    ),
    
    'list' => array
    (
        /*
         * all settings that are applied to records listing
         * we can define here: sorting, panel layout (filter, search, limit fields), 
         * label format, global operations, operations on each record
         */ 
        'sorting' => array
        (
    		'mode'                    => 1,
    		'fields'                  => array('headline'),
    		'flag'					  => 1,
    		'panelLayout'             => 'limit'
        ),
        'label'	  => array
        (
            'fields'				  => array('headline'),
            'format'				  => '%s'
        ),
        'global_operations' => array
        (
			'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
    	    )
        ),
        'operations' => array
        (
			'edit' => array
            (
    			'label'               => &$GLOBALS['TL_LANG']['tl_issuu_category']['edit'],
    			'href'                => 'table=tl_issuu',
    			'icon'                => 'edit.gif'
            ),
			'copy' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu_category']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
            ),
			'delete' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu_category']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
			'show' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu_category']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
            )
        )
    ),
    
    'palettes' => array
    (
        // palettes settings
		'default'                     => '{title_legend},headline,alias;{description_legend},description'
    ),
    
    'fields' => array
    (
        // fields that are visible in back end form
        'headline' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu_category']['headline'],
			'exclude'                 => false,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
        ),
        'description' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu_category']['description'],
			'exclude'                 => false,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'allowHtml'=>false)
        ),
        'alias' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu_category']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp' => 'alnum',
                                               'unique' => true,
                                               'spaceToUnderscore' => true,
                                               'maxlength' => 128,
                                               'tl_class' => 'w50'),
			'save_callback' => array
            (
                array('tl_issuu_category', 'generateAlias')
            )
        )
    )
    
);


/**
* Class tl_news
*
* Provide miscellaneous methods that are used by the data configuration array.
* @copyright  Aleja soft d.o.o. 2013
* @author     Matej Jurančič <http://www.aleja-soft.si>
* @package    Controller
*/
class tl_issuu_category extends Backend
{
    
    /**
     * Autogenerate an Issuu alias if it has not been set yet
     * 
     * @param mixed  	    $varValue
     * @param DataContainer $dc 
     * @return string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        
        $autoAlias = false;
    
        // Generate alias if there is none
        if (!strlen($varValue))
        {
            $autoAlias = true;
            $varValue = standardize($dc->activeRecord->headline);
        }
    
        $objAlias = $this->Database->prepare("SELECT id FROM tl_issuu_category WHERE alias=?")
                                   ->execute($varValue);
    
        // Check whether the news alias exists
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }
    
        // Add ID to alias
        if ($objAlias->numRows && $autoAlias) {
            $varValue .= '-' . $dc->id;
        }
    
        return $varValue;
        
    }
    
}