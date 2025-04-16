stack_name = idci_website
source_tag = dev
php_container_id = $(shell docker ps --filter name="$(stack_name)_php" -q)
user = www-data
node_version = 20

default: console

# SHELL
.PHONY: shell
shell:
	docker exec -it $(php_container_id) /bin/sh

.PHONY: bash
bash:
	docker exec -it $(php_container_id) /bin/bash

.PHONY: command
command:
	docker exec -it $(php_container_id) $(cmd)

# SYMFONY
.PHONY: console
console:
	docker exec -u $(user) -it "$(php_container_id)" php bin/console $(cmd)

.PHONY: phpcs-fix
cs-fix:
	docker run -u $(user) --rm -i -v `pwd`:`pwd` -w `pwd` cytopia/php-cs-fixer --rules=@Symfony --verbose fix $(php_sources) $(cmd)

.PHONY: composer
composer:
	docker exec -u www-data -it "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer $(cmd)

.PHONY: composer-update
composer-update:
	docker exec -u $(user) -it "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer update

.PHONY: composer-install
composer-install:
	docker exec -u $(user) -it "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer install

# NODE
.PHONY: yarn
yarn:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:$(node_version) yarn $(cmd)

.PHONY: encore
encore:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:$(node_version) yarn encore dev $(options)

.PHONY: encore-production
encore-production:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:$(node_version) yarn encore production $(options)

# IMAGES
.PHONY: build-image
build-image:
	docker build --target=$(source_tag) --build-arg source_tag=$(source_tag) --no-cache --network=host -t docker-registry.idci-consulting.fr/idci-consulting/website/php-fpm:$(source_tag) -f .docker/Dockerfile .

.PHONY: push-image
push-image:
	docker push docker-registry.idci-consulting.fr/idci-website/php-fpm:$(source_tag)

# STACKS
.PHONY: stack-deploy
stack-deploy:
	docker stack deploy -c .docker/docker-compose.yml ${stack_name}

.PHONY: stack-undeploy
stack-undeploy:
	docker stack rm ${stack_name}