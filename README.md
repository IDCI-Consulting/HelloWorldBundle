# IDCI Website

## Services

IDCI-Website project based on Symfony6.

## Requirements

- Docker

### Web reverse proxy

If you don't already have a docker web reverse proxy service (ex: traefik), you must start it
```sh
$ docker network create --scope swarm --driver overlay traefik_reverse_proxy
$ docker stack deploy -c .docker/traefik/docker-compose.yml traefik
```

To remove the traefik stack:
```sh
$ docker stack rm traefik
```

Once traefik run, you can check your browser at 127.0.0.1:8080

## Installation

### First steps

1. Add the following DNS entries in your host file:
```
# IDCI-Website
127.0.0.1       idci-website.docker
127.0.0.1       redis-commander.idci-website.docker
127.0.0.1       mailhog.idci-website.docker
127.0.0.1       wkhtmltopdf.idci-website.docker
```

2. Git clone this repository
```sh
$ git clone git@gitlab.idci-consulting.fr:idci-consulting/website.git
```

3. Go to the projet directory
```sh
$ cd website
```

4. If you need to rebuild docker app images, run the following command :
```sh
$ make build-image
```

### Start

To run the project docker stack :
```sh
$ make stack-deploy
```

### Stop

To stop the project docker stack :
```sh
$ make stack-undeploy
```

### Build assets

To build the assets, run the following commands :
```sh
$ make yarn
$ make encore
```

## For the developers

You'll need to change permissions to modify files :
```sh
sudo chown -R $USER:www-data . && chmod 775 -R . && find ./ -type f -exec chmod 664 {} \;
```

If you use a volume in your docker-compose, you need to update composer after the build-image :
```sh
$ make composer-install
```

If you want the assets to be built everytime you save a .scss or .js file, you can use :
```sh
$ make encore options="--watch"
```