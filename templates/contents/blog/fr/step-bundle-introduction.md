# Introduction à IDCIStepBundle


## Introduction

Suite au besoin d'un client, nous nous sommes demandé comment répondre à la problématique d'une création de workflow interactif.
Il nous fallait pouvoir réaliser facilement des parcours navigable par un internaute et déclencher facilement différents traitements (envoi de mail, sauvegarde en base de données, appel d'un web service) en fonction des actions utilisateurs (passage d'une étape à une autre).
L'évolutivité de la complexité des parcours étant un point important, l'ensemble du workflow se devait d'être configurable (et non pas hardcodé).

Nous avons testé des bundles existants, qui semblaient répondre à notre problématique client, mais ceux-ci n'étaient pas satisfaisant.
En bas de page, nous avons ajouté un tableau comparatif de ces différents bundles.

Ainsi, nous avons choisi de développer notre propre Bundle et nous l'avons pensé générique et réutilisable.

Cet article a pour vocation de vous présenter IDCIStepBundle.
Dans de prochains articles, nous rentrerons plus dans les détails et vous parlerons de ses spécifités au travers d'exemples concrets.

IDCIStepBundle permet de simplifier la création d'un parcours interactif à destination d'un internaute.
Sa configuration se définie par un système de **Map** (carte), de **Step** (étape) et de **Path** (chemin).
Nous avons utilisé la métaphore de la navigation tout au long de notre développement.

Dans un premier temps, nous devons définir une map, et imaginer chaque écran (page web) comme une step, puis les lier entre elles en utilisant les paths qui ajouteront des boutons de navigation.

> Remarque : cet article est destiné à Symfony 2.8


### Qu'est-ce qu'une map ?

Une map définit le workflow de navigation, elle se compose de steps et de paths.


### Qu'est-ce qu'une step ?

Une step se matérialise par une page web, et définit un potentiel point de passage.
Elle peut être de différents types. Par défaut, notre bundle fournit deux types de `step` :

 - **html** : affichage d'un contenu HTML
 - **form** : affichage d'un formulaire (FormType Symfony)


### Qu'est-ce qu'un path ?

Un path est un itinéraire qui a pour origine une step et pour destination zéro ou plusieurs steps.
Il peut être de différents types. Par défaut, notre bundle fournit trois types de `path` :

 - **single** : une seule destination
 - **conditionnel** : plusieurs destinations en fonction de règles conditionnelles.
 - **end** : le path qui détermine une fin de navigation.


## Légende

Nous allons utiliser un diagramme qui fera office de légende.

 - Les carrés représentent les steps.
 - Les flèches représentent les paths.

![Legende IDCIStepBundle](/images/blog/stepBundle_legend.png "Légende IDCIStepBundle")


## Pourquoi utiliser StepBundle ?

StepBundle permet de mettre en place aussi bien un parcours simple (une step, un path) qu'un parcours plus complexes (plusieurs steps et paths).
Vous pouvez commencer facilement à partir d'une configuration, qui représente la map, en utilisant le langage `yml` ou `json`.
Vous pouvez aussi représenter une map en utilisant directement la programmation objet, mais cela est peut-être plus compliqué pour des non initiés au code et à la programmation objet.

Voici une liste non exhaustive des cas d'utilisation de StepBundle :

 - Un formulaire de contact,
 - Un processus d'inscription,
 - Un questionnaire,
 - Une enquête,
 - Etc.

Sans plus attendre, partons à la rencontre de ce Bundle.


Commençons par créer un projet Symfony en version 2.8, nous appelerons ce projet `demo_step`.


```sh
$ composer create-project symfony/framework-standard-edition demo_step "2.8.*"
```


## Installation

Ajoutons la dépendance avec notre bundle, en modifiant le fichier `composer.json` :

```json
"require": {
    ...
    "idci/step-bundle": "~1.5"
},
```

Ou en éxécutant la commande composer suivante :

```sh
$ composer require idci/step-bundle:~1.5
```

Puis, installons cette nouvelle dépendance en utilisant composer :

```sh
$ composer update
```

Déclarons le bundle dans votre fichier `AppKernel.php` :

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

Enfin, ajoutons les spécificités de configuration de ce Bundle à notre application.
Pour cela, éditons le fichier `app/config/config.yml` :

```yaml
imports:
    ...
    - { resource: @IDCIStepBundle/Resources/config/config.yml }
```

Notre Bundle est installé et prêt à l'emploi.

Dans notre prochain article, nous testerons un cas concret d'utilisation du StepBundle : la création d'un formulaire de contact.


## Comparatif

Voici notre tableau comparatif (points forts / points faibles) des Bundles permetant de réaliser des workflows intéractifs.

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
                    <li></li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>Seulement en version beta.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>
                <a href="https://github.com/Sylius/SyliusFlowBundle" target="_blank">SyliusFlowBundle</a>
            </th>
            <td>
                <ul>
                    <li></li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>Affiché en "read only" sur Github</li>
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
                    <li>Possibilités de configuration sont nombreuses</li>
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

/* Conclusion */
