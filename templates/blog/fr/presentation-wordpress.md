﻿
# Présentation de WordPress

![WordPress logo](/build/images/wordpress.png "WordPress logo")


## A quoi sert WordPress ?

**WordPress** est un **CMS** (Content Management System : système de gestion de contenu),
qui vous permet de facilement rédiger du contenu sur le ou les sujets de votre choix et
de les mettre à disposition (ou non d'ailleurs) sur le Web.

Historiquement, WordPress fut créé pour répondre à la problématique du [Blog](http://fr.wikipedia.org/wiki/Blog).
C'est un conteneur de "posts", c'est à dire des articles agglomérés au fil du temps sur différents sujets.

Depuis sa création en 2003, WordPress a beaucoup évolué et offre aujourd'hui de nombreuses fonctionnalités, sa communauté
est très active et elle propose de nombreux plugins et extensions.

De plus WordPress est un outil gratuit développé sous licence **Open Source** GPLv2+ avec le langage **PHP**.

WordPress est devenu une formidable boite à outils pour la réalisation de site web.
Cependant d'autres solutions existent et dans certain cas se montre plus intéressante que WordPress.
Par exemple, si vous souhaitez réaliser une boutique en ligne, il vaudra mieux s'orienter
vers une solution métier ou des outils de type prestashop.

## Comment fonctionne WordPress ?

WordPress est une application PHP qui est destinée à fonctionner sur le Web.
Pour pouvoir utiliser WordPress il faut un [serveur HTTP](http://fr.wikipedia.org/wiki/Serveur_HTTP)
configuré pour pouvoir interpréter le langage PHP et un Système de Gestion de
Base de Donnée Relationnelle (SGBDR) : [MySQL](http://www.mysql.fr/).

Voici un schéma mettant en scène chaque application dans le traitement d'une requête HTTP reçue par un serveur web
hébergeant un site fait sous WordPress:

![WordPress web request](/build/images/web_server.png "WordPress web request")

 - Quand une **requette HTTP** est reçue par le **server Web**, celui-ci récupère le fichier "index" de **WordPress** défini dans le...
 - **Virtual Host**. Ce fichier PHP ainsi que tous ceux inclus par ce dernier sont interprétés par...
 - **le moteur de rendu PHP**. Les contenus stockés dans la base de données **MySQL** sont...
 - **Requêtés par SQL**. Le rendu **HTML** une fois réalisé est envoyé en...
 - **Réponse HTTP** au client web (= navigateur : Firefox, chromium, etc).

L'application se compose :

 - D'un [Front-Office](http://fr.wikipedia.org/wiki/Front_office_%28informatique%29).
qui sera la partie visible des internautes lorsque ceux-ci navigueront sur votre
site web accessible par une [URL](http://fr.wikipedia.org/wiki/url) bien définie.

 - D'un [Back-Office](http://fr.wikipedia.org/wiki/Back_office_%28informatique%29)
dédié à l'administration de votre site Web accessible par l'URL d'accueil de votre
site web suivi de **/wp-admin** (par défaut). Cet espace est réservé et nécessitera
de vous authentifier par un login et un mot de passe.


## Avantages et inconvénients de WordPress

Avantages :

 - Un **Back office complet**, facile à prendre en main
 - Un **Front office** très facilement **personnalisable** grâce aux thèmes
 - De nombreux **thèmes** et **plugins** téléchargeables gratuitement
 - C'est un outil **Open Source** et gratuit soutenu par une **communauté** très active

Inconvénients :

 - La réalisation d'un thème nécessite des connaissances basiques dans les langages HTML, CSS et PHP
 - Ce n'est pas la solution miracle pour la réalisation de tout type de site, il est
spécialisé dans la mise en ligne de contenus facilement éditables (CMS)


## Dans quels cas utiliser WordPress ?

L'utilisation de WordPress peut être un bon choix pour :

 - La réalisation d'un **blog**
 - La réalisation d'un **site vitrine**
 - La réalisation d'un **site d'information**

Dans les cas suivants il est souvent préférable de choisir une autre solutions:

 - La réalisation d'un site e-commerce (ventes de produits en ligne).
 - Plus généralement, la réalisation d'une application web orienté métier.

Si vous avez besoin d'une aide ou d'une expertise vous pouvez [nous contacter](http://www.idci-consulting.fr/contact "Contactez-nous").
