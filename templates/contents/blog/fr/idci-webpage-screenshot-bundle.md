# IDCIWebPageScreenshotBundle #


## Introduction ##

Qui n'a jamais eu envie d'insérer des miniatures sur une page web, pour avoir un aperçu d'autres pages en liens ?
Il s'agit d'une fonctionnalité attrayante qui pourrait par exemple être mis en place sur une page ""Partenaires",
sur le portfolio d'un développeur, etc.

Avec le bundle **[IDCIWebPageScreenshotBundle](https://github.com/IDCI-Consulting/WebPageScreenShotBundle "IDCIWebPageScreenshotBundle")** pour **Symfony2**,
il est désormais possible de générer facilement des **captures d'écrans de sites web**.


## Installation

<div class="notice info"><span></span>Remarque : cet article est destiné à Symfony 2.3.x</div>

Ce bundle nécessite GregwarImageBundle pour redimensionner les images.
Nous ajoutons donc les lignes suivantes à votre composer.json (à la racine de notre projet) :

```
    {
        [...]
        "require": {
            [...]
            "idci/webpagescreenshot-bundle": "dev-master",
            "gregwar/image-bundle": "dev-master"
        },
        [...]
    }
```

Puis, nous lançons la commande suivante, depuis la racine de notre projet :

```sh
    # composer.phar update
```

Vous devez avoir maintenant les dossiers `/vendor/idci` et `/vendor/gregwar` disponibles.

Ensuite, il nous faut installer l'outil [phantomjs](http://phantomjs.org/ "phantomjs") qui "scannera" les pages web afin d'en générer des images.
Sous Linux, il suffit de lancer la commande suivante :

```sh
    sudo apt-get install phantomjs
```

Dans le fichier `app/config.yml`, nous incluons la configuration du bundle :

```yaml
    imports:
        ....
        - { resource: @IDCIWebPageScreenShotBundle/Resources/config/config.yml }
```

Puis, nous ajoutons le controleur dans le fichier `app/routing.yml`.

```yaml
    idci_web_page_screen_shot:
        resource: "../../vendor/idci/webpagescreenshot-bundle/IDCI/Bundle/WebPageScreenShotBundle/Controller"
        type:     annotation
```

Dans le fichier `app/parameters.yml`, il sous faudra spécifier certaines valeurs par défaut :

```yaml
    parameters:
        ...
        screenshot_phantomjs_bin_path:   "/usr/bin/phantomjs"
        screenshot_width:                800
        screenshot_height:               600
        screenshot_mode:                 file
        screenshot_format:               png
        screenshot_cache_enabled:        true
        screenshot_cache_delay:          86400
        screenshot_cache_directory:      %kernel.cache_dir%/screenshot/cache/
```

Vous vous demandez peut-être à quoi correspondent ces paramètres.
Détaillons les :

 - **screenshot_phantomjs_bin_path** fait référence à l'emplacement de l'éxecutable phantomjs.
Sous Linux, vous pouvez utiliser la commande `whereis phantomjs` pour le connaitre.
 - **screenshot_width** et screenshot_height indiquent la résolution de l'image générée.
Le maximum s'élève à 1440 pour la largeur et 900 pour la hauteur.
 - **screenshot_mode** correspond au mode sous lequel sera retourné l'image. Les valeurs possibles sont :
    - **file** renvoie une image brute
    - **base64** renvoie une image encodée dans une chaîne de caractère
    - **url** renvoie un lien direct vers l'image
 - **screenshot_format** correspond au format de l'image.
3 formats sont disponibles : **gif**, **jpeg** (ou jpg) et **png**.
 - **screenshot_cache_enabled** est un booléan permettant d'autoriser la mise en cache des images ou non.
 - **screenshot_cache_delay** indique le temps de mise en cache des images (en **secondes**).
 - **screenshot_cache_directory** fait référence à l'emplacement des images.
 Vous pouvez utiliser **%kernel.cache_dir%** pour vous servir du cache symfony comme emplacement.
Dans le cas où vous choisissez de ne pas activer le cache, les images seront stockés à cet emplacement,
la différence étant qu'à chaque nouvelle demande, l'image sera écrasée par une nouvelle capture.

Les paramètres de rendu, c'est à dire la **largeur**, la **hauteur**, le **mode** et le **format**  peuvent être surchargés par la suite.


Enfin, il ne nous reste plus qu'à enregistrer ces Bundles dans le fichier `app/AppKernel.php`

```php
    // app/AppKernel.php
    use Symfony\Component\HttpKernel\Kernel;
    use Symfony\Component\Config\Loader\LoaderInterface;

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new IDCI\Bundle\WebPageScreenShotBundle\IDCIWebPageScreenShotBundle(),
                new Gregwar\ImageBundle\GregwarImageBundle(),
            );
        }
    }
```

Le bundle de **capture d'écran de pages web sur symfony2** est désormais installé.


## Utilisation du bundle

Deux méthodes sont disponibles pour générer des captures d'écrans.
Dans les deux cas, les images seront générées dans le répertoire indiqué dans le fichier `app/parameters.yml`.


### Utiliser la commande

Une commande Symfony permet de générer des captures d'écran :

```sh
    php app/console idci:create:screenshot [url] [width] [height] [mode] [format]
```

Un exemple plus concret donnerait ainsi ceci :

```sh
    php app/console idci:create:screenshot http://symfony.com 800 600 file jpg
```

Il est possible d'indiquer l'intégralité des paramètres, ou seulement l'URL.
Dans le deuxième cas, il vous sera suggéré les valeurs par défaut, que vous pourrez surcharger si vous le voulez.
Pour accepter les valeurs par défaut, il vous suffit d'appuyer sur la touche entrée.


### Utiliser le controleur

Un controlleur contenant deux actions existe déjà. Il devrait nous permettre de remplir la plupart de nos besoins.
Pour des besoins plus spécifiques, nous pouvons utilisé le service présenté plus bas.

Si vous souhaitez comprendre plus précisement son fonctionnement, le code est sur [GitHub.](https://github.com/IDCI-Consulting/WebPageScreenShotBundle/blob/master/Controller/ApiController.php "api-controller")

La première action permet de capturer un screenshot et de renvoyer l'image en réponse. La route correspondante est **/screenshot/capture**.
Ce sont les paramètres de l'URL qui définissent l'image à générer.
L'URL sera donc de ce type:

    http://mysymfonyapp/screenshot/capture?url=http://mywebsite.com&format=jpg&mode=url

Seul le paramètre **URL** est obligatoire. Si les autres ne sont pas renseignés, les valeurs par défaut seront utilisées.
Parmis les 3 modes disponibles, le mode **URL** permet de récupérer une URL correspondant à la deuxième action.

Cette deuxième action récupère simplement une image déjà générée. La route correspondante est **/screenshot/get/{nom_de_l_image}**.
L'URL sera donc de ce type:

    http://mysymfonyapp/screenshot/get/800x600_website.com.png


### Utiliser le service

Il se peut que vous ayez des besoins spécifiques, et que vous souhaitiez pouvoir générer des capture d'écran depuis un controlleur, ou un autre service par exemple.

Le **Screenshot Manager** est disponible depuis un service appelé **idci_web_page_screen_shot.manager**.
De cette manière, il est possible de faire la chose suivante :

```
    $screenshotManager = $this->get('idci_web_page_screen_shot.manager');
    $screenshot = $screenshotManager
        ->capture($params)
        ->resizeImage()
        ->getRenderer()
        ->render()
    ;
```

La fonction `render()` retourne la capture d'écran relativement au mode choisi.

**$params** est un array contenant les paramètres.
Dans le controlleur existant [(ApiController)](https://github.com/IDCI-Consulting/WebPageScreenShotBundle/blob/master/Controller/ApiController.php "api-controller")
cet array est construit à partir des paramètres de la requête.

Dans tous les cas, il devrait ressembler à quelque chose comme ceci :

```php
    $params = array(
        "url" => "http://mywebsite.com",
        "mode" => "base64",
        "width" => 1024,
        "file" => "gif"
    );
```

Encore une fois, seul l'URL est obligatoire dans cet array.


## Un exemple concret

Pour comprendre l'intérêt de ce bundle, rien ne vaut un exemple concret.
Imaginons alors que notre objectif soit de récupérer des captures d'écrans depuis un site WordPress (ou n'importe quel site d'ailleurs).
Il sera alors nécessaire de générer les images depuis une application Symfony, et de s'en servir comme web service.
Pour éviter un temps d'affichage de la page génante pour l'utilisateur, il est conseillé d'afficher une image par défaut,
puis d'injecter les images à l'aide de JavaScript, une fois celles-ci générées.

Voici donc un exemple simple d'une page WordPress affichant une liste de captures d'écran.
Même si vous n'êtes pas un habitué de ce CMS, vous ne devriez pas avoir de difficultés à comprendre le principe.

```
    <script type="text/javascript" src="/chemin/vers/js/screenshots.js"></script>
    [...]
    <ul>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content() ?>
            <li>
                <div class="screenshot">
                    <img
                        src="/chemin/vers/images/logo.jpg"
                        width="130"
                        height="130"
                        alt="logo"
                        data-url="<?php the_field("partner_website"); ?>" <!-- cet attribut contient le lien vers le site partenaire -->
                    >
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
    [...]
```

Et voici le script jQuery (screenshot.js dans notre exemple) permettant d'injecter l'image :

```
    //on attends le chargement de la page
    jQuery(document).ready(function()
        //on boucle sur les blocs ayant la classe "screenshot".
        jQuery('div.screenshot').each(function() {
            //on récupère l'image
            var logo = jQuery("img", this);
            //on créer le nouveau lien
            data = "http://mysymfonyapp/screenshot/capture?url="+logo.attr("data-url")+"&width=130&height=130&mode=file&format=jpg";
            //on supprime le lien de l'image par défaut, et on le remplace par le nouveau
            jQuery(logo).empty().attr("src", data);
        });
    });
```

Si vous avez une question à propos du **IDCIWebPageScreenshotBundle**, n'hésitez pas à nous [contacter]({{ path('contact', { _locale: app.translator.locale }) }} "Contactez-nous").
