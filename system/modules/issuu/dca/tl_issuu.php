<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

$this->loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_issuu'] = array
(
	'config' => array
    (
        // dca config settings go here
        'dataContainer'	    => 'Table',
        'ptable'		    => 'tl_issuu_category',
        'validFileTypes'	=> 'pdf,doc,docx,odt',
		'onsubmit_callback' => array
        (
            array('tl_issuu', 'postToIssuu')
        ),
        'onload_callback' => array 
        (
            array('tl_issuu', 'loadCategories')
        )
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
			'mode'                    => 4,
            'fields'                  => array('sorting', 'headline'),
            'flag'                    => 1,
            'headerFields'            => array('headline', 'description'),
            'panelLayout'             => 'search,limit',
            'child_record_callback'   => array('tl_issuu', 'listPublications')
        ),
        'global_operations' => array
        (
			'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'import' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_issuu']['import'],
				'href'                => 'key=import',
				'class'               => 'header_import',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
			'edit' => array
            (
    			'label'               => &$GLOBALS['TL_LANG']['tl_issuu']['edit'],
    			'href'                => 'act=edit',
    			'icon'                => 'edit.gif'
            ),
			'copy' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_page']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
			'delete' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
			'show' => array
            (
				'label'               => &$GLOBALS['TL_LANG']['tl_issuu']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
            )
        )
    ),

    'palettes' => array
    (
        // palettes settings
  		'default'                     => '{title_legend},headline,alias;{description_legend},description,keywords,language,category,doctype;{file_legend},file,access'
    ),

    'fields' => array
    (
        // fields that are visible in back end form
        'headline' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['headline'],
			'sorting'                 => true,
			'exclude'                 => false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50')
        ),
        'alias' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['alias'],
			'search'                  => false,
			'exclude'				  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
            (
                array('tl_issuu', 'generateAlias')
            )
        ),
        'documentId' => array
        (
            'label'					  => &$GLOBALS['TL_LANG']['tl_issuu']['documentId'],
            'search'				  => false,
			'exclude'                 => true,
            'inputType'				  => 'text',
            'filter'				  => false,
            'eval'					  => array('hideInput' => true, 'unique' => true, 'readonly' => true)
        ),
        'description' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['description'],
			'search'                  => true,
			'exclude'                 => false,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'allowHtml'=>false)
        ),
        'keywords' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['keywords'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class' => 'w50')
        ),
        'language' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['language'],
			'search'                  => true,
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'				  => array
			(
				'en' => 'English',
				'sl' => 'Slovenščina'
			),
			'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'category' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['category'],
			'search'                  => false,
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'				  => array
			(
				'000000' => &$GLOBALS['TL_LANG']['tl_issuu']['unknown'],
				'002000' => &$GLOBALS['TL_LANG']['tl_issuu']['business_marketing'],
				'007000' => &$GLOBALS['TL_LANG']['tl_issuu']['knowledge_resources'],
				'016000' => &$GLOBALS['TL_LANG']['tl_issuu']['other']
			),
			'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
		'doctype' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_issuu']['type'],
			'search'                  => false,
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'				  => array
			(
				'000000' => &$GLOBALS['TL_LANG']['tl_issuu']['unknown'],
				'003000' => &$GLOBALS['TL_LANG']['tl_issuu']['catalog'],
				'006000' => &$GLOBALS['TL_LANG']['tl_issuu']['manual_resource'],
				'007000' => &$GLOBALS['TL_LANG']['tl_issuu']['newsletter'],
				'012000' => &$GLOBALS['TL_LANG']['tl_issuu']['report']
			),
			'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'file' => array
        (
            'label'					  => &$GLOBALS['TL_LANG']['tl_issuu']['file'],
            'search'				  => false,
			'exclude'                 => true,
            'inputType'				  => 'fileTree',
            'eval'					  => array('fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => 'pdf,doc,docx,odt', 'mandatory' => true)
        ),
        'access' => array
        (
            'label'					  => &$GLOBALS['TL_LANG']['tl_issuu']['access'],
            'search'				  => false,
			'exclude'                 => true,
            'inputType'				  => 'select',
            'options'				  => array
            (
                'private' => &$GLOBALS['TL_LANG']['tl_issuu']['private'],
                'public'  => &$GLOBALS['TL_LANG']['tl_issuu']['public']
            )
        )
    )

);


/**
 * Class tl_issuu
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Aleja soft d.o.o. 2013
 * @author     Matej Jurančič <http://www.aleja-soft.si>
 * @package    Controller
 */
