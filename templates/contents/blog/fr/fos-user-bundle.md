
## FOSUserBundle gerer les utilisateurs avec Symfony2 

![Symfony2 logo](/images/blog/symfony2.png "Symfony2")

### Introduction ###
**Gérer les utilisateurs** inscription, connexion, droits d'accès, etc.. a toujours
été un plaisir avec **symfony** grâce aux plugins:

* sfGuardPlugin (propel)
* sfDoctrineGuardPlugin (doctrine)

Ce n'est pas bien plus dur avec **Symfony2** ! Cela est même simplifié au maximum,
bien que dépaysant au début. Le bundle **FOSUserBundle** répond aux mêmes problématiques
mais fluidifie les choses, les rend plus intuitives.

### FOSUserBundle : Installation ###

Il vous faut tout d'abord récupérer les sources du bundle et les placer dans le dossier vendor.
Pour cela, 2 solutions s'offrent à vous :

#### 1) Installation de FOSUSerBundle en clonant le dépôt git ####

    $ git clone https://github.com/FriendsOfSymfony/FOSUserBundle.git vendor/bundles/FOS/UserBundle

#### 2) Installation de FOSUSerBundle via les dépendances git ####

Ajoutez les lignes suivantes dans votre fichier `deps` :

[FOSUserBundle]
git=git://github.com/FriendsOfSymfony/FOSUserBundle.git
target=bundles/FOS/UserBundle

Puis mettez à jour les vendors :

    $ php bin/vendors install

Cette solution a l'avantage de permettre à n'importe qui travaillant avec vous sur
le projet de ne pas avoir à se soucier de quelles sont les vendors à installer et
où les trouver. Il lui suffira de mettre à jour les vendors.

Une fois **FOSUserBundle** ajouté aux vendors, il vous faut l'activer dans le Kernel:

    <!--?php <br ?--> // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
        );
    }
    
... Et ajouter le namespace qui va bien dans l'autoload:
    
    <!--?php <br ?--> // app/autoload.php
    
    $loader-&gt;registerNamespaces(array(
    // ...
        'FOS' =&gt; __DIR__.'/../vendor/bundles',
    ));

Si vous avez encore des doutes sur l'utilité des namespaces ou que vous êtes sceptiques
quant à son utilisation, je vous renvoie à ce très bon article de
[Pascal MARTIN sur le sujet](http://blog.pascal-martin.fr/post/php-5.3-namespace-1-espaces-de-noms).

### Créer votre entity User en surchargeant l'utilisateur de FOSUserBundle ###

Pour hériter d'un bundle avec Symfony2, il suffit de définir le bundle parent
dans votre classe :

    <!--?php <br ?--> // src/MyApp/UserBundle/MyAppUserBundle.php
    
    namespace MyApp\UserBundle;
    
    use Symfony\Component\HttpKernel\Bundle\Bundle;
    
    class MyAppUserBundle extends Bundle
    {
        public function getParent()
        {
            return 'FOSUserBundle';
        }
    }

Votre UserBundle hérite maintenant de toutes les vues, actions...du bundle FOSUserBundle.
Vous pouvez donc créer un nouvel utilisateur héritant de celui de base,
tous les fonctionnalités de FOSUserBundle s'appliqueront à cet utilisateur :

    <!--?php <br ?--> // src/MyApp/UserBundle/Entity/User.php
    
    namespace MyApp\UserBundle\Entity;
    
    use FOS\UserBundle\Entity\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
    * @ORM\Entity
    * @ORM\Table(name="fos_user")
    */
    class User extends BaseUser
    {
        /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
        protected $id;
    }

### Configuration de FOSUserBundle ###

Il vous faut maintenant configurer le bundle.
Pour cela, éditez votre fichier `config.yml`:

    # app/config/config.yml
    fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    # Permet de renseigner la nouvelle entity utilisateur
    user_class: MyApp\UserBundle\Entity\User

Votre bundle est désormais operationnel, vous pouvez mettre à jour votre base de données :

    $ php app/console doctrine:schema:update --force

A ce stade là, vous devez normalement avoir une table fos_user dans votre base de données, et un dossier
`vendor/bundles/FOS/UserBundle`
contenant la hierarchie habituelle d'un bundle Symfony2.

Générez ensuite les getters et setters de votre nouvelle entity :

    $ php app/console doctrine:generate:entities MyAppUserBundle:User

Vos entités sont operationnelles, il ne vous reste plus
qu'à gérer vos formulaires. Tout d'abord, le formulaire utilisateur :

    $ php app/console doctrine:generate:form MyAppUserBundle:User

Maintenant, en lançant la commande

    $ php app/console

Vous avez désormais toute une floppée d'actions possibles grâce au bundle **FOSUserBundle** :

    fos
    fos:user:activate Activate a user
    fos:user:change-password Change the password of a user.
    fos:user:create Create a user.
    fos:user:deactivate Deactivate a user
    fos:user:demote Demote a user by removing a role
    fos:user:promote Promotes a user by adding a role

Un coup d'oeil à la suite de cet article vous permettra de faire un tour d'horizon
rapide de ce bundle pour une utilisation optimale.

### Ajoutez des champs à votre entity User ###

Vous souhaitez également que l'utilisateur renseigne nom, prénom, date de naissance,
etc... lors de son inscription, que faire ?

Avec symfony 1.x, il fallait créer une nouvelle classe Profil qui était embarquée
dans le formulaire de création d'un utilisateur.

Avec symfony2, nul besoin d'utiliser une logique aussi complexe que les formulaires embarqués.
Il suffit de créer une nouvelle entity dans *votre* bundle qui héritera des attributs
et mécanismes inhérents à l'utilisateur **FOSUserBundle** mais possédant également
*vos* attributs. Pour fil conducteur nous allons ajouter ensemble un lieu d'habitat
pour l'utilisateur. Pour cela, créez un nouveau bundle que vous appelerez
`UserBundle` qui heritera de **FOSUserBundle**.

    <!--?php <br ?--> // src/MyApp/UserBundle/Entity/User.php
    
    namespace MyApp\UserBundle\Entity;
    
    use FOS\UserBundle\Entity\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
    * @ORM\Entity
    * @ORM\Table(name="fos_user")
    */
    class User extends BaseUser
    {
        /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
        protected $id;
        
        // Ajoutez vos attributs ici, un attribut *location* de type *text* pour notre exemple :
        /**
        * @ORM\Column(type="text")
        */
        protected $location;
        
    }
        
Il vous faut ensuite modifier le formulaire d'inscription. Pour cela il vous faut
étendre celui de base fournis par le bundle **FOSUserBundle** en ajoutant vos champs personnalisés.
Pour continuer sur notre exemple, voici comment ajouter notre fameux champ *location* :
        
    namespace MyApp\UserBundle\Form\Type;
    
    use Symfony\Component\Form\FormBuilder;
    use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
    
    class RegistrationFormType extends BaseType
    {
        public function buildForm(FormBuilder $builder, array $options)
        {
            parent::buildForm($builder, $options);
            
            // Ajoutez vos champs ici, revoilà notre champ *location* :
            $builder-&gt;add('location');
        }
        
        public function getName()
        {
            return 'myapp_user_registration';
        }
    }

Il vous faut maintenant préciser à votre fichier de config quel formulaire utiliser
pour l'inscription des utilisateurs :

    # app/config/config.yml
    fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    # Permet de renseigner la nouvelle entity utilisateur
    user_class: MyApp\UserBundle\Entity\User
    # Permet de renseigner le nouveau formulaire d'inscription
    registration:
    form:
    type: myapp_user_registration

Votre entity est désormais à jour. Vous pouvez désormais actualiser votre base de
données, votre table `fos_user` contiendra vos nouveaux champs :

    $ php app/console doctrine:schema:update --force

![Base de données](/images/blog/database.png)

### Routing, actions et templates utilisés par FOSUserBundle ###

Vous devez en tout premier lieu importer toutes les routes du bundle :

    # app/config/routing.yml
    fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"
    
    fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile
    
    fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register
    
    fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting
    
    fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
    
    Découvrons ensemble les routes que **FOSUserBundle** a généré pour nous en utilisant
    une commande Symfony2 listant les routes :
    
    $ php app/console router:debug
    
    Vous devriez voir apparaître (en plus de vos autres routes) les routes suivantes :
    
    fos_user_security_login ANY /login
    fos_user_security_check ANY /login_check
    fos_user_security_logout ANY /logout
    fos_user_profile_show GET /profile/
    fos_user_profile_edit ANY /profile/edit
    fos_user_registration_register ANY /register/
    fos_user_registration_check_email GET /register/check-email
    fos_user_registration_confirm GET /register/confirm/{token}
    fos_user_registration_confirmed GET /register/confirmed
    fos_user_resetting_request GET /resetting/request
    fos_user_resetting_send_email POST /resetting/send-email
    fos_user_resetting_check_email GET /resetting/check-email
    fos_user_resetting_reset GET|POST /resetting/reset/{token}
    fos_user_change_password GET|POST /change-password/change-password
    
    Vous avez également ces routes dans le routing interne à **FOSUserBundle**, où vous
    pouvez voir quelle est l'action cible de la route.
    Par exemple, pour la gestion des profils, jetons un coup d'oeil au fichier de routing :
    
    <!-- vendor/bundles/FOS/UserBundle/Resources/config/routing/profile.xml -->
    
    
    FOSUserBundle:Profile:show
    GET
    
    
    FOSUserBundle:Profile:edit
Comme vous pouvez le remarquer, les fichiers de routing fournis par **FOSUserBundle**
sont au format xml.

### Gérer les droits d'accès des utilisateurs avec Symfony2 ###

Tout est désormais fonctionnel, vous n'avez plus qu'à restreindre l'accès aux pages souhaitées.
Cela se passe dans le fichier de configuration `security.yml` :

    security:
    providers:
    fos_userbundle:
    id: fos_user.user_manager
    
    encoders:
    "FOS\UserBundle\Model\UserInterface": sha512
    
    firewalls:
    main:
    pattern: ^/
    form_login:
    provider: fos_userbundle
    csrf_provider: form.csrf_provider
    logout: true
    anonymous: true

    # C'est ici que tout se passe : qui a accès à quoi ?
    access_control:
    - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/admin/, role: ROLE_ADMIN }

    role_hierarchy:
    ROLE_ADMIN: ROLE_USER
    ROLE_SUPER_ADMIN: ROLE_ADMIN

