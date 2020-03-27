<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => "Décrivez-vous en quelques lignes ...",
                'attr' => [
                    'placeholder' => 'Présentez vous en quelques mots. Seuls les membres d\'Immo Digital pourront voir votre prose.',
                    'rows' => 8, 
                    'cols' => 100
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_label' => 'Téléchargement',
                'download_uri' => false,
                'image_uri' => false,
                'imagine_pattern' => 'avatar', //nom dans Liip_image
                'asset_helper' => true,
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }

   
}
