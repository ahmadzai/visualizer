<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProvinceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('provinceRegion')->add('provinceName')->add('provinceNamePashtu')->add('provinceNameDari')->add('provinceRhizomeCode')->add('entryDate');

        $builder->add('provinceName', TextType::class, array('label' => 'Name', 'attr' => array('class'=>'form-control')))
            ->add('provinceNamePashtu', TextType::class, array('label' => 'Pashtu Name', 'attr' => array('class'=>'form-control'), 'required'=>false))
            ->add('provinceNameDari', TextType::class, array('label' => 'Dari Name', 'attr' => array('class'=>'form-control'), 'required'=>false))
            ->add('provinceRhizomeCode', TextType::class, array('label' => 'Rhizome Code', 'attr' => array('class'=>'form-control'), 'required'=>false))
            ->add('provinceRegion', ChoiceType::class, array('label'=> 'Region', 'choices' => array(
                'CR'=>'CR',
                'ER'=>'ER',
                'SR'=>'SR',
                'WR'=>'WR',
                'NR'=>'NR',
                'SER'=>'SER',
                'NER'=>'NER'
            ), 'attr' => array('class'=>'form-control select2'), 'placeholder'=>'Select Region'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Province'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_province';
    }


}
