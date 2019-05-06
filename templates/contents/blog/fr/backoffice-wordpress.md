﻿
# Introduction au back office de WordPress
![Wordpress logo](/images/blog/wordpress.png "Wordpress logo")


Dans cet article, nous allons découvrir ensemble les nombreuses propriétés du CMS WordPress.

WordPress propose deux interfaces, chacune ont une utilité respective :

 - Le back office : c'est un espace réservé, accessible par un login et un mot de passe, et non visible par les internautes.
 - Le front office : c'est la partie publique, visible par les internautes.

Nous allons donc nous concentrer sur la prise en main des fonctionnalités essentielles du back office.

Pour la documentation complète, nous vous renvoyons au site officiel : [codex.wordpress.org](http://codex.wordpress.org/Main_Page).

Si vous n'avez pas encore installé WordPress, vous pouvez vous référer à notre article [Installer WordPress]({{ path('article', { _locale: app.translator.locale, file: 'install-wordpress' }) }}).


## À quoi sert le back office de WordPress ?

Le back office de WordPress est une interface de travail qui nous permet de gérer les contenus et les fonctionnalités.

![WP Admin](/images/blog/wp_install_8.png "WP Admin")

Une fois connecté dans cet espace, nous pourrons alors :

 - Gérer le contenu de notre site (Articles, Pages, Médias, Liens)
 - Modérer les commentaires déposés par les internautes
 - Modifier l'apparence du site (par l'utilisation de thèmes)
 - Ajouter des extensions (plugins) pour enrichir les fonctionnalités de notre site
 - Administrer les utilisateurs
 - Etc


## Accès au back office

Dans un premier temps, nous allons accéder à notre back office.

Pour cela, nous devons ouvrir un navigateur web et renseigner l'adresse de notre site suivi de **/wp-admin**.

Par défaut, l'URL de connexion se construit comme cela : "www.nom-de-votre-site/wp-admin"

<p class="notice info" markdown="1">
Pour une question de sécurité et pour éviter les attaques par brute force, nous vous conseillons de changer l'URL de connexion.
De nombreux plugins proposés par le CMS répondent à ce besoin.
</p>


## L'interface du back office

La navigation s'effectue via le menu présent sous la forme d'une barre latérale, présent à gauche de votre écran.

Le contenu de différents liens proposé dans le tableau de bord s'affichera dans la zone principale, au centre.

L'ergonomie de WordPress est séduisante de par sa simplicité et sa prise en main rapide.

![BackOffice](/images/blog/backoffice.png "BackOffice")


## Les différences entre un article et une page


### Les caractéristiques d'une page et d'un article

Les articles contiennent :

 - Un nom d'auteur
 - Une date de publication (WordPress classent les articles par date)
 - Une ou plusieurs catégories et -parfois, des mots-clefs : ces caractéristiques servent à organiser le contenu du site
 - Un fil de commentaires

Particularités des pages :

 - Pas de date de publication
 - Pas d'affichage du nom de l'auteur
 - Pas de fil de commentaires
 - Une présence dans les menus
 - Possibilité de les hiérarchiser


### La sauvegarde en base de données

Les articles et les pages sont des informations enregistrées dans la même table : **wp_posts**.
Cependant, la valeur du champ **post_type** est différente :

 - Si c'est un article : **post**
 - Si c'est une page : **page**

La sauvegarde en base de données diffère donc en fonction du type de post.


### En conclusion

 - Les pages sont utilisées pour afficher des informations pérennes, sans limite de temps. Généralement, les liens vers les pages sont regroupées sous la forme de menu, qui permettent à l'internaute de naviguer sur le site.
Il est possible de les hiérarchiser afin de constituer des sous-menus, des sous-sous-menu, etc.

 - Les articles sont utilisés pour afficher des informations d'actualité sur un sujet précis. Il est possible de les catégoriser.

