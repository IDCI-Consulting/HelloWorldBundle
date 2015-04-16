Idci Website
============

### Requirements

* docker
* docker-compose

#### Installation

```sh
$ git clone http://gitlab.idci-consulting.fr/idci-consulting/idci-website.git idci
$ docker-compose up
```

#### Installation of `composer` packgaes

###### You have to connect in your Docker container

```sh
$ docker exec -it NAME_OF_YOUR_CONTAINER bash
# composer update
# exit
```
###### You have to set the access rights

```sh
$ cd path/to/idci-website
$ sudo chmod 775 . -R && sudo chown $USER:www-data . -R
```