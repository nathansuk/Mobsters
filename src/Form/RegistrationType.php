<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Pseudo sur Habbocity'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Adresse e-mail'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => false ,
                'attr' => [
                    'placeholder' => 'Confirmation du mot de passe'
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
