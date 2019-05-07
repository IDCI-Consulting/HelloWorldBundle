# Creating a contact form with IDCIStepBundle


## Introduction

Let us get back to our StepBundle with a concrete application : the creation of a simple contact form.
If you need more information about how to install and use StepBundle, you can read our [introduction article](https://idci-consulting.fr/en/article/step-bundle-introduction).


## Create the contact form with StepBundle

We chose to build a simple process composed of one step, a contact form, and one path, representing the submission of the input data.
We can picture this case as the use of WordPress's contact form 7, Symfony style.

Here's an illustration of the expected result :

![Screenshot Contact Form](/images/blog/screenshot_contact_form.png "Screenshot Contact Form")

In the search bar, we see `localhost:8000` followed by the route we previously set up : `/contact`.
Then, we can see the title for our contact form 'Personal information', followed by the input fields for the user to fill in.
Since our form is only composed of one step and one path, we will use an "end" button instead of "next".

**Are you ready to start with StepBundle ?**

We will work in the `DefaultController.php` file from the default generated bundle `AppBundle`.

Let's start by creating a first action named `contact` :

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

In this action, we will define our first **map**.
This one is composed of only one step which will display a form allowing a user to input contact data. As seen earlier, two types of steps exist by default : `'html'` and `'form'`.
In our case, we will use the 'form' type, thanks to which we will be able to define all the input fields we want to display to the user.
In our example, we will ask for a first name, last name, phone number and email adress.

Then, our only path will be the submission of the form data which will end the navigation.
As we have seen it above, there exists three typs of paths : `single`, `conditional` and `end`. In our case, we will use the 'end' type.

```php
    ...
    public function contactAction(Request $request)
    {
        //Create the map
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('contact map')
            //Create the step and add the fields
            ->addStep('info', 'form', array(
                'title'            => 'Contact',
                'description'      => 'The contact form',
                'builder'          => $this->get('form.factory')->createBuilder()
                    ->add('first_name',   'text', array('label' => 'First Name'))
                    ->add('last_name',    'text', array('label' => 'Last Name'))
                    ->add('phone_number', 'text', array('label' => 'Phone Number'))
                    ->add('email',        'text', array('label' => 'Email'))
                ,
            ))
            //Create the path
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

Our map is now ready, thus we have to create the `navigator` from it :

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

Finally, you have to define the **redirections** that take place according to the way the user navigates.
Three cases are possible :
 - End of navigation : when one follows a route of 'end' type
 - Navigation : when one follows a route of 'single' or 'conditional' type
 - Return : when one decides to go back to a previous step


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

The work in the controller is done, now we only have to display our `navigator` in a twig template. To do so, let's edit the `Resources/views/Default/contact.html.twig` file :

{% verbatim %}
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
{% endverbatim %}

## Events : sending an email

The actions that we will execute during our process of steps and paths are defined by the `event actions`. We created some which are present by default, but it is possible to create your own.
For the sake of our example, we will create an event action aiming to send an email at the end of our step (when the user clicks on the `end` button).
It is possible to connect `event actions` to `paths` or `steps` at any point of your process.


### Creating a service for our mailer event action

We can see a service as a class which is accessible everywhere in our application.
Firstly, we have to create a `PathEventAction` :


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
> Note : To send our email, we used the **Swift Mailer** library, which you can read about in the [Symfony documentation](http://symfony.com/doc/current/email.html). Don't forget to configure it before usage.

The `setDefaultParameters` function allows us to define our parameters. In our case, we just need one parameter : the adress to which we will send the email.

```php
/**
 * {@inheritdoc}
 */
protected function setDefaultParameters(OptionsResolverInterface $resolver)
{
    // Define an 'email' parameter which is a required String.
    $resolver
        ->setRequired(array('email'))
        ->setAllowedTypes(array(
            'email' => array('string')
        ))
    ;
}
```
***********************************************************************************************************************************************************************************************
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

Here's the final rendering of our `PathEventAction` :

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


### Declare our event as a service

So, we have to create our `PathEventAction`. Subsequently, we have to declare it to make it accessible from the `container`.
We have to go to our `services.yml` file :

```yml
services:
    idci_step.path_event.action.send_thanks_email:
        class: IDCI\AppBundle\Path\Event\Action\SendThanksEmailPathEventAction
        arguments: [@mailer]
        tags:
            - { name: idci_step.path_event.action, alias: send_thanks_email }
```

In this way, in the process's configuration, we could use our service thanks to the id `send_thanks_email`.


### Use the service

To use our service and connect our event, we have to go to our `DefaultController.php`file.

IDCIStepBundle is based on the Symfony FormType's using to display a step.
Navigation's buttons are "submit" type's input to send datas of the current step.

So, `event` system is lean on events defined by the Framework to the FormType.

If you need more, you can read the Symfony doc : [Les Form Events](https://symfony.com/doc/2.8/form/events.html).

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
                                    'email' => '{% verbatim %}{{ flow_data.data.info.email }}{% endverbatim %}',
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

Your form is ready, you just have to test it.

Open your Symfony project in your browser and go to the `localhost:8000/contact` URL in order to enjoy your new registration form.

Here's how we depict it with our caption :

![Diagramm simple form](/images/blog/stepBundle_contact.png "Diagramm Simple Form")


## IDCIStepBundle's particularity

We have seen how to declare a configuration in the Controller, however, IDCIStepBundle allows altough to create maps directly in `config.yml` file and not in `DefaultController.php`.
We recommend you to use this method because this avoid to need to rewrite the code, and this is lighter.

Heres's our same example in our `config.yml` file :

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
                                        email: '{% verbatim %}{{ flow_data.data.info.email }}{% endverbatim %}'
```

In the `DefaultController.php` :

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


## Conclusion

IDCIStepBundle offer a large possibilities's scope thanks to configuration and personnalisation. It is possible to do simple process, as we saw it with our contact form, but it is also possible to optimize it, by creating `PathEventAction` and configure themselves as you want.
We couls also, as we have seen it, create morecomplex process.

Then, IDCIStepBundle allows to do modifications directly in the configuration, without having to rewrite in the Controller, which allow more maniabily.

Don't hesitate to do your feedback.

If you need help or expertise to your Symfony projects, you could [contact us]({{ path('contact', {_locale: app.translator.locale}) }} "Contact us")
