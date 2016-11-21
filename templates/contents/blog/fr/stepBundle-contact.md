# Comment créer un formulaire de contact avec StepBundle ? #


## Introduction ##


Nous avons crée StepBundle pour répondre à un besoin client.
Il nous fallait pouvoir réaliser facilement des parcours interactifs avec un internaute et déclencher facilement différents évenements (envoi de mail, sauvegarde en base de données, appel d'un web service) en fonction des actions utilisateurs (passage d'une étape à une autre). L'évolutivité de la complexité des parcours étant un point important, l'ensemble du workflow se devait d'être configurable (et non pas hardcodé).

Nous avons testé d'autres bundles existants, qui semblaient répondre à notre problématique client, mais ceux-ci ne nous pas convaincu.
Dans un autre articile, nous vous présenterons un comparatif entre ces différents bundles et StepBundle.

Ainsi, nous avons choisi de développer notre propre Bundle. En cela, nous avons pensé ce bundle générique et réutilisable.

Cet article a pour vocation de vous le présenter au travers de deux exemples concrets.

Le StepBundle permet de simplifier la création d'un parcours interactif à destination d'un internaute.Sa configuration se définie par un système de map (carte), de step (étape) et de path (chemin).
Nous avons utilisé la métaphore de la navigation tout au long de notre développement.

Dans un premier temps, vous devez définir une map, et imaginer chaque écran (page web) comme une step, puis les lier entre elles en utilisant les paths qui ajouteront des boutons de navigation.

### Qu'est-ce qu'une map ? ###

Une map définit le workflow de navigation, elle se compose de steps et de paths.

### Qu'est-ce qu'une step ? ###

Une step se matérialise par une page web, et définit un potentiel point de passage. Elle peut être de différents types. Par défaut, notre bundle fournit deux types :
    * html : affichage d'un contenu HTML.
    * form : affichage d'un formulaire (FormType Symfony).

### Qu'est-ce qu'un path ? ###

Un path est un itinéraire qui a pour origine une step et pour destination zéro ou plusieurs steps. Il peut être de différents types. Par défaut, notre bundle fournit trois types : 
    * le single : une seule destination
    * le conditionnel : plusieurs destinations en fonction de règles conditionnelles.
    * le end : le chemin qui détermine une fin de navigation.


## Légende ##

Nous allons utiliser un diagramme qui fera office de légende.

* Les carrés représentent les steps.
* Les flèches représentent les paths.

![Legende StepBundle](demo_step/img/legendStepBundle.png "Légende StepBundle")

## Pourquoi utiliser StepBundle ? ##

StepBundle permet de mettre en place aussi bien un parcours simple (une étape, un chemin) qu'un parcours plus complexes (plusieurs étapes et chemins). Vous pouvez commencer facilement à partir d'une configuration, qui représente la map, en utilisant le langage `yml` ou `json`.
Vous pouvez aussi représenter une map en utilisant directement la programmation objet, mais cela est peut-être plus compliqués pour des non initiés au code et à la programmation objet.

Voici une liste non exhaustive des cas d'utilisation de StepBundle : un formulaire de contact, un processus d'inscription, un questionnaire, une enquête, etc.

Sans plus attendre, rentrons dans le vif du sujet et partons à la rencontre de ce Bundle au travers de deux exemples plus concrets.

Commençons par créer un projet Symfony en version 2.8, nous appelerons ce projet 'demo_step'.

```sh
$ symfony new demo_step 2.8
```


## Installation ##

Déclarons les dépendances avec notre bundle, en modifiant le fichier `composer.json` :

```json
"require": {
    ...
    "idci/step-bundle": "dev-master"
},
```

Ou en éxécutant la commande composer suivante :

TODO: composer install idci/step-bundle dev-master
// attention à la version

Puis, installons ces nouvelles dépendances en utilisant composer :

```sh
$ php composer.phar update
```

Enfin, enregistrons le bundle dans votre fichier `AppKernel.php` :

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\StepBundle\IDCIStepBundle(),
    );
}
```

Notre bundle est installé et prêt à l'emploi, nous allons maintenant tester un cas d'utilisation : un cas simple, le formulaire de contact.

## Créer le formulaire de contact avec StepBundle ##

Nous avons donc choisi de réaliser un parcours composé d'une seule étape : un formulaire de contact, et un seul chemin représentant la soumission des données saisies. 
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
Celle-ci se compose donc d'une seule étape (step) qui affichera un formulaire afin d'y saisir des données. Comme nous l'avons vu plus haut, il existe de base deux types d'étapes : 'html' et 'form'. Dans notre cas, le type 'form' sera celui que nous allons utiliser. Grâce à celui-ci, nous allons pouvoir définir l'ensemble des champs de saisie que nous voulons afficher à l'internaute.
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
    * Le retour : lorsque l'on décide de retourner à une étape précédente.


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

Notre formulaire est prêt, il ne vous reste plus qu'à le tester.

Ouvrez dans votre navigateur votre projet Symfony et rendez-vous sur l'URL `/contact` afin d'apprécier votre nouveau formulaire de contact.

![Legende simple form](demo_step/img/legend_simple_form.png "Légende Simple Form")


## Enregistrer les données ##



// Finir le cas : sauvegarder les données et/ou envoi de mail (c'est mieux). que se passe il quand on valide le formulaire ?

Passer par les events.


## Conclusion ##


