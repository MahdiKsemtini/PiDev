<?php

namespace App\Form;

use App\Entity\Formation;
use Cassandra\Map;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Labelle',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Labelle du formation',
                    'class'=>'prompt srch_explore',
                    'name'=>'labelle',
                    'id'=>'id_lab',
                    'maxlength'=>'64']])
            ->add('Description',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Description du formation',
                    'class'=>'prompt srch_explore',
                    'name'=>'description',
                    'id'=>'id_des',
                    'maxlength'=>'64']])
            ->add('DateDebut',DateType::class,[
                'attr'=>[
                    'placeholder'=>'date debut ',
                    'class'=>'prompt srch_explore',
                    'name'=>'dateDebut',
                    'id'=>'id_db',
                    'maxlength'=>'64']])
            ->add('DateFin',DateType::class,[
                'attr'=>[
                    'placeholder'=>'date fin',
                    'class'=>'prompt srch_explore',
                    'name'=>'dateFin',
                    'id'=>'id_df',
                    'maxlength'=>'64']])
            ->add('Lieu',TextType::class,[
                'attr'=>[
                    'placeholder'=>'lieu',
                    'class'=>'prompt srch_explore',
                    'name'=>'lieu',
                    'id'=>'id_lieu',
                    'maxlength'=>'64']])
            ->add('Domaine',TextType::class,[
                'attr'=>[
                    'placeholder'=>'domaine',
                    'class'=>'prompt srch_explore',
                    'name'=>'domaine',
                    'id'=>'id_domaine',
                    'maxlength'=>'64']])
            ->add('Montant',NumberType::class,[
                'attr'=>[
                    'placeholder'=>'montant',
                    'class'=>'prompt srch_explore',
                    'name'=>'montant',
                    'id'=>'id_montant',
                    'maxlength'=>'64']])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
