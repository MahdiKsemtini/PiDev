<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('type', TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'quelle est le type de votre reclamation',
                    'class'=>'prompt srch_explore',
                    'name'=>'type',
                    'id'=>'id_type',
                    'maxlength'=>'64',
                ]
            ])
            ->add('texteReclamation', TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'ecrire le text de votre reclamation',
                    'class'=>'prompt srch_explore',
                    'name'=>'textReclamation',
                    'id'=>'id_textReclamation',
                    'maxlength'=>'64',
                ]
            ])
            ->add('Submit', SubmitType::class,[
                'attr' => ['class' => 'login-btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
