<?php

class announcementListener extends jEventListener{
    function ongetMapAdditions ($event) {
        $bp = jApp::urlBasePath();

        $event->add(
            array(
                'js' => array($bp.'announcement/js/announcement.js'),
                'css' => array($bp.'announcement/css/announcement.css'),
                'jsvars' => array(
                    'announcementConfig' => array(
                        'urlGetLastInfo' => jUrl::get('announcement~ajax:getLastInfo'),
                        'urlSetCloseInfo' => jUrl::get('announcement~ajax:setCloseInfo'),
                        'modalHeader' => jLocale::get('announcement~announcement.modal_header'),
                    ),  
                ),
            )
        );    
    }
}
