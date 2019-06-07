#!/bin/sh

export APP_VERSION=v3.x
export CONTAINER_RELEASE_IMAGE=docker-registry.idci-consulting.fr/idci/website/php-fpm:master

export APP_ENV="dev"
export APP_DEBUG=1
export APP_HOST="idci.docker"
export DATABASE_NAME="idci"
export DATABASE_USERNAME="idci"
export DATABASE_PASSWORD="idci"
export DEV_USER_ID=1000
export DEV_GROUP_ID=1000

export TRAEFIK_ADMINER_FRONTEND_RULE="Host:adminer.${APP_HOST}"
export TRAEFIK_NGINX_FRONTEND_RULE="Host:${APP_HOST}"
