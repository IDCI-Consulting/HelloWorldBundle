## Introduction to IDCIStepBundle ##

## Introduction ##

Further to a customer need, we asked ourself how to answer to the problematic of a conversational worflow's creation.
We wanted to realize easily some interactives processes with an internet user and trigger differents events (send an email, backup on database, call to a web service) according to users actions (transition of a step to an other). The evolution of processes complexity was an important point, whole of the workflow needed to be configurable (and not hardcode).

We have tested some existing bundles, which seems to answer to our customer problematic, but these did not convinced us. At the bottom of the page, we have added a table comparative of these differents bundles.

In this way, we have chosen to develop our own bundle. In that, we thought this bundle generic and reusable.

This article intended to present you IDCIStepBundle. In our next articles, we will get to the heart of the matter and speak of IDCIStepBundle specificities by concrete examples.

IDCIStepBundle allows you to simplify the interactive process's creation bound for the internet user. Its setup define itself by a system of map, step and path.
We have used the navigation metaphor throughout our whole development.

In the first time, we have to define a map and imagine every screen (web page) as a step, then link itselves by using paths which will add navigations's buttons.

> Note : this article is intended for Symfony 2.8

### What is a map ? ###

A map defined the navigation's workflow, it is composed of steps and paths.

### What is a step ? ###

A step materialize itself by a web page, and define a potential passage's point. It can be of differents types. By default, our bundle supply two types :
   * html : display of a HTML content
   * form : display of a form (FormType Symfony)

### What is a path ? ###

A path is a route which has a step as origine and zero or several steps as destinations. It can be of diferents types. By default, our bundle supply three types :
   * single : one destination
   * conditional : several destinations according to conditionals rules
   * end : determine a navigation's end

## Legend ##

We will use a diagram which be used as a legend.

   * Squares represents steps
   * Array reprents paths

![IDCIStepBundle legend](/images/blog//legend_StepBundle.png "IDCIStepBundle legend")

## Why use IDCIStepBundle ? ##

IDCIStepBundle allows to organize a simple process (one step, one path) but also a complex process (several steps and paths). You can start easily from a configuration, which represent the map, by using the language `yml` or `json`.
You could also represente a map by using directly the object programmation, but this is maybe more complicated for non initiated in code and object programmation.

Here's a non exhaustive list of use cases of StepBundle ;
 * contact form
 * inscription process
 * questionnaire
 * survey
 * etc.

Let's cut to chase et meet this Bundle !

Start by create a Symfony project in 2.8 version, we will name this project `demo_step`.

```sh
$ composer create-project symfony/framework-standard-edition my_project_name "2.8.*"
```

## Installation ##

Let's declare the dependencie, by modifiying the file `composer.json` :

```json
"require": {
    ...
    "idci/step-bundle": "~1.5"
},
```

Or by executing this composer commande :

```sh
composer require idci/step-bundle:~1.5
```

Then let's install theses news dependencies by using composer :

```sh
$ php composer.phar update
```

Finally, registre the bundle in your `AppKernel.php` file :

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\StepBundle\IDCIStepBundle(),
    );
}
```

Our bundle is installed and ready for use.

Below, our comparative table of bundles aiming to demonstrate strongs points and weak point.

In our next article, we will test a concrete case of StepBundle utilisation : the creation of a contact form.


## Table ##

CraueFormFlow
Kitpage
Sylius Flow Bundle
SURFnet SamlBundle
