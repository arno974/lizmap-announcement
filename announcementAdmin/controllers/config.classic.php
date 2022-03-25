<?php
/**
* altiProfil administration
* @package   lizmap
* @subpackage admin
* @author    3liz
* @copyright 2020 3liz
* @link      http://3liz.com
* @license GPL 3
*/

class configCtrl extends jController {

    // Configure access via jacl2 rights management
    public $pluginParams = array(
        '*' => array( 'jacl2.right'=>'lizmap.admin.access'),
        'modify' => array( 'jacl2.right'=>'lizmap.admin.services.update'),
        'edit' => array( 'jacl2.right'=>'lizmap.admin.services.update'),
        'save' => array( 'jacl2.right'=>'lizmap.admin.services.update')
    );

    function __construct( $request ) {
        parent::__construct( $request );        
    }

    /**
     * Display a summary of the information taken from the ~ configuration file.
     *
     * @return Administration backend for the repositories.
     */
    function index() {
        $rep = $this->getResponse('html');

        // Create the form
        $form = jForms::create('announcementAdmin~config');   

        $tpl = new jTpl();
        $tpl->assign( 'form', $form );
        $rep->body->assign('MAIN', $tpl->fetch('config_view'));     
        
        return $rep;
    }

    /**
     * Modification of the configuration.
     * @return Redirect to the form display action.
     */
    public function modify(){
        // Create the form
        $form = jForms::create('announcementAdmin~config');

        // redirect to the form display action
        $rep= $this->getResponse("redirect");
        $rep->action="announcementAdmin~config:edit";
        return $rep;
    }

    /**
     * Display the form to modify the config.
     * @return Display the form.
     */
    public function edit(){
        $rep = $this->getResponse('html');        
        // Get the form
        $form = jForms::get('announcementAdmin~config');

        if ( !$form ) {
            // redirect to default page
            jMessage::add('error in edit');
            $rep =  $this->getResponse('redirect');
            $rep->action = 'admin~config:index';
            return $rep;
        }
        
        // Display form
        $tpl = new jTpl();
        $confUrlEngine = &jApp::config()->urlengine;

        $bp = $confUrlEngine['basePath'];

        $rep->addCSSLinkModule('announcementAdmin', 'css/announcementAdmin.css');
        $rep->addCSSLinkModule('announcementAdmin', 'css/assets/buttons.bootstrap.min.css');
        $rep->addCSSLink($bp.'assets/css/jquery.dataTables.css');
        $rep->addJSCode('var _URLGETMSG = "' . jUrl::get('announcementAdmin~ajax:getAllAnnouncement') . '";');    
        $rep->addJSCode('var _URLTRUNCATEMSG = "' . jUrl::get('announcementAdmin~ajax:truncateAnnouncementTable') . '";');  
        $rep->addJSCode('var _URLDELETEMSG = "' . jUrl::get('announcementAdmin~ajax:deleteAnnouncement') . '";');  
        $rep->addJSCode('var _LABELTRUNCATEMSG = "' . jLocale::get("announcementAdmin~announcementAdmin.list.buttons.truncate").'";');  
        $rep->addJSCode('var _LABELREMOVEMSG = "' . jLocale::get("announcementAdmin~announcementAdmin.list.label.removerow").'";');  
        $rep->addJSLink($bp.'assets/js/jquery.dataTables.min.js');
        $rep->addJSLink($bp.'assets/js/jquery.dataTables.bootstrap.js');
        
        $rep->addJSLink($bp.'assets/js/ckeditor5/ckeditor.js');        
        $rep->addJSLinkModule('announcementAdmin', 'js/assets/dataTables.buttons.min.js');
        $rep->addJSLinkModule('announcementAdmin', 'js/assets/buttons.bootstrap.min.js');
        $rep->addJSLinkModule('announcementAdmin', 'js/assets/moment.min.js');        
        $rep->addJSLinkModule('announcementAdmin', 'js/assets/datetime.js');
        $rep->addJSLinkModule('announcementAdmin', 'js/announcementAdmin.js');
        $tpl->assign('form', $form);
        $rep->body->assign('MAIN', $tpl->fetch('announcementAdmin~config_edit'));
        return $rep;       
    }

    /**
  * Save the data for the config.
  * @return Redirect to the index.
  */
  function save(){
    
    $form = jForms::get('announcementAdmin~config');
    
    // token
    $token = $this->param('__JFORMS_TOKEN__');
    if( !$token ){
      // redirection vers la page d'erreur
      $rep= $this->getResponse("redirect");
      $rep->action="announcementAdmin~config:index";
      return $rep;
    }

    // If the form is not defined, redirection
    if( !$form ){
      $rep= $this->getResponse("redirect");
      $rep->action="announcementAdmin~config:index";
      return $rep;
    }

    // Set the other form data from the request data
    $form->initFromRequest();

    // Check the form
    if ( !$form->check() ) {
      // Errors : redirection to the display action
      $rep = $this->getResponse('redirect');
      $rep->action='announcementAdmin~config:edit';
      $rep->params['errors']= "1";
      return $rep;
    }

    // Save the data
    $fData = array();
    foreach ( $form->getControls() as $ctrl ) {
        if ( $ctrl->type != 'submit' ){
          
          $val = $form->getData( $ctrl->ref );
          jLog::log('form ref : ' . $ctrl->ref . ' / value : '. $val );
          $fData[$ctrl->ref] = $val;   
        }
    }

    $profile = 'announcement';
    $dao = jDao::get('announcementAdmin~announcementDetails', $profile);
    $record = jDao::createRecord('announcementAdmin~announcementDetails', $profile);
    $record->repository = $fData['repository'];
    $record->project = $fData['project'];
    $record->content = $fData['content'];
    $record->display_type = $fData['display_type'];    
    jLog::log('form value : ' . $fData['display_type']);    
    $record->permanent = $fData['permanent'];
    $dao->insert($record); 

    //MORE CONCISE WAY TO DO THIS BUT DON'T KNOW HOW TO SPECIFY THE CONNECTION ?
    //$form->saveToDao('announcementAdmin~announcementDetails');
    
    // Redirect to the validation page
    jMessage::add( jLocale::get("announcementAdmin~announcementAdmin.form.inserted") );
    $rep= $this->getResponse("redirect");
    $rep->action="announcementAdmin~config:edit";
    return $rep;    
  }
}
