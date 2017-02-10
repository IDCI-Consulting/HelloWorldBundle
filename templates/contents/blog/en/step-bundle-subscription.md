# Create a complex process with IDCIStepBundle


## Introduction

Once again, we return at our IDCIStepBundle, this time we are going to present you a more complex use : creation of a registration process.
If you need details about installation and use, you could report yourself to our introduction article here /*link*/


## Create the registration form with IDCIStepBundle

So, we have chosen to create a process composed of several steps and several paths.

For this example, we have chosen to imagine an extract of registration form to university, in that, the user's choice between two cities will change the destination's page.

Once more, we are goig to work in the `DefaultController.php` file.

Here's an illustration of the rendering we are waiting for.

![IDCIStepBundle Subscription URL](/images/blog/stepBundle_subscription_url.png "IDCIStepBundle Subscription URL")

If you are watching the first lines closer in the example below, you will will find the URL of our website : we add parameters explaining the final destination.
At the end of our process, user is redirecting to the IDCI-Consulting's home page.
Also, through the "choice" paremeter, we have organize a drop-down menu.
In this example, user chooses his study city, Lyon or Paris. According to his answer, he will be redirected to the courses of a city, or the other.
But we will discuss this later by watching paths...

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
     * @Route("/subscription/", name="subscription")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function subscriptionAction(Request $request)
    {
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('test map', array(), array (
                'final_destination' => 'http://www.idci-consulting.fr',
            ))
            ->addStep('personal', 'form', array(
                'title'            => 'Personal information',
                'description'      => 'The personal data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name', 'text', array(
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                    ->add('last_name', 'text')
                    ->add('phone_number','text')
                    ->add('email', 'text')
                    ->add('adress', 'text')
                    ->add('post_code', 'text')
                    ->add('city','text')
            ))
            ->addStep('cursus', 'form', array(
                'title'            => 'Your course',
                'description'      => 'Course and studying city',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('diploma_date', 'choice', array(
                        'label'   => "When did you obtain your high school diploma ?",
                        'choices' => range(date('Y') - 100, date('Y')),
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                    ->add('university_level', 'choice', array(
                        'label' => 'What is your university level ?',
                        'choices' => array(
                            'bac1' => 'Bac+1',
                            'bac2' => 'Bac+2',
                            'bac3' => 'Bac+3',
                            'bac4' => 'Bac+4',
                        )
                    ))
                    ->add('study_city', 'choice', array(
                        'label' => 'Where do you want to study ?',
                        'choices' => array(
                            'Lyon' => 'Lyon',
                            'Paris' => 'Paris',
                        ),
                    ))
            ))
            ->addStep('cursus_lyon', 'form', array(
                'title'            => 'Welcome to Lyon, we hope you like quenelle of pike !',
                'description'      => 'The differents courses in Lyon',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('cursus', 'choice', array(
                        'choices' => array(
                            'bts_com' => 'HDN Communication',
                            'bts_mktg' => 'HDN Marketing',
                            'bachelor_com' => 'Bachelor Communication',
                            'bachelor_mktg' => 'Bachelor Marketing',
                            'bachelor_digital' => 'Bachelor Digital',
                            'master_comm' => 'Master degree Communication',
                            'master_mktg' => 'Master degree Marketing',
                            'master_digital' => 'Master degree Digital',
                        ),
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
            ))
            ->addStep('cursus_paris', 'form', array(
                'title'            => 'Welcome to Paris, we hope you like the subway !',
                'description'      => 'The differents courses in Paris',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('cursus', 'choice', array(
                        'choices' => array(
                            'bts_com' => 'HDN Communication',
                            'bts_mktg' => 'HDN Marketing',
                            'bts_business' => 'HDN Business',
                            'bachelor_com' => 'Bachelor Communication',
                            'bachelor_mktg' => 'Bachelor Marketing',
                            'bachelor_business' => 'Bachelor Business',
                            'master_comm' => 'Master degree Communication',
                            'master_mktg' => 'Master degree Marketing',
                            'master_business' => 'Master degree Business',
                            ),
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
            ))
            ->addStep('end', 'html', array(
                'title'       => 'Inscription online',
                'description' => 'Inscription done',
                'content'     => '<h1>Thank you !</h1><h2>Please, join the proof of entitlement</h2> <h3>inscriptions.school@school.com</h3>',
            ))

            ->addPath(
                'single',
                array(
                    'source'       => 'personal',
                    'destination'  => 'cursus',
                    'next_options' => array(
                        'label' => 'next',
                    ),
                )
            )
            ->addPath(
                'conditional_destination',
                array(
                    'source'       => 'cursus',
                    'destinations' => array(
                        'cursus_lyon' => '{% verbatim %}{{ flow_data.data.cursus.study_city == \'Lyon\' }}{% endverbatim %}',
                        'cursus_paris' => '{% verbatim %}{{ flow_data.data.cursus.study_city == \'Paris\' }}{% endverbatim %}'
                    ),
                    'default_destination' => 'cursus_paris',
                    'next_options' => array(
                        'label' => 'next',
                    ),
                )
            )
            ->addPath(
                'single',
                array(
                    'source'       => 'cursus_lyon',
                    'destination'  => 'end',
                    'next_options' => array(
                        'label' => 'next',
                    ),
                )
            )
            ->addPath(
                'single',
                array(
                    'source'       => 'cursus_paris',
                    'destination'  => 'end',
                    'next_options' => array(
                        'label' => 'next',
                    ),
                )
            )
            ->addPath(
                'end',
                array(
                    'source'       => 'end',
                    'next_options' => array(
                        'label' => 'end',
                    ),
                )
            )
            ->getMap($request)
        ;

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator($request, $map)
        ;

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

// revenir sur le conditional destination path

Your form is ready, you just have to test it.

Open your Symfony project in your browser and go to the `localhost:8000/subscription` URL in order to enjoy your new registration form.

Here's how we depict it with our caption :

![Diagramm IDCIStepBundle Subscription](/images/blog/stepBundle_subscription.png "Diagramm IDCIStepBundle Subscription")


### IDCIStepBundle's particularity

We have seen how to declare a configuration in the Controller, however, IDCIStepBundle allows altough to create maps directly in `config.yml` file and not in `DefaultController.php`.
We recommend you to use this method because this avoid to need to rewrite the code, and this is lighter.

Heres's our same example in our `config.yml` file :

```yaml
idci_step:
    maps:
        subscription:
            name: 'subscription'
            steps:
                personal:
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
                                    -
                                        name: 'zip_code'
                                        type: 'text'
                                    -
                                        name: 'city'
                                        type: 'text'
                cursus:
                    type: 'form'
                    options:
                        title: 'Your course'
                        description: 'Course and studying city'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'diploma_date'
                                        type: 'IDCI\ContactBundle\Form\Type\DiplomaDateFormType'
                                    -
                                        name: 'university_level'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: 'What is your university level ?'
                                            choices:
                                                bac1: 'Bac+1'
                                                bac2: 'Bac+2'
                                                bac3: 'Bac+3'
                                                bac4: 'Bac+4'
                                    -
                                        name: 'study_city'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: 'Where do you want to study ?'
                                            choices:
                                                Lyon: 'Lyon'
                                                Paris: 'Paris'
                cursus_lyon:
                    type: 'form'
                    options:
                        title: 'Welcome to Lyon, we hope you like quenelle of pike !'
                        description: 'The differents courses in Lyon'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'cursus'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            choices:
                                                bts_comm: 'HDN Communication'
                                                bts_mktg: 'HDN Marketing'
                                                bachelor_comm: 'Bachelor Communication'
                                                bachelor_mktg: 'Bachelor Marketing'
                                                bachelor_digital: 'Bachelor Digital'
                                                master_comm: 'Master degree Communication'
                                                master_mktg: 'Master degree Marketing'
                                                master_digital: 'Master degree Digital'
                cursus_paris:
                    type: 'form'
                    options:
                        title: 'Welcome to Paris, we hope you like the subway !'
                        description: 'The differents courses in Paris'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'cursus'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            choices:
                                                bts_comm: 'HDN Communication'
                                                bts_mktg: 'HDN Marketing'
                                                bts_business: 'HDN Business'
                                                bachelor_comm: 'Bachelor Communication'
                                                bachelor_mktg: 'Bachelor Marketing'
                                                bachelor_business: 'Bachelor Business'
                                                master_comm: 'Master degree Communication'
                                                master_mktg: 'Master degree Marketing'
                                                master_business: 'Master degree Digital'
                end:
                    type: 'html'
                    options:
                        title: 'Inscription online'
                        description: 'Inscription done'
                        content: 'Thank you ! Please, join the proof of entitlement. Inscriptions.school@school.com'
            paths:
                -
                    type: 'single'
                    options:
                        source: 'personal'
                        destination: 'cursus'
                        next_options:
                            label: 'next'
                -
                    type: 'conditional_destination'
                    options:
                        source: 'cursus'
                        destinations:
                            cursus_lyon: '{% verbatim %}{{flow_data.data.cursus.study_city == 'Lyon' }}{% endverbatim %}'
                            cursus_paris: '{% verbatim %}{{flow_data.data.cursus.study_city == 'Paris' }}{% endverbatim %}'
                        default_destination: 'cursus_paris'
                        next_options:
                            label: 'next'
                -
                    type: 'single'
                    options:
                        source: 'cursus_lyon'
                        destination: 'end'
                        next_options:
                            label: 'next'
                -
                    type: 'single'
                    options:
                        source: 'cursus_paris'
                        destination: 'end'
                        next_options:
                            label: 'next'
                -
                    type: 'end'
                    options:
                        source: 'end'
                        next_options:
                            label: 'end'
```

In the `DefaultController.php` file :

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
     * @Route("/subscription/", name="subscription")
     *
     *
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Default:defaultForm.html.twig")
     */
    public function subscriptionAction(Request $request)
    {
        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
            $request,
            'subscription'
            )
        ;

        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($navigator->getFinalDestination());
        }
        if ($navigator->hasNavigated() || $navigator->hasReturned()) {
            return $this->redirect($this->generateUrl('subscription', $navigator->getUrlQueryParameters()));
        }

        return array('navigator' => $navigator);
    }
}
```


## Event actions

`Event actions` define actions to execute during our navigation (steps, paths), we have organize some which are present by default with IDCIStepBundle, but it is possible to create some by yourself.
To our example, we are going to organize an event action aims at save in database the user's datas at the end of our step (while the clic on the `end` button).
It is possible to connect `event actions` on `paths` or `steps` when you want, on your process.

Requirements :
 - Doctrine
 - Entity Manager


### Create a service to our event action of back-up's datas

We could consider a service as a class wich is render accessible anywhere in our application.
Firstly, we have to create our `PathEventAction` :

> Remark : to our back-up's datas, we used the Doctrine library, you could read the Symfony doc  : [Doc Doctrine](http://symfony.com/doc/current/doctrine.html)._

```php
<?php
// src/AppBundle/Path/Event/Action

namespace AppBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Doctrine\ORM\EntityManager;

/**
 * @ORM\Table(name="SaveDataPathEventAction")
 *
 * @ORM\Entity
 */
class SaveDataPathEventAction extends AbstractPathEventAction
{
    /**
     * @var \EntityManager
     */
    private $em;

    /**
     * Constructor.
     *
     * @param \EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
```

The `doExecute` function, as its name indicate it, will allow the action to execute itself. So, it is in this function's body that we are to write the code allowing us to generate the back-up's datas.
`Persist` allows to say that this entity is now genereate directly by Doctrine.
`Flush` allows to Doctrine to effectuate necessaries requests to save entities.

If you need more informations, you can read the Doctrine's doc here [Doc `flush` et `persist`](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/working-with-objects.html)

```php
    /**
     * Do execute
     *
     * @param $event, $parameters
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        var_dump($parameters); die();
        try {
            $em->persist($subscription);
            $this->em->flush();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
```

The `setDefaultParameters` function allow us to define our parameters. In our case, we have specify two things :
 - unavoidables parameters (`setRequired`)
 - optionals parameters (`setOptional`)

```php
    /**
     * Set default parameters
     *
     * @param string $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'first_name',
                'last_name',
                'email',
                'study_city',
            ))
            ->setOptional(array(
                'phone_number',
                'zip_code',
                'city',
                'diploma_date',
                'university_level',
                'cursus',
            ))
        ;
    }
}
```
Here's the final rendering of our `PathEventAction` :

```php
<?php

namespace IDCI\ContactBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Doctrine\ORM\EntityManager;

/**
 * @ORM\Table(name="SaveDataPathEventAction")
 *
 * @ORM\Entity
 */
class SaveDataPathEventAction extends AbstractPathEventAction
{
    /**
     * @var \EntityManager
     */
    private $em;

    /**
     * Constructor.
     *
     * @param \EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Do execute
     *
     * @param $event, $parameters
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        var_dump($parameters); die();
        try {
            $em->persist($subscription);
            $this->em->flush();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * Set default parameters
     *
     * @param string $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'first_name',
                'last_name',
                'email',
                'study_city',
            ))
            ->setOptional(array(
                'phone_number',
                'zip_code',
                'city',
                'diploma_date',
                'university_level',
                'cursus',
            ))
        ;
    }
}
```


### Declare our event as a service

So, we have created our `PathEventAction`. Subsequently, we have to declare it, so it will be accessible from the `container`.
We have to go to our `services.yml` file :

```yml
    idci_step.path_event.action.save_data:
        class: IDCI\ContactBundle\Path\Event\Action\SaveDataPathEventAction
        arguments: [@doctrine.orm.entity_manager]
        tags:
            - { name: idci_step.path_event.action, alias: save_data }
```


### Use the service

IDCIStepBundle is based on the Symfony FormType's using to display a step.
Navigation's buttons are "submit" type's input to send datas of the current step.

So, `event` system is lean on events defined by the Framework to the FormType.

If you need more, you can read the Symfony doc : [Les Form Events](https://symfony.com/doc/2.8/form/events.html).

Here are two explanatories diagramms :

![StepBundle Form Events](/images/blog/stepBundle_FormEvent1.png "StepBundle Form Events")
![StepBundle Form Events](/images/blog/stepBundle_FormEvent2.png "StepBundle Form Events")

To use our new service and connect our event, we have to go to our `config.yml` file. We just have to add these few lines at the end of our `end` path :

```yaml
                ...
                    type: "end"
                    options:
                        source: "end"
                        next_options:
                            label: "end"
                        events:
                            form.post_bind:
                                -
                                    action: save_data
```


### Create the entity_manager

Finally, we have to create our entity manager, it is going to execute SQL requests.

// Principe d'encapsulation + doc ?
// Parler des champs

So, we are going to create a file named `subscription.php` :

```php
// src/AppBundle/Entity

<?php

namespace IDCI\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity
    * @ORM\Table(name="subscription")
    *
    */
class Subscription
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", name="last_name")
     */
    private $lastName;

    /**
     * @ORM\Column(type="decimal", scale=10, name="phone_number")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="zip_code")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @ORM\Column(type="date", name="diploma_date")
     */
    private $diplomaDate;

    /**
     * @ORM\Column(type="string", name="university_level")
     */
    private $universityLevel;

    /**
     * @ORM\Column(type="string", name="study_city")
     */
    private $studyCity;

    /**
     * @ORM\Column(type="string")
     */
    private $cursus;
```

Then, we have to generate getters and setters with this command's line :

$ php bin/console doctrine:generate:entities AppBundle/Entity/Subscription

If we watch at the end of our `subscription.php`file, we can see that getters and setters are automatically add themeselves :

// développer getter/setter

```php
<?php

namespace IDCI\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

...

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Subscription
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Subscription
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Subscription
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Subscription
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Subscription
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Subscription
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set diplomaDate
     *
     * @param \DateTime $diplomaDate
     *
     * @return Subscription
     */
    public function setDiplomaDate($diplomaDate)
    {
        $this->diplomaDate = $diplomaDate;

        return $this;
    }

    /**
     * Get diplomaDate
     *
     * @return \DateTime
     */
    public function getDiplomaDate()
    {
        return $this->diplomaDate;
    }

    /**
     * Set universityLevel
     *
     * @param string $universityLevel
     *
     * @return Subscription
     */
    public function setUniversityLevel($universityLevel)
    {
        $this->universityLevel = $universityLevel;

        return $this;
    }

    /**
     * Get universityLevel
     *
     * @return string
     */
    public function getUniversityLevel()
    {
        return $this->universityLevel;
    }

    /**
     * Set studyCity
     *
     * @param string $studyCity
     *
     * @return Subscription
     */
    public function setStudyCity($studyCity)
    {
        $this->studyCity = $studyCity;

        return $this;
    }

    /**
     * Get studyCity
     *
     * @return string
     */
    public function getStudyCity()
    {
        return $this->studyCity;
    }

    /**
     * Set cursus
     *
     * @param string $cursus
     *
     * @return Subscription
     */
    public function setCursus($cursus)
    {
        $this->cursus = $cursus;

        return $this;
    }

    /**
     * Get cursus
     *
     * @return string
     */
    public function getCursus()
    {
        return $this->cursus;
    }
}
```
Now, we have our `subscription` table with the mapping informations.
The, we have to create a related table in the database, here's the command :

$ php bin/console doctrine:schema:update --force

Thanks to our `persist` and `flush` method, we could register our data in the base.

Pour montrer que l'event marche, //screenshot de la fin du parcours

// Aller chercher des objets dans la base de données ?


## Conclusion


IDCIStepBundle offer a large possibilities's scope thanks to configuration and personnalisation. It is possible to do simple process, as we saw it with our contact form, but it is also possible to optimize it, by creating `PathEventAction` and configure themselves as you want.
We couls also, as we have seen it, create morecomplex process.

Then, IDCIStepBundle allows to do modifications directly in the configuration, without having to rewrite in the Controller, which allow more maniabily.

Don't hesitate to do your feedback.

If you need help or expertise to your Symfony projects, you could [contact us]({{ path('contact', {_locale: app.translator.locale}) }} "Contact us")
