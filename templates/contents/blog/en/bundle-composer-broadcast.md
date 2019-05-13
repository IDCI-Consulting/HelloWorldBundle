# Create a Symfony2 Bundle reusable and broadcast it via Composer

![Composer Packagist Github Symfony2](/images/blog/composer-packagist.png "Composer Packagist Github Symfony2")

## Introduction ##

With **Composer**, your Symfony2 Bundles (and not just them) are easily able and reusable.

[Composer](http://getcomposer.org/)  is a PHP package manager inspired from [npm](https://npmjs.org/) and [Bundler](http://gembundler.com/).
It requires the PHP's 5.3.2 version or recently new to run on.
It's multiplateform and run on perfectly on Windows, Linus and OSX.

So, this tool will allows you to install your `vendors` simply and rapidly in your Symfony2 projects.

The composer software lean on a repository named [Packagist](https://packagist.org/).
It assembles a set of installable packages via [composer](http://getcomposer.org/).
Packagist just analizes a file to json format (composer.json), including an information set about your bundle, like its name, dependancies...

This file `composer.json` must be at the root of your bundle et everything must be on a SCM as [Git](http://git-scm.com/), [Subversion](http://subversion.tigris.org/) ou [Mercurial](http://mercurial.selenic.com/).

In our example, we will use Github, well known by the Symfony2 community.

So, the purpose of this article is to show you how to create your own bundle to reuse it in your futures projects, and at the same time, to share it with the developpeurs's community Symfony2 ;)


Here's the steps you have to follow :

* Create a repository to your bundle
* Create the file `composer.json`, make a commit and a push
* Create an account and registre your bundle on Packagist.
* Activate the bundle's updating under Packagist (notification at a push, etc)

To make the explications easier, and because nothing is better than a good example, we are going to create a bundle named PartnerBundle.
We suppose that you know how to use the software Git and you have a GitHub account.

## Make a bundle available via Composer

### Writing the composer.json file

Once your repository created and cloned, you have to write the file `composer.json`.
Your bundle surely needs other bundles to run on, as Symfony, Twig, Doctrine, etc.
You have to indicate the minimas (dependencies) to respect to make it run on.
This file is at the root of your bundle.

Here's the minimum you must fill in :

    {
        "name": "your-vendor-name/package-name",
        "require": {
            "php": ">=5.3.0"
        }
    }

Here's the `composer.json` file used to our PartnerBundle :

    {
        "name": "idci/partner-bundle",
        "type": "symfony-bundle",
        "description": "Symfony PartnerBundle",
        "keywords": ["Partner application", "xml", "json", "api", "web service"],
        "license": "GPL-3.0+",
        "authors": [
            {
                "name": "Gabriel Bondaz",
                "email": "gabriel.bondaz@idci-consulting.fr",
                "homepage": "http://www.idci-consulting.fr"
            },
            {
                "name": "Baptiste Bouchereau",
                "email": "baptiste.bouchereau@idci-consulting.fr",
                "homepage": "http://www.idci-consulting.fr"
            }
        ],
        "require": {
            "php": ">=5.3.2",
            "symfony/framework-bundle": ">=2.1, "twig/twig": "*",
            "doctrine/doctrine-bundle": "*"
        },
        "autoload": {
            "psr-0": { "IDCI\\Bundle\\PartnerBundle": "" }
        },
        "target-dir": "IDCI/Bundle/PartnerBundle",
        "minimum-stability": "dev"
    }

You will find more informations on the `composer.json` file here :
[http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup](http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup)
Don't hesitate to take a look at composer.json files of bundles already existing, in vendors for example.

Once you writed your file, add it on Git :

    $ git add composer.json

Then, commit and push it on GitHub to allow Packagist to validate it next.

    $ git commit -m "Add composer file"
    $ git push

### Validate the composer.json file

First, sign in on [Packagist](https://packagist.org/).

Then, choose "Submit Package" and write the URL of your git repository of your related project.
For example : https://github.com/Mon-Entreprise/MonBundle.git

![Submit package](/images/blog/packagist_submit_package.png)

Of your `composer.json` is correct, your Bundle is registred and ready to be intregrated to a project.
You should have something similar : ![Package added](/images/blog/packagist_package_added.png)

### Activate the auto-update under packagist

If you click [on your personnal page](https://packagist.org/profile/) you should find all your packages.
You could see that the updating is not automatic. In other words, if you don't make a `$ git push` of improvements/corrections of your bundle on GitHub, these won't be take into account while updates `$ composer.phar update` of dependencies via Composer.
You will need to go on your Packagist account and make  manually the updating.
Fortunately, it's possible to activate auto-update.

Get back your API Token Packagist on this same page, then go to the GitHub page of your bundle and click on the tab "Settings" (up to the right).

![Git setting](/images/blog/git_settings.png)

Then, go to the tab "Serive Hooks". In the services's list, select Packagist.
Indicate your API token and your username. Check the "Active" button and validate.

![Hook services](/images/blog/service_hooks.png)

You could go back to [https://packagist.org/profile/](https://packagist.org/profile/).
Refresh the page.
If everything went fine, the green message indicating the updating type is gone.

## Installation of your bundle via Composer

And that's it !

You could check if your dependencies work :

* Add the dependence in one your Symfony2 (version 2.1 or more) project
* Modificate the `composer.json` file presents to the root of your project.

Vendors must have been previously installed with Composer.

Here's an example allowing add a dependence with the Bundle :
[PartnerBundle](https://github.com/IDCI-Consulting/PartnerBundle):

    ...
    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.1.*",
        ...
        "idci/partner-bundle": "master-dev" //ajout du bundle en question
    },
    ...

Run the dependencies's updating.

    $ php composer.phar update

If your `composer.json` is correctly written, your bundle should install itself automatically in `vendors`.

Now, you just have to developp it !

If you need help or expertise, you could [contact us](http://www.idci-consulting.fr/contact "Contactez-nous").
