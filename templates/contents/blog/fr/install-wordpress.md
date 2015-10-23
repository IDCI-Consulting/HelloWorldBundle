﻿
# Installer WordPress #
![Wordpress logo](/images/blog/wordpress.png "Wordpress logo")

Avant d'installer WordPress, je vous recommande la lecture de cet article sur comment
[mettre en place un environnement de développement pour réaliser un site avec php et mysql]({{ path('article', { _locale: app.translator.locale, file: 'environment-php-mysql' }) }}).

## Récupérer les sources de WordPress ##

Commencez par créer un dossier de travail (si ce n'est pas déjà fait). Puis récupérez
les sources de WordPress en les téléchargeants:

* [Version anglaise](http://wordpress.org/download/)
* [Version française](http://fr.wordpress.org/)

Décompressez l'archive ainsi récupérée dans votre dossier de travail. Assurez-vous
de bien avoir positionné les bons droits sur le dossier contenant les fichiers sources
de WordPress.

Pour les systèmes Debian et dérivés (Ubuntu, ...), exécutez les commandes suivantes:

    $ sudo chgrp www-data -R WORKSPACE/WP_PROJECT
    $ sudo chmod 775 -R WORKSPACE/WP_PROJECT

Remplacez WORKSPACE par l'emplacement de votre répertoire de travail et WP_PROJECT
par le nom de votre projet. Pour vérifier que les droits sont correctement positionnés,
vous pouvez lancer la commande suivante:

    $ ls -l WORKSPACE

Vous devez obtenir le résultat suivant:

    drwxrwxr-x 6 USER www-data 4096 2011-01-23 10:45 WP_PROJECT

## Installer WordPress ##

Ouvrez un navigateur puis accéder à l'URL que vous avez défini pour votre site (ex: **http://local.domain/**)
L'application détecte alors que c'est votre première connexion (par l'absence de configuration)
et vous propose alors de procéder à l'installation de WordPress. Le processus d'installation
de WordPress est simple et intuitif. Les informations qu'il vous faudra fournir sont:

* les paramètres d'accès à la base de donnée MySQL
* le titre de votre site
* le login, le mot de passe et le mail de l'administrateur

Voici une serie d'écran vous présentant l'installation de WordPress 3.2.1

![WP Install 1](/images/blog/wp_install_1.png "Etape 1")

![WP Install 2](/images/blog/wp_install_2.png "Etape 2")

Durant cette étape, il vous faudra renseigner les informations de connexion à la base de donnée.
![WP Install 3](/images/blog/wp_install_3.png "Etape 3")

![WP Install 4](/images/blog/wp_install_4.png "Etape 4")

![WP Install 5](/images/blog/wp_install_5.png "Etape 5")

![WP Install 6](/images/blog/wp_install_6.png "Etape 6")

Voilà vous venez d'installer WordPress !

## Accèder à l'espace d'administration (Back-Office) ##

Pour l'administration de votre site il faudra aller à l'adresse suivante **http://local.domain/wp-admin**
puis saisir vos login et mot de passe définis durant la phase d'installation pour
pouvoir accéder à l'espace d'administration de votre site.

![WP Install 8](/images/blog/wp_install_8.png "Etape 8")

## Visualiser votre site (Front-Office) ##

Pour visualiser votre site, rendez-vous à l'adresse **http://local.domain**

![WP Install 7](/images/blog/wp_install_7.png "Etape 7")

Bonne découverte !