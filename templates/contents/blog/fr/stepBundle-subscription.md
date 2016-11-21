# Comment créer un parcours complexe avec StepBundle ? #

## Introduction ##

Nous vous présentons aujourd'hui un deuxième article à propos d'une utilisation plus complexe de StepBundle.

Nous allons donc voir ensemble comment créer et mettre en place un processus d'inscription plus complexe. Vous pouvez vous référer à notre premier article (/*lien ici*/) pour ce qui concerne le vocabulaire, l'utilisation et l'installation de StepBundle.

Notre processus d'inscription sera composé de plusieurs steps et plusieurs paths.



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
     * @Route("/contact/", name="test")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
       
```

Ici, nous créeons notre map et mettons en place cinq steps.
Si vous regardez les premières lignes de plus près, vous trouverez l'URL de Google : nous avons ajouté des paramètres précisant la destination finale. A la fin de notre formulaire, l'utilisateur est donc redirigé vers la page d'accueil de Google.
Aussi, grâce au paramètre "choice", nous avons mis en place un menu déroulant.
Dans cet exemple, l'utilisateur choisit sa ville d'étude, Lyon ou Paris. En fonction de sa réponse, il sera redirigé vers les cursus d'une ville, ou de l'autre. Mais nous en rediscuterons en regardant les paths...

```php
    public function inscriptionAction(Request $request)
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
```

Voici maintenant les paths, ils sont au nombre de cinq.
Contrairement à notre premier exemple, nous utilisons aussi des paths à destinations conditionnelles.
Il vous faut aussi mettre une destination par défaut.

```php
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
Voici notre processus d'inscription avancé :

![Legende subscription form](demo_step/img/legend_subscription_form.png "Légende Subscription Form")


## Les events ##

Dans un deuxième temps, nous allons vous parler des fonctionnalités plus avancées du StepBundle : les events.
Ceux-ci vont vous permettre de personnaliser vos steps et vos paths.


