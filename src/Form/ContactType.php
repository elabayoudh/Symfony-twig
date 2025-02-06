<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('name', TextType::class, [
            'label' => 'Votre Nom',
            'attr' => [
                'placeholder' => 'Entrez votre nom',
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre nom'
                ])
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Votre Email',
            'attr' => [
                'placeholder' => 'Entrez votre email',
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre email'
                ]),
                new Email([
                    'message' => 'Veuillez entrer un email valide'
                ])
            ]
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Votre Message',
            'attr' => [
                'placeholder' => 'Tapez votre message ici',
                'rows' => 5,
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre message'
                ]),
                new Length([
                    'max' => 500,
                    'maxMessage' => 'Votre message ne peut pas dépasser {{ limit }} caractères'
                ])
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-primary btn-lg'
            ]
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Modifiez si nécessaire
        ]);
    }
}
