<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'last_name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
            ])
            ->add('content', TextAreaType::class, [
                'label' => 'content',
            ])
        ;

        $builder
            ->add('submit', SubmitType::class)
        ;
    }
}