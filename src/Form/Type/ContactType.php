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

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'company',
                'text',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    ),
                    'required' => false,
                )
            )
            ->add(
                'website',
                'url',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    ),
                    'required' => false,
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    )
                )
            )
            ->add(
                'firstname',
                'text',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    )
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    )
                )
            )
            ->add(
                'phonenumber',
                'number',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    )
                )
            )
            ->add(
                'project',
                'textarea',
                array(
                    'attr' => array(
                        'class' => 'input__field input__field--hoshi',
                    )
                )
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