D'autres usages sont possibles avec les articles en fonction de votre imagination:

 - Réaliser un encart promotionnel
 - Réaliser un slideshow
 - Afficher une "citation du jour"
 - ...


## Comment créer un article ?

Il suffit de cliquer dans le menu sur l'item **Articles** > **Ajouter** ou bien
**Articles** > **Tout les articles** > **Ajouter**

![Add post](/images/blog/add_post.png "Add post")

Saisir un **titre** puis le **contenu**.

![New post](/images/blog/new_post.png "New post")

Nous pouvons lui associer des catégories, qu'il est possible de créer à notre convenance.

![Post categories](/images/blog/post_categories.png "Post categories")

Nous pouvons lui associer des mots-clés pour un meilleur référencement.

![Post tags](/images/blog/post_tags.png "Post tags")

Une fois le contenu prêt, nous avons différentes possibilités :

* L'enregistrer comme brouillon, puis le retravailler ultérieurement
* Visualiser un aperçu dans le front office
* Le publier

![Post actions](/images/blog/post_actions.png "Post actions")

## Comment créer une page ?

Il suffit de cliquer dans le menu sur l'item **Pages** > **Ajouter** ou bien
**Pages** > **Toutes les pages** > **Ajouter**

![Add page](/images/blog/add_page.png "Add page")

Saisir un **titre** puis le **contenu**.

![New page](/images/blog/new_page.png "New page")

Comme nous l'avons vu, il est possible de hiérarchiser les pages.

Pour cela, nous devons définir une page parent. Une page ne peut avoir qu'un seul parent, mais peut avoir plusieurs pages enfants.
Nous pouvons renseigner un ordre d'affichage grâce au champ **champ order**. La plus petite valeur correspond à la première position dans le menu.

![Page attributs](/images/blog/page_attributs.png "Page attributs")

Une fois votre contenu prêt, nous avons différentes possibilités :

* L'enregistrer comme brouillon, puis le retravailler ultérieurement
* Visualiser un aperçu dans votre front office
* Le publier

![Page actions](/images/blog/post_actions.png "Page actions")


## L'éditeur de WordPress

WordPress permet d'éditer son contenu de deux façons :

 - L'éditeur **WYSIWYG** (What You See Is What You Get), onglet "Visuel"
 - L'utilisation du **HTML**, onlet "HTML"

À vous de choisir le mode d'édition qui vous convient le mieux.

![Change editor mode](/images/blog/editor_mode.png "Change editor mode")

Il est également possible d'utiliser un éditeur disposant de plus de fonctionnalités.
Il suffit pour cela de cliquer sur "Afficher/cacher les options avancées (touches: Alt+Shift+Z)".

![Advanced editor](/images/blog/advanced_editor.png "Advanced editor")

L'insertion de média (image, vidéo, son) s'effectue à l'aide des boutons d'actions
situés au-dessus de l'éditeur. Les interfaces pour l'insertion de média sont très intuitives.

![Add media](/images/blog/add_media.png "Add Media")

## Les permalinks ##

Le permalink correspond à l'URL permettant d'accèder à votre page ou à votre article. C'est l'identifiant Web d'un contenu bien précis de votre site.

![Permalink](/images/blog/permalink.png "Permalink")

Par défaut le permalink est constitué de l'URL de votre site suivi de /?p=id-post.
"id-post" est l'identifiant du contenu dans votre base de donnée MySQL (Table wp_post).

Pour une répondre à une problématique de référencement, nous vous conseillons d'optimiser cette URL avec des mots-clefs.
Pour modifier l'URL par défaut, il faut commencer par définir la chaîne de formatage de vos permalinks dans l'onglet **Réglages** > **Permaliens**.

![Permalink menu](/images/blog/permalink_menu.png "Permalink menu")

![Permalink format](/images/blog/permalink_format.png "Permalink format")

