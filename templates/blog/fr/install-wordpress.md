﻿
# Installer WordPress #

![Wordpress logo](/build/images/wordpress.png "Wordpress logo")

Avant d'installer WordPress, nous vous recommandons la lecture de notre article sur [la mise en place d'un environnement de développement]({{ path('website_article', { file: 'environment-php-mysql' }) }}).


## Récupérer les sources de WordPress

Nous vous recommandons de créer un dossier de travail, si ce n'est pas encore fait.

Nous récupérons ensuite les téléchargeants :

 - [Version anglaise](http://wordpress.org/download/)
 - [Version française](http://fr.wordpress.org/)

Il faut ensuite décompressez l'archive récupéré, dans notre dossier de travail.

Assurez-vous d'avoir les bons droits sur le dossier contenant les fichiers sources de WordPress.

Pour les systèmes Debian et dérivés (Ubuntu, ...), exécuter les commandes suivantes :

```sh
    $ sudo chgrp www-data -R WORKSPACE/WP_PROJECT
    $ sudo chmod 775 -R WORKSPACE/WP_PROJECT
```

Puis, nous remplaçons :

 - `WORKSPACE` par l'emplacement de notre répertoire de travail
 - `WP_PROJECT` par le nom de notre projet

Pour vérifier la bonne application des droits, lancer la commande suivante :

```sh
    $ ls -l WORKSPACE
```

Le résultat suivant doit être obtenu :

```
    drwxrwxr-x 6 USER www-data 4096 2011-01-23 10:45 WP_PROJECT
```


## Installer WordPress

Nous ouvrons un navigateur et accédons à l'URL prédéfini pour notre site (ex: **http://local.domain/**)
L'application détecte alors que c'est notre première connexion (de par l'absence de configuration) et propose alors de procéder à l'installation de WordPress.
Le processus d'installation de WordPress est simple et intuitif.

Voici les informations à fournir :

 - Les paramètres d'accès à la base de donnée MySQL
 - Le titre de notre site
 - Le login, le mot de passe et le mail de l'administrateur

Voici une série d'écrans présentant l'installation de WordPress 3.2.1 :

![WP Install 1](/build/images/wp_install_1.png "Etape 1")

![WP Install 2](/build/images/wp_install_2.png "Etape 2")

Durant cette étape, il faudra renseigner les informations de connexion à la base de données :

![WP Install 3](/build/images/wp_install_3.png "Etape 3")

![WP Install 4](/build/images/wp_install_4.png "Etape 4")

![WP Install 5](/build/images/wp_install_5.png "Etape 5")

![WP Install 6](/build/images/wp_install_6.png "Etape 6")

Voilà, vous venez d'installer WordPress !


## Accèder à l'espace d'administration (back office)

Pour l'administration de notre site, nous nous rendons à l'adresse suivante : **http://local.domain/wp-admin**.
Nous y saisissons ensuite les login et mot de passe prédéfini, ce qui nous permettra l'accès à notre espace personnel.

![WP Install 8](/build/images/wp_install_8.png "Etape 8")


## Visualiser notre site (front office)

Pour visualiser votre site, rendez-vous à l'adresse **http://local.domain**

![WP Install 7](/build/images/wp_install_7.png "Etape 7")


Bonne découverte !

Si vous avez une question ou un projet web à réaliser, n'hésitez pas à nous [contacter]({{ path('website_contact') }} "Contactez-nous").
