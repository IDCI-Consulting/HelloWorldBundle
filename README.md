IDCI - Website
==============

## Installation

### Requirements

If you don't already have a docker web reverse proxy service (ex: traefik), you must start it
```sh
$ docker stack deploy -c .docker/docker-compose-reverse-proxy.yml traefik
```

#### Local DNS Entries

Add the following DNS entries in your host file:
```
# IDCI-Consulting - Website
127.0.0.1    idci.docker
```

#### Build images

If you need to rebuild docker app images, run the following command :
```sh
$ make build-images
```

### Start

Load environment vars:
```sh
$ source .env.sh
```

To run the project docker stack :
```sh
$ docker stack deploy -c .docker/docker-compose.yml idci_website
```

### Stop

To stop the project docker stack :
```sh
$ docker stack rm idci_website
```

### Build assets

To build the assets, run the following commands :
```sh
$ make yarn
$ make encore
```

### Installing the app

Create the database using the following commannd :
```sh
$ make console cmd="d:s:u --dump-sql --force"
```

To install the app, run the following command :
```sh
$ make composer-install
```


//TODO: Move the following documentation in a dedicated parts

##### To enable the pre-commit hook, run this command

```sh
$ cp vendor/bruli/php-git-hooks/hooks/pre-commit .git/hooks/
```

##### You have to set the access rights

```sh
$ sudo chmod 775 . -R && sudo chown $USER:www-data . -R
```

##### To run a `symfony` command

```sh
$ make console cmd="your_command"
# example : make console cmd="make:entity"
```

##### To run all `phpunit` test

```sh
$ make phpunit (option="your_option")
```

To generate code coverage report in text format:
```sh
$ make phpunit-text
```

To generate code coverage report in html format:
```sh
$ make phpunit-html
```

##### To use command on your docker container

You can run command directly
```sh
$ make command cmd="your_command"
# example : make command cmd="ls"
```

Or open a bash on your container
```sh
$ make bash
```

##### To run `npm` or `yarn` command

For npm
```sh
$ make npm cmd="your_command"
# example : make npm cmd="install"
```

For yarn
```sh
$ make yarn cmd="your_command"
# example : make yarn cmd="install"
```

##### Webpack-encore

To compile assets
```sh
$ make encore (option="your_option") # --watch to recompile automaticaly when files changes
```

To create a production build
```sh
$ make encore-production (option="your_option")
```
