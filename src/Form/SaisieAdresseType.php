<?php

namespace App\Form;

use App\Entity\SaisieAdresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class SaisieAdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('adresse',TextType::class ,['attr'=>['placeholder'=>" Ex:  45 rue des saints-pères 75006 paris "]])
            
            ->add('rayon', RangeType::class, [
                'attr' => [
                    'value'=>1000,
                    'min' => 200,
                    'max' => 1000,
                   'step'=>200
                ]
            ])
           
            
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SaisieAdresse::class,
        ]);
    }
}
