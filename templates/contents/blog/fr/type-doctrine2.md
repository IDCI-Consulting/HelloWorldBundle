
# Comment créer un nouveau type Doctrine2 avec Symfony2 #
![Symfony2 logo](/images/blog/symfony2.png "Symfony2")

## Introduction ## 

Si vous avez rencontré à un moment donné de votre projet symfony2 la problématique
du stockage de médias en base de données vous vous êtes forcément posé la question
suivante : *Comment créer un nouveau type de données doctrine2 avec symfony2 ?*
En effet, il n'existe que peu de types de base avec Doctrine2, pour des soucis de généricité :

* string
* integer
* smallint
* bigint
* boolean
* decimal
* date
* time
* datetime
* text
* object
* array
* float

Comme trame de cet article, nous allons tenter de créer une nouvelle entité Media
qui sera stockée en base de données en type blob.

## Créer un nouveau type de données doctrine2 à symfony2 : le type Blob ## 

Deux solutions s'offrent à vous pour créer votre nouveau type :

* Créer un nouveau dossier dans votre bundle dans lequel vous implementerez votre nouveau type :
`src/MyApp/MyBundle/ORM`

* Implémenter votre nouveau type directement dans le vendor dédié :
`vendor/doctrine-dbal/lib/Doctrine/DBAL/Types`

Sachant que la seconde méthode aura pour avantage de rendre votre type utilisable
par tous les bundles mais l'inconvénient de ne pas fournir un *bundle clé en main*,
c'est à dire un bundle comprenant la déclaration des types dont il a besoin.

Pour notre exemple, nous allons privilégier la première méthode :
`src/MyApp/MyBundle/ORM`

C'est dans ce dossier que vous allez pouvoir créer vos nouveaux types de données.
Pour notre exemple, réalisez une classe BlobType qui
héritera de la classe AbstractPlatform.
Vous devrez déclarer obligatoirement les fonctions suivantes :

* public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
* public function convertToDatabaseValue($value, AbstractPlatform $platform)
* public function convertToPHPValue($value, AbstractPlatform $platform)


Ces fonctions vous permettent de définir un comportement pour vos champs.
Vous pouvez par exemple avoir besoin de crypter un mot de passe avant de l'enregistrer
en base de données.

    namespace MyApp\MyBundle\ORM;
    
    use Doctrine\DBAL\Platforms\AbstractPlatform;
    use Doctrine\DBAL\Types\Type;
    
    /**
    * Type that maps an Blog SQL to php objects
    */
    class BlobType extends Type
    {
        const BLOB = 'blob';
        
        public function getName()
        {
            return self::BLOB;
        }
        
        public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
        {
            return $platform->getDoctrineTypeMapping('blob');
        }
        
        public function convertToDatabaseValue($value, AbstractPlatform $platform)
        {
            return ($value === null) ? null : base64_encode($value);
        }
        
        public function convertToPHPValue($value, AbstractPlatform $platform)
        {
            return ($value === null) ? null : base64_decode($value);
        }
    }

## Ajouter le nouveau type Doctrine2 à notre bundle Symfony2 ## 

Une fois notre type créé, il faut indiquer à Doctrine2 que celui-ci est disponible
à l'utilisation. Pour cela, il nous faut enregistrer celui-ci grace à la fonction
registerDoctrineTypeMapping(). Celle-ci permet de charger
notre nouveau type de données dans la fonction boot()
du bootstrap de notre bundle :

    // src/MyApp/MyBundle/MyAppMyBundle.php
    namespace MyApp\MyBundle;
    
    use Symfony\Component\HttpKernel\Bundle\Bundle;
    use Doctrine\DBAL\Types\Type;
    
    class StoreAdBundle extends Bundle
    {
        public function boot()
        {
            $em = $this->container->get('doctrine.orm.entity_manager');
            
            // Ajout des nouveaux types à notre entity manager
            Type::addType('blob', 'MyApp\MyBundle\ORM\BlobType');
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('blob', 'blob');
        }
    }

Votre nouveau type est désormais configuré et prêt à l'emploi dans vos entités.

## Utiliser le nouveau type Doctrine2 dans une entity de notre bundle Symfony2 ## 

Pour terminer notre exemple d'ajout d'une entité Media persistée au format blob
en base de données à notre projet, voici ce à quoi pourrait ressembler celle-ci :

    // src/MyApp/MyBundle/Entity/Media.php
    namespace MyApp\MyBundle\Entity;
    
    use Gedmo\Mapping\Annotation as Gedmo;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
    * @ORM\Table(name="media")
    * @ORM\Entity()
    */
    class Media
    {
        /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
        protected $id;
        
        /**
        * @ORM\Column(type="string", length=100)
        */
        protected $name;
        
        // Utilisation de notre nouveau champs blob
        /**
        * @ORM\Column(type="blob")
        */
        protected $file;
        
        /**
        * @ORM\Column(type="string", length=100)
        */
        protected $mime_type;
        
        /**
        * @ORM\Column(type="integer", length=100)
        */
        protected $size;
        
        /**
        * @ORM\Column(type="string", length=5)
        */
        protected $extension;
        
        // getters
        // setters
        // ...
    }

En mettant à jour votre base de données via la commande

    $ php app/console doctrine:schema:update --force

Vous devriez désormais avoir une table media possédant votre nouveau champ blob.

Si vous avez besoin d'une aide ou d'une expertise pour vos projets symfony vous pouvez
[nous contacter]({{ path('contact', {_locale: app.translator.locale}) }} "Contactez-nous").