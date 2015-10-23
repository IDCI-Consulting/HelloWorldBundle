
# Installer les Behaviors Doctrine2 sous Symfony2 #
![Symfony2 logo](/images/blog/symfony2.png "Symfony2")


Voici un exemple sur comment implémenter les **Behaviors** via l'**EventListener** de **Doctrine2** dans **Symfony2**.
Dans cet exemple, nous allons mettre en place le **Behavior Timestampable** sur un objet **Entity** du model.

## Récupérer les extensions de Doctrine2 ## 
Placez vous dans le dossier /vendor de votre projet Symfony

    $ cd vendor

Cloner le repository git des extensions doctrine

    $ git clone https://github.com/l3pp4rd/DoctrineExtensions.git doctrine-extensions

Vous devez avoir maintenant le dossier suivant /vendor/doctrine-extensions.

## Paramétrer l'autoloader pour charger les extensions Doctrine2 ## 

Editer le fichier /app/autoload.php, et ajouter les lignes suivantes

    use Symfony\Component\ClassLoader\UniversalClassLoader;
    use Doctrine\Common\Annotations\AnnotationRegistry;
    
    $loader = new UniversalClassLoader();
    $loader->registerNamespaces(array(
        ...
        'Gedmo' => __DIR__.'/../vendor/doctrine-extensions/lib',
        ...
    ));
    ...
    $loader->register();

## Utiliser les annotations pour définir les champs Timestampable dans votre Entity ## 

Dans l'exemple suivant, nous allons ajouter les champs created_at et updated_at à l'objet Product.

    namespace ...\Entity;
    
    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Mapping\Annotation as Gedmo;
    ...
    
    class Product
    {
        ...
        
        /**
        * @var datetime $created_at
        *
        * @ORM\Column(name="created_at", type="datetime")
        * @Gedmo\Timestampable(on="create")
        */
        protected $created_at;
        
        /**
        * @var datetime $updated_at
        *
        * @ORM\Column(name="updated_at", type="datetime")
        * @Gedmo\Timestampable(on="update")
        */
        protected $updated_at;
        
        ...
    }

## Ajouter le listener ## 

Il faut maintenant ajouter le **listener** pour effectuer la mise à jour des champs created_at lors de la création,
et updated_at lors de la mise à jour, et ceci automatiquement lorsque l'Entity est persisté.

Editer votre class Bundle et ajouter ou mettre à jour la fonction boot()

    public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $evm = $em->getEventManager();
        // Timestampable
        $evm->addEventSubscriber(new \Gedmo\Timestampable\TimestampableListener());
    }

Voici des liens pour plus d'information sur les extensions de Doctrine2 :

[http://symfony.com/doc/2.0/cookbook/doctrine/common_extensions.html](http://symfony.com/doc/2.0/cookbook/doctrine/common_extensions.html)  
[http://www.doctrine-project.org/blog/doctrine2-behavioral-extensions](http://www.doctrine-project.org/blog/doctrine2-behavioral-extensions)

Ici vous trouverez un lien vers un Bundle pour les Doctrine Extensions:

[https://github.com/stof/StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle)