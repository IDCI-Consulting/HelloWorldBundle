Idci Website
============

### Requirements

* docker
* docker-compose

#### Installation

```sh
$ git clone http://gitlab.idci-consulting.fr/idci-consulting/idci-website.git
```

Go inside the cloned folder and use `Docker` to run all the needed containers

If the docker `dev` network doesn't exist:
```sh
$ docker network create dev
```

If the `nginx-proxy` is not running:
```sh
$ docker-compose -f docker/proxy-docker-compose.yml up -d
```

Then run the remaining containers:
```sh
$ docker-compose up -d
```

#### Installation of `composer` packages, `npm` modules & `bower` components

##### Composer

```sh
$ make composer-update
```

##### Npm

```sh
$ make npm-install
```

##### Bower

```sh
$ make bower-install
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
```
$ sudo bash -c "echo -e '\n# IDCI-Consulting - Website\n127.0.0.1       idci-website.docker adminer.idci-website.docker' >> /etc/hosts"
```


##### Run Gulp tasks

To run gulp tasks (to compile sass file for example) run the following (see the Gulpfile.js file to know the different tasks):
```sh
$ make gulp task="your_task"
# example make gulp task="watch"
```