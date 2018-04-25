<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 4/6/2018
 * Time: 12:14 PM
 */

namespace AppBundle\Form;


use AppBundle\Entity\District;
use AppBundle\Entity\Fieldbook;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FieldbookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('district', EntityType::class, array(
                'class' => District::class,
                'choice_value' => 'id',
                'choice_label' => 'districtName'
            ))
            ->add('cluster', TextType::class, array(
                'label'=>'Cluster'
            ))
            ->add('areaName', TextType::class, array(
                'label' => 'Area'
            ))
            ->add('smName')
            ->add('fieldbookEntries', CollectionType::class, [
                'label'        => 'Children',
                'entry_type'   => FieldbookEntryType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => 'item', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'table fieldbook-collection',
                ],

            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Fieldbook'
        ));
    }

    public function getBlockPrefix()
    {
        return 'FieldbookType';
    }

}