La partie importante à bien comprendre est celle de l'**access_control** :
Toutes les routes prefixées par /admin/ necessiteront le rôle **ROLE_ADMIN**,

Il vous suffit de préfixer les routes à protéger par /admin/ et le mécanisme de sécurité se mettra en oeuvre.
Là ou il vous fallait faire un backend sécurisé et un frontend avec symfony 1.x,
ou encore user du fichier security.yml pour une gestion plus fine des accès.
Vous pouvez désormais gérer au cas par cas les espaces sécurisés simplement grâce
à leurs routes !

### Récupérer et utiliser l'utilisateur dans une action avec Symfony2 ###

L'utilisateur est stocké en session, dans le contexte de l'application. Mais récupérer
celui-ci sous forme d'objet dans une action peut vite devenir un casse-tête,
bien que cela soit utile dans de nombreuses situations.
Voici une bonne façon de faire :

`$user = $this-&gt;container-&gt;get('security.context')-&gt;getToken()-&gt;getUser()`

Vous pouvez ensuite manipuler cet objet comme bon vous semble et récupérer vos attributs nouvellement créés :

`$user-&gt;getlocation()`

### Gérer l'utilisateur et ses droits d'accès dans un template twig avec Symfony2 ###

Comment tester si l'utilisateur possède les bons droits d'accès dans un template avec Symfony2?
Un jeu d'enfant avec twig ! En effet, twig dispose d'une kyrielle de fonctions de base :



### Utilisez vos propres templates pour décorer FOSUserBundle ###

Pour utiliser vos templates, il va falloir surcharger les templates existant du bundle
**FOSUserBundle** en respectant la hierarchie imposée par celui-ci.
Pour notre exemple, nous allons modifier le template d'inscription d'un nouvel utilisateur.
Pour respecter la hierarchie du bundle, créez un template `register.html.twig`
dans un nouveau dossier `Registration` de votre UserBundle :

    {% verbatim %}
    #src/MyApp/UserBundle/Resources/views/Registration/register.html.twig
    {% extends "::base.html.twig" %}
    
    {% block content %}
        <form class="fos_user_registration_register" action="{{ path('fos_user_registration_register') }}" method="POST">{{ form_widget(form) }}
            <div><input type="submit" value="{{ 'registration.submit'|trans({}, 'FOSUserBundle') }}" /></div>
        </form>
    {% endblock %}
    {% endverbatim %}

Ne pas oublier de vider le cache :

    $ php app/console cache:clear

