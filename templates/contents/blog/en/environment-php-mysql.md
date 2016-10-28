# Implement a workspace of developpment to realize a website with PHP and MySQL

![AMPP logos](/images/blog/apache-mysql-php-phpmyadmin.png)

## Why implement a workspace of developpment ?

We recommand you to work locally, in other words, to work directly on your computer.
You have to configure it to make it work :

* A web server interpreting PHP language
* The MySQL SGBDR to store datas of your application

This is the best way to take in charge and understand the functioning of most of applications realized with **PHP** and **MySQL** (WordPress, Joomla, Drupal, PrestaShop, Magento...)

So, it will be easier to :

* Developp the website (modificate templates and CSS files) without having to synchronize your files on a server
* Test plugins and templates without affect your website when it will be online
* Not be worried about a bad manipulation which could harm your datas.

Ideally, use a [revision control software](https://en.wikipedia.org/wiki/Version_control) in order to keep the historic of your website's revisions.

If you have to work in teams, it's an essential tool to set up at the beggining of your project ([Subversion](http://subversion.tigris.org/), [Git](http://git-scm.com/)).

Finally, we suggest you to use a [bug tracker](https://en.wikipedia.org/wiki/Comparison_of_issue-tracking_systems).

## Choose the HTTP server

Here's a non-exhaustive list you could intall on your computer :

* [Apache](http://httpd.apache.org/)
* [Lighttpd](http://www.lighttpd.net/)
* [Cheeroke](http://www.cherokee-project.com/)
* [NGNIX](http://nginx.org/)
* ...

Here's [a comparative of differents HTTP servers](http://en.wikipedia.org/wiki/Comparison_of_web_server_software).

To start, we recommand to use the **HTTP Apache server** version 2.X, it's the more common server ([netcraft](http://news.netcraft.com/archives/category/web-server-survey/)).
It has a lots of units and it's certainly one of the most complete in terms of functionalities.
Furthermore, it is packaged ready to use on a lot of operating systems.

* [WAMP](http://www.wampserver.com/en/) or [EasyPHP](http://www.easyphp.org/) under Windows
* [MAMP](http://www.mamp.info/en/index.html) under MacOs
* From repositories (apt, yum) under Linux

With Debian system and drifts (Ubuntu, etc), here's the request to install everything you need to make WordPress work :

    $ sudo apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin

Once your HTTP server is installed on your computer, you could consult the web page delivered by default by requesting your [localhost](https://en.wikipedia.org/wiki/Localhost).
For it, open a web browser and write this adress : **http://localhost**.

![phpinfo](/images/blog/it_works_apache.png)

<p class="notice question" markdown="1">
Sometimes, you have to specify the port, when it is differents of the one by default (port 80). For example, it's the case for MAMP on MacOS, you have to inform this adress http://localhost:8888.
</p>

## Configuration of a web server to interpret the PHP language


To make your application PHP run, you need that your HTTP server be able to interpret this language.
For example, with the Apache server, you have two choices :

* PHP in Apache module
* PHP in CGI

A lot of website display differences between these two methods. By default, Apache server will interpret the PHP code with the "module Apache" method, but you can configure it.

To check is your HTTP server has a good configuration to execute PHP code, you could create a file named phpinfo.php and drop it off the root of your HTTP server:

* c:\wamp\www\ avec WAMP sous Windows
* c:\EasyPHP\www avec EasyPHP sous Windows
* /Applications/MAMP/htdocs avec MAMP sous MacOS
* /var/www sous Linux
* ...

Write this in your phpinfo.php file :

    <?php php_info(); ?>

Then, request the URL **http://localhost/phpinfo.php**.
You should get that result :

![phpinfo](/images/blog/phpinfo.png)

## UseVirtualHosts ##

Virtual hosting is a method which allow to host several [domain names](**http://localhost/phpinfo.php**) on a physical server by using one [IP adress](https://en.wikipedia.org/wiki/IP_address). It allows to run server's ressources (memory, processor) by sharing them to websites's needs.

![Virtual Hosting](/images/blog/virtual_hosting.png)

Also, it's a good practice to implement when you work locally.
It allows to choose differents domains's names for each of your website.
For example :

* http://mon_site1
* http://mon_site2
* ...

Instead of URL like that :

* http://localhost/mon_site1
* http://localhost/mon_site2
* ...

Then, it allows you to store websites without respecting a strict tree view. It's your job to associate a physical space on the server files's system to a domain name.

Here's an example of a declaration of a Virtual Host, for Apache.
Replace **local.domain** by the domain name you want use, and **domain_path** by the physical space of the file including files of the website to associate.

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
    CustomLog /var/log/apache2/access_local.domain.log combined

<p class="notice question" markdown="1">
After every modifications on Apache configuration's files, don't forget to restart service, so your new parameters will taken into account.
</p>

To restart the **Apache service** on the Debian system, or others (Ubuntu, etc)

    $ sudo /etc/init.d/apache2 restart

or

    $ sudo service apache2 restart

## Modify a DNS entry locally : add a line in the hosts file

The Domain Name System [DNS](https://en.wikipedia.org/wiki/Domain_Name_System) is a service which allow to establish a correspondence between an IP adress and a domain name. When you enter an URL in your browser, your computer realizes a name resolution and collect the IP adress associated to the domain you asked for. This IP adress will allow to sent a HTTP request to destination of the good server. You can also define locally couples **IP adress Domain name**

![DNS resolution](/images/blog/dns_resolution.png "DNS resolution")

During a DNS resolution, your computer will execute differents things :
* Local resolution : search if the DNS entry is define locally. If it's the case, it will use this adresse.
*If it's not the case, 



Lors d'une résolution DNS votre ordinateur va commencer par faire une **1] Resolution local**,
c'est à dire rechercher si l'entrée DNS recherchée est définie localement. Si oui,
utilisation de cette adresse, sinon il va effectuer une **2] Résolution distante**.
Le serveur DNS interrogé retournera la reponse si il connait l'association recherchée,
sinon il interrogera un autre serveur DNS et ainsi de suite jusqu'à l'obtention de
**3] La réponse**. L'ordinateur à l'origine de la demande mettra **4] En cache la réponse**
ainsi obtenue pour d'éventuelles futures requêtes et pourra ainsi contacter le serveur
corespondant au domaine recherché.

Pour définir localement une association entre une adresse IP et un nom de domaine
il faut ajouter une entrée dans [le fichier 'hosts'](http://fr.wikipedia.org/wiki/Hosts).

Dans le cas d'un développement local, pour associer l'adresse **local.domain** à votre
propre machine c'est à dire le [localhost](http://fr.wikipedia.org/wiki/Localhost)
il faut ajouter l'entrée **127.0.0.1 local.domain**.

Sous système Debian et dérivés (Ubuntu, ...), éditer **le fichier hosts**:

    $ sudo vi /etc/hosts

Puis ajouter l'association Adresse IPNom de domaine souhaitée.

    127.0.0.1 local.domain

Pour vérifier la prise en compte de votre changement par votre système, effectuer
une requête ping. Vous devez obtenir le résultat suivant:

    $ ping local.domain
    PING localhost (127.0.0.1) 56(84) bytes of data.
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.041 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=3 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=4 ttl=64 time=0.027 ms
    ....

Si vous obtenez le message suivant:

    $ ping local.domain
    ping: unknown host local.domain

C'est que vous n'avez pas ajouté correctement l'entrée dans votre fichier hosts.

## Paramètrer MySQL ##

[MySQL](http://fr.wikipedia.org/wiki/MySQL) est un SGBDR (Système de Gestion de Base de Donnée Relationnelle).
C'est un logiciel libre développé sous double licence en fonction de l'utilisation
qui en est faite :

* Dans un produit libre: **licence publique générale GNU (GPL)**
* Dans un produit propriétaire: **licence payante**

Pour les applications comme WordPress, Joomla, Drupal, PrestaShop, Magento, ...
il faudra renseigner, durant la phase d'intallation, les informations pour la connexion
à la base de donnée MySQL.

Une bonne pratique consiste à créer un utilisateur avec des accès bien définis sur une base,
plutôt que d'utiliser le même compte sur toutes les bases (par exemple le compte root).
Cela permet un meilleur cloisonnement et offre ainsi une meilleure sécurité. Ce n'est
pas une obligation dans un environnement de développement mais vivement recommandé
dans un environnement 'dit' de production.
Pour cela vous pouvez utiliser l'outil [phpMyAdmin](http://fr.wikipedia.org/wiki/PhpMyAdmin),
accessible depuis un navigateur, et procéder comme suit:

![Privileges](/images/blog/pma_privileges.png "Aller sur l'onglet privilège")

![Nouveau utilisateur](/images/blog/pma_new_user.png "Nouveau utilisateur")

![Ajouter utilisateur](/images/blog/pma_add_new_user.png "Ajouter un nouveau utilisateur")

![Go](/images/blog/pma_go.png "Valider")

<p class="notice question" markdown="1">
L'URL d'accès à votre phpMyAdmin est différente suivant votre système d'exploitation
ou suivant votre configuration. Sous système Debian ou dérivés (Ubuntu, ...) il
est accessible depuis l'URL suivante: http://localhost/phpmyadmin
</p>

Vous pouvez également exécuter les requêtes SQL suivantes en replaçant **user**
par le nom de l'utilisateur que vous voulez créer et *** par le mot de passe

    CREATE USER 'user'@'%' IDENTIFIED BY '***';

    GRANT USAGE ON * . * TO 'user'@'%' IDENTIFIED BY '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

    CREATE DATABASE IF NOT EXISTS `user` ;

    GRANT ALL PRIVILEGES ON `user` . * TO 'user'@'%';

En résumé vous venez de créer une base nommée **user** et vous avez créé un utilisateur
**user** avec le mot de passe **user** disposant de tous les droits sur cette base.

    host: localhost
    nom de la base de donnée: user
    utilisateur: user
    mot de passe: user

Ces informations vous seront demandées lors de l'installation d'une application PHP
qui utilise le SGBDR MySQL pour stocker des données.

Si vous souhaitez une assistance pour la mise en place d'un environnement de développement
pour vos équipes ou encore une aide pour le déploiement et la migration de votre site,
vous pouvez nous [contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").
