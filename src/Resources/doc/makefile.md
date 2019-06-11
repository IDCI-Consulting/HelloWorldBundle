# Makefile Documentation

With the IDCI-Website project, we provide you a Makefile that contains a few usefull shortcut for development.

## Run commands

##### To run a `symfony` command (bin/console)

```sh
$ make console cmd="your_command"
# example : make console cmd="make:entity"
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

## Other usefull shortcuts

##### To run `phpunit` test

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

##### To check your code's quality

To use phploc (analysing the structure of the project)
```sh
make phploc
```

To use php_CodeSniffer (detect violations of defined coding standard)
```sh
make phpcs
```

To use php Copy/Paste Detector
```sh
make phpcpd
```

To use php Dead Code Detector
```sh
make phpdcd
```

##### To use Webpack-encore

To compile assets
```sh
$ make encore (option="your_option") 
# example --watch to recompile automaticaly when files changes
```

To create a production build
```sh
$ make encore-production (option="your_option")
```

##### Docker images

To build your Docker image
```sh
make build-images
```

To push your Docker image
```sh
make push-images
```