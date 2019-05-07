#!/bin/sh

source_folder="/var/www/idci_website"
lock_folder="init.lock"

initialize() {
	if mkdir "$source_folder/$lock_folder"; then
		echo "Locking succeeded" >&2
	else
		echo "Lock failed" >&2

		return
	fi

	trap "[ -d \"$source_folder/$lock_folder\" ] && rmdir \"$source_folder/$lock_folder\"" EXIT

	if [ $APP_ENV = "prod" ]; then
		rsync -a /usr/local/share/maier_main/ /var/www/maier_main/public
		php bin/console cache:clear --no-debug --no-interaction
	fi

	rmdir "$source_folder/$lock_folder"
}

setuser() {
	uid=${DEV_USER_ID:-33}
	gid=${DEV_GROUP_ID:-33}

	usermod -u $uid www-data
	groupmod -g $gid www-data
}

setpermissions() {
	chown --recursive www-data: /var/www/maier_main/var /var/www/maier_main/public/build /var/www/maier_main/public/bundles
}

setuser
initialize
setpermissions

exec "$@"
