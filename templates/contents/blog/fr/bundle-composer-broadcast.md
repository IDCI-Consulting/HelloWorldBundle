# Créer un bundle Symfony2 réutilisable et le diffuser via Composer #
![Composer Packagist GitHub Symfony2](/images/blog/composer-packagist.png "Composer Packagist GitHub Symfony2")


## Introduction

Avec l'arrivée de **Composer**, nous pouvons rendre nos Bundle Symfony2 (et pas seulement) facilement disponible et réutilisable.

[Composer](http://getcomposer.org/) est un gestionnaire de paquets PHP, inspiré par [npm](https://npmjs.org/) et [Bundler](http://gembundler.com/).
Il requiert la version 5.3.2 de PHP, ou supérieur, pour fonctionner.
Il est multiplateforme et fonctionne parfaitement sur Windows, Linux et OSX.

Cet outil va donc nous permetre d'installer de manière simple et rapide nos `vendors` dans nos projets Symfony2.

Le gestionnaire de paquets Composer s'appuie sur un dépôt nommé [Packagist](https://packagist.org/).
Celui-ci regroupe un ensemble de paquets installable via Composer.

Packagist se contente simplement d'analyer un fichier au format json (composer.json),
contenant un ensemble d'information sur votre bundle, comme son nom, ses dépendances, etc.

Ce fichier `composer.json` doit être à la racine de votre bundle et le tout doit être situé
sur un SCM tel que que [Git](http://git-scm.com/), [Subversion](http://subversion.tigris.org/) ou [Mercurial](http://mercurial.selenic.com/).
Dans notre exemple, nous utiliserons GitHub, bien connu de la communauté Symfony.

L'objectif de cet article est donc de vous montrer comment créér votre propre bundle afin de pouvoir facilement
le réutiliser dans vos futurs projets, et par la même occasion le partager à la communauté des développeurs Symfony2 ;)

Voici rapidement les étapes à effectuer:

* Créer un dépôt pour votre bundle.
* Créer le fichier composer.json le commiter (puis le pusher).
* Créer un compte et enregistrer votre bundle sur packagist.
* Activer la mise à jour automatique du bundle sous packagist (notification lors d'un push sur GitHub par exemple).

Pour faciliter les explications, et car rien ne vaut un bon exemple, nous allons créer un bundle nommé PartnerBundle.
Nous partons du fait que le logiciel de gestion de versions Git ne vous est pas inconnu et que vous possédez déjà un compte sur GitHub.


## Rendre un bundle disponible via composer


### Rédaction du fichier composer.json

Une fois notre dépôt crée et cloné, il nous faut rédiger le fichier `composer.json`.
Notre bundle nécessite sûrement lui même d'autres bundles pour fonctionner, tel que Symfony, twig, doctrine, etc.
Il va falloir indiquer les minimas (dépendances) à respecter pour le faire fonctionner.
Ce fichier se trouve à la racine de notre bundle.

Voici le minimum à indiquer :

```php
    {
        "name": "your-vendor-name/package-name",
        "require": {
            "php": ">=5.3.0"
        }
    }
```

Voici le fichier `composer.json` utilisé pour notre PartnerBundle :

```php
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
```

Pour des informations complémentaires sur le fichier `composer.json`, nous vous renvoyons à la [documentation](http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup) de Composer.
N'hésitez pas à jeter un coup d’œil dans les fichiers composer.json des bundles déjà présents dans les vendors, par exemple.

Une fois rédigé, nous allons ajouter ce fichier avec Git :

```sh
    $ git add composer.json
```

Puis, il nous faut commiter et pusher notre fichier sur GitHub afin que Packagist puisse le valider par la suite.


```sh
    $ git commit -m "Add composer file"
    $ git push
```

### Valider le fichier composer.json

Pour valider le fichier, il faut tout d'abord s'incrire sur [Packagist](https://packagist.org/)/
Puis, choisissez "Submit Package" et entrez l'URL de vôtre dépot git pour le projet correspondant.
Par exemple :

 - https://github.com/Mon-Entreprise/MonBundle.git

![Submit package](/images/blog/packagist_submit_package.png)

Si le fichier `composer.json` est correct, le bundle est alors enregistré et prêt à être intégré à un projet.
Nous devrions obtenir un résultat semblable à ceci :

![Package added](/images/blog/packagist_package_added.png)

### Activer l'auto-update sous Packagist

Si nous nous rendons sur notre [page perso](https://packagist.org/profile/), nous devrions trouver l'ensemble de nos paquets.

Nous pouvons voir que la mise à jour n'est pas automatique.

En d'autres termes, les améliorations et corrections effectuées avec un `$ git push` ne seront pas prise en compte lors d'une mise à jour `$ composer.phar update` des dépendances via Composer.
Il nous faudra nous rendre sur notre compte Packagist, puis effectuer la mise à jour manuellement.

Heureusement il est possible d'activer l'auto-update.

Puis, nous devons récupérer notre API Token Packagist sur cette même page, et nous rendre sur GitHub.
Sur la page de votre bundle, cliquez sur l'onglet "Settings" en haut à droite.

![Git setting](/images/blog/git_settings.png)

Puis, rendons nous sur l'onglet "Service Hooks" :

 - Dans la liste de services, sélectionnez Packagist.
 - Indiquez votre API Token et le nom d'utilisateur de votre compte.
 - Cochez le bouton "Active"
 - Validez.

![Hook services](/images/blog/service_hooks.png)

Puis, retournons sur notre [profil Packagist](https://packagist.org/profile/).
Après avoir rechargé la page, le message vert indiquant le type de mise à jour devrait avoir disparu.


## Installer notre bundle via Composer ##

Et c'est tout !

Nous pouvons vérifier le bon fonctionnement des dépendances.

Pour cela, ajoutez la dépendance dans un de vos projets Symfony2 (version 2.1 ou +).
Modifiez le fichier `composer.json` présent à la racine du projet (les vendors doivent bien sûr avoir été préalablement installés avec Composer).

Voici un exemple permettant d'ajouter une dépendance avec le Bundle [PartnerBundle](https://github.com/IDCI-Consulting/PartnerBundle):

```php
    ...
    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.1.*",
        ...
        "idci/partner-bundle": "master-dev" //ajout du bundle en question
    },
    ...
```

Enfin, lancez la mise à jour des dépendances:

```sh
    $ php composer.phar update
    ```

Notre bundle devrait s'installer automatiquement dans les `vendors`, au bon emplacement, si votre fichier `composer.json` est correctement rédigé.

Il ne vous reste plus qu'à le développer !

Si vous avez besoin d'une aide ou d'une expertise pour vos projets Symfony2 vous pouvez
[nous contacter](http://www.idci-consulting.fr/contact "Contactez-nous").
