<?php

namespace App\Form;

use App\Entity\EventLoisir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventLoisirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Labelle',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Labelle du Evenement',
                    'class'=>'prompt srch_explore',
                    'name'=>'labelle',
                    'id'=>'id_lab',
                    'maxlength'=>'64']])
            ->add('Description',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Description du l\'Evenement',
                    'class'=>'prompt srch_explore',
                    'name'=>'description',
                    'id'=>'id_des',
                    'maxlength'=>'64']])
            ->add('DateDebut',DateTimeType::class,[
                'attr'=>[
                    'date_widget'=>'single_text',
                    'placeholder'=>'date debut ',
                    'class'=>'prompt srch_explore',
                    'name'=>'dateDebut',
                    'id'=>'id_db',
                    'maxlength'=>'64']])
            ->add('DateFin',DateTimeType::class,[
                'attr'=>[
                    'date_widget'=>'single_text',
                    'placeholder'=>'date fin',
                    'class'=>'prompt srch_explore',
                    'name'=>'dateFin',
                    'id'=>'id_df',
                    'maxlength'=>'64']])
            ->add('Lieu',HiddenType::class,[
                'attr'=>[
                    'placeholder'=>'Lieu',
                    'class'=>'prompt srch_explore',
                    'name'=>'lieu',
                    'id'=>'lieu',
                    'maxlength'=>'64']])
            ->add('Domaine',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Domaine',
                    'class'=>'prompt srch_explore',
                    'name'=>'domaine',
                    'id'=>'domaine',
                    'maxlength'=>'64']])
            ->add('NbParticipant',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Nombre Participant',
                    'class'=>'prompt srch_explore',
                    'name'=>'NbParticipant',
                    'id'=>'id_NbParticipant',
                    'maxlength'=>'64']])
            ->add('lat',HiddenType::class,[
                'attr'=>[
                    'placeholder'=>'domaine',
                    'class'=>'prompt srch_explore',
                    'name'=>'lat',
                    'id'=>'lat',
                    'maxlength'=>'64']])
            ->add('lng',HiddenType::class,[
                'attr'=>[
                    'placeholder'=>'domaine',
                    'class'=>'prompt srch_explore',
                    'name'=>'lng',
                    'id'=>'lng',
                    'maxlength'=>'64']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventLoisir::class,
        ]);
    }
}
