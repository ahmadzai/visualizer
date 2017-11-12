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
                            'title' => 'AdminDataSummaryExport-'.date("Y-m-d")
                        )
                    ),
                    array(
                        'extend' => 'excel',
                        'text' => 'Export to Excel',
                        'title_attr' => 'To export all, first select All from the length menu in the left',
                        'button_options' => array(
                            'title' => 'AdminDataSummaryExport-'.date("Y-m-d")
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
            'page_length' => 50,
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

            ->add('region', Column::class, array(
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
            ->add('provinceName', Column::class, array(
                'title' => 'Province',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_province_names',
                        'classes' => 'form-control input-sm',
                        'placeholder' => 'Province'
                    ),
                ),
            ))
            ->add('districtName', Column::class, array(
                'title' => 'District',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_district_names',
                        'placeholder' => 'District',
                        'classes' => 'form-control input-sm',
                    ),
                ),
            ))
            ->add('subDistrict', Column::class, array(
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
            ->add('campaignName', Column::class, array(
                'title' => 'Campaign',
                //'width' => '90%',
                'filter' => array(Select2Filter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'url' => 'select2_campaign_names',
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

            ->add('vaccAbsent3Days', Column::class, array(
                'title' => 'Vac Absent Camp',
                'searchable' => false
                ))
            ->add('vaccAbsent5Day', Column::class, array(
                'title' => 'Vac Absent Revisit',
                'searchable' => false
            ))

            ->add('regSleep', Column::class, array(
                'title' => 'RegNSS',
                'searchable' => false,
            ))

            ->add('vaccSleep3Days', Column::class, array(
                'title' => 'Vac NSS Camp',
                'searchable' => false
            ))
            ->add('vaccSleep5Day', Column::class, array(
                'title' => 'Vac NSS Revisit',
                'searchable' => false
            ))

            ->add('regRefusal', Column::class, array(
                'title' => 'RegRefusal',
                'searchable' => false,
            ))

            ->add('vaccRefusal3Days', Column::class, array(
                'title' => 'Vac Refusal Camp',
                'searchable' => false
            ))
            ->add('vaccRefusal5Day', Column::class, array(
                'title' => 'Vac Refusal Revisit',
                'searchable' => false
            ))

            ->add('newPolioCase', Column::class, array(
                'title' => 'AFP Case',
                'searchable' => false,
                'visible' => false
                ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\AdmClsSum';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'adm_summary_datatable';
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
