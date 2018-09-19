'use strict';

import $ from 'jquery';
import 'bootstrap-multiselect';
import MainFilter from './filter/MainFilter';
import FilterListener from './filter/FilterListener';
import ApiCall from './common/AjaxRequest';
import FilterControl from './filter/FilterControl';
import Alerts from './common/Alerts';
import { SettingMain, SettingCatchup, SettingCoverage } from './setting/';

$(document).ready(function () {
    // intitalize filter
    new MainFilter();
    let listener = new FilterListener();
    let apiCall = new ApiCall();
    let filterControl = new FilterControl();

    let urlPostFix = $('#ajaxUrl').val();
    let url = "ajax_"+urlPostFix;
    let  Setting = SettingMain;                             // Dynamic Setting as per loaded page
    if(urlPostFix === "coverage_data")
        Setting = SettingCoverage;
    else if(urlPostFix === "catchup_data") {
        Setting = SettingCatchup;
        apiCall.updateMap('load_geojson',
            {'chartType':{'type':"map"},
               'renderTo': 'total_remaining_by_type_1',
               'data':{},
               'legend': false,
            }, {'map_type':'province'});
    }

    // load for this first time
    let filterData = listener.listenMain()   ;             // call listener to return filters data
    // set filterState
    filterControl.setFilterState({...filterData});
    filterData.loadWhat = 'trend';
    apiCall.updateAll(url, Setting, filterData, {...filterData, loadWhat:'info'});

    // When filter button is clicked
    $('#filterButton').click(function () {

        let filterData = listener.listenMain();
        if(filterData.campaign.length === 0)
            Alerts.error('Please select at least one campaign');
        else {
            let checkFilter = filterControl.checkFilterState(filterData);
            if (!checkFilter) {
                Alerts.filterInfo();
            } else {
                checkFilter === "both" ?
                    apiCall.updateAll(url, Setting, {...filterData, loadWhat: 'trend'},
                        {...filterData, loadWhat: 'info'}) :
                    apiCall.partiallyUpdate(url, Setting, filterData,
                        checkFilter + '-loader');
            }
        }
    })
});