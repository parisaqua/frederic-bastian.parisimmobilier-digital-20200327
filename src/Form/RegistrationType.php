<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
{
    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gender = ['M.' => 'Monsieur', 'Mme.' => 'Madame'];
        
        $builder

            ->add('gender', ChoiceType::class, [
                'choices' => $this->getChoices(),'label' => 'Civilité'
                ])

            ->add('firstName', TextType:: class, [
                'attr' => [
                    'placeholder' => 'Votre prénom ...'
                    ]
            ])
            ->add('lastName', TextType:: class, [
                'attr' => [
                    'placeholder' => 'Votre nom ...'
                    ]
            ])
            ->add('email', EmailType:: class, [
                'attr' => [
                    'placeholder' => 'Votre adresse e-mail ...'
                    ]
            ])
            ->add('hash', PasswordType:: class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Choisir un mot de passe'
                    ]
            ])
            ->add('passwordConfirm', PasswordType:: class, [
                'label' => 'Confirmation',
                'attr' => [
                    'placeholder' => 'Comfimer le mot de passe'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => "forms"
        ]);
    }

    private function getChoices()
    {
        $choices = User::GENDER;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
