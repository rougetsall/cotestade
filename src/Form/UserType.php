<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, [
            'label' => 'username',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'username'
            ]
        ])
        ->add('role', ChoiceType::class, [
            'label' => 'Role',
            'attr' => [
                'class' => 'form-control',
            ],
            'choices'  => [
                "Utilisateur" => 'ROLE_USER',
                "Administrateur" => 'ROLE_ADMIN'
            ]
        ])
        ->add('password', TextType::class, [
            'label' => 'password',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'password'
            ]
        ])
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
