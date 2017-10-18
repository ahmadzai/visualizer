<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;
use Sg\DatatablesBundle\Datatable\Filter\Select2Filter;

/**
 * Class AdminDataSummaryDatatable
 *
 * @package AppBundle\Datatables
 */
class AdminDataSummaryDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->extensions->set(array(
            'responsive' => false,
            //'buttons' => true,
            'buttons' => array(
                'create_buttons' => array(
                    array(
                        'extend' => 'colvis',
                        'text' => 'Cols visibility',
                        'title_attr' => 'Enable or disable columns',
                    ),
                    array(
                        'extend' => 'csv',
                        'text' => 'Export to CSV',
                        'title_attr' => 'To export all, first select All from the length menu in the left',
                        'button_options' => array(
                            'title' => 'AdminDataExport-'.date("Y-m-d")
                        )
                    ),
                    array(
                        'extend' => 'excel',
                        'text' => 'Export to Excel',
                        'title_attr' => 'To export all, first select All from the length menu in the left',
                        'button_options' => array(
                            'title' => 'AdminDataExport-'.date("Y-m-d")
                        )
                    ),
                ),
            ),
        ));

        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        ));

        $this->ajax->set(array(
        ));


        $this->options->set(array(
            'classes' => 'table table-bordered table-striped dataTable no-footer',
            'stripe_classes' => [],
            'individual_filtering' => true,
            'page_length' => 25,
            'length_menu' => array(array(10, 25, 50, 100, -1), array('10', '25', '50', '100', 'All')),
            'individual_filtering_position' => 'head',
            'order' => array(array($this->getDefaultOrderCol(), 'asc')),
            'order_cells_top' => true,
            //'global_search_type' => 'gt',
            'search_in_non_visible_columns' => true,
            'dom' => '<"row"
                        <"col-sm-4"l>
                        <"col-sm-4"B>
                        <"col-sm-4"f>r
                        >
                        t
                        <"row"
                        <"col-sm-5"i>
                        <"col-sm-7"p>
                        >',
        ));

        $this->features->set(array(
            'paging' => true,
            'searching' => true,
            'auto_width' => null,
            'defer_render'  => true,
            'length_change' => true,
            'processing' => true,
            'scroll_x' => true,

        ));


        $this->columnBuilder
            ->add('district.province.provinceRegion', Column::class, array(
                'title' => 'Region',
                //'width' => '90%',
                'visible'=>true,
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_region',
                        'classes' => 'form-control input-sm',
                        'placeholder' => 'Region'
                    ),
                ),
            ))
            ->add('district.province.provinceName', Column::class, array(
                'title' => 'Province',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_province',
                        'classes' => 'form-control input-sm',
                        'placeholder' => 'Province'
                    ),
                ),
            ))
            ->add('district.districtName', Column::class, array(
                'title' => 'District',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_district',
                        'placeholder' => 'District',
                        'classes' => 'form-control input-sm',
                    ),
                ),
            ))
            ->add('subDistrictName', Column::class, array(
                'title' => 'SubDistrict',
                'visible'=>false,
                'filter' => array(TextFilter::class,
                    array(
                        'search_type' => 'like',
                        'cancel_button' => false,
                        'classes' => 'form-control input-sm'
                    ),
                ),
                ))
            ->add('campaign.campaignName', Column::class, array(
                'title' => 'Campaign',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_campaign',
                        'classes' => 'form-control input-sm',
                        'placeholder'=>'Campaign'
                    ),
                ),
            ))

            ->add('cluster', Column::class, array(
                'title' => 'Cluster',
                'searchable' => false
                ))
            ->add('targetPopulation', Column::class, array(
                'title' => 'Target',
                'searchable' => false
                ))
            ->add('receivedVials', Column::class, array(
                'title' => 'Given Vials',
                'visible' => false,
                'searchable' => false,
                ))
            ->add('usedVials', Column::class, array(
                'title' => 'Used Vials',
                'visible' => false,
                'searchable' => false,
                ))
            ->add('child011', Column::class, array(
                'title' => 'Child011',
                'searchable' => false,
                ))
            ->add('child1259', Column::class, array(
                'title' => 'Child1259',
                'searchable' => false
                ))
            ->add('regAbsent', Column::class, array(
                'title' => 'RegAbsent',
                'searchable' => false,
                ))
            ->add('vaccAbsent', Column::class, array(
                'title' => 'VaccAbsent',
                'searchable' => false
                ))
            ->add('Absent', Column::class, array(
                'title' => 'Absents',
                'dql' => '(admindata.regAbsent-admindata.vaccAbsent)',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('regSleep', Column::class, array(
                'title' => 'RegNSS',
                'searchable' => false,
                'visible' => false
                ))
            ->add('vaccSleep', Column::class, array(
                'title' => 'VaccNSS',
                'searchable' => false,
                'visible' => false
                ))
            ->add('NSS', Column::class, array(
                'title' => 'NSS',
                'dql' => '(admindata.regSleep-admindata.vaccSleep)',
                'searchable' => false,
                'orderable' => true,
                'visible' => false
            ))
            ->add('regRefusal', Column::class, array(
                'title' => 'RegRefusal',
                'searchable' => false,
                ))
            ->add('vaccRefusal', Column::class, array(
                'title' => 'VaccRefusal',
                'searchable' => false,
                ))
            ->add('refusals', Column::class, array(
                'title' => 'Refusals',
                'dql' => '(admindata.regRefusal-admindata.vaccRefusal)',
                'searchable' => false,
                'orderable' => true,
            ))

            ->add('newPolioCase', Column::class, array(
                'title' => 'AFP Case',
                'searchable' => false,
                'visible' => false
                ))
            ->add('vaccDay', Column::class, array(
                'title' => 'Day',
                'searchable' => false,
                ))
//            ->add('entryDate', DateTimeColumn::class, array(
//                'title' => 'EntryDate',
//                ))
            ->add('id', Column::class, array(
                'title' => 'Id',
                'visible' => false,
                ))

//            ->add('campaign.campaignType', Column::class, array(
//                'title' => 'Campaign CampaignType',
//                ))
//            ->add('campaign.campaignStartDate', Column::class, array(
//                'title' => 'Campaign CampaignStartDate',
//                ))
//            ->add('campaign.campaignEndDate', Column::class, array(
//                'title' => 'Campaign CampaignEndDate',
//                ))
//            ->add('campaign.entryDate', Column::class, array(
//                'title' => 'Campaign EntryDate',
//                ))
//            ->add('campaign.campaignYear', Column::class, array(
//                'title' => 'Campaign CampaignYear',
//                ))
//            ->add('campaign.campaignMonth', Column::class, array(
//                'title' => 'Campaign CampaignMonth',
//                ))
//            ->add('campaign.id', Column::class, array(
//                'title' => 'Campaign Id',
//                ))

//            ->add('district.districtNamePashtu', Column::class, array(
//                'title' => 'District DistrictNamePashtu',
//                ))
//            ->add('district.districtNameDari', Column::class, array(
//                'title' => 'District DistrictNameDari',
//                ))
//            ->add('district.districtLpdStatus', Column::class, array(
//                'title' => 'District DistrictLpdStatus',
//                ))
//            ->add('district.districtRiskStatus', Column::class, array(
//                'title' => 'District DistrictRiskStatus',
//                ))
//            ->add('district.districtIcnStatus', Column::class, array(
//                'title' => 'District DistrictIcnStatus',
//                ))
//            ->add('district.entryDate', Column::class, array(
//                'title' => 'District EntryDate',
//                ))
//            ->add('district.id', Column::class, array(
//                'title' => 'District Id',
//                ))
//            ->add(null, ActionColumn::class, array(
//                'title' => $this->translator->trans('sg.datatables.actions.title'),
//                'actions' => array(
//                    array(
//                        'route' => 'admindata_show',
//                        'route_parameters' => array(
//                            'id' => 'id'
//                        ),
//                        'label' => $this->translator->trans('sg.datatables.actions.show'),
//                        'icon' => 'glyphicon glyphicon-eye-open',
//                        'attributes' => array(
//                            'rel' => 'tooltip',
//                            'title' => $this->translator->trans('sg.datatables.actions.show'),
//                            'class' => 'btn btn-primary btn-xs',
//                            'role' => 'button'
//                        ),
//                    ),
//                    array(
//                        'route' => 'admindata_edit',
//                        'route_parameters' => array(
//                            'id' => 'id'
//                        ),
//                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
//                        'icon' => 'glyphicon glyphicon-edit',
//                        'attributes' => array(
//                            'rel' => 'tooltip',
//                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
//                            'class' => 'btn btn-primary btn-xs',
//                            'role' => 'button'
//                        ),
//                    )
//                )
//            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\AdminData';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admindatasummary_datatable';
    }

    /**
     * Get User.
     *
     * @return mixed|null
     */
    private function getUser()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->securityToken->getToken()->getUser();
        } else {
            return null;
        }
    }

    /**
     * Is admin.
     *
     * @return bool
     */
    private function isAdmin()
    {
        return $this->authorizationChecker->isGranted('ROLE_ADMIN');
    }

    /**
     * Get default order col.
     *
     * @return int
     */
    private function getDefaultOrderCol()
    {
        return true === $this->isAdmin()? 1 : 0;
    }


}
