﻿
# Introduction au BackOffice de WordPress #
![Wordpress logo](/images/blog/wordpress.png "Wordpress logo")

Cet article présente certaines des nombreuses fonctionnalités disponible de l'outil WordPress.
De nombreuses documentations sur ce sujet sont disponibles sur la toile mais la plus complète
se trouve sur le site officiel [codex.wordpress.org](http://codex.wordpress.org/Main_Page).
L'idée de cet article est de vous permettre une rapide prise en main des fonctionnalités
essentielles et de vous accompagner en vous proposant un fil conducteur simple
dans les coulisses de ce séduisant CMS ...

Pour [installer WordPress]({{ path('article', { _locale: app.translator.locale, file: 'install-wordpress' }) }}), si c'est déjà fait, allons-y !

## A quoi sert le back office de WordPress ##

Le back office de WordPress est un espace réservé, accessible par un login et un mot de passe.

![WP Admin](/images/blog/wp_install_8.png "WP Admin")

Une fois connecté dans cet espace vous pourrez alors:

* Gérer le contenu de votre site (Articles, Pages, Médias, Liens)
* Modérer les commentaires déposés par les internautes
* Modifier l'apparence du site (par l'utilisation de thèmes)
* Ajouter des extensions (plugins) pour enrichir en fonctionnalités votre site
* Administrer les utilisateurs
* ...

<p class="notice question" markdown="1">
Pour accéder à votre back office, ouvrez un navigateur Web, puis renseignez dans
la barre d'adresse l'URL de votre site suivi de **/wp-admin** par défaut.
</p>

La navigation s'effectue par le menu qui est, présent sur la gauche de votre écran,
le contenu des différents liens se chargeant dans la zone principale au centre.
L'ergonomie de WordPress vous séduira par sa simplicité et sa rapide prise en main.

![BackOffice](/images/blog/backoffice.png "BackOffice")

## Qu'elle est la différence entre un 'article' et une 'page' ##

### La façon dont sont sauvegardées les données en base: ###

Les articles comme les pages sont des informations enregistrées dans la même table **wp_posts**
mais avec une valeur différente pour le champ **post_type** selon si c'est un article (**post**)
ou une page (**page**). La différence est donc faite dans l'utilisation.

### La façon dont ils sont utilisés: ###

Les pages sont utilisées pour afficher des informations pérennes sans réelle limite dans le temps.
Les liens vers les pages sont généralement regroupés sous la forme d'un menu afin
de proposer une navigation pour votre site. Il est possible de les hiérarchiser afin
de constituer des sous-menus, des sous-sous-menu ...

Les articles sont généralement utilisés pour afficher des informations d'actualité
sur un sujet précis. Il est possible de les catégoriser.

D'autres usages sont possibles avec les articles en fonction de votre imagination:

* Réaliser un encart promotionnel
* Réaliser un slideshow
* Afficher une 'citation du jour'
* ...

## Comment créer un article ##

Il suffit de cliquer dans le menu sur l'item **Articles** > **Ajouter** ou bien
**Articles** > **Tout les articles** > **Ajouter**

![Add post](/images/blog/add_post.png "Add post")

Saisissez un **titre** puis le **contenu**.

![New post](/images/blog/new_post.png "New post")

Vous pouvez lui associer des catégories que vous pouvez créer à votre convenance.

![Post categories](/images/blog/post_categories.png "Post categories")

Vous pouvez lui associer des mots-clés pour un meilleur référencement (si vos mots-clés sont pertinents).

![Post tags](/images/blog/post_tags.png "Post tags")

Une fois votre contenu prêt, vous pourrez alors:

* L'enregistrer comme brouillon, puis le retravailler ultérieurement
* Visualiser un aperçu dans votre front office
* Le publier

![Post actions](/images/blog/post_actions.png "Post actions")

## Comment créer une page ##

Il suffit de cliquer dans le menu sur l'item **Pages** > **Ajouter** ou bien
**Pages** > **Toutes les pages** > **Ajouter**

![Add page](/images/blog/add_page.png "Add page")

Saisissez un **titre** puis le **contenu**.

![New page](/images/blog/new_page.png "New page")

Vous pouvez hiérarchiser vos pages en définissant une page parent. Une page ne peut
avoir qu'un seul parent, mais elle peut avoir plusieurs pages enfants.
Vous pouvez définir un ordre d'affichage en renseignant le **champ order**.
La plus petite valeur correspond à la première position dans le menu.

![Page attributs](/images/blog/page_attributs.png "Page attributs")

Une fois votre contenu prêt, vous pourrez alors:

* L'enregistrer comme brouillon, puis le retravailler ultérieurement
* Visualiser un aperçu dans votre front office
* Le publier

![Page actions](/images/blog/post_actions.png "Page actions")

## L'éditeur de WordPress ##

Vous pouvez éditer votre contenu à l'aide de l'éditeur **WYSIWYG** (What You See Is What You Get)
ou bien directement saisir les balises **HTML**. A vous de choisir le mode d'édition
qui vous convient le mieux.

