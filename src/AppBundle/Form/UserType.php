<?php

namespace AppBundle\Form;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/29/2017
 * Time: 4:25 PM
 */
class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array('label'=>'First Name',
            'attr' => array('class'=>'form-control')))
            ->add('lastName', TextType::class, array('label'=>'Last Name',
                'attr' => array('class'=>'form-control'), 'required'=> false))
            ->add('email', EmailType::class)
            ->add('province', EntityType::class, array(
                'class'=>'AppBundle:Province',
                'choice_label'=>'provinceName',
                'attr' => array('class'=>'form-control select2'),
                'placeholder'=>'Select a Province',
                'required' => false))
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
                'required' => false,
            ))
            ->add('roles', ChoiceType::class, array('choices' => array(
                'Admin'=>'ROLE_ADMIN',
                'Editor'=>'ROLE_EDITOR',
                'Viewer'=>'ROLE_NORMAL_USER',
                'Partner' => 'ROLE_PARTNER'
            ), 'multiple'=> true, 'attr' => array('class'=>'form-control select2')))
            ->add('level', ChoiceType::class, array('choices' => array(
                'National' => 'National', 'Region' => 'Region', 'Province' => 'Province'),
                'attr' => array('class'=>'form-control select2'), 'placeholder'=>'Select a Job level', 'required'=>false))
            ->add('position', TextType::class, array('label'=>'Position',
                'attr' => array('class'=>'form-control'), 'required' => false))
            ->add('mobileNumber', TextType::class, array('label'=>'Mobile No',
                'attr' => array('class'=>'form-control'), 'required'=>false))
            ->add('enabled', ChoiceType::class, array(
                'choices' => array(
                    'Yes' => true,
                    'No' => false,
                )));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'csrf_token_id' => 'edit_user',
            // BC for SF < 2.8
            'intention' => 'edit_user',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_edit_form';
    }
}