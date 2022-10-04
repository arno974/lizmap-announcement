<?php

class ajaxCtrl extends jController {

    /* ***
        Get the last announcement for repository and/or project
    *** */
    public function getLastInfo(){
        $user = $this->param('user');
        $repository = $this->param('repository');
        $project = $this->param('project');       
        $profile = 'announcement';        

        $lastCloseRepo = NULL;
        

        if (!empty($user)) {   
            $dao = jDao::get('announcementAdmin~announcementClose', $profile);
            $lastCloseRepo = $this->getRepoLastAnnouncmentCloseTimeStamp($dao, $user, $repository);
            $lastCloseRepoProj = $this->getProjectLastAnnouncmentCloseTimeStamp($dao, $user, $repository, $project);
        }
               
        $dao = jDao::get('announcementAdmin~announcementDetails', $profile);    
        $lastInfo = array(); 
        $lastInfoRepo = $this->getLastInfoRepo($dao, $repository, $lastCloseRepo);        
        $lastInfoProject = $this->getLastInfoProject($dao, $repository, $project, $lastCloseRepoProj);
        if(count($lastInfoRepo)>0){
            $lastInfo['repo'] = $lastInfoRepo;            
        }
        if(count($lastInfoProject)>0){
            $lastInfo['project'] = $lastInfoProject;            
        }        
        $rep = $this->getResponse('json');
        $rep->data = $lastInfo;
        return $rep; 
    }

    private function getRepoLastAnnouncmentCloseTimeStamp($dao, $user, $repository){
        $conditions = jDao::createConditions();             
        $conditions->addCondition('user','=', $user);
        $conditions->addCondition('repository','=', $repository);
        $conditions->addItemOrder('date_close_msg','desc'); 
        $lastCloseRepoRec = $dao->findBy($conditions, 0, 1);  
        $lastCloseRepo = NULL;
        foreach ($lastCloseRepoRec as $row) {            
            $lastCloseRepo = $row->date_close_msg;
        }  
        return $lastCloseRepo;
    }

    private function getProjectLastAnnouncmentCloseTimeStamp($dao, $user, $repository, $project){
        // Get last close timestamp for repo and proj 
        $lastCloseRepoProj = NULL;
        $conditions = jDao::createConditions();
        $conditions->addCondition('user','=', $user);   
        $conditions->addCondition('repository','=', $repository); 
        $conditions->addCondition('project', '=', $project);
        $conditions->addItemOrder('date_close_msg','desc'); 
        $lastCloseRepoRec = $dao->findBy($conditions, 0, 1);             
        foreach ($lastCloseRepoRec as $row) {            
            $lastCloseRepoProj = $row->date_close_msg;
        } 
        return $lastCloseRepoProj;
    }

    private function getLastInfoRepo($dao, $repository, $lastCloseRepo){
        // Get message about repository
        $repoAnnouncement = array();
        $conditions = jDao::createConditions();
        $conditions->addCondition('repository','=', $repository);    
        $conditions->addCondition('project','=', '');      
        if (!is_null($lastCloseRepo)) { 
            $conditions->addCondition('timestamp', '>', $lastCloseRepo);
        }  
        $conditions->addItemOrder('timestamp','desc');
        $lastInfoRepoRec = $dao->findBy($conditions, 0, 1);               
        foreach ($lastInfoRepoRec as $row) {            
            $repoAnnouncement['id'] = $row->id;
            $repoAnnouncement['repository'] = $row->repository;
            $repoAnnouncement['content'] = $row->content; 
            $repoAnnouncement['timestamp'] = $row->timestamp;
            $repoAnnouncement['display_type'] = $row->display_type;
            $repoAnnouncement['permanent'] = $row->permanent;
        }
        return $repoAnnouncement;
    }

    private function getLastInfoProject($dao, $repository, $project, $lastCloseRepoProj){
        // Get specific message about project
        $projectAnnouncement = array();
        $conditions = jDao::createConditions();
        $conditions->addCondition('repository','=', $repository);        
        $conditions->addCondition('project','=', $project);
        if (!is_null($lastCloseRepoProj)) { 
            $conditions->addCondition('timestamp', '>', $lastCloseRepoProj);
        } 
        $conditions->addItemOrder('timestamp','desc');
        $lastInfoProjectRec = $dao->findBy($conditions, 0, 1);              
        foreach ($lastInfoProjectRec as $row) {            
            $projectAnnouncement['id'] = $row->id;
            $projectAnnouncement['repository'] = $row->repository;
            $projectAnnouncement['project'] = $row->project;
            $projectAnnouncement['content'] = $row->content;
            $projectAnnouncement['timestamp'] = $row->timestamp;
            $projectAnnouncement['display_type'] = $row->display_type;
            $projectAnnouncement['permanent'] = $row->permanent;
        }
        return $projectAnnouncement;
    }
   

    /* ***
        When the user close the announcement 
        the timestamp is recorded and will not be 
        display again until new message
    *** */
    public function setCloseInfo(){
        $repository = $this->param('repository');
        $project = $this->param('project');
        $user = $this->param('user');
        if (!empty($user)) { 
            $profile = 'announcement';
            $dao = jDao::get('announcementAdmin~announcementClose', $profile);

            $record = jDao::createRecord('announcementAdmin~announcementClose', $profile);
            $record->user = $user;
            $record->repository = $repository;
            $record->project = $project;

            $dao->insert($record);  

            $rep = $this->getResponse('json');
            $rep->data = "{'response':'closed'}";
            return $rep;
        }
    }
}