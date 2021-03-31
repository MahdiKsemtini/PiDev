<?php

namespace App\Form;

use App\Entity\OffreStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomProjet', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Insérer  titre de projet',
                    'class'=>'prompt srch_explore',
                    'name'=>'titre',
                    'id'=>'id_titre',
                    'maxlength'=>'64',
                ] ])

            ->add('competences', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Insérer les compétences requises',
                    'class'=>'prompt srch_explore',
                    'name'=>'competences',
                    'id'=>'id_competence',
                    'maxlength'=>'64',
                ] ])
            ->add('description', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Insérer votre description du projet',
                    'class'=>'prompt srch_explore',
                    'name'=>'competences',
                    'id'=>'id_competence',
                    'maxlength'=>'64',
                    "rows"=>"5",
                ] ])

            ->add('domaine', ChoiceType::class,[
                'attr'=>[
                    //  'placeholder'=>'Insérer votre description du projet',
                    //  'class'=>'ui hj145 dropdown cntry152 prompt srch_explore',
                    'name'=>'domaine',
                    'id'=>'id_domaine',
                    //  'maxlength'=>'64',
                    //  "rows"=>"5",

                ],
                'choices'  => [

                    'Informatique' => 'Informatique',
                    'Design' => 'Design',
                    'Artisanat' => 'Artisanat',
                    'jeux video' => 'jeux video',
                    'Marketing' => 'Marketing',
                    'Journalisme' => 'Journalisme',
                    'diagnostic des automobiles'=>'diagnostic des automobiles',
                ],

            ])



            ->add('fichier', FileType::class,[

                'constraints' => [
                    new File([
                        'maxSize' => '8000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])

            ->add('duree', TextType::class,[
                'attr'=>[
                    //  'placeholder'=>'Insérer  titre de projet',
                    'class'=>'prompt srch_explore',
                    'name'=>'salaire',
                    'id'=>'id_salaire',
                    'maxlength'=>'64',
                ] ])

            ->add('typeStage', ChoiceType::class,[
                'attr'=>[
                    //  'placeholder'=>'Insérer votre description du projet',
                    'class'=>'dropdown cntry152',
                    'name'=>'type',
                    'id'=>'id_type',
                    //  'maxlength'=>'64',
                    //  "rows"=>"5",

                ],
                'choices'  => [
                    'stage été' => 'stage été',
                    'stage initiation' => 'stage initiation',
                    'stage de perfectionnement' => 'stage de perfectionnement',
                    'stage de projet de fin étude' => 'stage de projet de fin étude',

                ],

            ])
            ->add('dateExpiration', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreStage::class,
        ]);
    }
}
