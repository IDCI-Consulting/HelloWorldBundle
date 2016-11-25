# Comment créer un formulaire de contact avec StepBundle ? #

## Introduction ##

Retour à notre StepBundle et son application effective : la création d'un formulaire de contact simple.
Pour des renseignements concernant l'utilisation et l'installation de StepBundle, vous pouvez vous reporter à notre article d'introduction ici /*mettre lien vers article d'intro*/.

## Créer le formulaire de contact avec StepBundle ##

Nous avons donc choisi de réaliser un parcours composé d'une seule step : un formulaire de contact, et un seul path représentant la soumission des données saisies. 
Nous pouvons voir ce cas comme l'utilisation du contact form 7 de Wordpress à la sauce Symfony.

Voici une illustration du rendu attendu.

![Screenshot Contact Form](/images/blog/screenshot_contact_form.png "Screenshot Contact Form")

Dans la barre de recherche, nous avons notre ```localhost:8000``` suivi de la route que nous avons préalablement configuré : ```/contact```.
Ensuite, nous pouvoir voir le titre de notre formulaire de contact 'Personal informations', suivi des champs de saisies à compléter par l'utilisateur.
Comme notre formulaire ne se compose que d'une seule step et d'une seule path, notre bouton n'est pas 'next' mais bien 'end'.

Prêt à commencer avec StepBundle ? 

Nous allons travailler dans le fichier `DefaultController.php` du bundle crée par défaut `AppBundle`.

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

Dans cette action, nous allons définir notre première map. 
Celle-ci se compose donc d'une seule step qui affichera un formulaire afin d'y saisir des données. Comme nous l'avons vu plus haut, il existe de base deux types de steps : 'html' et 'form'. Dans notre cas, le type 'form' sera celui que nous allons utiliser. Grâce à celui-ci, nous allons pouvoir définir l'ensemble des champs de saisie que nous voulons afficher à l'internaute.
Dans notre exemple, nous demanderons le prénom, le nom, le numéro de téléphone et l'adresse email.
 
Puis, notre seule chemin (path) sera la soumission du formulaire qui mettra fin à notre navigation. 
Comme nous l'avons vu plus haut, il existe trois types de chemins : 'single', 'conditional' et 'end'. Dans notre cas, le type 'end' sera celui que nous allons utiliser.

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

Notre map est maintenant prête, il faut maintenant créer le 'navigator' à partir de celle-ci : 

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

Enfin, il faut définir les redirections à effectuer en fonction de la navigation réalisée par l'internaute. Trois cas sont possibles : 
    * Fin de navigation : lorsque l'on emprunte un chemin de type 'end'.
    * Navigation : lorsque l'on emprunte un chemin de type 'single' ou 'conditional'.
    * Le retour : lorsque l'on décide de retourner à une step précédente.


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

Le travail dans le controleur est terminé, il ne nous reste plus qu'à afficher notre 'navigator' dans un template twig. Pour cela, éditons le ficher `Resources/views/Default/contact.html.twig` :

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

## Les events : générer un envoi de mail ##

Les events définissent les actions de notre parcours (steps, paths), nous en avons crée quelques uns qui sont présents par défaut, mais il est possible d'en créer soi même. 
Pour notre exemple, nous allons mettre en place un event pour envoyer un email à la fin de notre step (lors du clic sur le bouton `end`).
Il est possible de brancher des events sur des paths ou des steps à n'importe quel moment de votre parcours.

### Créer un service pour notre event d'envoi de mail ###

Nous pouvons considérer un service comme une classe qui est rendue accessible partout dans notre application.
Dans un premier temps, il nous faut donc créer notre `PathEventAction` :

> Remarque : Pour l'envoi de notre mail, nous avons utilisé la librairie Swif Mailer, nous vous renvoyons à la doc Symfony (.en) : [Doc Swift Mailer](http://symfony.com/doc/current/email.html)._

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
La fonction `setDefaultParameters` nous permet de définir nos paramètres. Dans notre cas, nous avons besoin d'un seul paramètre : l'adresse vers laquelle nous enverrons l'email.

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

La fonction `doExecute`, comme son nom l'indique, va permettre à l'action de s'éxécuter. C'est donc dans le corps de cette fonction que nous allons écrire le bout de code nous permettant de créer l'envoi de mail.

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

![Legende simple form](/images/blog/contact_legend.pdf "Légende Simple Form")

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



