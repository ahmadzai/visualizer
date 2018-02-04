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
class CoverageDataSummaryDatatable extends AbstractDatatable
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
                            'title' => 'CoverageDataSummaryExport-'.date("Y-m-d")
                        )
                    ),
                    array(
                        'extend' => 'excel',
                        'text' => 'Export to Excel',
                        'title_attr' => 'To export all, first select All from the length menu in the left',
                        'button_options' => array(
                            'title' => 'CoverageDataSummaryExport-'.date("Y-m-d")
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
            'type' => 'POST'
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
                        'search_type' => 'eq',
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

            ->add('clusterNo', Column::class, array(
                'title' => 'Cluster',
                'searchable' => false
            ))
            ->add('noTeams', Column::class, array(
                'title' => 'No. Teams',
                'searchable' => false
            ))
            ->add('noHouses', Column::class, array(
                'title' => 'No. Houses',
                'searchable' => false
            ))
            ->add('targetChildren', Column::class, array(
                'title' => 'Target',
                'searchable' => false
            ))
            ->add('vialsReceived', Column::class, array(
                'title' => 'Received Vials',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('vialsUsed', Column::class, array(
                'title' => 'Used Vials',
                'visible' => false,
                'searchable' => false,
            ))
//            ->add('vacWastage', Column::class, array(
//                'title' => 'Wastage',
//                'visible' => false,
//                'searchable' => false,
//            ))
            ->add('noHousesVisited', Column::class, array(
                'title' => 'HH Visited',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noResidentChildren', Column::class, array(
                'title' => 'Resident Child',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noGuestChildren', Column::class, array(
                'title' => 'Guest Child',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noChildInHouseVac', Column::class, array(
                'title' => 'Inhouse Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noChildOutsideVac', Column::class, array(
                'title' => 'Outside Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noAbsentSameDay', Column::class, array(
                'title' => 'Sameday Absent',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noAbsentSameDayFoundVac', Column::class, array(
                'title' => 'Sameday Found Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noAbsentSameDayVacByTeam', Column::class, array(
                'title' => 'Sameday Vac Team',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('AbsentSD', Column::class, array(
                'title' => 'Absent SD',
                'dql' => '(COALESCE(coverageclustersummary.noAbsentSameDay,0) - 
                           COALESCE(coverageclustersummary.noAbsentSameDayFoundVac,0) - 
                           COALESCE(coverageclustersummary.noAbsentSameDayVacByTeam,0))',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('noAbsentNotSameDay', Column::class, array(
                'title' => 'Not Sameday Absent',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noAbsentNotSameDayFoundVac', Column::class, array(
                'title' => 'Not Sameday Found Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noAbsentNotSameDayVacByTeam', Column::class, array(
                'title' => 'Not Sameday Vac Team',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('AbsentNSD', Column::class, array(
                'title' => 'Absent NSD',
                'dql' => '(COALESCE(coverageclustersummary.noAbsentNotSameDay,0) - 
                           COALESCE(coverageclustersummary.noAbsentNotSameDayFoundVac,0) - 
                           COALESCE(coverageclustersummary.noAbsentNotSameDayVacByTeam,0))',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('noNSS', Column::class, array(
                'title' => 'No. NSS',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noNSSFoundVac', Column::class, array(
                'title' => 'NSS Found Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noNSSVacByTeam', Column::class, array(
                'title' => 'NSS Vac Team',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('RemNSS', Column::class, array(
                'title' => 'Rem NSS',
                'dql' => '(COALESCE(coverageclustersummary.noNSS,0) - 
                           COALESCE(coverageclustersummary.noNSSFoundVac,0) - 
                           COALESCE(coverageclustersummary.noNSSVacByTeam,0) )',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('noRefusal', Column::class, array(
                'title' => 'No. Refusal',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noRefusalFoundVac', Column::class, array(
                'title' => 'Refusal Found Vac',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('noRefusalVacByTeam', Column::class, array(
                'title' => 'Refusal Vac Team',
                'visible' => false,
                'searchable' => false,
            ))
            ->add('Refusal', Column::class, array(
                'title' => 'Rem Refusal',
                'dql' => '(COALESCE(coverageclustersummary.noRefusal,0) - 
                           COALESCE(coverageclustersummary.noRefusalFoundVac,0) - 
                           COALESCE(coverageclustersummary.noRefusalVacByTeam,0))',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('TotalVac', Column::class, array(
                'title' => 'Total Vac',
                'dql' => '(COALESCE(coverageclustersummary.noRefusalFoundVac,0) + 
                           COALESCE(coverageclustersummary.noRefusalVacByTeam,0) +
                           COALESCE(coverageclustersummary.noNSSFoundVac,0) + 
                           COALESCE(coverageclustersummary.noNSSVacByTeam,0) +
                           COALESCE(coverageclustersummary.noAbsentNotSameDayVacByTeam,0) +
                           COALESCE(coverageclustersummary.noAbsentNotSameDayFoundVac,0) +
                           COALESCE(coverageclustersummary.noAbsentSameDayVacByTeam,0) +
                           COALESCE(coverageclustersummary.noAbsentSameDayFoundVac,0) +
                           COALESCE(coverageclustersummary.noChildInHouseVac,0) + 
                           COALESCE(coverageclustersummary.noChildOutsideVac,0))',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('afpCase', Column::class, array(
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
        return 'AppBundle\Entity\CoverageClusterSummary';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cvr_summary_datatable';
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
