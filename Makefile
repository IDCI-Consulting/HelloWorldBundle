# Variables

stack_name = idci_website

php_sources         ?= .
phpcs_ignored_files ?= vendor/*,var/cache/*

source_tag = current

php_container_id = $(shell docker ps --filter name="$(stack_name)_php" -q)
user = $(shell id -u)

default: console

# Bash Commands

.PHONY: bash
bash:
	docker exec -it $(php_container_id) bash

.PHONY: command
command:
	docker exec -it $(php_container_id) $(cmd)

# UTILS

.PHONY: composer-update
composer-update:
	docker exec -t "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer update

.PHONY: composer-install
composer-install:
	docker exec -t "$(php_container_id)" php -d memory_limit=-1 /usr/local/bin/composer install --no-interaction

.PHONY: install
install: composer-install yarn encore
	docker exec "$(php_container_id)" php bin/console assets:install

.PHONY: phploc
phploc:
	docker run --rm -i -v `pwd`:/project jolicode/phaudit bash -c 'phploc $(php_sources); exit $$?'

.PHONY: phpcs
phpcs:
	docker run --rm -i -v `pwd`:/project jolicode/phaudit bash -c 'phpcs $(php_sources) --extensions=php --ignore=$(phpcs_ignored_files) --standard=PSR2; exit $$?'

.PHONY: phpcpd
phpcpd:
	docker run --rm -i -v `pwd`:/project jolicode/phaudit bash -c 'phpcpd $(php_sources); exit $$?'

.PHONY: phpdcd
phpdcd:
	docker run --rm -i -v `pwd`:/project jolicode/phaudit bash -c 'phpdcd $(php_sources); exit $$?'

.PHONY: phpcs-fix
phpcs-fix:
	docker run --rm -i -v `pwd`:`pwd` -w `pwd` grachev/php-cs-fixer --rules=@Symfony --verbose fix $(php_sources)


# SYMFONY

.PHONY: phpunit
phpunit: ./vendor/bin/phpunit
	docker exec -it "$(php_container_id)" bash -c "./vendor/bin/phpunit $(options)"

.PHONY: phpunit-text
phpunit-text: ./vendor/bin/phpunit
	docker exec -it "$(php_container_id)" bash -c "./vendor/bin/phpunit --coverage-text"

.PHONY: phpunit-html
phpunit-html: ./vendor/bin/phpunit
	docker exec -it "$(php_container_id)" bash -c "./vendor/bin/phpunit --coverage-html var/phpunit-html"

.PHONY: console
console:
	docker exec -it "$(php_container_id)" bash -c "php bin/console $(cmd)"


# NODE

.PHONY: npm
npm:
	docker run --rm -i -v `pwd`:/usr/src/app -w /usr/src/app node:9.5.0 npm $(cmd)

.PHONY: yarn
yarn:
	docker run --rm -i -v `pwd`:/usr/src/app -w /usr/src/app node:9.5.0 yarn $(cmd)

encore:
	docker run --rm -i -v `pwd`:/usr/src/app -w /usr/src/app node:9.5.0 yarn encore dev $(options)

encore-production:
	docker run --rm -i -v `pwd`:/usr/src/app -w /usr/src/app node:9.5.0 yarn encore production $(options)

# IMAGES

.PHONY: build-images
build-images:
	docker build -t docker-registry.idci-consulting.fr/idci/website/php-fpm:$(source_tag) -f .docker/php/Dockerfile .

.PHONY: push-images
push-images:
	docker push docker-registry.idci-consulting.fr/idci/website/php-fpm:$(source_tag)
