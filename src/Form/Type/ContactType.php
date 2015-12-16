<?php

/**
 * Contact Form Type
 * User: brahim
 * Date: 27/05/15
 * Time: 13:52.
 */
namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Constraints;

class ContactType extends AbstractType
{
    private $name = 'contact';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $notBlankConstraint = new Constraints\NotBlank(array(
            'message' => 'Please complete this field.'
        ));

        $builder
            ->add('company', 'text', array())
            ->add('website', 'url', array(
                'constraints' => array(
                    new Constraints\Url()
                )
            ))
            ->add('name', 'text', array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('firstname', 'text', array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('email', 'email', array(
                'constraints' => array(
                    $notBlankConstraint,
                    new Constraints\Email(array(
                        'message' => 'The email is not valid.'
                    ))
                )
            ))
            ->add('phonenumber', 'number', array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('project', 'textarea', array(
                'constraints' => array(
                    $notBlankConstraint,
                )
            ))
            ->add('validate', 'submit', array(
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
