<?php

class ajaxCtrl extends jController {

    // Configure access via jacl2 rights management
    public $pluginParams = array(
        '*' => array( 'jacl2.right'=>'lizmap.admin.access'),
        'deleteAnnouncement' => array( 'jacl2.right'=>'lizmap.admin.services.update'),
        'truncateAnnouncementTable' => array( 'jacl2.right'=>'lizmap.admin.services.update'),
    );

    /**
      * Get the last announcement for repository and/or project
      */
    public function getAllAnnouncement()
    {        
        $dao = jDao::get('announcementAdmin~announcementDetails', 'announcement');
        $announcementList = $dao->findAll();  
        $resp = $this->getResponse('json');              
        $announcementArray = [];
        foreach($announcementList as $announcement) {
            $announcementArray[] = array(
                    'id' => $announcement->id, 
                    'timestamp' => $announcement->timestamp,
                    'repo' => $announcement->repository,
                    'proj' => $announcement->project,
                    'content' => strip_tags($announcement->content),
                    'permanent' => $announcement->permanent,
                    'display_type' => $announcement->display_type
            );
        }
        $resp->data = array('data' => $announcementArray);
        return $resp;
    }

    /**
      * Delete specific announcement
      * @id announcement id
      * ajax request
      */
    public function deleteAnnouncement()
    {   
        $resp = $this->getResponse('json');
        if (!$this->isValidDeleteRequest()) {
            $resp->setHttpStatus('403', 'Forbidden');
            $resp->data = array('result' => 'forbidden');

            return $resp;
        }
        
        $id = $this->param('id');     
        $cnx = jDb::getConnection( 'announcement' );
        $sql = sprintf('DELETE FROM announcement_details WHERE id=%1$d', $id);
        $cnx->query( $sql );
        $resp->data = array('result'=>'row deleted');
        return $resp;
        
    }

    /**
      * Delete all announcement
      * ajax request
      */
    public function truncateAnnouncementTable()
    {   
        $resp = $this->getResponse('json');
        if (!$this->isValidDeleteRequest()) {
            $resp->setHttpStatus('403', 'Forbidden');
            $resp->data = array('result' => 'forbidden');

            return $resp;
        }

        $cnx = jDb::getConnection( 'announcement' );     
        $sql = "DELETE FROM announcement_details;";        
        $cnx->query( $sql );
        $resp->data = array('result'=>'table truncated');
        return $resp;
        
    }

    /**
     * The deletions must be posted from the administration page,
     * with the token of its form, against CSRF
     */
    private function isValidDeleteRequest()
    {
        $form = jForms::get('announcementAdmin~config');

        return $this->request->isPostMethod()
            && $form
            && $form->isValidToken($this->param('__JFORMS_TOKEN__'));
    }
}