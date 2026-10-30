.PHONY: sync-www
LIZMAP_WWW ?= /var/www/lizmap/lizmap/www

# Copy the map JS and CSS to Lizmap during development, instead of running configurator.php -f
sync-www:
	rsync -a --delete announcement/install/www/announcement/ $(LIZMAP_WWW)/announcement/
