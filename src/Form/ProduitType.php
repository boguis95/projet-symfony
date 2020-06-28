<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', FileType::class, [
                "mapped" => false,
                "constraints" => [
                    new File([ 
                        "mimeTypes" => [ "image/jpeg", "image/png", "image/gif" ],
                        "mimeTypesMessage" => "Formats autorisés : jpg, png, gif",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Le fichier ne doit pas dépasser 2Mo"
                    ]) 
                ],
                "required" => false,

            ])
            ->add('reference')
            ->add('categorie')
            ->add('titre')
            ->add('description')
            ->add('couleur')
            ->add('public')
            ->add('prix')
            ->add('stock')
            ->add('taille')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
