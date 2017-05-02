# Mettre en place un environnement de développement pour réaliser un site avec PHP et MySQL #
![AMPP logos](/images/blog/apache-mysql-php-phpmyadmin.png)


## Pourquoi mettre en place un environnement de développement ?

Tout d'abord, nous vous recommandons de travailler en local, c'est-à-dire directement sur votre ordinateur.
Cela facilitera la prise en main et la compréhension du fonctionnement de la plupart des applications réalisées avec les technologies **PHP** et **MySQL**, à l'exemple de WordPress, Joomla, Drupal, PrestaShop, Magento, etc.

Nous devrons configurer celui-ci pour pouvoir faire fonctionner :

 - Un serveur Web interprétant le langage PHP
 - Le SGBDR MySQL pour stocker les données de votre application

Ainsi, il sera plus facile de :

 - Développer le site (modifier les templates et vos fichiers css) sans avoir à synchroniser nos fichiers sur un serveur
 - Tester des plugins et des thèmes rapidement et sans impacter votre site une fois celui-ci en ligne
 - Ne pas vous soucier d'une éventuelle mauvaise manipulation qui pourrait porter atteinte à vos données


Nous vous conseillons d'utiliser un [outil de gestion de version](http://fr.wikipedia.org/wiki/Gestion_de_versions),
afin de garder l'historique des changements apportés à votre site (révisions).
Si vous devez travailler en équipe, c'est un outil indispensable à mettre en place dès le début de votre projet ([Subversion](http://subversion.tigris.org/), [Git](http://git-scm.com/)).
Enfin, dans le cas d'un projet professionnel, nous vous recommandons vivement l'utilisation
d'un [logiciel de suivi de problèmes](http://fr.wikipedia.org/wiki/Logiciel_de_suivi_de_probl%C3%A8mes).


## Choisir le serveur HTTP ##

