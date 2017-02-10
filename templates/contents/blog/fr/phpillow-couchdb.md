# Installer PHPillow pour couchDB sur Symfony2

![Phpillow](/images/blog/phpillow.png)

Vous êtes bloqué pour installer le client PHP PHPillowpour couchdb dans Symfony2 ?
Vous êtes tombés au bon endroit !

<p class="notice question" markdown="1">
Si les mots CouchDB, Symfony2 et PHPillow ne vous semblent pas issus du fin fond
des montagnes sibériques, vous pouvez passez directement à la 2ème partie !
</p>

## PHPillow, CouchDB, Symfony2 : petit mémo

PHPillow est une librairie populaire permettant d'accéder facilement –et en php, à couchDB.
C'est un système de gestion de BDD orienté document (et donc NO-SQL) qui permet de répondre à des problématiques complexes avec un sgbdr classique.
En effet, chacun de vos documents possède les champs que vous voulez bien lui renseigner, comme dans la vie réelle.

Pour une liste de contacts par exemple, vous ne connaissez pas forcément l'adresse email, l'adresse postale, etc.
de chacun, mais seulement quelques infos pour chaque personne.
Mais vous avez pourtant bien une base de contacts, chaque contact étant une même entité à quelques attributs
près (c'est à dire que la liste des contacts forme bien une base de données).

CouchDB fonctionne sur ce principe :

![CouchDB document](/images/blog/couchdb-document.png)

Pour des informations supplémentaires, nous vous renvoyons à la doc [CouchDB](http://guide.couchdb.org/editions/1/en/why.html).


## Comment intégrer PHPillow à Symfony2 ?


Nous avons téléchargé PHPillow, et avons sous les yeux une structure assez complexe :

![CouchDB vendor arborescence](/images/blog/vendor-paths.png)

La partie qui nous intéresse est la partie src. C'est ici que se situe la libraire PHPillow à proprement parler, c'est-à-dire
toutes les classes répondant à la problématique d'échange de données / flux avec CouchDB.
C'est le cœur du projet.

Pour rappel, dans Symfony2 vos projets sont découpés de telle sorte :

 - app: configuration de votre application
 - src: le code source php de votre projet (=vos applications contenant vos bundles)
 - vendor: les librairies tierces utilisables par Symfony
 - web: racine des ressources de votre site web (images, css, js, etc..)

<p class="notice question">
Phpillow est une librairie tierce, au même titre que doctrine (l'ORM installé par défaut dans Symfony2),
ou encore Twig (le moteur de rendu par défaut conseillé dans Symfony2).
</p>

Celle-ci doit donc se placer dans le dossier vendor.

L'une des forces de Symfony2 est son autoloader, et c'est aussi là que ça se complique pour nous.
En effet, PHPillow comporte également un autoloader situé au `chemin /src/classes/autoload.php`.
Heureusement pour nous, nous n'avons pas à connaître le fonctionnement de cet autoloader interne car les développeurs de PHPillow
ont eu la délicate attention de nous fournir un **bootstrap** (comprenez programme d'amorçage)
qui permet d'**autoloader les classes** automatiquement.

C'est donc cette classe qu'il nous faut inclure à notre autoloader Symfony :

```php
    registerNamespaces(array([...]));
    $loader->registerPrefixes(array([...]));
    $loader->registerPrefixFallbacks(array([...]));
    $loader->registerNamespaceFallbacks(array([...]));
    $loader->register();

    […]

    // Autoload PHPillow classes thanks to the boostrap
    include __DIR__.'/../vendor/phpillow/src/bootstrap.php';
```

Si vous êtes toujours là, vous avez peut-être essayé d'instancier une connexion PHPillow,
persuadé qu'à partir de maintenant tout allait fonctionner :

```
    phpillowConnection::createInstance();
```

En faisant ceci vous avez eu droit à l'erreur vous indiquant que la classe phpillowConnection
n'était pas trouvée. Bien essayé !

C'est de notre faute ! Nous ne vous avons pas encore informé de la dernière nouveauté implémentée par PHP 5.3 et dans Symfony2 :
les namespaces.


## Comprendre les namespaces sous Symfony2 : une étape nécessaire pour y intégrer PHPillow

Un peu complexe à appréhender, les namespaces sont une avancée importante dans le monde du PHP !

Pour cela, nous vous renvoyons à cet excellent article en 2 parties (compter 1h pour la compréhension des subtilités
des namespaces, c'est primordial pour se familiariser avec le fonctionnement interne de Symfony2) :

 - [http://blog.pascal-martin.fr/post/php-5.3-namespace-1-espaces-de-noms](http://blog.pascal-martin.fr/post/php-5.3-namespace-1-espaces-de-noms)
 - [http://blog.pascal-martin.fr/post/php-5.3-namespace-2-espaces-de-noms](http://blog.pascal-martin.fr/post/php-5.3-namespace-2-espaces-de-noms)

Déja de retour ? Si vous avez bien compris, vous savez maintenant que pour vous
connecter à CouchDB via PHPillow vous devez écrire :

```
    phpillowConnection::createInstance();
```

En effet, PHPillow ne disposant pas de namespace, ses classes se trouvent dans l'espace global :
celles-ci sont donc accessibles en les faisant précéder d'un antislash. Si elles avaient été
dans un espace de nommage X il aurait fallu y accéder via X\ phpillowConnection.

Mais cela, vous l'avez déjà bien compris n'est-ce-pas ? ;)

<p class="notice question" markdown="1">
Ces namespaces sont valables pour toutes les classes de votre projet ne disposant pas d'un namespace :
vous devrez toujours les instancier de cette façon.
</p>

**Voilà, vous savez désormais tout sur l'ajout de la librairie PHPillow sur Symfony2 !**


Si vous avez une question ou un projet web à réaliser, n'hésitez pas à nous [contacter]({{ path('contact', { _locale: app.translator.locale }) }} "Contactez-nous").
