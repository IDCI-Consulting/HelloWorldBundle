# Introduction à StepBundle #


## Introduction ##


Suite au besoin d'un client, nous nous sommes demandé comment répondre à la problématique d'une création de worflow conversationnel. 
Il nous fallait pouvoir réaliser facilement des parcours interactifs avec un internaute et déclencher facilement différents évenements (envoi de mail, sauvegarde en base de données, appel d'un web service) en fonction des actions utilisateurs (passage d'une étape à une autre). L'évolutivité de la complexité des parcours étant un point important, l'ensemble du workflow se devait d'être configurable (et non pas hardcodé).

Nous avons testé d'autres bundles existants, qui semblaient répondre à notre problématique client, mais ceux-ci ne nous pas convaincu. En bas de page, nous avons ajouté un tableau comparatif de ces différents bundles.

Ainsi, nous avons choisi de développer notre propre Bundle. En cela, nous avons pensé ce bundle générique et réutilisable.

Cet article a pour vocation de vous le présenter au travers de deux exemples concrets.

StepBundle permet de simplifier la création d'un parcours interactif à destination d'un internaute. Sa configuration se définie par un système de map (carte), de step (étape) et de path (chemin).
Nous avons utilisé la métaphore de la navigation tout au long de notre développement.

Dans un premier temps, vous devez définir une map, et imaginer chaque écran (page web) comme une step, puis les lier entre elles en utilisant les paths qui ajouteront des boutons de navigation.


> Remarque : cet article est destiné à Symfony 2.8

### Qu'est-ce qu'une map ? ###

Une map définit le workflow de navigation, elle se compose de steps et de paths.

### Qu'est-ce qu'une step ? ###

Une step se matérialise par une page web, et définit un potentiel point de passage. Elle peut être de différents types. Par défaut, notre bundle fournit deux types :
    * html : affichage d'un contenu HTML.
    * form : affichage d'un formulaire (FormType Symfony).

### Qu'est-ce qu'un path ? ###

Un path est un itinéraire qui a pour origine une step et pour destination zéro ou plusieurs steps. Il peut être de différents types. Par défaut, notre bundle fournit trois types : 
    * le single : une seule destination
    * le conditionnel : plusieurs destinations en fonction de règles conditionnelles.
    * le end : le path qui détermine une fin de navigation.


## Légende ##

Nous allons utiliser un diagramme qui fera office de légende.

* Les carrés représentent les steps.
* Les flèches représentent les paths.

![Legende StepBundle](demo_step/img/legendStepBundle.png "Légende StepBundle")

## Pourquoi utiliser StepBundle ? ##

StepBundle permet de mettre en place aussi bien un parcours simple (une step, un path) qu'un parcours plus complexes (plusieurs steps et paths). Vous pouvez commencer facilement à partir d'une configuration, qui représente la map, en utilisant le langage `yml` ou `json`.
Vous pouvez aussi représenter une map en utilisant directement la programmation objet, mais cela est peut-être plus compliqué pour des non initiés au code et à la programmation objet.

Voici une liste non exhaustive des cas d'utilisation de StepBundle : un formulaire de contact, un processus d'inscription, un questionnaire, une enquête, etc.

Sans plus attendre, rentrons dans le vif du sujet et partons à la rencontre de ce Bundle.


Commençons par créer un projet Symfony en version 2.8, nous appelerons ce projet 'demo_step'.

```sh
$ symfony new demo_step 2.8
```


## Installation ##

Déclarons les dépendances avec notre bundle, en modifiant le fichier `composer.json` :

```json
"require": {
    ...
    "idci/step-bundle": "~1.5"
},
```

Ou en éxécutant la commande composer suivante :

```sh
composer require idci/step-bundle:~1.5
```

Puis, installons ces nouvelles dépendances en utilisant composer :

```sh
$ php composer.phar update
```

Enfin, enregistrons le bundle dans votre fichier `AppKernel.php` :

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\StepBundle\IDCIStepBundle(),
    );
}
```

Notre Bundle est installé et prêt à l'emploi.

Dans notre prochain article, nous testerons un cas concret d'utilisation du StepBundle : la création d'un formulaire de contact. Retrouvons nous ici /*mettre le lien*/


Ci-dessous, voici notre fameux tableau comparatif des Bundles visant à mettre en valeur leurs points forts et leurs points faibles.

## Comparatif ##

CraueFormFlow
Kitpage
Sylius Flow Bundle
SURFnet SamlBundle