Pour cela, il faut s'assurer que les droits soient les bons, car les modifications
de ce paramètre entraine la création d'un fichier [.htaccess](https://openclassrooms.com/courses/le-htaccess-et-ses-fonctionnalites)
à la racine de votre site Web.


<p class="notice info" markdown="1">
Pour utiliser les permalinks comme bon vous semble, il faudra s'assurer que votre serveur
Web est capable de faire de la réécriture d'URL. Dans le cas d'un serveur Web Apache,
il faut activer le module rewrite. Dans les autres cas il vous faudra faire ces modifications
par vous-même.
</p>

Pour des informations complémentaires, voici la documentation officielle sur les [permalinks](http://codex.wordpress.org/Using_Permalinks 'Using permalinks').

Une fois les réglages effectués, il ne nous reste plus qu'à définir les URL de nos articles et pages, directement dans la page d'édition :

![Permalink rewrite](/images/blog/permalink_rewrite.png "Permalink rewrite")

## Les commentaires

Les commentaires sont des messages laissés sur un article ou une page par des internautes.

Cette fonctionnalité est active par dafut sur l'ensemble de notre site.
Les commentaires se configure dans **Réglages** > **Discussion**

![Manage comments](/images/blog/manage_comments.png "Manage comments")


Voici une petite FAQ (Foire Aux Questions) des questions récurrentes.

### Comment désactiver les commentaires ?

Il est possible d'activer/désactiver les commentaires :

 - De manière générale, sur tout le site
 - De manière ciblée, sur un article ou une page


Voici comment faire pour désactiver tous les commentaires.

![Enable/Disable comments](/images/blog/enable_disable_comments.png "Enable/Disable comments")

Voici comment faire pour désactiver les commentaires sur une page ou un article bien précis.
Pour cela, il faut aller sur l'édition de la page ou de l'article pour la/lequel vous souhaitez
régler l'affichage des commentaires, puis afficher les **soptions de l'écran**.

![Display screen options](/images/blog/display_screen_options.png "Display screen options")

Ici, cochez l'option **discussion**.

![Screen options](/images/blog/screen_options.png "Screen options")

Une nouvelle fenêtre pour configurer les discussions liées à notre page ou notre article
est maintenant disponible un peu plus bas sur l'écran. Nous pouvons alors
activer ou non les commentaires sur la page ou l'article édité.

![Discussion box](/images/blog/discussion_box.png "Discussion box")

### Qui peut laisser un commentaire ?

Nous pouvons imposer ou non le renseignement d'informations personnelles lors du dépot de commentaire.
Autrement dit, les messages peuvent être anonyme ou non.

Par défaut le nom et le mail sont des champs obligatoires. Il est possible de modifier cela :

![Comment user data](/images/blog/comment_user_data.png "Comment user data")

### Les commentaires déposés par les internautes sont ils visibles sur mon site ?

Cela dépend encore une fois des réglages. Par défaut, les commentaires nécessitent une validation de la part de l'administrateur du site.
Les réglages s'effectuent de la manière suivante :

![Moderate comments](/images/blog/moderate_comments.png "Moderate comments")

<p class="notice info" markdown="1">
Une fois votre site en ligne, il est possible que vous receviez des commentaires
saisis par des "robots", c'est à dire des programmes informatiques. Ces commentaires
sont facilement reconnaissables car souvent leurs contenus sont sans rapport avec
le contenu de la page commentée. Une bonne solution pour lutter contre ces messages
indésirables est l'utilisation de plugin par exemple Akismet
Mais vous pouvez très bien les supprimer manuellement :)
</p>


## Ajouter des Thèmes ##

Les thèmes correspondent à un ensemble de fichiers qui définissent :

 - La structure HTML de vos pages
 - Le design par le biais des feuilles de styles (CSS) et des images

