<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * @copyright  Aleja soft d.o.o 2011 - 2013
 * @author     Matej Jurančič <matej@jurancic.com>
 * @package    IssuuImport
 * @license    LGPL
 */

/**
 * Class IssuuImport
 *
 * Provide methods to handle import of issuu publications.
 * 
 * @copyright  Aleja soft d.o.o 2011 - 2013
 * @author     Matej Jurančič <matej@jurancic.com>
 * @package    Controller
 */
class IssuuImport extends Backend
{
    /**
     * Api Key
     * @var string
     */
    private $_apiKey = '';
    
    /**
     * API Secret
     * @var string
     */
    private $_apiSecret = '';
    
    /**
     * Issuu username
     * @var string
     */
    private $_username = '';
    
    /**
     * Universal tag
     * @var string
     */
    private $_universalTag = '';
    
    
    /**
     * Number of publications found
     * @var type integer
     */
    public $numFound = 0;
    
    /**
     * Per page
     * 
     * @var integer
     */
    public $perPage = 20;
    
    /**
     * Current page
     * @var integer
     */    
    public $page = 0;
    
    
	/**
	 * Load the database object
	 */
	protected function __construct()
	{
		parent::__construct();
        $this->_apiKey = $GLOBALS['TL_CONFIG']['issuu_api_key'];
        $this->_apiSecret = $GLOBALS['TL_CONFIG']['issuu_api_secret'];
        $this->_username = $GLOBALS['TL_CONFIG']['issuu_username'];
        $this->_universalTag = $GLOBALS['TL_CONFIG']['issuu_tag_universal'];
    }
    
    
    /**
     * 
     * @param DataContainer $dc
     * @return string
     */
    public function importIssuu(DataContainer $dc)
    {
        if ($this->Input->get('key') !== 'import') {
            return '';
        }
        
        // get current page
        $page = $this->Input->get('page');
        $this->page = is_null($page) ? 0 : (int) $page;
        
        $pid = $dc->id;
        
        $this->loadLanguageFile("tl_issuu");
        $this->Template = new BackendTemplate('be_import_issuu');
        
        $this->Template->publications = $this->getPublicationRadioTable();
        if ($this->page > 0) {
            $this->Template->prevPage = ampersand(str_replace('&page=' . $this->page, '', $this->Environment->request) . '&page=' . ($this->page - 1));
        }
        if ($this->page < (int) floor($this->numFound / $this->perPage)) {
            $this->Template->nextPage = ampersand(str_replace('&page=' . $this->page, '', $this->Environment->request) . '&page=' . ($this->page + 1));
        }
        $this->Template->page = (int) $this->page;
        $this->Template->numFound = $this->numFound;
        
        $this->Template->hrefBack = ampersand(str_replace('&key=import', '', $this->Environment->request));
        $this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->request = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
        $this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_issuu']['import'][0]);
        $this->Template->lbl_prev = $GLOBALS['TL_LANG']['tl_issuu']['prev'];
        $this->Template->lbl_next = $GLOBALS['TL_LANG']['tl_issuu']['next'];
        $this->Template->lbl_numFound = $GLOBALS['TL_LANG']['tl_issuu']['numFound'];
        $this->Template->headline = $GLOBALS['TL_LANG']['tl_issuu']['import_headline'];
        
        // Create import form
        if ($this->Input->post('FORM_SUBMIT') === 'tl_import_issuu') {
            $publications = $this->Input->post('publications');
            foreach ($publications as $publication) {
                $this->_importPublication($publication, $pid);
            }
        }
        
        return $this->Template->parse();        
    }
    
    /**
     * 
     * @param type $value
     * @return \CheckBoxWizard
     */
    public function getPublicationRadioTable($value = null)
    {        
        $options = $this->getPublications();
        
        $widget = new CheckBoxWizard();
        $widget->id = 'publications';
        $widget->name = 'publications';
        $widget->strName = 'tl_issuu';
        $widget->strField = 'publications';
        $widget->options = $options;
        $widget->multiple = true;        
        $widget->label = 'Publications';
        
        // Validate input
        if ($this->Input->post('FORM_SUBMIT') === 'tl_import_issuu') {
            $widget->validate();        
            if ($widget->hasErrors()) {
                $this->blnSave = false;
            }
        }
     
        return $widget;        
    }
    
    /**
     * Get your publications from Issuu
     * 
     * @return type
     */
    public function getPublications()
    {
        $this->numFound = 0;
        $documents = array();

        $q           = 'tag:' . $this->_universalTag;
        $language    = 'sl';

        $startIndex  = $this->page * $this->perPage;
        $params      = 'title,docname';
        $format      = 'json';
        
        // prepare data
        $query = '?username=' . $this->_username . '&q=' . $q . 
        		 '&startIndex=' . $startIndex . '&pageSize=' . $this->perPage .
        		 '&format=' . $format . '&responseParams=' . $params .
                 '&sortBy=epoch&_=' . time();

        // make GET request
        $rq = new Request();
        $rq->send('http://search.issuu.com/api/2_0/document' . $query);
        
        // parse request
        if (!$rq->hasError()) {

            // parse json response
            $response = json_decode($rq->response);

            foreach ($response->response->docs as $doc) {
                $documents[] = array('value' => $doc->docname, 'label' => $doc->title);
            }
            $this->numFound = (int) $response->response->numFound;
        
        } else {
            $this->Template->error = $rq->error;
        }
        
        return $documents;        
    }
    
    /**
     * Import publication data
     * 
     * @param string  $docname
     * @param integer $pid
     * @return void
     */
    private function _importPublication($docname, $pid)
    {   
        $access      = 'public';
        $format      = 'json';        
        $alias       = $docname;
        
        $action      = 'issuu.document.update';
        
        // prepare data
        $data = array(
            'action'		=> $action,
            'apiKey'	    => $this->_apiKey,
            'name'			=> $alias,
        	'format'		=> $format
        );
        
        // build signature
        $signature = $this->_apiSecret;          // set API secret
        ksort($data);                            // sort data array
        foreach ($data as $key => $value) {
            $signature.= $key . $value;          // concatenate request name-value pairs
        }
        $data['signature'] = md5($signature);    // encrypt string
        
        // build request string
        $requestData = array();
        foreach ($data as $key => $value) {
            $requestData[] = $key . '=' . $value;
        }
        $rqData = implode('&', $requestData);

        // make POST request
        $rq = new Request();
        $rq->send('http://api.issuu.com/1_0', $rqData, 'POST');
        
        // parse request
        if (!$rq->hasError()) {
        
            // parse json response
            $response = json_decode($rq->response);
            $document = $response->rsp->_content->document;
            
            $data = array(
                'pid'			=> $pid,
                'headline'      => $document->title,
                'alias'	        => $docname,
                'description'	=> $document->description,
                'keywords'		=> implode(',', $document->tags),
                'category'		=> $document->category,
                'doctype'		=> $document->type,
                'language'		=> $document->language,
                'documentId'	=> $document->documentId,
                'access'		=> $document->access,
                'sorting'		=> 0
            );
        
            //echo '<pre>'; print_r($data); die;
            
			$objInsertStmt = $this->Database->prepare("INSERT INTO tl_issuu %s")
				                            ->set($data)
				                            ->execute();
			if ($objInsertStmt->affectedRows) {
				$insertID = $objInsertStmt->insertId;
			}
        
        } else {
            var_dump($error);
        }
    }    
}