![Change editor mode](/images/blog/editor_mode.png "Change editor mode")

Il est également possible d'utiliser un éditeur disposant de plus de fonctionnalités.
Il suffit pour cela de cliquer sur "Afficher/cacher les options avancées (touches: Alt+Shift+Z)".

![Advanced editor](/images/blog/advanced_editor.png "Advanced editor")

L'insertion de média (image, vidéo, son) s'effectue à l'aide des boutons d'actions
situés au-dessus de l'éditeur. Les interfaces pour l'insertion de média sont très intuitives.

![Add media](/images/blog/add_media.png "Add Media")

## Les permalinks ##

Le permalink correspond à l'URL (Unified Ressource Locator) permettant d'accèder
à votre page ou votre article. C'est l'identifiant Web d'un contenu bien précis de votre site.

![Permalink](/images/blog/permalink.png "Permalink")

Par défaut le permalink est constitué de l'URL de votre site suivi de /?p=id-post
Ou 'id-post' est l'identifiant du contenu dans votre base de donnée MySQL (Table wp_post).

Si vous souhaitez travailler le référencement Web de vos contenus, il peut être intéressant
d'optimiser cette URL avec des mots clés au lieu d'utiliser l'écriture par défaut.
Pour cela il faut commencer par définir la chaîne de formatage de vos permalinks
dans l'onglet **Réglages** > **Permaliens**

![Permalink menu](/images/blog/permalink_menu.png "Permalink menu")

![Permalink format](/images/blog/permalink_format.png "Permalink format")

Assurer vous d'avoir mis les bons droits sur votre dossier Web, car les modifications
de ce paramètre entraine la création d'un fichier [.htaccess](http://fr.wikipedia.org/wiki/Htacces)
à la racine de votre site Web.

<p class="notice question" markdown="1">
Pour utiliser les permalink comme bon vous semble, il faudra s'assurer que votre serveur
Web est capable de faire de la réécriture d'URL. Dans le cas d'un serveur Web Apache,
il faut activer le module rewrite. Dans les autres cas il vous faudra faire ces modifications
par vous-même.
</p>

Pour plus d'informations voici un [lien](http://codex.wordpress.org/Using_Permalinks 'Using permalinks')
sur comment utiliser les permalinks sur le site [http://codex.wordpress.org](http://codex.wordpress.org 'Wordpress Codex')

Une fois vos réglages effectués, il ne vous reste plus qu'à définir les URL de vos
Articles ou de vos Pages directement dans la page d'édition de ces derniers:

![Permalink rewrite](/images/blog/permalink_rewrite.png "Permalink rewrite")

## Comment fonctionne les commentaires ##

Les commentaires sont des messages laissés sur un article ou une page par des internautes.
C'est une fonctionnalité qui est active par défaut sur l'ensemble de votre site.
Les commentaires se configure dans **Réglages** > **Discussion**

![Manage comments](/images/blog/manage_comments.png "Manage comments")

Voici une petite FAQ (Foire Aux Questions):

### Comment désactiver les commentaires ? ###

Vous pouvez activer/désactiver les commentaires de manière globale, c'est à dire sur tout le site:

![Enable/Disable comments](/images/blog/enable_disable_comments.png "Enable/Disable comments")

Ou bien de manière plus ciblée, c'est à dire sur une page ou un article bien précis.
Pour cela aller sur l'édition de la page ou de l'article pour la/lequel vous souhaitez
régler l'affichage des commentaires, puis afficher les options d'écran:

![Display screen options](/images/blog/display_screen_options.png "Display screen options")

et cocher l'option **discussion**

![Screen options](/images/blog/screen_options.png "Screen options")

Une nouvelle fenêtre pour configurer les discussions liées à votre page ou votre article
est maintenant disponible un peu plus bas sur votre écran. Vous pouvez alors
activer ou non les commentaires sur la page ou l'article édité.

![Discussion box](/images/blog/discussion_box.png "Discussion box")

### Qui peut laisser un commentaire ? ###

Vous pouvez imposer ou non le renseignement d'informations personnelles avant de pouvoir
déposer un commentaire. Autrement dit, acceptez-vous ou non des messages de personnes anonymes.
Par défaut le nom et le mail sont des champs obligatoires. Vous pouvez modifier cela
commme suit:

![Comment user data](/images/blog/comment_user_data.png "Comment user data")

### Les commentaires déposés par les internautes sont ils visibles sur mon site ? ###

Cela dépend encore une fois de vos réglages, par défaut les commentaires nécessitent
une validation de la part de l'administrateur du site. Il ne vous reste plus qu'à
faire vos réglages.

![Moderate comments](/images/blog/moderate_comments.png "Moderate comments")

<p class="notice question" markdown="1">
Une fois votre site en ligne, il est possible que vous receviez des commentaires
saisis par des 'robots', c'est à dire des programmes informatiques. Ces commentaires
sont facilement reconnaissables car souvent leurs contenus sont sans rapport avec
le contenu de la page commentée. Une bonne solution pour lutter contre ces messages
indésirables est l'utilisation de plugin par exemple Akismet
Mais vous pouvez très bien les supprimer manuellement :)
</p>

