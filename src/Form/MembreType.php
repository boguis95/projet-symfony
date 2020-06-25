<?php

namespace App\Form;

use App\Entity\Membre;
use Doctrine\DBAL\Exception\ReadOnlyException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [ "label" => "E-mail"])
            ->add('password', PasswordType::class, [ 
                "label" => "Mot de passe",
                // "constraints" => [
                //     new Regex([
                //         "pattern" => "/^(?=.{6,10}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                //         "message" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre, un caractère spécial"
                //     ])
                // ],
                "help" => "Le mot de passe doit comporter entre 6 et 10 caractères et au moins une minuscule, une majuscule, un chiffre, un caractère spécial",
                "mapped" => false,
                "required" => false,
            ])
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 5,
                        "max" => 20, 
                        "minMessage" => "Le pseudo doit comporter au minimum {{ limit }} caractères",
                        "maxMessage" => "Le pseudo ne doit passer dépasser {{ limit }} caractères"
                    ])
                ]
            ])
            ->add('civilite', ChoiceType::class, [
                "choices" => [
                    "M." => "h",
                    "Mme" => "f",
                    "Autre" => "a"
                ]
            ])
            ->add('nom', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "max" => 50, 
                        "minMessage" => "Le nom doit comporter au minimum {{ limit }} caractères",
                        "maxMessage" => "Le nom ne doit passer dépasser {{ limit }} caractères"
                    ])
                ]

            ])
            ->add('prenom', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "max" => 50, 
                        "minMessage" => "Le prénom doit comporter au minimum {{ limit }} caractères",
                        "maxMessage" => "Le prénom ne doit passer dépasser {{ limit }} caractères"
                    ])
                ],
                "label" => "Prénom"

            ])
            ->add('adresse', TextareaType::class, [
                "constraints" => [
                    new Length([
                        "max" => 255, 
                        "maxMessage" => "Le prénom ne doit passer dépasser {{ limit }} caractères"
                    ])
                ],

            ])
            ->add('code_postal',TextType::class, [
                "constraints" => [
                    new Regex([
                        "pattern" => "/^[0-9]{5}$/",
                        "message" => "Le code postal ne doit comporte que 5 chiffres"
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "max" => 50, 
                        "minMessage" => "La ville doit comporter au minimum {{ limit }} caractères",
                        "maxMessage" => "La ville ne doit passer dépasser {{ limit }} caractères"
                    ])
                ],

            ])
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Membre" => "ROLE_USER",
                    "Administrateur" => "ROLE_ADMIN"
                ],
                "multiple" => true,
                "expanded" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
