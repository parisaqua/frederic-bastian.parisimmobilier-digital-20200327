<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Option;
use App\Entity\Property;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AdminPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
            ->add('city')
            ->add('address')
            ->add('postalCode')
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('sold')
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])

            ->add('manager', EntityType::class, array(
                'class' => User::class,
                'label' => 'Gestionnaire',
                'required' => true,
                'choice_label' => 'fullName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '['.'%'."ROLE_PROPERTYMANAGER".'%'.']')
                        ->orderBy('u.lastName', 'ASC');   
                },     
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => "forms"
        ]);
    }

    public function getChoices() {
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
