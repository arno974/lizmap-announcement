<?php
/**
* @package   lizmap
* @subpackage announcementAdmin
* @author    your name
* @copyright 2011-2020 3liz
* @link      http://3liz.com
* @license    All rights reserved
*/


class announcementAdminModuleInstaller extends jInstallerModule {
    
    function install() {
        if ($this->firstDbExec()){ 
            $dbPath = jApp::configPath('db');
            $this->copyFile('./sql/announcement.db', $dbPath);
        }    

        if ($this->entryPoint->getEpId() == 'admin') {
            $localConfigIni = $this->entryPoint->localConfigIni->getMaster();

            $adminControllers = $localConfigIni->getValue('admin', 'simple_urlengine_entrypoints');
            $mbCtrl = 'announcementAdmin~*@classic';
            if (strpos($adminControllers, $mbCtrl) === false) {
                // let's register announcementAdmin controllers
                $adminControllers .= ', '.$mbCtrl;
                $localConfigIni->setValue('admin', $adminControllers, 'simple_urlengine_entrypoints');
            }
        }
    }
}