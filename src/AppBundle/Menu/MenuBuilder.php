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
class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;


    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     *
     * This Menu is needed to be replaced by dynamic menu, which will be loading from database
     * Entity, this is a temporary solution
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {

        // Check if the user has specific roles
//        $user = $this->container->get('security.token_storage')->getToken()->getUser();
//        dump($user);
//        die;
        $adminRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $editRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_EDITOR');
        $partnerRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_PARTNER');

        $menu = $factory->createItem('Home');
        $menu->setChildrenAttributes(array('class'=>'sidebar-menu', 'data-widget'=>'tree'));

        $menu->addChild("Home", array('route'=>'home', 'extras'=>['route'=>'cluster_main']))->setExtra('info', 'the main dashboard');
        $menu['Home']->setAttribute('icon','fa-home');
        // ------------------------------------------------ Admin Data ------------------------------------------------
        $menu->addChild("Admin Data", array('uri'=>'#'))->setExtra('info', 'the main dashboard');
        $menu['Admin Data']->setAttribute('icon','fa-database');
        $menu['Admin Data']->setAttribute('sub_menu_icon', 'fa-angle-left');

        // Sub menu (child of Admin Data
        // Dashboard
        $menu['Admin Data']->addChild("Dashboard", array('route'=>'admin_data', 'extras'=>['route'=>'cluster_admin_data']))->setExtra('info', 'Admin Data');
        $menu['Admin Data']->setChildrenAttributes(array('class'=>'treeview-menu'));
        $menu['Admin Data']['Dashboard']->setAttribute('icon','fa-dashboard');
        if($editRole) {
            // Data Download
            $menu['Admin Data']->addChild("Download", array('route'=>'admin_data_download'))->setExtra('info', 'Admin Data');
            // if the user had edit role

            $menu['Admin Data']['Download']->setAttribute('icon', 'fa-download');
            // Data Upload
            {
                $menu['Admin Data']->addChild("Upload", array('route' => 'import_data', 'routeParameters' => ['entity' => 'coverage_data'],
                    'extras' => ['route' => 'import_admin_data_handle']))
                    ->setExtra('info', 'Admin Data');
                $menu['Admin Data']['Upload']->setAttribute('icon', 'fa-upload');
                // Data Entry
                $menu['Admin Data']->addChild("Data Entry", array('uri' => '#'))->setExtra('info', 'of Admin Data');
                $menu['Admin Data']['Data Entry']->setAttribute('icon', 'fa-table');
            }
        }

        //------------------------------------------------------- Catchup Data ---------------------------------------
        $menu->addChild("Catchup Data", array('uri'=>'#'))->setExtra('info', 'the main dashboard');
        $menu['Catchup Data']->setAttribute('icon','fa-database');
        $menu['Catchup Data']->setAttribute('sub_menu_icon', 'fa-angle-left');

        // Sub menu (child of Catchup Data
        // Dashboard
        $menu['Catchup Data']->addChild("Dashboard", array('route'=>'catchup_data', 'extras'=>['route'=>'cluster_catchup_data']))->setExtra('info', 'Catchup Data');
        $menu['Catchup Data']->setChildrenAttributes(array('class'=>'treeview-menu'));
        $menu['Catchup Data']['Dashboard']->setAttribute('icon','fa-dashboard');
        if($editRole) {
            // Data Download
            $menu['Catchup Data']->addChild("Download", array('route' => 'catchup_data_download'))->setExtra('info', 'Catchup Data');
            $menu['Catchup Data']['Download']->setAttribute('icon', 'fa-download');
            // Data Upload
            $menu['Catchup Data']->addChild("Upload", array('route' => 'import_data', 'routeParameters' => ['entity' => 'catchup_data'],
                'extras' => ['route' => 'import_catchup_data_handle']))
                ->setExtra('info', 'Catchup Data');
            $menu['Catchup Data']['Upload']->setAttribute('icon', 'fa-upload');
            // Data Entry
            $menu['Catchup Data']->addChild("Data Entry", array('uri' => '#'))->setExtra('info', 'Catchup Data');
            $menu['Catchup Data']['Data Entry']->setAttribute('icon', 'fa-table');
        }

        if(!$partnerRole) {
            //------------------------------------------------------- ICN Data TPM ---------------------------------------
            $menu->addChild("ICN Monitoring TPM", array('uri' => '#'))->setExtra('info', 'ICN Monitoring Report');
            $menu['ICN Monitoring TPM']->setAttribute('icon', 'fa-database');
            $menu['ICN Monitoring TPM']->setAttribute('sub_menu_icon', 'fa-angle-left');

            // Sub menu (child of Catchup Data
            // SMs Performance
            $menu['ICN Monitoring TPM']->addChild("SMs Performance", array('route' => 'icn_monitoring_sm',
                'extras' => ['route' => 'cluster_icn_monitoring_sm']
            ))->setExtra('info', 'Report');
            $menu['ICN Monitoring TPM']->setChildrenAttributes(array('class' => 'treeview-menu'));
            $menu['ICN Monitoring TPM']['SMs Performance']->setAttribute('icon', ' fa-bar-chart');

            // CCSs Performance
            $menu['ICN Monitoring TPM']->addChild("CCSs Performance", array('route' => 'icn_monitoring_ccs',
                'extras' => ['route' => 'cluster_icn_monitoring_ccs']
            ))->setExtra('info', 'Report');
            $menu['ICN Monitoring TPM']->setChildrenAttributes(array('class' => 'treeview-menu'));
            $menu['ICN Monitoring TPM']['CCSs Performance']->setAttribute('icon', ' fa-bar-chart');
        }

        // TPM SM/CCS Upload Option
        if($editRole) {
            $menu['ICN Monitoring TPM']->addChild("Upload", array('route' => 'import_data_odk',
                'extras' => ['route' => '']))
                ->setExtra('info', 'ICN Data by TPM');
            $menu['ICN Monitoring TPM']['Upload']->setAttribute('icon', 'fa-upload');
        }

        /*------------------------------------------------------- ICN Data Internal ---------------------------------------*/
        if(!$partnerRole) {
            $menu->addChild("ICN Monitoring", array('uri' => '#'))->setExtra('info', 'Internal ICN Monitoring Report');
            $menu['ICN Monitoring']->setAttribute('icon', 'fa-database');
            $menu['ICN Monitoring']->setAttribute('sub_menu_icon', 'fa-angle-left');

            // Sub menu (child of Catchup Data
            // SMs Performance
            $menu['ICN Monitoring']->addChild("SMs Performance", array('route' => 'int_icn_monitoring_sm',
                'extras' => ['route' => 'cluster_icn_monitoring_sm']
            ))->setExtra('info', 'Report (internal ODK)');
            $menu['ICN Monitoring']->setChildrenAttributes(array('class' => 'treeview-menu'));
            $menu['ICN Monitoring']['SMs Performance']->setAttribute('icon', ' fa-bar-chart');

            // CCSs Performance
            $menu['ICN Monitoring']->addChild("CCSs Performance", array('route' => 'int_icn_monitoring_ccs',
                'extras' => ['route' => 'cluster_icn_monitoring_ccs']
            ))->setExtra('info', 'Report (internal ODK)');
            $menu['ICN Monitoring']->setChildrenAttributes(array('class' => 'treeview-menu'));
            $menu['ICN Monitoring']['CCSs Performance']->setAttribute('icon', ' fa-bar-chart');
        }

        // TPM SM/CCS Upload Option
        if($editRole) {
            $menu['ICN Monitoring']->addChild("Upload", array('route' => 'int_import_data_odk',
                'extras' => ['route' => '']))
                ->setExtra('info', 'ICN Data by Internal ODK');
            $menu['ICN Monitoring']['Upload']->setAttribute('icon', 'fa-upload');
        }


        // ------------------------------------------------------- End of ICN Data --------------------------------
        if($adminRole)
            $menu->addChild('other', array('route'=>'homepage'))->setAttribute('icon','fa-link');


        return $menu;
    }

    public function sideMenu(FactoryInterface $factory, array $options)
    {

        $adminRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $editRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_EDITOR');
        $partnerRole = $this->container->get('security.authorization_checker')->isGranted('ROLE_PARTNER');

        $menu = $factory->createItem('SideBarMenu');
        $menu->setChildrenAttributes(array('class'=>'control-sidebar-menu sidebar-menu', 'data-widget'=>'tree'));

        // Campaign Management
        $menu->addChild("Campaigns Management", array('route'=>'campaign_index'))
            ->setExtra('info', 'Manage Campaigns');
        $menu['Campaigns Management']->setAttribute('icon','fa-eyedropper');
        // Users Management
        $menu->addChild("User Management", array('route'=>'list_all_users'))
            ->setExtra('info', 'Manage Users');
        $menu['User Management']->setAttribute('icon','fa-user');
        // ------------------------------------------------ Location Mgt ------------------------------------------------
        $menu->addChild("Location", array('uri'=>'#'))->setExtra('info', 'Manage Locations');
        $menu['Location']->setAttribute('icon','fa-map');
        $menu['Location']->setAttribute('sub_menu_icon', 'fa-angle-left');
        $menu['Location']->setChildrenAttributes(array('class'=>'treeview-menu'));
        // Sub Menu
        $menu['Location']->addChild("Provinces", array('route'=>'province_index'))
            ->setExtra('info', 'Manage Provinces');
        $menu['Location']['Provinces']->setAttribute('icon','fa-cog');
        $menu['Location']->addChild("Districts", array('route'=>'district_index'))
            ->setExtra('info', 'Manage Districts');
        $menu['Location']['Districts']->setAttribute('icon','fa-cog');

        // ------------------------------------------------ Upload Mgt ------------------------------------------------
        $menu->addChild("Upload Mgt", array('uri'=>'#'))->setExtra('info', 'Manage Data Upload');
        $menu['Upload Mgt']->setAttribute('icon','fa-upload');
        $menu['Upload Mgt']->setAttribute('sub_menu_icon', 'fa-angle-left');
        $menu['Upload Mgt']->setChildrenAttributes(array('class'=>'treeview-menu'));
        // Sub Menu
        $menu['Upload Mgt']->addChild("Uploader Mgt", array('route'=>'uploadmanager_index'))
            ->setExtra('info', 'Mange Uploader');
        $menu['Upload Mgt']['Uploader Mgt']->setAttribute('icon','fa-upload');
        $menu['Upload Mgt']->addChild("Manage Files", array('route'=>'manage_uploaded_files'))
            ->setExtra('info', 'Manage Uploaded Files');
        $menu['Upload Mgt']['Manage Files']->setAttribute('icon','fa-file');

        // ------------------------------------------------ External Services Mgt ------------------------------------------------
        $menu->addChild("Ext Services Mgt", array('uri'=>'#'))
            ->setExtra('info', 'Manage External Services');
        $menu['Ext Services Mgt']->setAttribute('icon','fa-plug');
        $menu['Ext Services Mgt']->setAttribute('sub_menu_icon', 'fa-angle-left');
        $menu['Ext Services Mgt']->setChildrenAttributes(array('class'=>'treeview-menu'));
        // Sub Menu
        $menu['Ext Services Mgt']->addChild("Register Service", array('route'=>'apiconnect_index'))
            ->setExtra('info', 'Service Registration');
        $menu['Ext Services Mgt']['Register Service']->setAttribute('icon','fa-registered');
        $menu['Ext Services Mgt']->addChild("Sync ONA Data", array('route'=>'ona_connect'))
            ->setExtra('info', 'Sync remote data');
        $menu['Ext Services Mgt']['Sync ONA Data']->setAttribute('icon','fa-retweet');

        // BenchMark Management
        $menu->addChild("Heatmap Benchmark", array('route'=>'heatmap_benchmark_index'))
            ->setExtra('info', 'Manage Heatmap Benchmarks');
        $menu['Heatmap Benchmark']->setAttribute('icon','fa-table');

        return $menu;
    }


}