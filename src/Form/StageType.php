<?php

namespace App\Form;

use App\Entity\OffreStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomProjet')
            ->add('competences')
            ->add('description')
            ->add('domaine')
            ->add('fichier')
            ->add('duree')
            ->add('typeStage')
            ->add('dateCreation')
            ->add('etat')
            ->add('societe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreStage::class,
        ]);
    }
}
