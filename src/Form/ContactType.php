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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'label' => 'contact.company',
                'label_attr' => [
                    'class' => 'text-label',
                ],
                'translation_domain' => 'contact',
                'required' => false,
            ])
            ->add('website', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'label' => 'contact.website',
                'label_attr' => [
                    'class' => 'text-label',
                ],
                'translation_domain' => 'contact',
                'required' => false,
            ])
            ->add('fullName', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'label' => 'contact.full_name',
                'label_attr' => [
                    'class' => 'text-label',
                ],
                'translation_domain' => 'contact',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'label' => 'contact.email',
                'label_attr' => [
                    'class' => 'text-label',
                ],
                'translation_domain' => 'contact',
                'constraints' => [
                    new Assert\Email(),
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'contact.subject',
                'translation_domain' => 'contact',
                'choices' => [
                    'contact.placeholder.choice_1' => 'Développement applicatif',
                    'contact.placeholder.choice_2' => 'Formations',
                    'contact.placeholder.choice_3' => 'Partenariats',
                    'contact.placeholder.choice_4' => 'Autre',
                ],
                'placeholder' => 'contact.placeholder.label'
            ])
            ->add('content', TextAreaType::class, [
                'label' => 'contact.content',
                'label_attr' => [
                    'class' => 'text-label',
                ],
                'translation_domain' => 'contact',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('captcha', CaptchaType::class, [
                'invalid_message' => 'La saisie n\'est pas correcte'
            ])
        ;

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'button.submit',
                'attr' => [
                    'class' => 'button',
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {    
        $resolver->setDefaults([
            'translation_domain' => 'contact'
        ]);
    }
}