# IDCIWebPageScreenshotBundle #


## Introduction ##

Who has never wanted to insert a thumbnail on a web page to take a peek at other web pages?
It's an attractive feature which could by example be set up on a "Partners" page, on
a developper's portfolio, etc.

With **[IDCIWebPageScreenshotBundle](https://github.com/IDCI-Consulting/WebPageScreenShotBundle "IDCIWebPageScreenshotBundle")** for **Symfony2**,
it is now possible to easily generate **websites screenshots**.


## Installation

<div class="notice info"><span></span>Notice: this article is for Symfony 2.3.x</div>

This bundle needs **GregwarImageBundle** for image resizing.
We consequently add the following lines to our composer.json (at the project's root) :

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

Then we execute the following command, still at the project's root.

```sh
    # composer.phar update
```

You must now have the `vendor/idci` and `vendor/gregwar` files available.

Next we need to install the [phantomjs](http://phantomjs.org/ "phantomjs") tool which will "scan" the web pages in order to generate the images.
On Linux, we just need to execute the following command :

```sh
    sudo apt-get install phantomjs
```

We then include the bundle's configuration in `app/config.yml` :

```yaml
    imports:
        ....
        - { resource: @IDCIWebPageScreenShotBundle/Resources/config/config.yml }
```

Afterwards we add the controller in `app/routing.yml` :

```yaml
    idci_web_page_screen_shot:
        resource: "../../vendor/idci/webpagescreenshot-bundle/IDCI/Bundle/WebPageScreenShotBundle/Controller"
        type:     annotation
```

We'll need to specify certain default values in `app/parameters.yml` :

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

You may wonder what these parameters refer to.
Let's go through each of them :

 - **screenshot_phantomjs_bin_path** refers to the location of the phantomjs executable.
On Linux, you can use the `whereis phantomjs` command to know this.
 - **screenshot_width** and **screenshot_height** indicate the generated image's definition.
The maximum is a width of 1440 and a height of 900.
 - **screenshot_mode** is the way the image will be returned. The available settings are :
    - **file** returns a raw image
    - **base64** returns an image encoded in a text string
    - **url** returns a direct link to the image
 - **screenshot_format** refers to the image format.
3 formats are available : **gif**, **jpeg** (or jpg) and **png**.
 - **screenshot_cache_enabled** is a boolean allowing (or not) the **caching** of images.
 - **screenshot_cache_delay** indicates the duration of the caching in **seconds** le temps de mise en cache des images (en **secondes**).
 - **screenshot_cache_directory** references the location of the images.
 You can use **%kernel.cache_dir%** to use the Symfony cache for your images.
 Should you choose not to enable the cache, the images will still be put at that location,
 the difference being an existing image will be replaced by a new one at each capture.

The render parameters, a.k.a. **width**, **height**, **mode** and **format** can be overwritten afterwards.

Lastly, we just need to register these bundles in `app/AppKernel.php`

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

The **Symfony2 website screenshot** bundle is now installed.

## Using the bundle

There are two available ways of generating screenshots.
In both cases, the images are generated in the directory mentioned in `app/parameters.yml`.


### Using commands

There's a Symfony command for generating screenshots :

```sh
    php app/console idci:create:screenshot [url] [width] [height] [mode] [format]
```

A more concrete example would go like so :

```sh
    php app/console idci:create:screenshot http://symfony.com 800 600 file jpg
```

It is possible to type in all the parameters, or only the URL.
In the second case, you will be presented with a suggestion of default values which you can overwrite if you want to.
To accept the default values, you just need to press the enter key.


### Using the controller

A controller with two actions already exists. It should be able to fulfill most of our needs.
For more specific needs, you can use the service described below.

If you wish to look more deeply into it's functioning, the code is on  [GitHub.](https://github.com/IDCI-Consulting/WebPageScreenShotBundle/blob/master/Controller/ApiController.php "api-controller")

The **first action** performs a screen capture and returns the image in answer. The corresponding route is **/screenshot/capture**.
The URL parameters define the image to generate.
The URL will then be like such :

    http://mysymfonyapp/screenshot/capture?url=http://mywebsite.com&format=jpg&mode=url

Only the **URL** parameter is mandatory. If the others are not indicated, the default vaules will be used.
Among the 3 available modes, **URL** allows to get an URL corresponding to the second action.

This **second action** simply gets an already generated image. The corresponding route is **/screenshot/get/{image_name}**.
The URL will then be like such :

    http://mysymfonyapp/screenshot/get/800x600_website.com.png


### Using the service

You may happen to have specific needs, or may want to generate screenshots from a controller or an other service, for example.

The **Screenshot Manager** is available from a service called **idci_web_page_screen_shot.manager**.
That way, it is possible to do the following :

```
    $screenshotManager = $this->get('idci_web_page_screen_shot.manager');
    $screenshot = $screenshotManager
        ->capture($params)
        ->resizeImage()
        ->getRenderer()
        ->render()
    ;
```

The `render()` function returns the screen capture relative to the choosen mode.

**$params** is an array containing the parameters.
In the existing controller [(ApiController)](https://github.com/IDCI-Consulting/WebPageScreenShotBundle/blob/master/Controller/ApiController.php "api-controller"),
this array is build from the request parameters.

In all cases, it should look something like this :

```php
    $params = array(
        "url" => "http://mywebsite.com",
        "mode" => "base64",
        "width" => 1024,
        "file" => "gif"
    );
```

Again, only the URL is mandatory in this array.


## A concrete example

To understand this bundle's interest, there's nothing like a practical example.
Let's imagine our goal is to get screen captures from a WordPress website (or any site by the way).
It will then be necessary to generate the images from a Symfony application, and to use it as a web service.
To avoid an annoying page loading time, it is advised to display a default image, then to inject the images with
JavaScript when they are generated.

Here is now a simple example of a WordPress page displaying a list of screen captures.
Even if you are not used to this CMS, you should have no difficulties understanding the principle.

```
    <script type="text/javascript" src="/path/to/js/screenshots.js"></script>
    [...]
    <ul>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content() ?>
            <li>
                <div class="screenshot">
                    <img
                        src="/path/to/images/logo.jpg"
                        width="130"
                        height="130"
                        alt="logo"
                        data-url="<?php the_field("partner_website"); ?>" <!-- this attribute contains the url to the partner website -->
                    >
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
    [...]
```

And here is the jQuery script (screenshot.js in our example) which allows to inject the image :

```
    //Wait for the page loading
    jQuery(document).ready(function()
        //Loop on the blocks with the "screenshot" class.
        jQuery('div.screenshot').each(function() {
            //Get the image
            var logo = jQuery("img", this);
            //Create the new link
            data = "http://mysymfonyapp/screenshot/capture?url="+logo.attr("data-url")+"&width=130&height=130&mode=file&format=jpg";
            //Delete the link of the default image and replace it by the new one
            jQuery(logo).empty().attr("src", data);
        });
    });
```

If you have a question regarding **IDCIWebPageScreenshotBundle**, don't hesitate to [contact us]({{ path('website_contact') }} "Contact us").
