# Créer un parcours complexe avec IDCIStepBundle


## Introduction

Une fois de plus, nous retournons à notre IDCIStepBundle, cette fois-ci, nous allons vous présenter une utilisation plus complexe : la création d'un processus d'inscription.
Pour des renseignements concernant l'utilisation et l'installation de IDCIStepBundle, vous pouvez vous reporter à notre article d'introduction ici /*mettre lien vers article d'intro*/.


## Créer le processus d'inscription avec StepBundle

Nous avons donc choisi de créer un parcours composé de plusieurs steps et de plusieurs paths.

Pour cet exemple, nous avons choisi d'imaginer un extrait de processus d'inscription à l'université, en cela, le choix de l'utilisateur entre deux villes d'étude va changer la page de destination.

Une fois de plus, nous allons travailler dans le fichier `DefaultController.php`

Voici une illustration du rendu attendu.

![IDCIStepBundle Subscription URL](/images/blog/stepBundle_subscription_url.png "IDCIStepBundle Subscription URL")

Si vous regardez les premières lignes de plus près dans l'exemple ci-dessous, vous trouverez l'URL de notre site : nous avons ajouté des paramètres précisant la destination finale. A la fin de notre formulaire, l'utilisateur est donc redirigé vers la page d'accueil d'IDCI Consulting.
Aussi, grâce au paramètre "choice", nous avons mis en place un menu déroulant.
Dans cet exemple, l'utilisateur choisit sa ville d'étude, Lyon ou Paris. En fonction de sa réponse, il sera redirigé vers les cursus d'une ville, ou de l'autre. Mais nous en rediscuterons en regardant les paths...

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
                        'cursus_lyon' => '{{ flow_data.data.cursus.study_city == \'Lyon\' }}',
                        'cursus_paris' => '{{ flow_data.data.cursus.study_city == \'Paris\' }}'
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

Votre formulaire est prêt, il ne vous reste plus qu'à le tester.

Ouvrez votre projet Symfony dans votre navigateur, et rendez-vous sur l'URL `localhost:8000/subscription` afin d'apprécier votre nouveau parcours d'inscription.

Voici comment nous l'avons représenté avec notre légende :

![Légende IDCIStepBundle Subscription](/images/blog/stepBundle_subscription.png "Légende IDCIStepBundle Subscription")


### Particularité de IDCIStepBundle

Nous avons vu comment déclarer un configuration dans le Controller, cependant, IDCIStepBundle permet aussi de créer nos maps directement dans le fichier `config.yml` et non dans le fichier `DefaultController.php`.
Nous vous conseillons cette méthode car cela évite d'avoir besoin de réecrire le code, et cela est plus léger.

Voici notre même exemple dans notre fichier `config.yml`:

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
                            cursus_lyon: '{{flow_data.data.cursus.study_city == 'Lyon' }}'
                            cursus_paris: '{{flow_data.data.cursus.study_city == 'Paris' }}'
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


## Les event actions

Les `event actions` définissent les actions à éxécuter lors de notre navigation (steps, paths), nous en avons mis en place quelques uns qui sont présents par défaut avec IDCIStepBundle, mais il est possible d'en créer soi même.
Pour notre exemple, nous allons mettre en place un event action visant à sauvegarder en base les données de l'utilisateur à la fin de notre step (lors du clic sur le bouton `end`).
Il est possible de brancher des `event actions` sur des `paths` ou des `steps` à n'importe quel moment de votre parcours.

Pré-requis :
- Doctrine
- Entity Manager


### Créer un service pour notre event action de sauvegarde de données

Nous pouvons considérer un service comme une classe qui est rendue accessible partout dans notre application.
Dans un premier temps, il nous faut donc créer notre `PathEventAction` :

