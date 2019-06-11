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
