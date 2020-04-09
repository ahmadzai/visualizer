<?php

namespace AppBundle\Form;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/29/2017
 * Time: 4:25 PM
 */
class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array('label'=>'First Name',
            'attr' => array('class'=>'form-control')))
            ->add('lastName', TextType::class, array('label'=>'Last Name',
                'attr' => array('class'=>'form-control')))
            ->add('province', EntityType::class, array(
                'class'=>'AppBundle:Province',
                'choice_label'=>'provinceName',
                'attr' => array('class'=>'form-control select2'),
                'required'=>false,
                'placeholder'=>'Select a Province'))
            ->add('region', ChoiceType::class, array('choices' => array(
                'CR'=>'CR',
                'ER'=>'ER',
                'SR'=>'SR',
                'WR'=>'WR',
                'NR'=>'NR',
                'SER'=>'SER',
                'NER'=>'NER'
            ),
                'attr' => array('class'=>'form-control select2'),
                'placeholder'=>'Select a Region',
                'required'=>false,
            ))
            ->add('roles', ChoiceType::class, array('choices' => array(
                'Admin'=>'ROLE_ADMIN',
                'Editor'=>'ROLE_EDITOR',
                'COVID19Editor' => 'ROLE_COVID19_EDITOR',
                'Viewer'=>'ROLE_NORMAL_USER',
                'Partner' => 'ROLE_PARTNER'
            ), 'multiple'=> true, 'attr' => array('class'=>'form-control select2')))
            ->add('level', ChoiceType::class, array('choices' => array(
                'International' => 'International',
                'National' => 'National',
                'Region' => 'Region',
                'Province' => 'Province'),
                'attr' => array('class'=>'form-control select2'), 'placeholder'=>'Select a Job level'))
            ->add('position', TextType::class, array('label'=>'Position',
                'attr' => array('class'=>'form-control')))
            ->add('mobileNumber', TextType::class, array('label'=>'Mobile No',
                'attr' => array('class'=>'form-control')));


    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}