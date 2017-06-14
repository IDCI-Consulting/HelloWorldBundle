nginx_container_name = idci-website-nginx
php_container_name = idci-website-php
mysql_container_name =  idci-website-mysql
node_container_name =  idci-website-node

.PHONY: bash
bash:
	docker exec -it $(php_container_name) bash

.PHONY: npm-install
npm-install:
	docker-compose -f node-docker-compose.yml run --rm node npm install

.PHONY: bower-install
bower-install:
	docker-compose -f node-docker-compose.yml run --rm node bower install --allow-root

.PHONY: gulp
gulp:
	docker-compose -f node-docker-compose.yml run --rm --service-ports node gulp $(task)

.PHONY: composer-update
composer-update:
	docker-compose run --rm php composer update

.PHONY: composer-install
composer-install:
	docker-compose run --rm php composer install

.PHONY: command
command:
	docker exec -it $(php_container_name) bash -c "$(cmd); exit $$?"

.PHONY: phpunit
phpunit: ./vendor/phpunit/phpunit/phpunit ./phpunit.xml.dist
	docker-compose run --rm php bash -c './vendor/phpunit/phpunit/phpunit -c . && exit $$?'

default: bash

