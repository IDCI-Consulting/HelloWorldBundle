## Introduction to IDCIStepBundle


## Introduction

Further to a customer need, we asked ourself how to answer to the problematic of an interactive worflow's creation.
We wanted to realize easily some navigables processes with an internet user and trigger differents events (send an email, backup on database, call to a web service) according to users actions (transition of a step to an other).
The evolution of processes complexity was an important point, whole of the workflow needed to be configurable (and not hardcode).

We have tested some existing bundles, which seems to answer to our customer problematic, but these did not convinced us.
At the bottom of the page, we have added a table comparative of these differents bundles.

In this way, we have chosen to develop our own bundle. We thought this bundle generic and reusable.

This article intended to present you IDCIStepBundle. In our next articles, we will get to the heart of the matter and speak of IDCIStepBundle specificities by concrete examples.

IDCIStepBundle allows you to simplify the interactive process's creation bound for the internet user. Its setup define itself by a system of map, step and path.
We have used the navigation metaphor throughout our whole development.

In the first time, we have to define a map and imagine every screen (web page) as a step, then link itselves by using paths which will add navigations's buttons.

<p class="notice question" markdown="1">
This article is intended for Symfony 2.8
</p>

### What is a map ?

A map defined the navigation's workflow, it is composed of steps and paths.


### What is a step ?

A step materialize itself by a web page, and define a potential passage's point. It can be of differents types. By default, our bundle supply two types :

 - html : display of a HTML content
 - form : display of a form (FormType Symfony)


### What is a path ?

A path is a route which has a step as origine and zero or several steps as destinations. It can be of diferents types. By default, our bundle supply three types :

 - single : one destination
 - conditional : several destinations according to conditionals rules
 - end : determine a navigation's end


## Legend

We will use a diagram which be used as a legend.

 - Squares represents steps
 - Array reprents paths

![IDCIStepBundle diagramm](/images/blog//stepBundle_legend.png "IDCIStepBundle diagramm")


## Why use IDCIStepBundle ?

IDCIStepBundle allows to organize a simple process (one step, one path) but also a complex process (several steps and paths). You can start easily from a configuration, which represent the map, by using the language `yml` or `json`.
You could also represente a map by using directly the object programmation, but this is maybe more complicated for non initiated in code and object programmation.

Here's a non exhaustive list of use cases of StepBundle :

 - contact form
 - inscription process
 - questionnaire
 - survey
 - etc.

Let's cut to chase et meet this Bundle !

Start by create a Symfony project in 2.8 version, we will name this project `demo_step`.

```sh
$ composer create-project symfony/framework-standard-edition my_project_name "2.8.*"
```


## Installation

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


## Table

<table>
    <thead>
        <tr>
            <th>Bundle</th>
            <th>Forces</th>
            <th>Faiblesses</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>
                <a href="https://github.com/craue/CraueFormFlowBundle" target="_blank">CraueFormFlowBundle</a>
            </th>
            <td>
                <ul>
                    <li>Un FormType pour tous les flow</li>
                    <li>Retour à la page précédente et enregistrement des informations</li>
                    <li>Proposition de deux types d'approches (un FormType pour tout le flow, un par step)
                </ul>
            </td>
            <td>
                <ul>
                    <li>Création de nombreux fichiers lors de l'installation</li>
                    <li>Pas d'enregistrement de l'ensemble des données de navigation (quand réactualisation)</li>
                    <li>Moins pratique pour la réalisation d'un long formulaire, car 1 fichier = 1 step</li>
                    <li>Pas de modifications dans la conf, donc moins de maniabilité</li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>
                <a href="https://github.com/kitpages/KitpagesWorkflowBundle" target="_blank">KitpagesWorkflowBundle</a>
            </th>
            <td>
                <ul>
                    <li>Seulement en version beta.</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li></li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>
                <a href="https://github.com/Sylius/SyliusFlowBundle" target="_blank">SyliusFlowBundle</a>
            </th>
            <td>
                <ul>
                    <li>Affiché en "read only" sur Github</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li></li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>
                <a href="https://github.com/IDCI-Consulting/StepBundle" target="_blank">IDCIStepBundle</a>
            </th>
            <td>
                <ul>
                    <li>Usage natif de merge token <!--Rééxpliquer rapidement--></li>
                    <li>Retour en arrière</li>
                    <li>Debuger avec historique de navigation</li>
                    <li>Configuration directement dans la conf, donc controller plus léger et plus maniable</li>
                    <li>Event actions à brancher sur les paths ou les steps</li>
                    <li>Enregistrement de l'ensemble des données de navigation (retour en arrière, réactualisation de page)
                </ul>
            </td>
            <td>
                <ul>
                    <li>Pas de FormType pour tous les flow</li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

If you need help or expertise about IDCIStepBundle, you could [contact us]({{ path('contact', {_locale: app.translator.locale}) }} "Contact us")
