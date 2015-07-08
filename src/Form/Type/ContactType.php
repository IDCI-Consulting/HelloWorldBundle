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
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'company',
                'text',
                array(
                    'required' => false,
                )
            )
            ->add(
                'website',
                'url',
                array(
                    'required' => false,
                )
            )
            ->add(
                'name',
                'text',
                array()
            )
            ->add(
                'firstname',
                'text',
                array()
            )
            ->add(
                'email',
                'email',
                array(
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'phonenumber',
                'number',
                array()
            )
            ->add(
                'project',
                'textarea',
                array()
            );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'contact';
    }
}
