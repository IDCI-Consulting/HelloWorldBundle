stack_name = idci-website
source_tag = dev
php_container_id = $(shell docker ps --filter name="$(stack_name)_php" -q)
user = $(shell id -u)

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
	docker exec -it "$(php_container_id)" php bin/console $(cmd)

.PHONY: phpcs-fix
phpcs-fix:
	docker run --rm -i -v `pwd`:`pwd` -w `pwd` grachev/php-cs-fixer --rules=@Symfony --verbose fix $(php_sources)

.PHONY: composer-update
composer-update:
	docker exec -t "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer update

.PHONY: composer-install
composer-install:
	docker exec -t "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer install --no-interaction

# NODE
.PHONY: yarn
yarn:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:15 yarn $(cmd)

.PHONY: encore
encore:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:15 yarn encore dev $(options)

.PHONY: encore-production
encore-production:
	docker run --rm -it -v `pwd`:/usr/src/app -w /usr/src/app node:15 yarn encore production $(options)

# IMAGES
.PHONY: build-image
build-image:
	docker build --target=$(source_tag) --build-arg source_tag=$(source_tag) --no-cache --network=host -t docker-registry.idci-consulting.fr/idci-website/php-fpm:$(source_tag) -f .docker/Dockerfile .

.PHONY: push-image
push-image:
	docker push docker-registry.idci-consulting.fr/idci-website/php-fpm:$(source_tag)

# STACKS
.PHONY: stack-deploy
stack-deploy:
	docker stack deploy -c .docker/docker-compose.yml ${stack_name}

.PHONY: stack-undeploy
stack-undeploy:
	docker stack rm idci-website