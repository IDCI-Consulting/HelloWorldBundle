<?php

/**
 * Contact Form Type
 * User: brahim
 * Date: 27/05/15
 * Time: 13:52.
 */
namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Constraints;
use FabSchurt\Silex\Provider\Captcha\Form\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type;

class ContactType extends AbstractType
{
    private $name = 'contact';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $notBlankConstraint = new Constraints\NotBlank(array(
            'message' => 'Please complete this field.'
        ));

        $builder
            ->add('company', TextType::class, array())
            ->add('website', UrlType::class, array(
                'constraints' => array(
                    new Constraints\Url()
                )
            ))
            ->add('name', TextType::class, array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('firstname', TextType::class, array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    $notBlankConstraint,
                    new Constraints\Email(array(
                        'message' => 'The email is not valid.'
                    ))
                )
            ))
            ->add('phonenumber', NumberType::class, array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('project', TextareaType::class, array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('captcha', CaptchaType::class)
            ->add('validate', SubmitType::class, array(
                'label' => 'Send',
            ))
        ;
    }

    /**
     * Returns the name of ContactType.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of ContactType.
     *
     * @return string The name of this type
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
