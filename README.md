Idci Website
============

### Requirements

* docker

#### Installation

```sh
$ git clone http://gitlab.idci-consulting.fr/idci-consulting/idci-website.git
```

Go inside the cloned folder and use `Docker` to run all the needed containers

To build the docker image:
```sh
$ make build-images
```

If you don't already have a docker web reverse proxy service (ex: traefik), you must start it :
```sh
$ docker stack deploy -c .docker/proxy-docker-compose.yml web_reverse_proxy
```

To deploy the stack to docker swarm
```sh
$ docker stack deploy -c docker-compose.yml idci_website
```

To push the docker images:
```sh
$ make push-images
```

#### Installation of `composer` packages

```sh
$ make composer-install
```

If you want to update your librairies:
```sh
$ make composer-update
```

##### To enable the pre-commit hook, run this command

```sh
$ cp vendor/bruli/php-git-hooks/hooks/pre-commit .git/hooks/
```

##### You have to set the access rights

```sh
$ sudo chmod 775 . -R && sudo chown $USER:www-data . -R
```

To get access on your web applications, modify the `/etc/hosts` file:
```sh
$ sudo bash -c "echo -e '\n# IDCI-Consulting - Website\n127.0.0.1       idci.docker adminer.idci.docker' >> /etc/hosts"
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