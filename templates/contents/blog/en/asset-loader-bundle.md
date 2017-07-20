{# verbatim #}

# Asset Loader Bundle

When working with the Symfony 2 / 3 frameworks, we often find ourselves facing the same issues regarding the loading of front-end dependencies or **assets**, particularly when working with custom *Form Types*. In this article, we will expose the different problems that we encouter and the solution that we offer.


## Introduction

To picture the previously-mentioned problems, we'll use a simple typical example.  
Let's imagine we want to create a custom form type, which would be based on the Text type, allowing us to handle a system of *tags*.  
We'll use **[Taggle.js](https://jquery.com/)** in order to generate the tags and **[JQuery](https://sean.is/poppin/tags)** to implement an *autocompletion* system.  
Therefore, we have 3 scripts : one specific to the widget and two dependencies, Taggle and JQuery.

___

We could be tempted to include the dependencies in the **widget script** (in `<script>` tags), which would not be an issue if we only use **one instance** of it at a time. This effectively becomes problematic when we wish to use multiple ones in a form : **the dependencies are loaded in the DOM as many times as there are instances of the widget**.

![rendu avec DOM](/images/blog/asset-loader-demo-1.png)

We could avoid this problem of multiple dependency loading by including the dependencies in the `<head>` tag of the `base.html.twig` file, but this introduces two further problems :

- The dependencies will be included in every page of the application, even when we don't need them
- This implies more work for the developpers wanting to implement the widget, because they'd have to include the dependencies themselves in the file.

___

Another problem appears if our widget uses globally loaded dependencies, e.g. JQuery, which would then be registered in the `{% block javascripts %}` of the  `base.html.twig` file. There is then a risk that the widget script runs before these dependencies are loaded, being placed **above** in the code.  
A possible solution to avoid this issue would be to **delay** the execution of the widget script with the following native function :

```javascript
window.addEventListener('load', function () {
    // code goes here ...
});
```

With this technique, the script only runs when the window is entirely loaded, meaning our dependencies should be loaded and available. But this doesn't solve the issue of dependency duplication.

___

Furthermore, scripts loaded at the middle of the page, **[above the fold](https://en.wikipedia.org/wiki/Above_the_fold)**, introduce minor page speed optimisation issues. [More](https://www.optimizely.com/optimization-glossary/above-the-fold/).

___

Thus, we want to be able to :

- Only load the required dependencies **once per page**
- Not load the widgets scripts if there are no such widgets on the page
- Load the dependencies at the bottom of the page

**Fortunately, our [bundle](https://github.com/IDCI-Consulting/AssetLoaderBundle) does that all at once**



## Installation

First, we need to add **AssetLoaderBundle** as a dependency of our project. To do so, we simply need to add it to the `composer.jscon` file.

```json
"require": {
    ...
    "idci/asset-loader-bundle": "dev-master"
},
```

Then, let's install this dependency with composer :

```sh
$ php composer.phar update
```

Finally let's register the bundle in the application *kernel*:

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

## Using the Bundle

Adding assets to our custom form type is pretty simple :

- Our **AbstractType** has to implement the **getAssetCollection()** method from the **AssetProviderInterface** interface. **AssetCollection** represents an array of Asset objects.
- We must define our type as a **service** and add a tag named **idci_asset_loader.asset_provider**
- The dependencies have to be placed in one or more `twig` files, with the other template files

Our AbstractType will therefore look like this :

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

        // argument passed
        $this->assetCollection->add(new Asset('Form/tags_type_script.html.twig', ['form' => $view]));

        $view->vars['separator']           = $options['separator'];
        $view->vars['jsTransformFunction'] = $options['jsTransformFunction'];

        if (isset($options['url'])) {
            $view->vars['url'] = $options['url'];
        }

        return $view->vars;
    }

    // ...
}
```

Our script uses elements from `view` in order to work, so we pass it as an argument of **the Asset**.  
In our case, because our script needs dependencies to work properly, we need to load these before the actual widget script. Thus, we include the two elements (dependencies and actual script) in two `twig` files :

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

Don't forget to declare your form type as a **service** in the `services.yml` file :

```yml
# app/config/services.yml
services:
    tags_type:
        class: AppBundle\Form\Type\TagType
        tags:
            - { name: form.type, alias: tags_type}
            - { name: idci_asset_loader.asset_provider, alias: tags_type}
```

> If you have multiple assets which have to be loaded in a precise order, you can add a priority to the asset as 3rd argument (-1 by default). The higher the priority, the sooner the asset will be loaded in the DOM.

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

### Loading the asset manually

You can use the **idci_asset_loader.asset_dom_loader** service in order to load the assets of one or more *providers*.

```php
<?php

// Loads the assets from all the providers
$this->get('idci_asset_loader.asset_dom_loader')->loadAll();

// Loads the assets from one provider identified by its alias
$this->get('idci_asset_loader.asset_dom_loader')->load('tags_type');
```

### Loading the assets automaically

To allow the *subscriber* to load the dependencies **automatically** (recommended), add the following parameter to the `config.yml` file :

```yml
# app/config/config.yml

idci_asset_loader:
    auto_load: true
```

___

Thank you for reading. Don't hesitate to [contact us](https://idci-consulting.fr/en/contact) for further information.

{% endverbatim %}
