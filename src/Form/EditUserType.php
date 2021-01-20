<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' =>
                        [
                            'Utilisateur' => 'ROLE_USER',
                            'Administrateur' => 'ROLE_ADMIN',
                        ],
                    'required' => true,
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
        ;

        $builder->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesArray) {
                        return count($rolesArray) ? $rolesArray[0]: null;
                    },
                    function ($rolesString) {
                        return [$rolesString];
                    }
                )
            )
        ;
    }
}
