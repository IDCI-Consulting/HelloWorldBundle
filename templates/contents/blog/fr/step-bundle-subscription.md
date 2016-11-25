# Comment créer un parcours complexe avec StepBundle ? #

## Introduction ##

Une fois de plus, nous retournons à notre StepBundle, cette fois-ci, nous allons vous présenter une utilisation plus complexe : la création d'un processus d'inscription.
Pour des renseignements concernant l'utilisation et l'installation de StepBundle, vous pouvez vous reporter à notre article d'introduction ici /*mettre lien vers article d'intro*/.


## Créer le processus d'inscription avec StepBundle ##

Nous avons donc choisi de créer un parcours composé de plusieurs steps et de plusieurs paths.

Pour cet exemple, nous avons choisi d'imaginer un extrait de processus d'inscription à l'université, en cela, le choix de l'utilisateur entre deux villes d'étude va changer la page de destination.


Une fois de plus, nous allons travailler dans le fichier `DefaultController.php`

```php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/subscription/", name="subscription")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
       
```

Ici, nous créeons notre map et mettons en place cinq steps.

/*Mettre illustration*/

Si vous regardez les premières lignes de plus près, vous trouverez l'URL de Google : nous avons ajouté des paramètres précisant la destination finale. A la fin de notre formulaire, l'utilisateur est donc redirigé vers la page d'accueil de Google.
Aussi, grâce au paramètre "choice", nous avons mis en place un menu déroulant.
Dans cet exemple, l'utilisateur choisit sa ville d'étude, Lyon ou Paris. En fonction de sa réponse, il sera redirigé vers les cursus d'une ville, ou de l'autre. Mais nous en rediscuterons en regardant les paths...

```php
    public function subscriptionAction(Request $request)
    {   
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('test map', array(), array (
                'final_destination' => 'http://www.google.fr',
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
Votre formulaire est prêt, il ne vous reste plus qu'à le tester.

Ouvrez votre projet Symfony dans votre navigateur, et rendez-vous sur l'URL `localhost:8000/suscription` afin d'apprécier votre nouveau formulaire de contact.

Voici comment nous l'avons représenté avec notre légende :

![Legende simple form](/images/subscription_legend.pdf "Légende Simple Form")


### Particularité de StepBundle ###

Nous avons vu comment déclarer un configuration dans le Controller, cependant, StepBundle permet aussi de créer nos maps directement dans le fichier `config.yml` et non dans le fichier `DefaultController.php`. 
Nous vous conseillons cette méthode car cela évite d'avoir besoin de réecrire le code, et cela est plus léger.

Voici notre même exemple dans notre fichier `config.yml`: 

```yaml
 subscription:
            name: "subscription"
            steps:
                personal:
                    type: "form"
                    options:
                        title: "Personal informations"
                        description: "The personal data step"
                        @builder:
                            worker: "form_builder"
                            parameters:
                                fields:
                                    -
                                        name: "first_name"
                                        type: "text"
                                    -
                                        name: "last_name"
                                        type: "text"
                                    -
                                        name: "phone_number"
                                        type: "text"
                                    -
                                        name: "email"
                                        type: "text"
                                    -
                                        name: "zip_code"
                                        type: "text"
                                    -
                                        name: "city"
                                        type: "text"
                cursus:
                    type: "form"
                    options:
                        title: "Your course"
                        description: "Course and studying city"
                        @builder:
                            worker: "form_builder"
                            parameters:
                                fields:
                                    -
                                        name: 'diploma_date'
                                        type: 'IDCI\ContactBundle\Form\Type\DiplomaDateFormType'
                                    -
                                        name: 'university_level'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: "What is your university level ?"
                                            choices:
                                                bac1: "Bac+1"
                                                bac2: "Bac+2"
                                                bac3: "Bac+3"
                                                bac4: "Bac+4"
                                    -
                                        name: 'study_city'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: "Where do you want to study ?"
                                            choices:
                                                lyon: "Lyon"
                                                paris: "Paris"
                cursus_lyon:
                    type: "form"
                    options:
                        title: "Welcome to Lyon, we hope you like quenelle of pike !"
                        description: "The differents courses in Lyon"
                        @builder:
                            worker: "form_builder"
                            parameters:
                                fields:
                                    -
                                        name: "cursus"
                                        type: "Symfony\Component\Form\Extension\Core\Type\ChoiceType"
                                        options:
                                            choices:
                                                bts_comm: "HDN Communication"
                                                bts_mktg: "HDN Marketing"
                                                bachelor_comm: "Bachelor Communication"
                                                bachelor_mktg: "Bachelor Marketing"
                                                bachelor_digital: "Bachelor Digital"
                                                master_comm: "Master degree Communication"
                                                master_mktg: "Master degree Marketing"
                                                master_digital: "Master degree Digital"
                cursus_paris:
                    type: "form"
                    options:
                        title: "Welcome to Paris, we hope you like the subway !"
                        description: "The differents courses in Paris"
                        @builder:
                            worker: "form_builder"
                            parameters:
                                fields:
                                    -
                                        name: "cursus"
                                        type: "Symfony\Component\Form\Extension\Core\Type\ChoiceType"
                                        options:
                                            choices:
                                                bts_comm: "HDN Communication"
                                                bts_mktg: "HDN Marketing"
                                                bts_business: "HDN Business"
                                                bachelor_comm: "Bachelor Communication"
                                                bachelor_mktg: "Bachelor Marketing"
                                                bachelor_business: "Bachelor Business"
                                                master_comm: "Master degree Communication"
                                                master_mktg: "Master degree Marketing"
                                                master_business: "Master degree Digital"
                end:
                    type: "html"
                    options:
                        title: "Inscription online"
                        description: "Inscription done"
                        content: "Thank you ! Please, join the proof of entitlement. Inscriptions.school@school.com"
            paths:
                -
                    type: "single"
                    options:
                        source: "personal"
                        destination: "cursus"
                        next_options:
                            label: "next"
                -
                    type: "conditional_destination"
                    options:
                        source: "cursus"
                        destinations:
                            cursus_lyon: "{{flow_data.data.cursus.study_city == 'lyon' }}"
                            cursus_paris: "{{flow_data.data.cursus.study_city == 'paris' }}"
                        default_destination: "cursus_paris"
                        next_options:
                            label: "next"
                -
                    type: "single"
                    options:
                        source: "cursus_lyon"
                        destination: "end"
                        next_options:
                            label: "next"
                -
                    type: "single"
                    options:
                        source: "cursus_paris"
                        destination: "end"
                        next_options:
                            label: "next"
                -
                    type: "end"
                    options:
                        source: "end"
                        next_options:
                            label: "end"
```

Dans le `DefaultController.php` :

```php
<?php

namespace IDCI\ContactBundle\Controller;

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

## Conclusion ##

StepBundle offre un large champ de possibilités grâce à la configuration et la personnalisation. Il est possible de mettre des parcours simples, comme nous l'avons vu avec notre formulaire de contact, mais il est aussi envisageable d'optimiser celui-ci, par exemple en créeant des `PathEventAction` et en les configurant selon vos souhaits. Nous pouvons aussi, comme nous venons de le voir, créer des parcours plus complexes.

Puis, StepBundle permet de faire des modifications directement dans la configuration, sans avoir besoin de rééecrire dans le Controller, ce qui permet plus de maniabilité.

N'hésitez pas à nous faire vos retours.

Si vous avez besoin d'aide ou d'une expertise, vous pouvez [nous contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").




