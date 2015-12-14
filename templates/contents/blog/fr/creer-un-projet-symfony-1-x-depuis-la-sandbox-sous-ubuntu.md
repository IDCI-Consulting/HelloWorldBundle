# Créer un projet Symfony 1.x depuis la sandbox sous Ubuntu #

![Symfony1 logo](/images/blog/symfony1.png "Symfony1 logo")

## Introduction ##

Ce tutoriel explique comment mettre en place un projet Symfony depuis la sandox sous Ubuntu.
Le but de ce post est de proposer une recette clé en main pour ne rien oublier lors de la création de vos nombreux projets avec le framework PHP Symfony.

Si vous ne savez pas encore ou stocker votre projet, je vous recommande de créer un dossier workspace à la racine de votre Home Directory

mkdir $HOME/workspace

## Téléchargement de la sandox de Symfony ##

Vous pouvez récupérer la sandox depuis la page 'installation' sur le site du projet [Symfony](http://www.symfony-project.org/installation)
puis télécharger la version sandbox pour Symfony 1.4.

Ou bien directement par ces liens pour télécharger la sandox avec la dernière version du framework Symfony 1.4x

* [symfony-1.4.x.tgz](http://www.symfony-project.org/get/sf_sandbox_1_4.tgz)
* [symfony-1.4.x.zip](http://www.symfony-project.org/get/sf_sandbox_1_4.zip)

Ou enfin

    $ wget http://www.symfony-project.org/get/sf_sandbox_1_4.tgz $HOME/workspace

## Extraction de Symfony ##

Décompresser l'archive récupérée.

     $ tar -zxvf $HOME/workspace/sf_sandbox_1_4.tgz

Puis renommer ce dossier en PROJECT_NAME.

    $ cp -R $HOME/workspace/sf_sandbox_1_4 $HOME/workspace/PROJECT_NAME

## Configurer un vhost apache ##

Nous allons créer (ou éditer si vous l'avez déjà créé) un fichier vhost. Dans notre cas nous avons un fichier vhost nommé sfProject dans lequel nous déclarons tous les projets Symfony que nous développons. Vous pouvez également créer un fichier vhost par projet afin de plus facilement les activer/désactiver au niveau d'Apache.

    $ sudo gedit /etc/apache2/sites-available/sfProject

Puis déclarer votre vhost pour votre nouveau projet comme suit:


    ServerName local.PROJECT_NAME
    
    DocumentRoot /home/USER_NAME/workspace/PROJECT_NAME/web
    
    Options Indexes FollowSymLinks
    AllowOverride All
    
    Alias /sf /home/USER_NAME/workspace/PROJECT_NAME/lib/vendor/symfony/data/web/sf
    
    Order Allow,Deny
    Allow from all

    # Possible values include: debug, info, notice, warn, error, crit, alert, emerg.
    LogLevel warn
    ErrorLog /var/log/apache2/PROJECT_NAME_error.log
    CustomLog /var/log/apache2/PROJECT_NAME_access.log combined

Puis activer votre vhost si vous venez de le créer:

    $ sudo a2ensite sfProject

Cette commande créer un lien symbolic depuis votre fichier /etc/apache2/sites-available/sfProject vers /etc/apache2/sites-enabled/sfProject.
Pour vérifier si votre fichier est bien déclaré comme "à activer" au chargement d'apache, regarder si il est bien présent dans le dossier /etc/apache2/sites-enabled/

    $ ls -l /etc/apache2/sites-enabled/

Vous devez avoir cette ligne

    $ lrwxrwxrwx 1 root root 28 2010-07-05 18:24 sfProject -> ../sites-available/sfProject

Enfin pensez à redémarrer votre serveur Apache si vous avez apporté des modifications ou venez de créer le vhost ou encore de l'activer/désactiver.

    $ sudo /etc/init.d/apache2 restart

## Ajouter une entrée dans votre host ##

Pour pouvoir utiliser votre vhost Apache nouvellement créée il faut envoyer vos requêtes HTTP vers votre serveur (en local) avec le bon host en paramètre, pour cela le plus simple c'est de modifier votre fichier host afin d'envoyer toutes les requêtes vers votre serveur Apache.

    $ sudo gedit /etc/hosts

puis ajouter la correspondance suivante:

    127.0.0.1 local.PROJECT_NAME

Pour tester si vous avez correctement configuré votre fichier host, exécuter une simple commande ping:

 $ ping local.PROJECT_NAME

Vous devez obtenir le résultat suivant:

    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.049 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.049 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.049 ms
    64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.049 ms
    ...

## Projet créé ! ##

Le projet Symfony doit maintenant être créé
Vous devez avoir le résultat suivant affiché dans votre navigateur à l'adresse http://local.PROJECT_NAME.

![Symfony Project Created](/images/blog/sf_project_created.png "Symfony Project Created")

Il ne vous reste plus qu'a faire votre projet avec Symfony.
Pour tout de suite vous familiariser avec les commandes, définissez l'auteur du code source

    $ cd $HOME/workspace/PROJECT_NAME

puis

    $ php symfony configure:author "FirstName LastName"