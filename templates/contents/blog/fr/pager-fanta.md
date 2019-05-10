# Installation de PagerFantaBundle #

![WhiteOctober Pagerfanta Symfony2](/images/blog/pagerfanta.png "WhiteOctober Pagerfanta Symfony2")


## Introduction

Même s'il est très facile de récupérer des listes, cela peut être un véritable casse-tête de **paginer vos entités facilement avec symfony2**.

Heureusement, il existe un bundle chargé de paginer tout ça pour nous, le bien-nommé : **WhiteOctoberPagerfantaBundle** !

<p class="notice warning" markdown="1">
Cet article est destiné à Symfony 2.1.x
</p>


Pour les habitués de l'installation de bundle, la démarche est toujours la même :

 - Configuration du composer.json
 - Enregistrement du bundle dans l'AppKernel.

Si c'est le premier Bundle que vous installez, voici l'explication détaillée :

Ajouter les lignes suivantes à votre composer.json (à la racine de votre projet) :

```
    {
        [...]
        "require": {
            [...]
            "pagerfanta/pagerfanta": "dev-master",
            "white-october/pagerfanta-bundle": "dev-master"
        },
        [...]
    }
```

Lancer ensuite la commande suivante dans votre projet :

```sh
    $ composer.phar update
```

Vous devez avoir maintenant les dossiers `/vendor/idci`, `/vendor/pager-fanta` et `/vendor/white-october`
comprenant eux-même les bundles précedemment cités.

Il ne vous reste plus qu'à enregistrer le **Bundle WhiteOctoberPagerfantaBundle** dans le fichier `app/AppKernel.php` :

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
                new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            );
        }
        // ...
    }
```

Le bundle est désormais installé !


## Pagination avec WhiteOctoberPagerfantaBundle ##


### Utilisation de pagerfanta dans le Controller ###

Maintenant que notre bundle est installé, son utilisation est relativement simple.
Toute la logique métier du bundle se situe au niveau de notre controller :

**1** - Instanciation de l'Adpater qui nous convient : tout Adpater qui implémente l'interface `Pagerfanta\Adapter\AdapterInterface`.

De base, **Pagerfanta propose plusieurs adapters déja implémentés**, voici les principaux, et leurs paramètres :

 - `ArrayAdapter` pour paginer un array
 - `PropelAdapter` pour paginer une PropelQuery
 - `DoctrineORMAdapter` pour paginer une DoctrineORMQuery / un DoctrineORMQueryBuilder
 - `DoctrineCollectionAdapter` pour paginer une Doctrine Collection
 - `DoctrineODMMongoDBAdapter` pour paginer une DoctrineODMMongoDB Query

**2** - Instanciation d'un **PagerFanta**, en lui donnant notre Adpater en paramètre.

**3** - Travail avec notre Pagerfanta, en lui donnant le nombre d'éléments par page à afficher, ainsi que la page courante (qui est la première page par défaut).

Prenons l'exemple de l'affichage d'une liste de news sur votre page d'accueil :

```php
    // Namespace/MonBundle/Controller/
    // ...
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Exception\NotValidCurrentPageException;

    class NewsController extends Controller
    {
        /**
        * News
        *
        * Si aucune page n'est passée en paramètre, par défaut c'est la page 1
        * @Route("/", name="homepage", defaults={"page" = 1})
        * @Route("/{page}", name="homepage_paginated")
        * @Template()
        */
        public function homeAction($page)
        {
            // On récupère l'entity manager
            $em = $this->getDoctrine()->getEntityManager();

            // Pour l'exemple on prends un DoctrineORMAdapter auquel on passe une Query
            // On pourrait également lui passer un QueryBuilder
            $adapter = new DoctrineORMAdapter($em->getRepository('NamespaceMonBundle:News')->getMyQuery());
            $pager = new PagerFanta($adapter);

            // Il est judicieux de définir un paramètre dans le app/config/config.yml pour le nb d'éléments à afficher par page
            $pager->setMaxPerPage($this->container->getParameter('nb_de_news_par_page'));

            try {
                $pager->setCurrentPage($page);
            } catch (NotValidCurrentPageException $e) {
                throw new NotFoundHttpException();
            }

            /* Pour infos, quelques méthodes utiles du pager :
                $pager->getNbResults();
                $pager->getMaxPerPage();
                $pager->getNbPages();
                $pager->haveToPaginate();
                $pager->hasPreviousPage();
                $pager->getPreviousPage();
                $pager->hasNextPage();
                $pager->getNextPage();
                $pager->getCurrentPageResults();
            */

            // On envoie notre pager (notre liste d'objets paginée) à notre vue
            return array(
                'pager' => $pager
            );
        }
    }
```


### Utilisation de pagerfanta dans la vue

Il nous reste maintenant à traiter l'affichage de ce fameux pager au niveau de notre vue.
Si vous utilisez déjà Twig, c'est un jeu d'enfant. Sinon... c'est le moment de vous y mettre ! ;)

```
    {% verbatim %}
    {# On affiche les pages : "Précédent 1 2 3 ... 4 5 Suivant" #}
    {% if pager.haveToPaginate %}
        {{ pagerfanta(pager, 'default', {'routeName': 'homepage_paginated'}) }}
    {% endif %}

    {# On boucle sur le pager contenant nos news paginées #}
    {% for news in pager.currentPageResults %}

    {{ news.title }}

    {{ news.content }}

    {% else %}

    Aucune news disponible.

    {% endfor %}

    {# On ré-affiche les pages dans un souci d'ergonomie: "Précédent 1 2 3 ... 4 5 Suivant" #}
    {% if pager.haveToPaginate %}
        {{ pagerfanta(pager, 'default', {'routeName': 'homepage_paginated'}) }}
    {% endif %}
    {% endverbatim %}
```

Pour information, le bundle propose également un **template un peu plus sympa basé sur bootstrap** (lien à insérer) :

```
    {% verbatim %}
    {{ pagerfanta(pager, 'twitter_bootstrap', {'routeName': 'homepage_paginated'}) }}
    {% endverbatim %}
```

L'exemple est, bien sûr, simplifié au maximum mais a le mérite d'être clair.
Voilà, vous savez désormais tout sur la **pagination dans Symfony2 à l'aide de pagerfanta**.

Encore une fois, avant de vous lancer à corps perdu dans le developpement d'une nouvelle fonctionnalité,
allez toujours jeter un petit coup d'oeil à [KNPBundles](http://knpbundles.com/)
pour voir si un bundle faisant votre bonheur n'existerait pas déjà ! ;)

Si vous avez une question sur le **PagerFantaBundle** n'hésitez pas à [nous contacter](http://www.idci-consulting.fr/contact "Contactez-nous").
