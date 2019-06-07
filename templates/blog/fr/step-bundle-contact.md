# Créer un formulaire de contact avec IDCIStepBundle


## Introduction

Retour à notre StepBundle avec un cas concret : la création d'un formulaire de contact simple.
Pour des renseignements concernant l'utilisation et l'installation de IDCIStepBundle, vous pouvez vous reporter à notre [article d'introduction](https://idci-consulting.fr/fr/article/step-bundle-introduction).


## Créer le formulaire de contact avec IDCIStepBundle

Nous avons choisi de réaliser un parcours composé d'une seule step : un formulaire de contact, et un seul path représentant la soumission des données saisies.
Nous pouvons voir ce cas comme l'utilisation du contact form 7 de Wordpress à la sauce Symfony.

Voici une illustration du rendu attendu.

![Step Bundle Contact Form URL](/images/blog/stepBundle_contact_url.png "Contact Form URL")

Dans la barre de recherche, nous avons notre `localhost:8000` suivi de la route que nous avons préalablement configuré : `/contact`.
Ensuite, nous pouvons voir le titre de notre formulaire de contact 'Personal information', suivi des champs de saisie à compléter par l'utilisateur.
Comme notre formulaire ne se compose que d'une seule step et d'un seule path, nous placerons un bouton de fin de parcours ("end").

**Prêts à commencer avec IDCIStepBundle ?**

Nous allons travailler dans le fichier `DefaultController.php` du bundle créé par défaut `AppBundle`.

Commençons par créer une première action `contact` :

```php
// src/AppBundle/Controller/DefaultController.php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/contact/", name="contact")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function contactAction(Request $request)
    {
        ...
    }
```

Dans cette action, nous allons définir notre première **map**.
Celle-ci se compose donc d'une seule step qui affichera un formulaire afin d'y saisir des données. Comme nous l'avons vu plus tôt, il existe de base deux types de steps : `html` et `form`.
Dans notre cas, le type 'form' sera celui que nous allons utiliser. Grâce à celui-ci, nous allons pouvoir définir l'ensemble des champs de saisie que nous voulons afficher à l'internaute.
Dans notre exemple, nous demanderons le prénom, le nom, le numéro de téléphone et l'adresse email.  

Puis, notre seul chemin (path) correspondra à l'action de soumission du formulaire qui mettra fin à notre navigation.
Comme nous l'avons vu plus haut, il existe trois types de chemins : `single`, `conditional` et `end`. Dans notre cas, le type 'end' sera celui que nous allons utiliser.

```php
    ...
    public function contactAction(Request $request)
    {
        //Création de la map
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('contact map')
            //Création de la step et ajout des champs
            ->addStep('info', 'form', array(
                'title'            => 'Contact',
                'description'      => 'The contact form',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name',   'text', array('label' => 'First Name'))
                    ->add('last_name',    'text', array('label' => 'Last Name'))
                    ->add('phone_number', 'text', array('label' => 'Phone Number'))
                    ->add('email',        'text', array('label' => 'Email'))
                ,
            ))
            //Création de la path
            ->addPath(
                'end',
                array(
                    'source'       => 'info',
                    'next_options' => array(
                        'label' => 'end',
                    ),
                )
            )
            ->getMap($request)
        ;
    }
```

Notre map est maintenant prête, il faut donc créer le `navigator` à partir de celle-ci :

```php
    ...
    public function contactAction(Request $request)
    {
        ...
        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator($request, $map)
        ;
    }
```

Enfin, il faut définir les **redirections** à effectuer en fonction de la navigation réalisée par l'internaute. Trois cas sont possibles :
 - Fin de navigation : lorsque l'on emprunte un chemin de type 'end'.
 - Navigation : lorsque l'on emprunte un chemin de type 'single' ou 'conditional'.
 - Le retour : lorsque l'on décide de retourner à une step précédente.


```php
    ...
    public function contactAction(Request $request)
    {
        ...
        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($navigator->getFinalDestination());
        }
        if ($navigator->hasNavigated() || $navigator->hasReturned()) {
            return $this->redirect($this->generateUrl('contact', $navigator->getUrlQueryParameters()));
        }

        return array('navigator' => $navigator);
    }
}
```

Le travail dans le contrôleur est terminé, il ne nous reste plus qu'à afficher notre `navigator` dans un template twig. Pour cela, éditons le ficher `Resources/views/Default/contact.html.twig` :

{%verbatim%}
```twig
{# src/AppBundle/Resources/views/Default/contact.html.twig #}
{% extends "::base.html.twig" %}

{% block stylesheets %}
    {{ parent() }}
    {{ step_stylesheets(navigator) }}
{% endblock %}

{% block javascripts %}
    {{ parent() }}
    {{ step_javascripts(navigator) }}
{% endblock %}

{% block body %}
    {{ step(navigator) }}
{% endblock %}
```
{%endverbatim%}

## Les event actions : générer un envoi de mail

Les `event actions` définissent les actions à éxécuter lors de notre navigation (steps, paths). Nous en avons intégré quelques uns par défaut avec IDCIStepBundle, mais il est possible d'en créer soi même.
Pour notre exemple, nous allons mettre en place un event action visant à envoyer un email à la fin de notre step (lors du clic sur le bouton `end`).
Il est possible de brancher des `event actions` sur des `paths` ou des `steps` à n'importe quel moment de votre parcours.


### Créer un service pour notre event action d'envoi de mail

Nous pouvons considérer un service comme une classe qui est rendue accessible partout dans notre application.
Dans un premier temps, il nous faut donc créer notre `PathEventAction` :

```php
<?php
// src/AppBundle/Path/Event/Action

namespace AppBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

class SendThanksEmailPathEventAction extends AbstractPathEventAction
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
    }
}
```

> Remarque : pour l'envoi de notre mail, nous avons utilisé la librairie **Swift Mailer**, nous vous renvoyons à la [documentation Symfony](http://symfony.com/doc/current/email.html) (en). N'oubliez pas de la configurer avant utilisation.

La fonction `setDefaultParameters` nous permet de définir nos **paramètres**. Dans notre cas, nous avons besoin d'un seul paramètre : l'adresse vers laquelle nous enverrons le mail.

```php
/**
 * {@inheritdoc}
 */
protected function setDefaultParameters(OptionsResolverInterface $resolver)
{
    // Définition d'un paramètre 'email' qui est de type String et est obligatoire
    $resolver
        ->setRequired(array('email'))
        ->setAllowedTypes(array(
            'email' => array('string')
        ))
    ;
}
```

La fonction `doExecute`, comme son nom l'indique, va permettre à l'action de s'**éxécuter**. C'est donc dans le corps de cette fonction que nous allons écrire le code nous permettant de réaliser l'envoi de mail.

```php
/**
 * {@inheritdoc}
 */
protected function doExecute(PathEventInterface $event, array $parameters = array())
{
    try {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hey there')
            ->setFrom('noreply@idci-consulting.fr')
            ->setTo($parameters['email'])
            ->setBody($parameters['message'])
        ;

        $this->mailer->send($message);
    } catch (\Exception $e) {
        throw $e;
    }
}
```

Voici le rendu final de notre `PathEventAction` :

```php
<?php

namespace AppBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

class SendThanksEmailPathEventAction extends AbstractPathEventAction
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('email'))
            ->setAllowedTypes(array(
                'email' => array('string')
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject('Hey there')
                ->setFrom('noreply@idci-consulting.fr')
                ->setTo($parameters['email'])
                ->setBody($parameters['message'])
            ;

            $this->mailer->send($message);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
```


### Déclarer notre event action en tant que service

Nous avons donc créé notre `PathEventAction`. Déclarons le pour qu'il soit accessible depuis le `container`. Pour cela, nous éditons le fichier `services.yml` :

```yml
// src/AppBundle/Resources/config

services:
    idci_step.path_event.action.send_thanks_email:
        class: AppBundle\Path\Event\Action\SendThanksEmailPathEventAction
        arguments: [@mailer]
        tags:
            - { name: idci_step.path_event.action, alias: send_thanks_email
```

Ainsi, dans la configuration du parcours, nous pourrons utiliser notre service taggé grâce à l'identifiant (**alias**) `send_thanks_email`.


### Utiliser le service

Pour utiliser notre nouveau service et ainsi brancher notre event action, rendons nous dans notre fichier `DefaultController.php`.

IDCIStepBundle se base sur l'utilisation d'un **FormType** Symfony pour afficher une step, les boutons de navigation étant des input de type "submit" pour envoyer les données de la step en cours.

Le système d'`event` s'appuie donc sur les événements définis par le framework pour les FormType.

Nous vous renvoyons à la doc Symfony pour en savoir plus: [Les Form Events](https://symfony.com/doc/2.8/form/events.html).

Voici deux schémas explicatifs :

![StepBundle Form Events](/images/blog/stepBundle_FormEvent1.png "StepBundle Form Events")

<br><br>

![StepBundle Form Events](/images/blog/stepBundle_FormEvent2.png "StepBundle Form Events")

###Le cas du flow_data
Comme nous allons le voir, nous utilisons ci-dessous le `flow_data`, qui va nous permettre de **recueillir les données** que l'utilisateur a rentré dans un champ. Ici, nous l'avons utilisé deux fois :

 - L'adresse mail : le flow data nous permet de connaître l'adresse de destination.
 - Le prénom : le flow data nous permet de personnaliser notre email en fonction du prénom de l'utilisateur.

C'est le principe du merge token (champ de fusion), nativement proposé par IDCIStepBundle, qui propose de remplacer des informations.
Le premier paramètre, `flow_data`, est prédéfini. Pour le deuxième paramètre, ici `data`, il en existe trois cas :
    /*TROIS CAS MANQUANTS*/
Puis, il nous suffit d'ajouter le nom de l'étape et le champ concerné.


```php
// src/AppBundle/Controller/DefaultController

class DefaultController extends Controller
{
    ...
    public function contactAction(Request $request)
    {
        $map = $this
            ...
            ->addPath(
                'end',
                array(
                    'source'       => 'info',
                    'next_options' => array(
                        'label' => 'end',
                    ),
                    'events' => array(
                        'form.post_bind' => array(
                            array(
                                'action'      => 'send_thanks_email',
                                'parameters'  => array(
                                    'email'   => '{% verbatim %}{{ flow_data.data.info.email }}{% endverbatim %}',
                                    'message' => 'Thank you  {% verbatim %}{{ flow_data.data.info.first_name }}{% endverbatim %} for contacting us',
                                )
                            )
                        )
                    )
                )
            )
        ;
        ...
    }
}
```


Votre formulaire est prêt, il ne vous reste plus qu'à le tester.

Ouvrez votre projet Symfony dans votre navigateur, et rendez-vous sur l'URL `localhost:8000/contact` afin d'apprécier votre nouveau formulaire de contact.

Voici comment nous l'avons représenté avec notre légende :

![Legende simple form](/images/blog/stepBundle_contact.png "Légende Simple Form")


## Particularité de IDCIStepBundle

Nous avons vu comment déclarer un configuration dans le contrôleur, cependant, IDCIStepBundle permet aussi de créer nos maps directement dans le fichier `config.yml` et non dans le fichier `DefaultController.php`.
Nous vous conseillons cette méthode car cela évite d'avoir besoin de réécrire le code, et cela est plus léger.

Voici le même exemple dans notre fichier `config.yml`:

```yaml
# app/config/config.yml
....
idci_step:
    maps:
        contact:
            name: 'contact'
            steps:
                info:
                    type: 'form'
                    options:
                        title: 'Personal informations'
                        description: 'The personal data step'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'first_name'
                                        type: 'text'
                                    -
                                        name: 'last_name'
                                        type: 'text'
                                    -
                                        name: 'phone_number'
                                        type: 'text'
                                    -
                                        name: 'email'
                                        type: 'text'
            paths:
                -
                    type: 'end'
                    options:
                        source: info
                        next_options:
                            label: 'end'
                        events:
                            form.post_bind:
                                -
                                    action: send_thanks_email
                                    name: send_thanks_email
                                    parameters:
                                        email: "{% verbatim %}{{ flow_data.data.info.email }}{% endverbatim %}"
                                        message: "Thank you {% verbatim %}{{ flow_data.data.info.first_name }}{% endverbatim %} to contact us"
```

Dans le `DefaultController.php` :

```php
// src/AppBundle/Controller/DefaultController
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/contact/", name="contact")
     *
     *
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Default:contact.html.twig")
     */
    public function contactAction(Request $request)
    {
        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
                $request,
                'contact'
            )
        ;

        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($navigator->getFinalDestination());
        }
        if ($navigator->hasNavigated() || $navigator->hasReturned()) {
            return $this->redirect($this->generateUrl(
                'contact',
                $navigator->getUrlQueryParameters()
            ));
        }

        return array('navigator' => $navigator);
    }
}
```


## Conclusion

IDCIStepBundle offre un large champ de possibilités grâce à la **configuration** et la **personnalisation**. Il est possible de créer des parcours simples, comme nous l'avons vu avec notre formulaire de contact, même s'il est aussi envisageable d'optimiser celui-ci, par exemple en créant des event actions (PathEventAction, StepEventAction) et en les configurant selon vos souhaits.
Il est aussi possible de créer des parcours plus complexes, comme nous le verrons dans un prochain article.
Puis, IDCIStepBundle permet de faire des modifications directement dans la configuration, sans avoir besoin de modifier le contrôleur, ce qui permet plus de maintenabilité.

N'hésitez pas à nous faire vos retours sur ce bundle.

Si vous avez besoin d'aide ou d'une expertise, vous pouvez [nous contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").
