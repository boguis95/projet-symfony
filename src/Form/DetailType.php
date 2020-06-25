<?php

namespace App\Form;

use App\Entity\Detail;
use App\Entity\Produit, App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')
            ->add('prix')
            ->add('commande', EntityType::class, [
                "class" => Commande::class,
                "choice_label" => "id"
            ])
            ->add('produit', EntityType::class, [
                "class" => Produit::class,
                "choice_label" => "titre"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Detail::class,
        ]);
    }
}
