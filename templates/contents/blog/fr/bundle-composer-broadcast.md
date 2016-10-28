
# Créer un bundle symfony2 réutilisable et le diffuser via composer #
![Composer Packagist Github Symfony2](/images/blog/composer-packagist.png "Composer Packagist Github Symfony2")

## Introduction ##

Avec l'arrivée de **composer**, vous pouvez rendre vos bundle Symfony2 (et pas seulement) facilement disponible et réutilisable.

[Composer](http://getcomposer.org/) est un gestionnaire de paquets PHP inspiré par [npm](https://npmjs.org/) et [Bundler](http://gembundler.com/).
Il requiert la version 5.3.2 de PHP ou supérieur pour fonctionner.
Il est multiplatforme et fonctionne parfaitement sur Windows, Linux et OSX.

Cet outil va donc vous permetre d'installer de manière simple et rapide vos `vendors` dans vos projets Symfony2.

Le logiciel composer s'appuie sur un dépôt nommé [Packagist](https://packagist.org/).
Celui-ci regroupe un ensemble de paquets installable via [composer](http://getcomposer.org/).

Packagist se contente simplement d'analyer un fichier au format json (composer.json),
contenant un ensemble d'information sur votre bundle, comme son nom, ses dépendances, ...

Ce fichier `composer.json` doit être à la racine de votre bundle et le tout doit être situé
sur un SCM tel que que [Git](http://git-scm.com/), [Subversion](http://subversion.tigris.org/) ou [Mercurial](http://mercurial.selenic.com/).
Dans notre exemple, nous utiliserons github, bien connu de la communauté symfony2.

Le but de cet article est donc de vous montrer comment créér votre propre bundle afin de pouvoir facilement
le réutiliser dans vos futurs projet, et par la même occasion le partager à la communauté des développeurs Symfony2 ;)

Voici rapidement les étapes à effectuer:

* Créer un dépôt pour votre bundle.
* Créer le fichier composer.json le commiter (puis le pusher).
* Créer un compte et enregistrer votre bundle sur packagist.
* Activer la mise à jour automatique du bundle sous packagist (notification lors d'un push sur github par exemple).

Pour faciliter les explications, et car rien ne vaut un bon exemple, nous allons créer un bundle nommé PartnerBundle.
Nous partons du fait que le logiciel de gestion de versions Git ne vous est pas inconnu et que vous possédez déjà un compte sur gitHub.

## Rendre un bundle disponible via composer ##

### Rédaction du fichier composer.json ###

Une fois vôtre dépôt crée et cloné, il vous faut rédiger le fichier `composer.json`.
Votre bundle nécessite sûrement lui même d'autres bundles pour fonctionner, tel que Symfony, twig, doctrine, etc.
Il va falloir indiquer les minimas (dépendances) à respecter pour le faire fonctionner.
Ce fichier se trouve à la racine de votre bundle.

Voici le minimum à remplir:

    {
        "name": "your-vendor-name/package-name",
        "require": {
            "php": ">=5.3.0"
        }
    }

Voici le fichier `composer.json` utilisé pour notre PartnerBundle

    {
        "name": "idci/partner-bundle",
        "type": "symfony-bundle",
        "description": "Symfony PartnerBundle",
        "keywords": ["Partner application", "xml", "json", "api", "web service"],
        "license": "GPL-3.0+",
        "authors": [
            {
                "name": "Gabriel Bondaz",
                "email": "gabriel.bondaz@idci-consulting.fr",
                "homepage": "http://www.idci-consulting.fr"
            },
            {
                "name": "Baptiste Bouchereau",
                "email": "baptiste.bouchereau@idci-consulting.fr",
                "homepage": "http://www.idci-consulting.fr"
            }
        ],
        "require": {
            "php": ">=5.3.2",
            "symfony/framework-bundle": ">=2.1, "twig/twig": "*",
            "doctrine/doctrine-bundle": "*"
        },
        "autoload": {
            "psr-0": { "IDCI\\Bundle\\PartnerBundle": "" }
        },
        "target-dir": "IDCI/Bundle/PartnerBundle",
        "minimum-stability": "dev"
    }

Vous trouverez plus d'informations sur le fichier `composer.json` à cette adresse:
[http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup](http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup)
N'hésitez pas à jeter un coup d’œil dans les fichiers composer.json des bundles déjà présents dans les vendors par exemple.

Une fois rédigé, ajoutez ce fichier sous git

    $ git add composer.json

Puis commitez-le et pushez le sur gitHub pour permettre à Packagist de le valider par la suite.

    $ git commit -m "Add composer file"
    $ git push

### Valider le fichier composer.json ###

Premièrement, inscrivez-vous sur [Packagist](https://packagist.org/)

Puis choisissez "Submit Package" et entrez l'URL de vôtre dépot git pour le projet correspondant
ex: https://github.com/Mon-Entreprise/MonBundle.git

![Submit package](/images/blog/packagist_submit_package.png)

Si votre fichier `composer.json` est correct, vôtre bundle est alors enregistré et prêt à être intégré à un projet.
Vous devriez obtenir quelque chose qui ressemble à ceci:

![Package added](/images/blog/packagist_package_added.png)

### Activer l'auto-update sous packagist ###

Si vous vous rendez [sur votre page perso](https://packagist.org/profile/) vous devriez trouver l'ensemble de vos paquets.
Vous pouvez voir que la mise à jour n'est pas automatique. En d'autres termes,
si vous faite un `$ git push` des améliorations/correstions de votre bundle sur gitHub,
celles-ci ne seront pas prises en compte lors d'une mise à jour `$ composer.phar update` des dépendances via composer,
Il vous faudra alors vous rendre sur votre compte packagist puis effectuer la mis à jour manuellement.
Heureusement il est possible d'activer l'auto-update.

Récupérer sur cette même page votre API Token Packagist, puis rendez vous sur gitHub
sur la page de votre bundle cliquez sur l'onglet "Settings" en haut à droite.

![Git setting](/images/blog/git_settings.png)

Puis allez sur l'onglet "Service Hooks". Dans la liste de services, sélectionnez Packagist.
Indiquez votre API Token et le nom d'utilisateur de votre compte. Cochez le bouton "Active" et validez.

![Hook services](/images/blog/service_hooks.png)

Vous pouvez retourner sur [https://packagist.org/profile/](https://packagist.org/profile/). Rechargez la page.
Si tout s'est bien passé, le message vert indiquant le type de mise à jour à disparu.

## Intallation de votre bundle via composer ##

Et c'est tout! Vous pouvez vérifier le bon fonctionnement des dépendances.

Pour cela, Ajoutez la dépendance dans un de vos projet Symfony2 (version 2.1 ou +).
Pour cela modifiez le fichier `composer.json` présent à la racine du projet
(Les vendors doivent bien sûr avoir été préalablement installés avec composer).

Voici un exemple permettant d'ajouter une dépendance avec le Bundle [PartnerBundle](https://github.com/IDCI-Consulting/PartnerBundle):

    ...
    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.1.*",
        ...
        "idci/partner-bundle": "master-dev" //ajout du bundle en question
    },
    ...

Lancer la mise à jour des dépendances:

    $ php composer.phar update

Votre bundle devrait s'installer automatiquement dans les `vendors`, au bon emplacement
si votre fichier `composer.json` est correctement rédigé. Il ne vous reste plus qu'à le développer !

Si vous avez besoin d'une aide ou d'une expertise pour vos projets symfony2 vous pouvez
[nous contacter](http://www.idci-consulting.fr/contact "Contactez-nous").
