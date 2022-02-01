# Stage 0:
FROM php:8.1.1-fpm AS base

## Install dependencies
RUN apt update \
	&& apt install -y git zip libpq-dev rsync

## Add php.ini configuration and the entrypoint script
ADD .docker/conf/php/php.ini /usr/local/etc/php/php.ini
ADD .docker/entrypoint.sh /entrypoint.sh

WORKDIR /var/www/app

# ## Copy App files
# COPY composer.json composer.lock symfony.lock .env* package.json webpack.config.js ./
# COPY assets assets/
# COPY bin bin/
# COPY config config/
# COPY migrations migrations/
# COPY public public/
# COPY src src/
# COPY templates templates/
# COPY translations translations/

## Create empty var cache and var log directory
RUN mkdir -p /var/www/app/var/cache /var/www/app/var/log

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]


# Stage 1:
FROM base AS build

## Install composer 2
ADD ["https://getcomposer.org/installer", "https://composer.github.io/installer.sig", "./"]
RUN php ./installer --2 --install-dir="/usr/local/bin/" --filename="composer" && rm -f installer installer.sig
RUN mkdir -p /var/www/.composer/cache && chown -R www-data /var/www/.composer

## Install App dependencies (using composer in PROD env)
ARG APP_ENV=prod
ENV APP_ENV ${APP_ENV}
RUN set -eux; \
    composer install --no-dev --no-scripts --no-suggest --no-interaction --prefer-dist --optimize-autoloader; \
    composer dump-autoload --no-dev --optimize --classmap-authoritative;

## Install app assets
RUN php bin/console assets:install

## Install node and npm using nvm
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 14

## Build assets
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.2/install.sh | bash -
RUN . $NVM_DIR/nvm.sh \
	&& nvm install $NODE_VERSION \
	&& nvm alias default $NODE_VERSION \
	&& nvm use default \
	&& npm install --global yarn \
	&& yarn --version \
	&& yarn install \
	&& yarn encore production

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# Stage 2:
FROM build as dev

RUN apt update --allow-releaseinfo-change
RUN apt install -y --no-install-recommends gcc libc6-dev
RUN pecl install xdebug-beta && docker-php-ext-enable xdebug

# installing project
ENV APP_ENV=dev
ENV APP_ENV ${APP_ENV}

RUN composer install --no-interaction --optimize-autoloader
RUN . $NVM_DIR/nvm.sh \
	&& yarn --version \
	&& yarn install \
	&& yarn encore dev

CMD ["php-fpm", "-F"]

# Stage 3:
FROM base as prod

## Copy built vendors
ARG APP_ENV=prod
ENV APP_ENV ${APP_ENV}
COPY --from=build /var/www/app/vendor /var/www/app/vendor

## Copy built assets and bundles in order to use and share them (entrypoint)
COPY --from=build /var/www/app/public/build /usr/local/share/public/build/
COPY --from=build /var/www/app/public/bundles /usr/local/share/public/bundles/

## Define volumes
VOLUME /var/www/app/var/cache
VOLUME /var/www/app/public/build

CMD ["php-fpm", "-F"]