<?php
/**
 * @package   lizmap
 * @subpackage announcementAdmin
 * @author    Arnaud Vandecasteele
 * @copyright 2021-2026 GEOLAB
 * @link      https://github.com/arno974/lizmap-announcement
 * @license   GPL 3
 */

use Jelix\Installer\Module\API\LocalConfigurationHelpers;
use Jelix\Routing\UrlMapping\EntryPointUrlModifier;
use Jelix\Routing\UrlMapping\MapEntry\MapInclude;

class announcementAdminModuleConfigurator extends \Jelix\Installer\Module\Configurator
{
    public function getDefaultParameters()
    {
        return array();
    }

    public function declareUrls(EntryPointUrlModifier $registerOnEntryPoint)
    {
        $registerOnEntryPoint->havingName(
            'admin',
            array(
                new MapInclude('urls.xml'),
            )
        );
    }

    public function localConfigure(LocalConfigurationHelpers $helpers)
    {
        // An existing profile is kept, so it can point to another database
        $helpers->declareDbProfile(
            'announcement',
            array(
                'driver' => 'sqlite3',
                'database' => 'var:db/announcement.db',
            ),
            false
        );
    }
}
