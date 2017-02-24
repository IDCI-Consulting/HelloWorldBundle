nginx_container_name = idci-website-nginx
php_container_name = idci-website-php
mysql_container_name =  idci-website-mysql
node_container_name =  idci-website-node

.PHONY: bash composer-add-github-token composer-update npm-install bower-install gulp-watch gulp-prod command

bash:
	docker exec -it $(php_container_name) bash

composer-add-github-token:
	docker-compose run --rm php composer config --global github-oauth.github.com $(token)

npm-install:
	docker-compose -f node-docker-compose.yml run --rm node bash -c "npm install; exit $$?"

bower-install:
	docker-compose -f node-docker-compose.yml run --rm node bash -c "bower install --allow-root; exit $$?"

gulp:
	docker-compose -f node-docker-compose.yml run --rm --service-ports node bash -c "gulp $(task)"

composer-update:
	docker-compose run --rm php composer update

command:
	docker exec -it $(php_container_name) bash -c "$(cmd); exit $$?"
