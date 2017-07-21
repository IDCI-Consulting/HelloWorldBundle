{% verbatim %}
# Asset Loader Bundle

La création de champs de formulaires personnalisés (*[Form Types](https://symfony.com/doc/current/reference/forms/types.html)*) avec le framework Symfony 2 (et 3) confronte les développeurs à des problématiques de chargement de dépendances front-end (ou **assets**) tel que des fichiers javascripts ou css. Nous allons vous présenter dans cet article les problèmes fréquemment rencontrés et comment les résoudre avec le bundle **IdciAssetLoader**. La lecture de cet article nécessite que vous soyez à l'aise avec les **Form Type** de Symfony 2.

## Introduction

Plaçons nous dans un exemple de cas typique: imaginons que nous voulons créer un champ de formulaire personnalisé basé sur le type texte, qui nous permettra d'afficher des tags sous forme de valeurs séparées par des virgules.
Nous utilisons **[Taggle.js](https://sean.is/poppin/tags)** pour le rendu des tags et **[JQuery](https://jquery.com/)** afin d'implémenter un système *d'autocompletion*.  

Nous avons donc:

 * un script propre au widget qui sera reponsable de la manipulation du DOM pour transformer l'input en un champ compatible avec Taggle.js
 * des scripts de dépendances: Taggle et JQuery
___

Nous serions tenté d'inclure les dépendances dans le **script du widget** (dans des balises `<script>`), ce qui ne pose pas de problème si notre widget n'est rendu qu'une seule fois sur la page. Ceci devient en effet problématique lorsque l'on souhaite en inclure plusieurs sur une même page : **JQuery et Taggle seront chargés dans le DOM autant de fois qu'il y a d'instances du widget**, comme illustré ci-dessous.

![rendu avec DOM](/images/blog/asset-loader-demo-1.png)

Nous pourrions contourner ce problème de chargement multiple en incluant les dépendances dans le `{% block javascripts %}` du fichier `base.html.twig`, mais cela pose deux problèmes :

- Les dépendances seront incluses dans toutes les pages de l'application, même lorsque l'on n'en a pas besoin
- Cela implique plus de travail pour les développeurs voulant implémenter ce widget car ils devront ajouter eux-même les dépendances dans le fichier.

___

Un autre problème se pose si notre widget utilise des scripts utilisés globalement, comme par exemple JQuery, qui seront donc chargés dans le `{% block javascripts %}` du fichier `base.html.twig`. Le script du widget va s'executer avant le chargement de de JQuery, car le javascript se situera au niveau du widget dans le DOM, alors que le `{% block javascripts %}` est proprement rendu en bas de page.
Une solution possible pour contourner ce problème peut être de **reporter** l'exécution de ce code grâce à la fonction native suivante :

```javascript
window.addEventListener('load', function () {
    // code goes here ...
});
```

Avec cette technique, le script ne s'exécutera que lorsque la fenêtre sera entièrement chargée, nos dépendances devraient donc être disponibles à ce moment là. Mais cela ne résoud pas le problème de duplication des dépendances.

___

De plus, les scripts chargés au milieu de la page (au dessus de la **[ligne de flottaison](http://www.seonity.com/definition-la-ligne-de-flottaison.php)**) posent un problème mineur d'optimisation du temps de chargement de la page. [En savoir plus](https://developers.google.com/speed/docs/insights/PrioritizeVisibleContent?hl=fr)
___

On voudrait donc pouvoir :

* Ne charger les dépendances nécessaires **qu'une seule fois par page**
* Ne pas charger les scripts des widgets s'il n'y a pas de widget sur la page
* Charger les dépendances en bas de la page


**Ca tombe bien, notre [bundle](https://github.com/IDCI-Consulting/AssetLoaderBundle) fait tout ça à la fois**


## Installation du bundle

Premièrement, il faut charger **AssetLoaderBundle** en dépendance de notre projet. Pour cela, ajoutons-le dans le fichier `composer.json` :

```json
"require": {
    ...
    "idci/asset-loader-bundle": "dev-master"
},
```

Installons ensuite cette dépendance avec composer :

```sh
$ php composer.phar update
```


Puis, enregistrons le bundle dans le *kernel* de l'application :

```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IDCI\Bundle\AssetLoaderBundle\IDCIAssetLoaderBundle(),
    );
}
```

## Utilisation du Bundle

Ajouter des assets dans notre form type est assez simple :

* Notre **AbstractType** doit implémenter la méthode **getAssetCollection()** à partir de l'interface **AssetProviderInterface**. **AssetCollection** représente un tableau d'objets Asset
* Nous devons définir notre type en tant que **service** et ajouter un tag nommé **idci_asset_loader.asset_provider**
* Les dépendances doivent être placées dans un ou plusieurs fichiers `twig`, avec les autres fichiers de templates

Notez que vous pouvez ajouter des assets de cette manière pour n'importe quel service, et non pas exclusivement pour les form types. Il suffit de créer une classe implémentant l'interface **AssetProviderInterface**, et d'enregistrer cette clase en tant que service avec le tag **idci_asset_loader.asset_provider**.

Notre AbstractType devra donc ressembler à ceci :

```php
<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\Asset;
use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

class TagType extends AbstractType implements AssetProviderInterface
{
    /**
     * @var AssetCollection
     */
    private $assetCollection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assetCollection = new AssetCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetCollection()
    {
        return $this->assetCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->assetCollection->add(new Asset('AppBundle:Form:tags_type_assets.html.twig'));

        // Passage de paramètre
        $this->assetCollection->add(new Asset('Form/tags_type_script.html.twig', ['form' => $view]));

        // On ajoute une option pour définir le spérateur du tag
        $view->vars['separator']           = $options['separator'];

        // On ajoute une option pour pouvoir formater les données d'autocomplétion récupérées via l'API
        $view->vars['jsTransformFunction'] = $options['jsTransformFunction'];

        // L'url de l'api json d'autocomplétion
        if (isset($options['url'])) {
            $view->vars['url'] = $options['url'];
        }

        return $view->vars;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'separator'           => ',',
                'jsTransformFunction' => 'function (tags) { return tags; };'
            ))
            ->setOptional(array(
                'url'
            ))
            ->setAllowedTypes(array(
                'separator'           => array('string'),
                'jsTransformFunction' => array('string'),
                'url'                 => array('string')
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'extra_form_tags';
    }
    /**
     * {@inheritdoc}
     *
     * @deprecated
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
```

Notre script utilise des éléments de `view` pour fonctionner, nous passons donc celle-ci en paramètre **de l'Asset**.  
Ici, puisque notre script a besoin de dépendances pour fonctionner, il faut charger celles-ci avant.
Ainsi, on inclut ces deux éléments (dépendances et script propre) dans deux fichiers `twig` :

```twig
{# app/Resources/views/Form/tags_type_assets.html.twig #}

<script type="text/javascript" src="{{ asset('js/taggle.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/taggle.css') }}" />
<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jqueryui.js') }}"></script>

```

```twig
{# app/Resources/views/Form/tags_type_script.html.twig #}

<script type="text/javascript">
        (function () {
            var formId = '{{ form.vars.id }}';
            var separator = '{{ form.vars.separator }}';
            var textarea = document.getElementById(formId);
            var tags = [];

            if (textarea.value !== '') {
                tags = textarea.value.split(separator);
            }

            var updateTags = function () {
                var taggleTags = taggle.getTags();
                textarea.value = taggleTags.values.join(separator);
            };

            textarea.style.display = 'none';

            var taggle = new Taggle('tags_' + formId, {
                tags: tags,
                onTagAdd: updateTags,
                onTagRemove: updateTags
            });

            {% if form.vars.url is defined %}
                var container = taggle.getContainer();
                var input = taggle.getInput();
                var request = $.ajax({
                    url: '{{ form.vars.url }}',
                    method: 'GET'
                });

                var formatTags = {{ form.vars.jsTransformFunction }};

                request.done(function (tags) {
                    $(input).autocomplete({
                        source: formatTags(tags),
                        appendTo: container,
                        position: { at: 'left bottom', of: container },
                        select: function(event, data) {
                            event.preventDefault();
                            //Add the tag if user clicks
                            if (event.which === 1) {
                                taggle.add(data.item.value);
                            }
                        }
                    });
                });
            {% endif %}
        })();

</script>

```

N'oubliez pas de déclarer votre form type en tant que **service** dans le fichier `services.yml` :

```yml
# app/config/services.yml
services:
    tags_type:
        class: AppBundle\Form\Type\TagType
        tags:
            - { name: form.type, alias: tags_type}
            - { name: idci_asset_loader.asset_provider, alias: tags_type}
```

> Si vous avez plusieurs assets qui doivent être chargés dans un ordre précis dans un soucis de dépendance, vous pouvez ajouter une priorité à l'Asset (-1 par défaut). Plus la priorité est haute, plus tôt l'asset sera chargé dans le DOM.

```php
<?php

$this->assetCollection->add(new Asset('MyBundle:Form:form_type_asset_1.html.twig', [], 0));
$this->assetCollection->add(
    new Asset(
        'MyBundle:Form:form_type_asset_2.html.twig',
        [
            'options' => $options,
            'form'    => $view,
        ],
        1
    )
);

```


### Chargement manuel des assets

Vous pouvez utiliser le service **idci_asset_loader.asset_dom_loader** pour charger les assets d'un ou de tous les *providers*.

```php
<?php

// Charge les assets de tous les providers
$this->get('idci_asset_loader.asset_dom_loader')->loadAll();

// Charge les assets d'un seul provider identifié par son alias
$this->get('idci_asset_loader.asset_dom_loader')->load('tags_type');
```

### Chargement automatique des assets

Pour permettre au *subscriber* de charger les dépendances **automatiquement** (recommandé), ajoutez le paramètre suivant dans le fichier `config.yml` :

```yml
# app/config/config.yml

idci_asset_loader:
    auto_load: true
```
___

Voici un aperçu du résultat de notre Bundle :

![résultat final](/images/blog/asset-loader-demo-2.png)

___

Merci de nous avoir suivi tout au long de cet article.  
N'hésitez pas à [nous contacter](https://idci-consulting.fr/fr/contact) pour plus d'informations.

{% endverbatim %}
