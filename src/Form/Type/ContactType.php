<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    private $name = 'contact';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $notBlankConstraint = new Constraints\NotBlank([
            'message' => 'Please complete this field.'
        ]);

        $builder
            ->add('company', TextType::class)
            ->add('website', UrlType::class, [
                'constraints' => [
                    new Constraints\Url()
                ]
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    $notBlankConstraint,
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    $notBlankConstraint,
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    $notBlankConstraint,
                    new Constraints\Email([
                        'message' => 'The email is not valid.'
                    ])
                ]
            ])
            ->add('phonenumber', NumberType::class, [
                'constraints' => [
                    $notBlankConstraint,
                ]
            ])
            ->add('project', TextareaType::class, [
                'constraints' => [
                    $notBlankConstraint,
                ]
            ])
            ->add('captcha', CaptchaType::class, [
                'label' => 'captcha',
                'width' => 200,
                'height' => 70,
                'length' => 6,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'submit_button'
                ]
            ])
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
