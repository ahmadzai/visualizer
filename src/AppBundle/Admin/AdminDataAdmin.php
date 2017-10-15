<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AdminDataAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('district', null, array(), 'entity', array(
                'class'=>'AppBundle:District',
                'choice_label' => 'districtName',
                'choice_value' => 'id',
                'label' => 'District',
            ))
            ->add('subDistrictName')
            ->add('clusterName')
            ->add('targetPopulation')
            ->add('receivedVials')
            ->add('usedVials')
            ->add('child011')
            ->add('child1259')
            ->add('regAbsent')
            ->add('vaccAbsent')
            ->add('regSleep')
            ->add('vaccSleep')
            ->add('regRefusal')
            ->add('vaccRefusal')
            ->add('newPolioCase')
            ->add('vaccDay')
            ->add('entryDate')
            ->add('missed')
            ->add('sleep')
            ->add('refusal')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('district', null, array(), 'entity', array(
                'class'=>'AppBundle:District',
                'choice_label' => 'districtName',
                'choice_value' => 'id',
                'label' => 'District',
            ))
            ->add('subDistrictName', null, array('label'=>'Sub District'))
            ->add('clusterName', null, array('label'=>'Cluster'))
            ->add('targetPopulation', null, array('label'=>'Target'))
            ->add('receivedVials', null, array('label'=>'R-Vials'))
            ->add('usedVials', null, array('label'=>'U-Vials'))
            ->add('child011')
            ->add('child1259')
            ->add('regAbsent', null, array('label'=>'Reg-Absent'))
            ->add('vaccAbsent', null, array('label'=>'Vac-Absent'))
            ->add('missed', null, array('label'=>'Rem-Absent'))
            ->add('regSleep', null, array('label'=>'Reg-NSS'))
            ->add('vaccSleep', null, array('label'=>'Vac-NSS'))
            ->add('sleep', null, array('label'=>'Rem-NSS'))
            ->add('regRefusal', null, array('label'=>'Reg-Refusal'))
            ->add('vaccRefusal', null, array('label'=>'Vac-Refusal'))
            ->add('refusal', null, array('label'=>'Rem-Refusal'))
            ->add('newPolioCase', null, array('label'=>'APF Case'))
            ->add('vaccDay', null, array('label'=>'Day'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
//                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('district', 'entity', array(
                'class'=>'AppBundle:District',
                'choice_label' => 'districtName',
                'choice_value' => 'id',
                'label' => 'District',
            ))
            ->add('subDistrictName', null, array('label'=>'Sub District'))
            ->add('clusterName', null, array('label'=>'Cluster'))
            ->add('targetPopulation', null, array('label'=>'Target'))
            ->add('receivedVials', null, array('label'=>'R-Vials'))
            ->add('usedVials', null, array('label'=>'U-Vials'))
            ->add('child011')
            ->add('child1259')
            ->add('regAbsent', null, array('label'=>'Reg-Absent'))
            ->add('vaccAbsent', null, array('label'=>'Vac-Absent'))

            ->add('regSleep', null, array('label'=>'Reg-NSS'))
            ->add('vaccSleep', null, array('label'=>'Vac-NSS'))

            ->add('regRefusal', null, array('label'=>'Reg-Refusal'))
            ->add('vaccRefusal', null, array('label'=>'Vac-Refusal'))

            ->add('newPolioCase', null, array('label'=>'APF Case'))
            ->add('vaccDay', null, array('label'=>'Day'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('clusterName')
            ->add('clusterNo')
            ->add('cluster')
            ->add('subDistrictName')
            ->add('targetPopulation')
            ->add('receivedVials')
            ->add('usedVials')
            ->add('child011')
            ->add('child1259')
            ->add('regAbsent')
            ->add('vaccAbsent')
            ->add('regSleep')
            ->add('vaccSleep')
            ->add('regRefusal')
            ->add('vaccRefusal')
            ->add('newPolioCase')
            ->add('vaccDay')
            ->add('entryDate')
            ->add('missed')
            ->add('sleep')
            ->add('refusal')
            ->add('id')
        ;
    }
}