## Ajouter des Thèmes ##

Les thèmes correspondent à un ensemble de fichiers qui définissent la structure HTML
de vos pages ainsi que le design par le biais des feuilles de styles (CSS) et des images.

Vous pouvez télécharger de nombreux thème sur le site officiel de WordPress
[http://wordpress.org/extend/themes/](http://wordpress.org/extend/themes/ "WordPress Themes")

Lorsque vous avez téléchargé un thème au format **'zip'**, décompresser le, puis
ajouter le dossier ainsi obtenu dans le dossier **wp-content/themes** de votre projet WordPress.
Aller dans l'onglet **Apparence** > **Thèmes**.

![Themes](/images/blog/themes.png "Themes")

Le nouveau thème doit apparaître parmi les thèmes disponibles.
Il ne vous reste plus qu'à l'activer.

Pour visualiser ce nouveau thème, rendez-vous sur la partie FrontOffice de votre site Web.

<p class="notice question" markdown="1">
Il est également possible d'installer des thèmes directement depuis le backoffice.
Cependant Cela nécessite d'avoir installé et configuré un serveur FTP (ou FTPS) afin
de récupérer les thèmes choisis.
</p>

## Ajouter des Plugins ##

Les plugins sont des fonctionnalités supplémentaires qui peuvent être ajoutées à votre site Web.
Il en existe un grand nombre et ce chiffre est en augmentation constante.

Vous pouvez télécharger des plugins sur le site officiel de WordPress
[http://wordpress.org/extend/plugins/](http://wordpress.org/extend/plugins/ "WordPress Plugins")

Lorsque vous avez téléchargé un plugin au format **'zip'**, décompresser le, puis
ajouter le dossier ainsi obtenu dans le dossier **wp-content/plugins** de votre projet WordPress.
Aller dans l'onglet **Extensions**.

![Plugins](/images/blog/plugins.png "Plugins")

Le nouveau plugin doit apparaître parmi les plugins disponibles.
Il ne vous reste plus qu'à l'activer.

<p class="notice question" markdown="1">
Chaque plugin s'utilise différemment, certain ajoute une entrée dans le menu pour
être configuré, d'autre s'utilise via des tags à insérer dans vos contenus, ...
Afin de savoir comment utiliser un plugin, prenez connaissance de la documentation
fournie avec le plugin: Directement sur le site de WordPress. En lisant le fichier **readme.txt** généralement présent dans le dossier du plugin.
</p>

## Gérer les utilisateurs ##

WordPress est une application **multi-utilisateur** permettant donc à plusieurs personnes
de gérer les contenus diffusés par l'outil.
Différents niveaux de droits peuvent être associés à un utilisateur à savoir:

* Administrateur
* Abonnée
* Editeur
* Auteur
* Contributeur

Pour gérer les utilisateurs aller sur l'onglet **Utilisateurs** du menu

![Users](/images/blog/users.png "Users")

## Paramétrage de votre site ##

### Changer le titre et le slogan ###

Pour modifier le titre de votre site/blog ainsi que le slogan, informations souvent
mises en avant côté FrontOffice, Aller dans l'onglet **Réglages** > **Général**.

![General parameters](/images/blog/general.png "General parameters")

![General parameters options](/images/blog/general_options.png "General parameters options")

### Modifier la page d'accueil ###

Par défaut, WordPress liste les derniers articles publiés sur votre page d'accueil.
Ceci peut être modifié pour ne lister qu'un certain nombre d'articles, ou encore
pour afficher une page 'statique' à la place. Pour cela aller dans l'onglet **Réglages** > **Lecture**.

![Lecture](/images/blog/lecture.png "lecture")

<p class="notice question" markdown="1">
Il faut avoir créé au moins une page pour pouvoir effectuer ce changement !
</p>

![Homepage parameters](/images/blog/homepage_parameters.png "Homepage parameters")

Maintenant il ne vous reste plus qu'a travailler vos contenus !

Après chaque modification réalisée côté backoffice, retournez sur la partie front,
c'est à dire la partie publique de votre site, et visualisez le rendu.

<p class="notice question" markdown="1">
Si vous utilisez la navigation par onglet et qu'une fenêtre affiche déjà une
page modifiée. Relancez une requête HTTP depuis votre navigateur afin d'actualiser
le contenu modifiés (touches: F5 ou ctrl+R).
</p>

N'hésitez pas à laisser vos commentaires.

Si vous souhaitez une formation plus avancée sur WordPress vous pouvez également
[nous contacter]({{ path('contact', { _locale: app.translator.locale}) }} "Contactez-nous").