Le template d'inscription sera dorénavant décoré par le template de base.
Ce mécanisme vous permet d'utiliser une des grandes forces de
[Twig](http://twig.sensiolabs.org/): l'héritage de templates !

### Envoyez un email de confirmation pour la création d'un utilisateur ###

Vous rêviez de pouvoir valider automatiquement vos utilisateurs en leur envoyant
un email de confirmation avec un lien à cliquer pour valider ladite inscription ?
FOSUSerBundle l'a fait !

En effet, il existe une fonctionnaltié toute prête permettant cet envoi d'email et validation
de compte au-to-ma-tique. Cependant, celle-ci est désactivée par défaut.
Pour l'activer,rien de plus simple :

    # app/config/config.yml
    fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    
    # Permet de renseigner la nouvelle entity utilisateur
    user_class: MyApp\UserBundle\Entity\User
    
    # Permet de définir quel service de mail utiliser
    # On utilise twig_swift pour pouvoir envoyer un email en HTML
    service:
    mailer: fos_user.mailer.twig_swift
    # Permet de renseigner le nouveau formulaire d'inscription
    registration:
    form:
    type: myapp_user_registration
    # Permet la validation automatique du compte par envoi d'un email
    confirmation:
    enabled: true
    from_email:
    # Adresse de l'expediteur
    address: noreply@monsiteweb.com
    # Nom de l'expediteur
    sender_name: Admin de monsiteweb.com
    # Permet de définir le template de l'email à envoyer (en html)
    email:
    template: MyAppMonBundle:User:registration.email.twig

Il ne vous reste plus ensuite qu'à définir le template que vous souhaitez utiliser pour l'envoi d'email :

    {% verbatim %}
    {# src/MyApp/MonBundle/Resources/views/User/registration.email.twig #}
    
    {% block subject %}Confirmation d'inscription{% endblock %}
    
    {% block body_text %}
    {% verbatim false %}
    Bonjour {{ user.username }} !
    
    Vous devez confirmé voter inscription [....] en cliquant sur le lien suivant : {{ confirmationUrl }}
    
    Amicalement,
    Administrateur de monsiteweb.com
    {% endblock %}
    
    {% block body_html %}
        {#
            Vous pouvez ici définir le HTML que vous souhaitez ou même utiliser la force de twig, à savoir l'inclusion de template :
            include 'MyAppMonBundle:User:registration_email.html.twig'
        #}
    {% endblock %}
    {% endverbatim %}

### Utiliser la ligne de commande pour gérer vos utilisateurs avec FOSUserBundle ###

Le bundle FOSUSerBundle vous propose toute une kyrielle de commandes permettant de gérer vos utilisateurs :

#### 1) Création &amp; activation ####

    $ php app/console fos:user:create monutilisateur test@example.com motdepasse
    $ php app/console fos:user:activate monutilisateur
    $ php app/console fos:user:deactivate monutilisateur

#### 2) Gestion des rôles ####

    $ php app/console fos:user:promote monutilisateur ROLE_ADMIN
    $ php app/console fos:user:demote testuser ROLE_ADMIN

#### 3) Changement de mot de passe ####

    $ php app/console fos:user:change-password monutilisateur nouveaumotdepasse

### Erreurs courantes avec FOSUserBundle ###

#### 1) Problèmes de configuration de FOSUserBundle ####

**Problème :**

The child node "user_class" at path "fos_user" must be configured.
The child node "db_driver" at path "fos_user" must be configured.
The child node "firewall_name" at path "fos_user" must be configured.

**Solution :** Vous n'avez pas configuré l'un des éléments
_necessaire_ au bon fonctionnement de FOSUserBundle, à savoir :

fos_user:
db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
firewall_name: main
user_class: MyApp\UserBundle\Entity\User

**Problème :**

ErrorException: Warning: class_parents(): Class MyApp\Entity\User does not exist and could not be loaded in */vendor/doctrine/lib/Doctrine/ORM/Mapping/ClassMetadataFactory.php line 223

**Solution :** Vous avez mal configuré votre user_class
dans `app/config/config.yml`. Vérifiez que votre namespace
est bon, que votre classe se situe bien là ou vous l'avez définie.

#### 2) Problèmes de surcharge de l'entity User de FOSUserBundle ####

**Problème :** Vous n'arrivez pas à surcharger le formulaire d'édition
de profil de FOSUserBundle, rien à y faire vous avez cette erreur :

Neither property "username" nor method "getUsername()" nor method "isUsername()" exists in class "FOS\UserBundle\Form\Model\CheckPassword"

**Solution :** La surcharge du formulaire d'édition de profil de
FOSUserBundle est un peu différente de celle du formulaire d'inscription. En effet,
la fonction à surcharger dans le cas du formulaire d'inscription est buildForm(), or
pour l'édition de profil, c'est la fonction *buildUserForm()* qu'il faut surcharger !

    <!--?php <br ?--> namespace MyApp\UserBundle\Form\Type;
    
    use Symfony\Component\Form\FormBuilder;
    use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
    
    class ProfileFormType extends BaseType
    {
        public function buildUserForm(FormBuilder $builder, array $options)
        {
            parent::buildUserForm($builder, $options);
            // On ajoute nos champs :
            $builder-&gt;add('location');
        }
    
        public function getName()
        {
            return 'myapp_user_profile';
        }
    }

### Conclusion ###

**Symfony2** est amélioré par rapport à son predecesseur vieillissant. Là où **symfony**
proposait un plugin agreable d'utilisation mais rendant les choses vite complexe
pour une utilisation avancée, Symfony2 propose une approche plus pragmatique du problème :
Faites ce que vous voulez, comme vous le voulez et où vous le voulez en héritant du bundle de base.
Ce framework ainsi que ses bundles sont des outils de grande qualité !
N'hésitez pas à nous faire vos retours.

Si vous souhaitez une formation sur les frameworks **symfony** ou **Symfony2**
[contactez-nous](http://www.idci-consulting.fr/contact).