Il existe différents serveur HTTP que vous pouvez installer sur votre ordinateur.
Voici une liste non exhaustive:

 - [Apache](http://httpd.apache.org/)
 - [Lighttpd](http://www.lighttpd.net/)
 - [Cheeroke](http://www.cherokee-project.com/)
 - [NGNIX](http://nginx.org/)
 - ...

Voici [un comparatif des différents serveurs HTTP](http://en.wikipedia.org/wiki/Comparison_of_web_server_software)(en.).
Pour commencer,nous vous recommandandons d'utiliser le **serveur HTTP Apache** dans sa version 2.x.
C'est le serveur HTTP le plus repandu au monde ([netcraft](http://news.netcraft.com/archives/category/web-server-survey/)),
il dispose de nombreux modules et c'est certainement l'un des plus complet en terme de fonctionnalités.
De plus il est packagé prêt à l’emploi sur de nombreux systèmes d'exploitations.

 - [WAMP](http://www.wampserver.com/en/) ou [EasyPHP](http://www.easyphp.org/) sous Windows
 - [MAMP](http://www.mamp.info/en/index.html) sous MacOs
 - Depuis des dépôts (apt, yum) sous linux

Sous système Debian et dérivés (Ubuntu, etc), voici la commande pour pouvoir installer tout ce dont vous aurez besoin pour pouvoir faire fonctionner WordpPress :

```sh
    $ sudo apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin
```

Une fois que vous aurez installé votre serveur HTTP sur votre ordinateur, vous pourrez
alors consulter la page Web délivrée par défaut. Pour cela, renseigner l'URL `http://localhost`.

![phpinfo](/images/blog/it_works_apache.png)

<p class="notice question" markdown="1">
Il est parfois nécessaire de préciser le port lorsqu'il est différent de celui par défaut à savoir le port 80.
C'est par exemple le cas pour MAMP sur MacOS, où il vous faudra renseigner l'adresse suivante: http://localhost:8888
</p>


## Configuration du serveur WEB pour interpréter le langage PHP

Pour pouvoir faire fonctionner votre application PHP, il faut que votre serveur HTTP soit capable d’interpréter ce langage.

Dans le cas du serveur Apache, vous avez le choix entre deux méthodes :

 - PHP en module Apache
 - PHP en CGI

De nombreux sites exposent les différences entre ces deux méthodes. Par défaut, le serveur Apache interprétera le code PHP par la méthode "module Apache", mais ceci est paramétrable.
Pour vérifier que le serveur HTTP est bien configuré pour l'éxécution de code PHP, vous pouvez créer un fichier nommé phpinfo.php et le déposer à la racine Web de votre serveur HTTP :

 - c:\wamp\www\ avec WAMP sous Windows
 - c:\EasyPHP\www avec EasyPHP sous Windows
 - /Applications/MAMP/htdocs avec MAMP sous MacOS
 - /var/www sous Linux
 - ...

Dans ce fichier, écrire le code PHP suivant :

```
    <?php php_info(); ?>
```

Puis, requêter l'URL `http://localhost/phpinfo.php`.
Vous devez obtenir le résultat suivant :

![phpinfo](/images/blog/phpinfo.png)

## Utiliser les VirtualHosts ##

Le virtual hosting est une méthode qui permet d'héberger plusieurs [noms de domaine](http://fr.wikipedia.org/wiki/Nom_de_domaine) sur un serveur physique, en utilisant une seule [adresse IP](http://fr.wikipedia.org/wiki/Adresse_IP).
Cela permet de mieux exploiter les ressources (mémoire, processeur) du serveur en les partageant, pour les besoins de plusieurs sites.

![Virtual Hosting](/images/blog/virtual_hosting.png)

C'est également une bonne pratique à mettre en place lorsque vous travaillez localement.
Cela permet de choisir des noms de domaines différents pour chacun de vos sites.

Par exemple:

 - http://mon_site1
 - http://mon_site2
 - ...

Au lieu des URLs du type :

 - http://localhost/mon_site1
 - http://localhost/mon_site2
 - ...

Cela permet également de stocker des sites web sans respecter une arborescence stricte.
C'est donc votre rôle d'associer l'emplacement physique d'un site (sur le système de fichiers du serveur) à un nom de domaine.

Voici à quoi ressemble la déclaration d'un Virtual Host pour Apache.
Remplacez **local.domain** par le nom de domaine que vous souhaitez utiliser et **domain_path** par l'emplacement physique du dossier contenant les fichiers du site web à associer.

```
    ServerName local.domain
    ServerAdmin webmaster@local.domain.fr

    DocumentRoot /home/user/workspace/domain_path


    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    allow from all

    ErrorLog /var/log/apache2/error_local.domain.log
    # Possible values include: debug, info, notice, warn, error, crit, alert, emerg.
    LogLevel warn
    CustomLog /var/log/apache2/access_local.domain.log combine
```

<p class="notice warning" markdown="1">
Après chaque modifications dans les fichiers de configuration d'apache, pensez à redémarrer le service pour que les nouveaux paramètres soient pris en compte.
</p>

Pour redémarrer le **service Apache** sur système Debian et dérivés (Ubuntu, ...) :

```
    $ sudo /etc/init.d/apache2 restart
```

ou

```
    $ sudo service apache2 restart
```


## Modifier une entrée DNS localement : Ajouter une ligne dans le fichier hosts

Un peu de théorie :

Le Domaine Name System [DNS](http://fr.wikipedia.org/wiki/Domain_Name_System) est un service permettant d'établir une correspondance entre une adresse IP et un nom de domaine.
Lorsque vous saisissez une URL dans votre navigateur, votre ordinateur va réaliser une résolution de nom, c'est-à-dire récupérer l'adresse IP associée au domaine demandé.

Cette adresse IP ainsi obtenue va permettre d'envoyer une requête HTTP à destination du bon serveur.
Il est également possible de définir localement des couples **Adresse IP / Nom de domaine**.

![DNS resolution](/images/blog/dns_resolution.png "DNS resolution")

Lors d'une résolution DNS votre ordinateur va commencer par faire :

 - **Une résolution locale** : recherche si l'entrée DNS recherchée est définie localement.
Si oui, cette adresse est utilisée, sinon, il va effectuer...
 - **Une résolution distante** . Le serveur DNS interrogé retournera la reponse si il connait l'association recherchée,
sinon il interrogera un autre serveur DNS et ainsi de suite jusqu'à l'obtention de...
 - **La réponse**. L'ordinateur à l'origine de la demande mettra...
 - **La réponse en cache**, pour d'éventuelles futures requêtes et pourra ainsi contacter le serveur correspondant au domaine recherché.

Pour définir localement une association entre une adresse IP et un nom de domaine
il faut ajouter une entrée dans le fichier [hosts](http://fr.wikipedia.org/wiki/Hosts).

Dans le cas d'un développement local, pour associer l'adresse **local.domain** à votre propre machine c'est-à-dire le localhost, il faut ajouter l'entrée **127.0.0.1 local.domain**.

Sous système Debian et dérivés (Ubuntu, ...), éditez **le fichier hosts** :

```sh
    $ sudo vi /etc/hosts
```

Puis, ajoutez l'association Adresse IPNom de domaine souhaitée :

```
    127.0.0.1 local.domain
```

Pour vérifier la prise en compte de votre changement par votre système, effectuez une requête ping.
Vous devez obtenir le résultat suivant:

```sh
    $ ping local.domain
    PING localhost (127.0.0.1) 56(84) bytes of data.
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.041 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=3 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=4 ttl=64 time=0.027 ms
    ....
```

Si vous obtenez le message suivant :

```sh
    $ ping local.domain
    ping: unknown host local.domain
```

C'est que vous n'avez pas ajouté correctement l'entrée dans votre fichier hosts.


## Paramètrer MySQL

[MySQL](http://fr.wikipedia.org/wiki/MySQL) est un SGBDR (Système de Gestion de Base de Donnée Relationnelle).
C'est un logiciel libre développé sous double licence en fonction de l'utilisation qui en est faite :

 - Dans un produit libre : **licence publique générale GNU (GPL)**
 - Dans un produit propriétaire : **licence payante**

Pour les CMS (WordPress, Joomla, Drupal, etc), il faudra renseigner, durant la phase d'installation, les informations pour la connexion à la base de donnée MySQL.

La bonne pratique consiste à créer un utilisateur avec des accès bien définis sur une base, plutôt que d'utiliser le même compte sur toutes les bases (par exemple, le compte root).
Cela permet un meilleur cloisonnement et offre ainsi une meilleure sécurité.
Ce n'est pas une obligation dans un environnement de développement mais vivement recommandé dans un environnement "dit" de production.
Pour cela vous pouvez utiliser l'outil [phpMyAdmin](http://fr.wikipedia.org/wiki/PhpMyAdmin), accessible depuis un navigateur, et procéder comme suit :

![Privileges](/images/blog/pma_privileges.png "Aller sur l'onglet privilège")

![Nouveau utilisateur](/images/blog/pma_new_user.png "Nouveau utilisateur")

![Ajouter utilisateur](/images/blog/pma_add_new_user.png "Ajouter un nouveau utilisateur")

![Go](/images/blog/pma_go.png "Valider")

<p class="notice question" markdown="1">
L'URL d'accès à votre phpMyAdmin est différente suivant votre système d'exploitation
ou suivant votre configuration. Sous système Debian ou dérivés (Ubuntu, ...) il
est accessible depuis l'URL suivantec: http://localhost/phpmyadmin
</p>

Vous pouvez également exécuter les requêtes SQL suivantes en replaçant **user** par le nom de l'utilisateur que vous voulez créer et *** par le mot de passe :

```
    CREATE USER 'user'@'%' IDENTIFIED BY '***';

    GRANT USAGE ON * . * TO 'user'@'%' IDENTIFIED BY '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

    CREATE DATABASE IF NOT EXISTS `user` ;

    GRANT ALL PRIVILEGES ON `user` . * TO 'user'@'%';
```

En résumé, vous venez de créer une base nommée **user** et vous avez créé un utilisateur **user** avec le mot de passe **user** disposant de tous les droits sur cette base.

```
    host: localhost
    nom de la base de donnée: user
    utilisateur: user
    mot de passe: user
```

Ces informations vous seront demandées lors de l'installation d'une application PHP qui utilise MySQL pour stocker des données.

Si vous souhaitez une assistance pour la mise en place d'un environnement de développement
pour vos équipes ou encore une aide pour le déploiement et la migration de votre site,
vous pouvez nous [contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").
