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
* If it's not the case, it will effectuate an away resolution. The questioned DNS will return the answer if it knows the searched association, otherwise, it will question an other DNS server and so on and so forth until it obtains...
* The answer
* The computer which made the request originally will put the obtained answer in cache for potentials futures requests so it can contact server related to searched domain.


To define locally an association between an IP adress and a domain name, you have to add an entry in the ['hosts' file](https://en.wikipedia.org/wiki/Hosts_(file)).

In the case of a local developpment, to associate the adress **local.domain** to your own machine, ie the [localhost](https://en.wikipedia.org/wiki/Localhost). You have to add the entry **127.0.0.1 local domain**.

With Debian system and others (Ubuntu, etc) edit the **hosts files**:

    $ sudo vi /etc/hosts

Then, add the assocation IP adress / Domain name you want:

    127.0.0.1 local.domain

To check the consideration of your change by your system, effectuate a ping request.
You have to obtain this result :

    $ ping local.domain
    PING localhost (127.0.0.1) 56(84) bytes of data.
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.041 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=3 ttl=64 time=0.033 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=4 ttl=64 time=0.027 ms
    ....

If you obtain this message:


    $ ping local.domain
    ping: unknown host local.domain

It's that you didn't had the entry correctly in your hosts file.

## Configure MySQL ##

[MySQL](https://en.wikipedia.org/wiki/MySQL) is a DBMS (Data Base Managment Systeme). It's a free software developped under a double licence according to the utilisation :

* In a free product: **general public license (GNU)**
* In an owner product: **charged licence** 

For applications like WordPress, Joomla, Drupal, PrestaShop, Magento, etc, you have to inform, during the installation phase, informations for connexions to the MySQL's database.


A good practice includes to create a user with defined access on a base, rather than use the same account on every bases (for example, the root account). It allows to have a better compartmentalization and a best security. It's not an obligation in a development's environment, but it's intensely recommand to a production's environment.
For this, you can use the tool [phpMyAdmin](https://en.wikipedia.org/wiki/PhpMyAdmin), easy to reach from a browser, and apply this : 

![Privileges](/images/blog/pma_privileges.png "Go to the Privilege tab")

![Nouveau utilisateur](/images/blog/pma_new_user.png "New user")

![Ajouter utilisateur](/images/blog/pma_add_new_user.png "Add a new user")

![Go](/images/blog/pma_go.png "Validate")

<p class="notice question" markdown="1">
The access URL to your phpMyAdmin is different according to your operating system or your configuration. With Debian system and others (Ubuntu, etc), it accesibles from the URL http://localhost/phpmyadmin.
</p>

You could also execute theses SQL requests by replacing **user** by the user name you want to create and *** by the password:

    CREATE USER 'user'@'%' IDENTIFIED BY '***';

    GRANT USAGE ON * . * TO 'user'@'%' IDENTIFIED BY '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

    CREATE DATABASE IF NOT EXISTS `user` ;

    GRANT ALL PRIVILEGES ON `user` . * TO 'user'@'%';

To sum up, you just created a base named **user** and a user **user** with the password **user** available having all rights on this database.

    host: localhost
    nom de la base de donnée: user
    utilisateur: user
    mot de passe: user

These informations will be request to you during an application PHP's intallation which use the DBMS to store datas.


If you need an assistance to arrange a development's environment to your teams, or an help to deployment and migration of your website, you could [contact us]({{ path('contact', {_locale: app.translator.locale}) }} "Contact us").