> Remarque : Pour la sauvegarde de nos données, nous avons utilisé la librairie Doctrine, nous vous renvoyons à la doc Symfony (.en) : [Doc Doctrine](http://symfony.com/doc/current/doctrine.html)._

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

La fonction `doExecute`, comme son nom l'indique, va permettre à l'action de s'éxécuter. C'est donc dans le corps de cette fonction que nous allons écrire le bout de code nous permettant de générer la sauvegarde de données.
`Persist` permet de dire que cette entité est maintenant gérée directement par Doctrine.
`Flush` permet à Doctrine d'effectuer les requêtes nécessaires pour sauvegarder les entités.

Pour plus de renseignements concernant l'utilisation de `flush` et `persist`, nous vous renvoyons à la doc d'Open Classrooms, cette fois-ci en français : [Doc `flush` et `persist`](https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/manipuler-ses-entites-avec-doctrine2)._

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

La fonction `setDefaultParameters` nous permet de définir nos paramètres. Dans notre cas, nous avons spécifié deux choses :
 - les paramètres obligatoires (`setRequired`)
 - les paramètres optionnels (`setOptional`)

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

Voici le rendu final de notre `PathEventAction` :

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


### Déclarer notre event en tant que service

Nous avons donc crée notre `PathEventAction`. Dans un deuxième temps, il faut le déclarer pour que celui-ci soit accessible depuis le `container`. Pour cela, nous nous rendons dans le fichier `services.yml` :

```yml
    idci_step.path_event.action.save_data:
        class: IDCI\ContactBundle\Path\Event\Action\SaveDataPathEventAction
        arguments: [@doctrine.orm.entity_manager]
        tags:
            - { name: idci_step.path_event.action, alias: save_data }
```


### Utiliser le service

IDCIStepBundle se base sur l'utilisation d'un FormType Symfony pour afficher une step. Les boutons de navigation étant les input de type "submit" pour envoyer les données de la step en cours.

Le système `d'event` s'appuie donc sur les événements définis par le Framework pour les FormType.

Nous vous renvoyons à la doc Symfony pour en savoir plus: [Les Form Events](https://symfony.com/doc/2.8/form/events.html).

Voici deux schémas explicatifs :

![StepBundle Form Events](/images/blog/stepBundle_FormEvent1.png "StepBundle Form Events")
![StepBundle Form Events](/images/blog/stepBundle_FormEvent2.png "StepBundle Form Events")

Pour utiliser notre nouveau service et ainsi brancher notre event, rendons nous dans notre fichier `config.yml`. Il nous suffit d'ajouter ces quelques lignes à la fin de notre path `end`:

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


### Créer l'entity_manager

Enfin, nous devons créer notre entity manager, celui-ci va effectuer les requêtes SQL.
// Principe d'encapsulation + doc ?
// Parler des champs
Nous allons donc créer un fichier `subscription.php` :

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

Ensuite, il nous faut générer les getters et les setters grâce à cette ligne de commande :

$ php bin/console doctrine:generate:entities AppBundle/Entity/Subscription

Si nous regardons à la fin de notre fichier `subscription.php`, nous voyons que les getters et les setters se sont ajoutés automatiquement à la fin de notre fichier :

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

Nous avons maintenant notre table `subscription` avec les informations de mapping
Ensuite, il nous faut créer une table correspondante dans la base de données, voici la commande :

$ php bin/console doctrine:schema:update --force


Grâce à notre méthode `persist` et notre `flush`, nous pouvons enregistrer nos données dans la base.

Pour montrer que l'event marche, //screenshot de la fin du parcours

// Aller chercher des objets dans la base de données ?


## Conclusion


IDCIStepBundle offre un large champ de possibilités grâce à la configuration et la personnalisation. Il est possible de mettre des parcours simples, comme nous l'avons vu avec notre formulaire de contact, mais il est aussi envisageable d'optimiser celui-ci, par exemple en créeant des `PathEventAction` et en les configurant selon vos souhaits. Nous pouvons aussi, comme nous venons de le voir, créer des parcours plus complexes.

Puis, IDCIStepBundle permet de faire des modifications directement dans la configuration, sans avoir besoin de rééecrire dans le Controller, ce qui permet plus de maniabilité.

N'hésitez pas à nous faire vos retours.

Si vous avez besoin d'aide ou d'une expertise, vous pouvez [nous contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").
