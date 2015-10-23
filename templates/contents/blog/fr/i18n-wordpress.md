
# Comment internationaliser un thème WordPress #
![Wordpress logo](/images/blog/wordpress.png "Wordpress logo")
## Internationaliser un theme wordpress ##

L'article ci-dessous décrit les grandes étapes permettant d'internationaliser vos templates de thème wordpress. Il s'agit d'une bonne pratique permettant d'anticiper de futures traductions dans d'autres langues. Pour cela, wordpress utilise le standard PHP pour sites multilingues : les fichiers PO (GetText Portable Object files). Les fichiers utilisé pour une traduction sont les suivants:

* ".pot". Un fichier POT (Portable Object Template) est généré à partir du texte d'une application. Dans notre cas il s'agit de wordpress.
* ".po". Le fichier PO (Portable Object) est crée à l'aide d'un logiciel de traduction tel que poedit. Il contient le texte à traduire, et la traduction correspondante. Il y a donc autant de fichiers PO que de langues à traduire.
* ".mo". Le fichier MO (Machine Object) contient les mêmes informations que le fichier PO, mais diffère par son format. Un fichier PO est facile à lire pour un humain, tandis qu'un fichier MO est opimisé pour les machines. C'est donc celui-ci qui sera utilisé par votre serveur web.

## Les fonctions __( ) et _e( ) ##

Les textes dans vos templates qui ne sont pas saisi en [backoffice]({{ path('article', { _locale: app.translator.locale, file: 'backoffice-wordpress' }) }})
et que vous souhaitez traduire doivent être signalés comme étant "internationalisables". Pour cela il existe deux fonctions, `__()` et `_e()`:

    <?php __("chaine a traduire", "nom_du_theme"); ?>
    
    <?php 
          _e("chaine a traduire", "nom_du_theme");
          // qui équivaut simplement à echo __("chaine a traduire", "nom_du_theme");
    ?>

Si vous souhaitez en savoir plus vous pouvez consulter la [page du codex correspondante](http://codex.wordpress.org/Translating_WordPress).

## Génération d'un fichier pot ##

Wordpress met à disposition un utilitaire php pour générer des fichiers ".pot".
Le fichier pot est créé automatiquement à partir des textes qui se trouve dans les fonctions de références `_e()` et `__()` (sans les traductions).
Il est utilisé par la suite pour générer un fichier de traduction par langue supplémentaire.

Récupérez l'utilitaire:

    $ svn checkout http://i18n.svn.wordpress.org/tools/trunk /repertoire/de/destination

Générer le fichier .pot:

    $ php makepot.php wp-theme ../wordpress/wp-content/themes/nom_du_theme/

Un fichier nommé "nom_du_theme.pot" a alors été généré.
Créez un dossier **languages** à la racine du thème et copiez celui-ci dedans.

## Génération d'un fichier de traduction ##

Installez le logiciel **Poedit**
Sous ubuntu, vous pouvez l'obtenir via apt:

    $ sudo apt-get install poedit

Allez sur "Fichier", puis "Nouveau catalogue depuis un fichier pot".
Sélectionner votre fichier pot.
Dans la fenêtre qui s’affiche, indiquez au minimum le nom du projet et l’encodage du fichier.
Enregistrez le fichier en fonction de la langue de traduction (de_DE, fr_FR, en_US...)
Vous n’avez plus qu’à traduire vos textes.

Enregistrez, poedit se charge de créer le fichier ".mo" correspondant.
Copier le fichier ".mo" ainsi généré à la racine de votre thème.

## Chargement de la traduction ##

Ajouter le code suivant dans le fichier `functions.php`.
Si ce il n'existe pas créez le à la racine de votre thème.

    /**
    * I18N
    */
    load_theme_textdomain('nom_du_theme');

## Mise à jour de la traduction ##

Si vous rajoutez ou modifiez des textes dans vos templates de thèmes, vous n'aurez pas tout à refaire.
Regénerer le fichier ".pot", puis avec l'outil poedit, ouvrez votre fichier ".po" précédement traduit.
Cliquez sur **Catalogue** puis **Mettre à jour depuis un fichier pot** et sélectionnez le fichier en question.
Vous n'avez alors plus qu'à traduire les textes nouvellement ajoutés.

Si vous avez un projet web à réaliser, n'hésitez pas à nous [contacter]({{ path('contact', { _locale: app.translator.locale }) }} "Contactez-nous").