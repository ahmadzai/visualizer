<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 12:10 PM
 */
class MenuBuilder2 implements ContainerAwareInterface
{
    use ContainerAwareTrait;


    public function mainMenu(FactoryInterface $factory, array $options)
    {
        //$request = $this->container->get('request_stack')->getCurrentRequest();

        $menu = $factory->createItem('Home');
        $menu->setChildrenAttributes(array('class'=>'sidebar-menu', 'data-widget'=>'tree'));

        $menu->addChild("Home", array('route'=>'home'))->setExtra('info', 'the main dashboard');
        $menu['Home']->setAttribute('icon','fa-home');
        // ------------------------------------------------ Admin Data ------------------------------------------------
        $menu->addChild("Admin Data", array('uri'=>'#'))->setExtra('info', 'the main dashboard');
        $menu['Admin Data']->setAttribute('icon','fa-database');
        $menu['Admin Data']->setAttribute('sub_menu_icon', 'fa-angle-left');

        // Sub menu (child of Admin Data
        // Dashboard
        $menu['Admin Data']->addChild("Dashboard", array('route'=>'admin_data'))->setExtra('info', 'Admin Data');
        $menu['Admin Data']->setChildrenAttributes(array('class'=>'treeview-menu'));
        $menu['Admin Data']['Dashboard']->setAttribute('icon','fa-dashboard');
        // Data Download
        $menu['Admin Data']->addChild("Download", array('uri'=>'#'))->setExtra('info', 'Admin Data');
        $menu['Admin Data']['Download']->setAttribute('icon', 'fa-download');
        // Data Upload
        {
            $menu['Admin Data']->addChild("Upload", array('route' => 'import_admin_data',
                'extras'=>['route'=>'import_admin_data_handle']))
                ->setExtra('info', 'Admin Data');
            $menu['Admin Data']['Upload']->setAttribute('icon', 'fa-upload');
            // Data Entry
            $menu['Admin Data']->addChild("Data Entry", array('uri' => '#'))->setExtra('info', 'of Admin Data');
            $menu['Admin Data']['Data Entry']->setAttribute('icon', 'fa-table');
        }

        //------------------------------------------------------- Catchup Data ---------------------------------------
        $menu->addChild("Catchup Data", array('uri'=>'#'))->setExtra('info', 'the main dashboard');
        $menu['Catchup Data']->setAttribute('icon','fa-database');
        $menu['Catchup Data']->setAttribute('sub_menu_icon', 'fa-angle-left');

        // Sub menu (child of Catchup Data
        // Dashboard
        $menu['Catchup Data']->addChild("Dashboard", array('uri'=>'#'))->setExtra('info', 'Catchup Data');
        $menu['Catchup Data']->setChildrenAttributes(array('class'=>'treeview-menu'));
        $menu['Catchup Data']['Dashboard']->setAttribute('icon','fa-dashboard');
        // Data Download
        $menu['Catchup Data']->addChild("Download", array('uri'=>'#'))->setExtra('info', 'Catchup Data');
        $menu['Catchup Data']['Download']->setAttribute('icon', 'fa-download');
        // Data Upload
        $menu['Catchup Data']->addChild("Upload", array('route'=>'import_catchup_data',
            'extras'=>['route'=>'import_catchup_data_handle']))
            ->setExtra('info', 'Catchup Data');
        $menu['Catchup Data']['Upload']->setAttribute('icon', 'fa-upload');
        // Data Entry
        $menu['Catchup Data']->addChild("Data Entry", array('uri'=>'#'))->setExtra('info', 'Catchup Data');
        $menu['Catchup Data']['Data Entry']->setAttribute('icon', 'fa-table');

        // ------------------------------------------------------- End of Catchup Data --------------------------------
        $menu->addChild('other', array('route'=>'homepage'))->setAttribute('icon','fa-link');


        return $menu;
    }


}