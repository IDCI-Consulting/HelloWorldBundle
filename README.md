Idci Website
============

### Requirements

* docker
* docker-compose

#### Installation

```sh
$ git clone http://gitlab.idci-consulting.fr/idci-consulting/idci-website.git idci
$ cd path/to/idci
$ docker-compose up
```

#### Installation of `composer` packages

##### You have to connect in your Docker container

```sh
$ docker exec -it NAME_OF_YOUR_CONTAINER bash
# In your Docker container run these commands
$ composer update
$ exit
```
##### To enable the pre-commit hook, run this command

```sh
$ cp vendor/bruli/php-git-hooks/hooks/pre-commit .git/hooks/
```

##### You have to set the access rights

```sh
$ sudo chmod 775 . -R && sudo chown $USER:www-data . -R
```

Edit you /etc/hosts file, then you can now access http://dev.idci.fr/index_dev.php
