# How to create a contact form with StepBundle ? #

## Introduction ##

Back to our StepBundle and its effective application : the creation of a simple contact form. 
If you need more informations about StepBundle's using and installation, you could read our introduction article here /*mettre le lien*/

## Create the contact form with StepBundle ##

So, we have chosen to realize a process composed of one step : a contact form, and one path representing input datas's submission .
We can imagine this case as the utilisation of WordPress's contact form 7 with the Symfony touch.

Here's an illustration of the rendering : 

![Screenshot Contact Form](/images/blog/screenshot_contact_form.png "Screenshot Contact Form")

In the search bar, we have our ```localhost:8000``` follow by the route we previously set up : ```/contact```.
Then, we can see the title of our contact form 'Personal informations', follow by input fields that the user will complete.
As our form is composed by one step and one path, our button is not "next", but "end".

Ready to start with StepBundle ?

We will work in the bundle's `DefaultController.php` file  created by default `AppBundle`.

Let's start by creating a first action `contact` : 

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

In this action, we will define our first map.
So, this is composed of one step which will display a form in order to enter datas. As we had seen it above, there are two types of steps by default : 'html' and 'form'. In our case, the 'form' type will be the one we will use. Thanks to this, we will define all the input fields we want to display to the user.
In our example, we will ask first name, last name, phone number and email adress.

Then, our own path will be the submission of the form which will end the navigation.
As we have seen it above, it exists three typs of paths : 'single', 'conditional' and 'end'. In our case, the 'end' type will be the one we will use.

```php
    ...
    public function contactAction(Request $request)
    {
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('contact map')
            ->addStep('info', 'form', array(
                'title'            => 'Contact',
                'description'      => 'The contact form',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name', 'text', array('label' => 'prénom'))
                    ->add('last_name', 'text') //faire pareil
                    ->add('phone_number','text')
                    ->add('email','text')
                ,
            ))
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

Our map is ready now. You have to create the 'navigator' from this one:

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

Finally, you have to define the redirections to make according to the navigation realized by the user.
Three cases are possible :
   * End of navigation : when you follow a route of 'end' type
   * Navigation : when you follow a path of 'single' or 'conditional' type
   * Return : when you decide to go back to a previous step


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
            return $this->redirect($this->generateUrl('test', $navigator->getUrlQueryParameters()));
        }

        return array('navigator' => $navigator);
    }
}
```

We have now finished to work in our controller, we just have to diplay our 'navigator' in a twig template.
We have to edit our file `Resources/views/Default/contact.html.twig` :

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

## Events : generate an email's sending ## 

Events define actions of our process (steps, paths), we have created a few which are present by default, but it is possible to create some.
For our example, we will create an event to send an email at the end of our step (when the user clics on `end` button).
It's possible to connect events on paths or steps any time of your process.

### Create a service to our event ###

We can considere a service as a class which is accessible every where in our application.
In a first phase, we have to create a `PathEventAction` :

> Note : To send our email, we have used the Swift Mailer library, you can read the Symfony documentation : [Doc Swift Mailer](http://symfony.com/doc/current/email.html)._

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
    public function __construct(\Swift_Mailer $mailer) // On injecte le mailer
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

The `setDefaultParameters` function allows us to define our parameters. In our cas, we just need one parametre : the email adress.

```php
/**
 * {@inheritdoc}
 */
protected function setDefaultParameters(OptionsResolverInterface $resolver)
{
    // on défini un paramètre 'email' qui est un string et est requis
    $resolver
        ->setRequired(array('email'))
        ->setAllowedTypes(array(
            'email' => array('string')
        ))
    ;
}
```

The `doExecute` function, as its name said, will allow to the action to execute itself. So, it's in this function's body that we will write the code allowing us to create the email's sending.

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
            ->setBody('Hi, we are going to treat your request.')
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
                ->setBody('Hi, we are going to treat your request.')
            ;
            
            $this->mailer->send($message);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
```

### Déclarer notre event en tant que service ###

Nous avons donc crée notre `PathEventAction`. Dans un deuxième temps, il faut le déclarer pour que celui-ci soit accessible depuis le `container`. Pour cela, nous nous rendons dans le fichier `services.yml` :

```yml
services:
    idci_step.path_event.action.send_thanks_email:
        class: IDCI\AppBundle\Path\Event\Action\SendThanksEmailPathEventAction
        arguments: [@mailer]
        tags:
            - { name: idci_step.path_event.action, alias: send_thanks_email }
```

Ainsi, dans la configuration du parcours, nous pourrons utiliser notre service grâce à l'identifiant `send_thanks_email`.

### Utiliser le service ###

Pour utiliser notre nouveau service et ainsi brancher notre event, rendons nous dans notre fichier `DefaultController.php`.

StepBundle se base sur l'utilisation d'un FormType Symfony pour afficher une step. Les boutons de navigation étant des input de type "submit" pour envoyer les données de la step en cours.

Le système "d'évent" s'appuie donc sur les événements définis par le Framework pour les FormType, nous vous revoyons à la doc Symfony pour en savoir plus: [Les Form Events](https://symfony.com/doc/2.8/form/events.html).

/*Mettre un schéma*/


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
                                'action'     => 'send_thanks_email',
                                'parameters' => array(
                                    'email' => '{{ flow_data.data.info.email }}',
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

## Particularité de StepBundle ##

Nous avons vu comment déclarer un configuration dans le Controller, cependant, StepBundle permet aussi de créer nos maps directement dans le fichier `config.yml` et non dans le fichier `DefaultController.php`. 
Nous vous conseillons cette méthode car cela évite d'avoir besoin de réecrire le code, et cela est plus léger.

Voici notre même exemple dans notre fichier `config.yml`: 
      
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
                                        email: '{{ flow_data.data.info.email }}'
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
     * @Template("AppBundle:Default:defaultForm.html.twig")
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

## Conclusion ##

StepBundle offre un large champ de possibilités grâce à la configuration et la personnalisation. Il est possible de mettre des parcours simples, comme nous l'avons vu avec notre formulaire de contact, mais il est aussi envisageable d'optimiser celui-ci, par exemple en créant des `PathEventAction` et en les configurant selon vos souhaits.
Il est aussi possible de créer des parcours plus complexes, comme nous le verrons dans notre prochain article.
Puis, StepBundle permet de faire des modifications directement dans la configuration, sans avoir besoin de rééecrire dans le Controller, ce qui permet plus de maniabilité.

N'hésitez pas à nous faire vos retours.

Si vous avez besoin d'aide ou d'une expertise, vous pouvez [nous contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").



