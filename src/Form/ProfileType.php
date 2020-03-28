<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
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
                'label' => "Présentation",
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots...'."\n" .'Seuls les membres d\'Immo Digital pourront voir votre prose.',
                    'rows' => 6, 
                    'cols' => 100
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => false,
                'allow_delete' => true,
                // 'download_label' => 'Téléchargement',
                'download_uri' => false,
                // 'image_uri' => false,
                // 'imagine_pattern' => 'avatar', //nom dans Liip_image
                'asset_helper' => true,
                'delete_label' => 'Supprimer l\'image actuelle ?',
                
                'attr' => [
                    'onchange'    => 'previewFile()',
                    'placeholder' => 'Une photo ?',
                ]
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }

   
}
