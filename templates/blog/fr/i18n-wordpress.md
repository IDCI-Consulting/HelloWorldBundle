
# Comment internationaliser un thème WordPress ?
![Wordpress logo](/build/images/wordpress.png "Wordpress logo")


## Internationaliser un thème WordPress

Cet article décrit les grandes étapes permettant d'internationaliser les templates de thème wordpress.
Il s'agit d'une bonne pratique permettant d'anticiper de futures traductions dans d'autres langues.
Pour cela, WordPress utilise le standard PHP pour sites multilingues : les fichiers PO (GetText Portable Object files).
Les fichiers utilisé pour une traduction sont les suivants :

 - ".pot". Un fichier POT (Portable Object Template) est généré à partir du texte d'une application. Dans notre cas il s'agit de wordpress.
 - ".po". Le fichier PO (Portable Object) est crée à l'aide d'un logiciel de traduction tel que poedit. Il contient le texte à traduire, et la traduction correspondante. Il y a donc autant de fichiers PO que de langues à traduire.
 - ".mo". Le fichier MO (Machine Object) contient les mêmes informations que le fichier PO, mais diffère par son format. Un fichier PO est facile à lire pour un humain, tandis qu'un fichier MO est opimisé pour les machines. C'est donc celui-ci qui sera utilisé par votre serveur web.


## Les fonctions __( ) et _e( ) ##

Les textes dans les templates qui ne sont pas saisi en [backoffice]({{ path('website_article', { file: 'backoffice-wordpress' }) }})
et que vous souhaitez traduire doivent être signalés comme étant "internationalisables".
Pour cela il existe deux fonctions, `__()` et `_e()`:

```php
    <?php __("chaine a traduire", "nom_du_theme"); ?>

    <?php
          _e("chaine a traduire", "nom_du_theme");
          // qui équivaut simplement à echo __("chaine a traduire", "nom_du_theme");
    ?>
```

Si vous souhaitez en savoir plus, vous pouvez consulter le [codex](http://codex.wordpress.org/Translating_WordPress).


## Génération d'un fichier pot

WordPress met à disposition un utilitaire PHP pour générer des fichiers ".pot".
Le fichier pot est créé automatiquement à partir des textes qui se trouve dans les fonctions de références `_e()` et `__()` (sans les traductions).
Il est utilisé par la suite pour générer un fichier de traduction par langue supplémentaire.

Pour récupérer l'utilitaire, nous tapons la commande suivante :

```sh
    $ svn checkout http://i18n.svn.wordpress.org/tools/trunk /repertoire/de/destination
```

Puis nous générons le fichier .pot:

```
    $ php makepot.php wp-theme ../wordpress/wp-content/themes/nom_du_theme/
```

Un fichier nommé "nom_du_theme.pot" a alors été généré.
Nous créeons un dossier **languages** à la racine du thème et copions celui-ci dedans.


## Génération d'un fichier de traduction ##

Puis, nous installons le logiciel **Poedit**.

Sous Ubuntu, nous pouvons l'obtenir via apt :

```
    $ sudo apt-get install poedit
```

Par la suite, nous devons :

 - Aller sur `Fichier`, puis `Nouveau catalogue depuis un fichier pot`
 - Sélectionner votre fichier pot
 - Dans la fenêtre qui s’affiche, indiquer au minimum le nom du projet et l’encodage du fichier
 - Enregistrer le fichier en fonction de la langue de traduction (de_DE, fr_FR, en_US...)

Nous n'avons plus qu'à traduire nos textes.

Puis, nous enregistrons.
Poedit se charge de créer le fichier ".mo" correspondant.

Enfin, nous copions le fichier ".mo" ainsi généré à la racine de notre thème.


## Chargement de la traduction

Nous ajoutons le code suivant dans le fichier `functions.php`.
Si ce fichier n'existe pas, il faut le créer à la racine de notre thème.

```
    /**
    * I18N
    */
    load_theme_textdomain('nom_du_theme');
```


## Mise à jour de la traduction

Si vous rajoutez ou modifiez des textes dans vos templates de thèmes, vous n'aurez pas tout à refaire.
Il suffira de regénerer le fichier ".pot", puis avec l'outil poedit, ouvrir le fichier ".po" précédement traduit.
Cliquez sur `Catalogue` puis `Mettre à jour depuis un fichier pot` et sélectionnez le fichier en question.
Vous n'avez alors plus qu'à traduire les textes nouvellement ajoutés.

Si vous avez une question ou un projet web à réaliser, n'hésitez pas à nous [contacter]({{ path('website_contact') }} "Contactez-nous").
