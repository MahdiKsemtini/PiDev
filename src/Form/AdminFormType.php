<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[

                    'placeholder'=>'Enter the last name.',
                    'class'=>'prompt srch_explore',
                    'name'=>'first_name',
                    'id'=>'id_nom',
                    'maxlength'=>'60',
                ]])
            ->add('prenom',TextType::class,[
                'attr'=>[

                    'placeholder'=>'Enter the first name.',
                    'class'=>'prompt srch_explore',
                    'name'=>'last_name',
                    'id'=>'id_prenom',
                    'maxlength'=>'60',
                ]])
            ->add('login',TextType::class,[
                'attr'=>[

                    'placeholder'=>'Insert the login to use.',
                    'class'=>'prompt srch_explore',
                    'name'=>'login',
                    'id'=>'id_login',
                    'maxlength'=>'60',
                ]])
            ->add('password',TextType::class,[
                'attr'=>[

                    'placeholder'=>'Insert the password to use.',
                    'class'=>'prompt srch_explore',
                    'name'=>'password',
                    'id'=>'id_password',
                    'maxlength'=>'60',
                ]])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Select Category' => null,
                    'Admin des evenements' => 'Admin des events',
                    'Admin des reclamations' => 'Admin des reclamations',
                    'Admin des emplois' => 'Admin des emplois'
                ],
                'attr' => [
                    'class'=>'ui hj145 dropdown cntry152 prompt srch_explore'
                ]
            ])
            ->add('etat', ChoiceType::class, [
                'attr' => [
                    'class'=>'ui hj145 dropdown cntry152 prompt srch_explore'
                ],
                'choices'  => [
                    'Account  validation' => null,
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',

                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
