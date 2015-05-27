<?php
/**
 * Created by PhpStorm.
 * User: brahim
 * Date: 27/05/15
 * Time: 13:52
 */

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', 'text', array(
                'attr'     => array(
                    'placeholder' => 'company',
                ),
                'required' => false
            ))
            ->add('name', 'text', array(
                'attr' => array(
                    'placeholder' => 'name'
                )
            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'email'
                )
            ))
            ->add('website', 'url', array(
                'attr'     => array(
                    'placeholder' => 'website',
                ),
                'required' => false
            ))
            ->add('phonenumber', 'text', array(
                'attr' => array(
                    'placeholder' => 'phonenumber'
                )
            ))
            ->add('project', 'textarea', array())
        ;
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