De nombreux [thèmes](http://wordpress.org/extend/themes/ "WordPress Themes") sont disponibles sur le site de WordPress.

À la fin du téléchargement d'un thème, le dossier apparait au format **.zip**, il faut le décompresser puis l'ajouter dans le dossier **wp-content/themes** du projet WordPress.
Ensuite, il faut se rendre dans l'onglet **Apparence** > **Thèmes**.

![Themes](/images/blog/themes.png "Themes")

Le nouveau thème doit apparaître parmi les thèmes disponibles.
Il ne nous reste plus qu'à l'activer.

Pour visualiser ce nouveau thème, rendez-vous sur la partie front office du site web.

<p class="notice question" markdown="1">
Il est également possible d'installer des thèmes directement depuis le back office.
Cependant, cela nécessite d'avoir installé et configuré un serveur FTP (ou FTPS) afin de récupérer les thèmes choisis.
</p>


## Ajouter des Plugins ##

Les plugins sont des fonctionnalités supplémentaires qui peuvent être ajoutées à un site Web.
Il en existe un grand nombre et ce chiffre est en augmentation constante.

WordPress propose un large choix de [plugins](http://wordpress.org/extend/plugins/ "WordPress Plugins") directement sur son site.

À la fin du téléchargement d'un plugin, le dossier apparait au format **.zip**,  il faut le décompresser puis l'ajouter dans le dossier **wp-content/plugins** du projet WordPress.
Ensuite, il faut se rendre dans l'onglet **Extensions**.


![Plugins](/images/blog/plugins.png "Plugins")

Le nouveau plugin doit apparaître parmi les plugins disponibles.
Il ne vous reste plus qu'à l'activer.

<p class="notice info" markdown="1">
Chaque plugin s'utilise différemment, certains ajoutent une entrée dans le menu pour être configuré, d'autres s'utilisent via des tags à insérer dans vos contenus, etc.
Afin de savoir comment utiliser un plugin, prenez connaissance de la documentation en lisant le fichier **readme.txt** généralement présent dans le dossier du plugin.
</p>

## Gérer les utilisateurs ##

WordPress est une application **multi-utilisateur** permettant donc à plusieurs personnes de gérer les contenus diffusés par l'outil.
Différents niveaux de droits peuvent être associés à un utilisateur, à savoir :

 - Administrateur
 - Abonnée
 - Editeur
 - Auteur
 - Contributeur

Pour gérer les utilisateurs, allez sur l'onglet **Utilisateurs** du menu.

![Users](/images/blog/users.png "Users")


## Paramétrage de votre site ##


### Changer le titre et le slogan ###

Pour modifier le titre et le slogan de notre site, deux informations souvent mise en avant en front office, rendons nous dans l'onglet **Réglages** > **Général**.

![General parameters](/images/blog/general.png "General parameters")

![General parameters options](/images/blog/general_options.png "General parameters options")

### Modifier la page d'accueil ###

Par défaut, WordPress liste les derniers articles publiés sur votre page d'accueil.
Ceci peut être modifié pour ne lister qu'un certain nombre d'articles, ou encore pour afficher une page "statique" à la place.
Pour cela, allez dans l'onglet **Réglages** > **Lecture**.

![Lecture](/images/blog/lecture.png "lecture")


<p class="notice info" markdown="1">
Il faut avoir créé au moins une page pour pouvoir effectuer ce changement !
</p>

![Homepage parameters](/images/blog/homepage_parameters.png "Homepage parameters")

Maintenant, il ne nous reste plus qu'à travailler nos contenus !

Après chaque modification effectuée en back office, n'hésitez pas à vous rendre en front office pour visualiser votre travail !


<p class="notice question" markdown="1">
Si vous utilisez la navigation par onglet et qu'une fenêtre affiche déjà une
page modifiée. Relancez une requête HTTP depuis votre navigateur afin d'actualiser
le contenu modifiés (touches: F5 ou ctrl+R).
</p>


Si vous avez des questions ou souhaitez une formation plus avancée sur WordPress, n'hésitez pas à [nous contacter]({{ path('contact', { _locale: app.translator.locale}) }} "Contactez-nous").
