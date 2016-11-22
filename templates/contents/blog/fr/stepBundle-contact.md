# Comment créer un formulaire de contact avec StepBundle ? #


## Introduction ##

Retour à notre StepBundle et son application effective : la création d'un formulaire de contact simple.
Pour des renseignements concernant l'utilisation et l'installation de StepBundle, vous pouvez vous reporter à notre article d'introduction ici /*mettre lien vers article d'intro*/.


## Créer le formulaire de contact avec StepBundle ##

Nous avons donc choisi de réaliser un parcours composé d'une seule step : un formulaire de contact, et un seul chemin représentant la soumission des données saisies. 
Nous pouvons voir ce cas comme l'utilisation du contact form 7 de Wordpress à la sauce Symfony.

Voici une illustration du rendu attendu.

![Screenshot Contact Form](/images/screenshot_contact_form.png "Screenshot Contact Form")

Dans la barre de recherche, nous avons notre ```localhost:8000``` suivi de la route que nous avons préalablement configuré : ```/contact```.
Ensuite, nous pouvoir voir le titre de notre formulaire de contact 'Personal informations', suivi des champs de saisies à compléter par l'utilisateur.
Comme notre formulaire ne se compose que d'une seule step et d'une seule path, notre bouton n'est pas 'next' mais bien 'end'.

Prêt à commencer avec StepBundle ? 

Nous allons travailler dans le fichier `DefaultController.php` du bundle crée par défaut `AppBundle`.

Commençons par créer une première action `contact` : 


```php
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
```

Notre map est maintenant prête, il faut maintenant créer le 'navigator' à partir de celle-ci : 

```php
        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator($request, $map)
        ;
```

Enfin, il faut définir les redirections à effectuer en fonction de la navigation réalisée par l'internaute. Trois cas sont possibles : 
    * Fin de navigation : lorsque l'on emprunte un chemin de type 'end'.
    * Navigation : lorsque l'on emprunte un chemin de type 'single' ou 'conditional'.
    * Le retour : lorsque l'on décide de retourner à une step précédente.


```php
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

Votre formulaire est prêt, il ne vous reste plus qu'à le tester.

Ouvrez votre projet Symfony dans votre navigateur, et rendez-vous sur l'URL `localhost:8000/contact` afin d'apprécier votre nouveau formulaire de contact.

![Legende simple form](demo_step/img/legend_simple_form.png "Légende Simple Form")


## Enregistrer les données ##


Finir le cas par un envoi de mail

Passer par les events.


## Conclusion ##


