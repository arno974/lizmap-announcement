# lizmap-announcement

This Lizmap module displays messages from the administrator to the users of the maps.

The administrator adds a message for a whole repository or for a single project, displayed
as an alert on the map or as a modal window.

![Screenshot 2022-04-19 at 10-35-28 Administration](https://user-images.githubusercontent.com/425243/163941760-f92d5339-79a0-4151-89d6-964673d40e74.png)

In the administration panel, previous messages can be viewed or deleted.

![Screenshot 2022-04-19 at 10-35-39 Administration](https://user-images.githubusercontent.com/425243/163940926-496925e3-4862-44b0-9e88-4c784d700556.png)

The message is then displayed when a map of the repository or of the project is opened.

![Screenshot 2022-04-19 at 10-38-36 simple - simple - Lizmap](https://user-images.githubusercontent.com/425243/163941732-c3e5b5dd-345c-4a25-a5a8-e0d816b45f19.png)

## Compatibility

Lizmap Web Client 3.8 to 3.11, tested with Lizmap 3.9.6.

For older versions of Lizmap (Jelix 1.6), use the commit
[`1e2e509`](https://github.com/arno974/lizmap-announcement/tree/1e2e509).

The repository contains two Jelix modules:

* `announcementAdmin`: the administration of the messages and the database,
* `announcement`: the display of the messages on the maps. It requires `announcementAdmin`.

## Installation

The modules are installed with [Composer](https://getcomposer.org), in `lizmap/my-packages`.
The package is not published on Packagist, so declare the GitHub repository first:

```bash
cd lizmap/my-packages
cp -n composer.json.dist composer.json
composer config repositories.announcement vcs https://github.com/arno974/lizmap-announcement
composer require "arno974/lizmap-announcement:@dev"
```

Then configure and install the modules, from the `lizmap` directory:

```bash
php install/configurator.php announcementAdmin
php install/configurator.php announcement
php install/installer.php
install/clean_vartmp.sh
install/set_rights.sh
```

Check that the administration module is declared on the `admin` entry point, in
`lizmap/var/config/localurls.xml`. Otherwise its page is displayed without the administration menu:

```xml
<entrypoint name="admin">
    <url include="urls.xml" module="announcementAdmin" pathinfo="/announcementAdmin"/>
</entrypoint>
```

If a module was already configured, `configurator.php` does nothing for it unless the `-f` option is given.

### Database

The configurator declares the `announcement` connection profile in `lizmap/var/config/profiles.ini.php`,
with a SQLite database stored in `lizmap/var/db/announcement.db`. The installer creates the tables.

An `announcement` profile declared before the configuration is kept. Only a SQLite script is provided for now.

### Upgrade from version 0.1

The previous installer copied the SQLite database to `lizmap/var/config/db`.

* If `profiles.ini.php` already contains an `announcement` profile, it is kept and the messages too.
* Otherwise, copy the old file to `lizmap/var/db/announcement.db` before running `installer.php`.

## Usage

In the administration, the menu *User Announcements > Announcements management* gives a form
to add a message, and the list of the messages.

* A message is displayed on the maps of its repository, or only on the map of its project.
  On a map, the last message of the repository and the last message of the project are displayed.
* Once closed by a connected user, a message is not displayed again to this user,
  until a new message is published.
* A **permanent** message is displayed each time the map is opened, even after it was closed.
* Anonymous users see the message each time they open the map: their closes are not recorded.

## Development

Install the package from your local clone, with a symbolic link, in `lizmap/my-packages`:

```bash
composer config repositories.announcement '{"type": "path", "url": "/path/to/lizmap-announcement", "options": {"symlink": true}}'
composer require "arno974/lizmap-announcement:@dev"
```

The PHP files, templates, forms and locales are read through the link. Run `install/clean_vartmp.sh`
after changing a template, a form or a locale.

The JavaScript and CSS files of the maps are copied to `lizmap/www/announcement/` by the configurator.
After changing them, copy them again with:

```bash
make sync-www
```

The `LIZMAP_WWW` variable (default `/var/www/lizmap/lizmap/www`) gives another Lizmap `www` directory.
