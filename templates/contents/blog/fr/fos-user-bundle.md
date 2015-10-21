### Introduction

<div class="notice question">
<span></span>
<p>ATTENTION : Cet article a été écrit pour FOSUserBundle 1.2</p>
</div>

**Gérer les utilisateurs** inscription, connexion, droits d'accès, etc.. a toujours 
été un plaisir avec **symfony** grâce aux plugins:

 * sfGuardPlugin (propel)
 * sfDoctrineGuardPlugin (doctrine)

Ce n'est pas bien plus dur avec **Symfony2** ! Cela est même simplifié au maximum,
bien que dépaysant au début. Le bundle **FOSUserBundle** répond aux mêmes problématiques
mais fluidifie les choses, les rend plus intuitives.


### FOSUserBundle : Installation

Il vous faut tout d'abord récupérer les sources du bundle et les placer dans le dossier vendor.
Pour cela, 2 solutions s'offrent à vous :

#### 1) Installation de FOSUSerBundle en clonant le dépôt git 

    $ git clone https://github.com/FriendsOfSymfony/FOSUserBundle.git vendor/bundles/FOS/UserBundle

#### 2) Installation de FOSUSerBundle via les dépendances git

Ajoutez les lignes suivantes dans votre fichier <span class="special">deps</span> :

    [FOSUserBundle]
        git=git://github.com/FriendsOfSymfony/FOSUserBundle.git
        target=bundles/FOS/UserBundle

Puis mettez à jour les vendors :

    $ php bin/vendors install

Cette solution a l'avantage de permettre à n'importe qui travaillant avec vous sur
le projet de ne pas avoir à se soucier de quelles sont les vendors à installer et
où les trouver. Il lui suffira de mettre à jour les vendors.

Une fois **FOSUserBundle** ajouté aux vendors, il vous faut l'activer dans le Kernel:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new FOS\UserBundle\FOSUserBundle(),
        );
    }

... Et ajouter le namespace qui va bien dans l'autoload:

    <?php
    // app/autoload.php

    $loader->registerNamespaces(array(
        // ...
        'FOS' => __DIR__.'/../vendor/bundles',
    ));

Si vous avez encore des doutes sur l'utilité des namespaces ou que vous êtes sceptiques
quant à son utilisation, je vous renvoie à ce très bon article de
[Pascal MARTIN sur le sujet](http://blog.pascal-martin.fr/post/php-5.3-namespace-1-espaces-de-noms).


### Conclusion

**Symfony2** est amélioré par rapport à son predecesseur vieillissant. Là où **symfony**
proposait un plugin agreable d'utilisation mais rendant les choses vite complexe
pour une utilisation avancée, Symfony2 propose une approche plus pragmatique du problème :
Faites ce que vous voulez, comme vous le voulez et où vous le voulez en héritant du bundle de base.
Ce framework ainsi que ses bundles sont des outils de grande qualité !
N'hésitez pas à nous faire vos retours.

Si vous souhaitez une formation sur les frameworks **symfony** ou **Symfony2**
<a href="http://www.idci-consulting.fr/fr/contact">contactez-nous</a>.