<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 4/6/2018
 * Time: 12:14 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FieldbookEntryType extends AbstractType
{
    protected $userToken;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->userToken = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$user = $this->userToken->getToken()->getUser();

        $builder
            ->add('houseNo', TextType::class, array('label'=>'House'))
            ->add('houseHead', TextType::class, array('label'=>'House Head'))
            ->add('childName', TextType::class, array('label'=>'Child Name'))
            ->add('childGender', ChoiceType::class, array(
                    'label'=>'Gender',
                    'choices'=> array(
                        'Male'=>1,
                        'Female'=>0
                    )
                ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\FieldbookEntry'
        ));
    }

    public function getBlockPrefix()
    {
        return 'FieldbookEntryType';
    }

}