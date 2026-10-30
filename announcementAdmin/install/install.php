<?php
/**
 * @package   lizmap
 * @subpackage announcementAdmin
 * @author    Arnaud Vandecasteele
 * @copyright 2021-2026 GEOLAB
 * @link      https://github.com/arno974/lizmap-announcement
 * @license   GPL 3
 */

use Jelix\Installer\Module\API\InstallHelpers;

class announcementAdminModuleInstaller extends \Jelix\Installer\Module\Installer
{
    public function install(InstallHelpers $helpers)
    {
        $helpers->database()->useDbProfile('announcement');
        $helpers->database()->execSQLScript('sql/install');
    }
}
