nginx_container_name = acme-app-nginx
php_container_name = acme-app-php
mysql_container_name =  acme-app-mysql

.PHONY: pac bash composer-add-github-token composer-update mysql-export mysql-import command

pac:
	docker exec -it $(php_container_name) bash -c "php app/console $(cmd); exit $$?"

bash:
	docker exec -it $(php_container_name) bash

composer-add-github-token:
	docker exec -t $(php_container_name) bash -c "composer config --global github-oauth.github.com $(token); exit $$?"

composer-update:
	docker exec -it $(php_container_name) bash -c "composer update; exit $$?"

mysql-export:
	docker exec -t $(mysql_container_name) bash -c "mysqldump -p'app' -u app app" > $(path)

mysql-import:
	docker exec -t $(mysql_container_name) bash -c "mysql -p'app' -u app app" < $(path)

command:
	docker exec -it $(php_container_name) bash -c "$(cmd); exit $$?"

default: pac
