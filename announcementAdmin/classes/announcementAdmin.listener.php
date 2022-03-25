<?php
class announcementAdminListener extends jEventListener{
  function onmasteradminGetMenuContent ($event) {
      // Create the "lizmap" parent menu item
    if (jAcl2::check("lizmap.admin.access")) {
      $bloc = new masterAdminMenuItem('informationAdmin', jLocale::get("announcementAdmin~announcementAdmin.main.menu"), '', 121);        
      // Child for the configuration of Mascarine forms
      $bloc->childItems[] = new masterAdminMenuItem(
        'announcementAdmin_config',
        jLocale::get("announcementAdmin~announcementAdmin.menu.link"),
        jUrl::get('announcementAdmin~config:modify'),
        122
      );    
      // Add the bloc
      $event->add($bloc);
    }
  }
}

