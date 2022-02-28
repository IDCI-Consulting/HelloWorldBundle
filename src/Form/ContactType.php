<?php

namespace App\Form;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entreprise', TextType::class, [
                'label' => 'entreprise',
                'required' => false,
            ])
            ->add('website', TextType::class, [
                'label' => 'website',
                'required' => false,
            ])
            ->add('fullName', TextType::class, [
                'label' => 'full_name',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                // 'constraints' => [
                //     new Assert\Email(),
                // ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'subject',
                'choices' => [
                    'Développement applicatif' => 'Développement applicatif',
                    'Formations' => 'Formations',
                    'Partenariats' => 'Partenariats',
                    'Autre' => 'other',
                ],
                'placeholder' => 'Veuillez sélectionner un sujet'
            ])
            ->add('content', TextAreaType::class, [
                'label' => 'content',
                // 'constraints' => [
                //     new Assert\Length([
                //         'min' => 10,
                //         'max' => 1500,
                //     ]),
                // ],
            ])
            ->add('captcha', CaptchaType::class);
        ;

        $builder
            ->add('submit', SubmitType::class)
        ;
    }
}