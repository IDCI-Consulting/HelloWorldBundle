# How to create a new type Doctrine2 with Symfony2 #

![Symfony2 logo](/build/images/symfony2.png "Symfony2")

## Introduction ## 

If you have encounter the problematic of the medias's storage in database when you were working on a Symfony2 project, you necessarily ask yourself : *How to create a new datas's type Doctrine2 with Symfony2 ?*
Indeed, not a lot of base's types exists with Doctrine2, because of genericities's issues :

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

In this article, we will create a new Media entity which will store in database with type Blob.

## Create a new data's type Doctrine2 to Symfony2 : the Blog type ##

You have two solutions to create your new type :

* Create a new file in your bundle and implement your new type on it:
`src/MyApp/MyBundle/ORM`

* Implemente your new type directly in the dedicate vendo:
`vendor/doctrine-dbal/lib/Doctrine/DBAL/Types`

Knowing that the second method has the advantage of making your type able to be used by every bundles, but the inconvenient is that it not supply a *all-inclusive bundle*, i.e a bundle including types's declaration its need.

For our example, we will favour the first method:
`src/MyApp/MyBundle/ORM`

In this file, you will be able to create your new datas's types.

Create a BlobType class which take over the AbstractPlatform class.
You necessarily have to declare these functions ;

* public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
* public function convertToDatabaseValue($value, AbstractPlatform $platform)
* public function convertToPHPValue($value, AbstractPlatform $platform)

These functions allow you to define a behaviour to your fields.
For example, you could need to encrypt a password before registre it in data base.

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

## Add the new type Doctrine2 to your bundle Symfony2 ##

Once you type is created, you have to indicate to Doctrine2 that it is able to use.
So, you have to registre it through the function registerDoctrineTypeMapping(). 
It allows to charge your new data's type in the boot() function of your bootstrap's bundle:

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

Your new type is now configure et ready to run in your entities.

## Use the new type Doctrine2 in an entity of your bundle Symfony2 ##

To finish our addition example of a Media entity remained at Blob format in database to your project, here's what it looks like :


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

By uptading your data base with the command:

    $ php app/console doctrine:schema:update --force

So, you should be able to have a Media table having your new field Blob.

If you need help or expertise to your Symfony projects, you could [contact us]({{ path('website_contact') }} "Contact us")