class tl_issuu extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
    
    /**
     * Autogenerate an Issuu alias if it has not been set yet
     * @param mixed
     * @param object
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

        $objAlias = $this->Database->prepare("SELECT id FROM tl_issuu WHERE alias=?")
                                   ->execute($varValue);

        // Check whether the issuu alias already existed
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if ($objAlias->numRows && $autoAlias) {
            $varValue .= '-' . $dc->id;
        }

        return $varValue;        
    }

    /**
     * List publications
     * 
     * @param array $arrRow
     * @return string
     */
    public function listPublications($arrRow)
    {
        return 
        '<div>
        	<p><strong>' . $arrRow['headline'] . '</strong><br />' . $arrRow['description'] . '</p>'
        . '</div>' . "\n";
    }

    /**
     * Adds or updates publication at Issuu
     * 
     * @param  DataContainer $dc 
     * @return void
     */
    public function postToIssuu(DataContainer $dc)
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord) {
            return;
        }
        
        $apiKey      = $GLOBALS['TL_CONFIG']['issuu_api_key'];
        $apiSecret   = $GLOBALS['TL_CONFIG']['issuu_api_secret'];
        $baseUrl     = 'http://' . $_SERVER['HTTP_HOST'] . $GLOBALS['TL_CONFIG']['websitePath'] . '/';
        
        $access      = $dc->activeRecord->access;
        $format      = 'json';
        
        $alias       = $dc->activeRecord->alias;
        $title       = $dc->activeRecord->headline;
        // prepend universal tag
        $tags        = $GLOBALS['TL_CONFIG']['issuu_tag_universal'] . ', ' . $dc->activeRecord->keywords;
        $description = $dc->activeRecord->description;
        $language    = $dc->activeRecord->language;
        $category    = $dc->activeRecord->category;
        $type        = $dc->activeRecord->doctype;
        $documentId  = $dc->activeRecord->documentId;

        // prepare data
        $data = array(
            'apiKey'	    => $apiKey,
            'name'			=> $alias,
        	'title'			=> $title,
        	'tags'			=> $tags,
        	'description'	=> $description,
        	'language'		=> $language,
            'access'        => $access,
        	'category'		=> $category,
        	'type'			=> $type,
        	'format'		=> $format
        );

        if ($dc->activeRecord->documentId && strlen($documentId) > 0) {            
            // update existing            
            $data['action'] = 'issuu.document.update';
        } else {            
            // upload new
            $data['action']   = 'issuu.document.url_upload';
            $file             = $baseUrl . $dc->activeRecord->file;
            $data['slurpUrl'] = $file;
        }

        // build signature
        $signature = $apiSecret;                 // set API secret
        ksort($data);                            // sort data array
        foreach ($data as $key => $value) {
            $signature.= $key . $value;          // concatenate request name-value pairs
        }

        $data['signature'] = md5($signature);            // encrypt string

        // build request string
        $requestData = array();
        foreach ($data as $key => $value) {
           $requestData[] = $key . '=' . $value;
        }
        $rqData = implode('&', $requestData);

        // make POST request
        $rq = new Request();
        $rq->send('http://api.issuu.com/1_0?', $rqData, 'POST');

        $response = json_decode($rq->response);
        $stat = $response->rsp->stat;

        // parse request
        if ($stat === 'ok') {
            
            // parse json response
            $document = $response->rsp->_content->document;

            // update some values if neccessary
            $updateData = array();

            $docId = $document->documentId;
            if ($docId !== $documentId) {
                $updateData['documentId'] = $docId;
            }

            $name = $document->name;
            if ($alias !== $name) {
                $updateData['alias'] = $name;
            }

            if (count($updateData) > 0) {
                $this->Database->prepare("UPDATE tl_issuu %s WHERE id=?")
                               ->set($updateData)
                               ->execute($dc->id);
            }
            
        } else if ($stat === 'fail') {
            $error = $response->rsp->_content->error;
            exit($error->message . ' (' . $error->code . ')');
        }        
    }
    
    /**
     * 
     * @param DataContainer $dc
     */
    function makeReadonly(DataContainer $dc)
    {        
        if ($dc->activeRecord) {
            var_dump($dc); die;
        }        
    }
    
    /**
     * 
     * @param DataContainer $dc
     */
    public function loadCategories(DataContainer $dc)
    {
        //echo '<pre>'; print_r($dc); die;
    }
}
