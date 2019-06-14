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
		rsync -a /usr/local/share/idci_website/ /var/www/idci_website/public
	fi

	gosu www-data sh -c "rm -fr $source_folder/var/cache/*"
	gosu www-data sh -c "chown www-data:www-data $source_folder/var"

	rmdir "$source_folder/$lock_folder"
}

setuser() {
	uid=${DEV_USER_ID:-33}
	gid=${DEV_GROUP_ID:-33}

	usermod -u $uid www-data
	groupmod -g $gid www-data
}

setpermissions() {
	chown --recursive www-data: /var/www/idci_website/var /var/www/idci_website/public/build /var/www/idci_website/public/bundles
}

setuser
initialize
setpermissions

exec "$@"
