<?php

class announcementListener extends jEventListener{
    function ongetMapAdditions ($event) {        
        $js = array();
        $css = array(); 
        $jscode = array();

        $js = array(            
            jUrl::get('jelix~www:getfile', array('targetmodule'=>'announcement', 'file'=>'js/announcement.js'))
        );

        $css = array(            
            jUrl::get('jelix~www:getfile', array('targetmodule'=>'announcement', 'file'=>'css/announcement.css'))
            
        );
        
        $jscode = array(
            'var _URLGETLASTINFO = "' . jUrl::get('announcement~ajax:getLastInfo') . '"',
            'var _SETUSERCLOSEINFO = "' . jUrl::get('announcement~ajax:setCloseInfo') . '"',   
            'var _MODALHEADER = "' . jLocale::get('announcement~announcement.modal_header') . '"',         
        );

        $event->add(
            array(
                'js' => $js,
                'css' => $css,
                'jscode' => $jscode
            )
        );    
    }
}
