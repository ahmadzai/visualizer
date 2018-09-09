'use strict';

/*
Charts Settings for Main Dashboard
1. Object key will be equal to the JSON key returning by Server
2. Object key will also be equal to the container charts is rendering to
3. ChartType, Color and Other Required Properties of the Chart should be Cleared
4. You can also add 'data' property, however, that will be added/replaced by the actual data
----------------------- MultiAxisesChart ----------------------------------------------
object:        { renderTo, data:[], titles:[], indicators:[], colors:[], chartType:{}}

----------------------- Column, Bar, Stack, Area charts  with default Values ----------
object:        {renderTo, data: [], chartType:{type:'column'},
                  combination: [],
                  colors: Highcharts.getOptions().colors,
                  titles: {xTitle: null, yTitle: null},
                  legend: {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                  menu: [{chart:'percent', title:'Percent Stack'},
                      {chart: 'column', title:'Column Chart'}],
                  large:null,
                  yAxisFormatter:""}

---------------------  Pie, Donut Charts with Default Values -------------------------
object:         { renderTo, data : [], legend : false,
                  colors : Highcharts.getOptions().colors,
                  chartType : {type:'pie'}, area : 'large', menu : null }

--------------------- Line, Spline Charts with Default Values -----------------------
Arguments:     { renderTo, data : [], chartType : {type:'line'},
                 colors : Highcharts.getOptions().colors,
                 titles : {xTitle: null, yTitle: null},
                 legend : {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                 menu : [{chart:'spline', title:'Spline Chart'},
                     {chart: 'line', title:'Line Chart'}]
                }

--------------------- Table, HTML --------------------------------------------------
Arguments:      { renderTo, data : [], chartType : {type:'table/html'} }

 */
import CatchupChartsSetting from "./Catchup";

const MainChartsSetting = {};

// ------------------- PIE chart One campaign --------------------------------------

// The first row of one campaign
MainChartsSetting['missed_by_reason_pie_1'] = {
    'colors':['#FFFF00', '#C99900', '#FF0000'],
    'chartType':{'type':'pie'}, 'legend':false, 'area':'small'
};

MainChartsSetting['total_remaining_1'] = {'chartType':{'type':"column"},
    'colors':['#FFB32D'], 'legend':{'enabled':false}};

// The first row of one campaign
MainChartsSetting['campaign_title'] = {'chartType':{'type':'html'}};

MainChartsSetting['info_box'] = {'chartType':{'type':'html'}};

MainChartsSetting['info_table'] = {'chartType':{'type':'html'}};


// All Type Missed
MainChartsSetting['recovered_all_type_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FFB32D', '#B7B3BE'],
    'chartType' : {'type': 'halfpie'},
    'legend':true
};
// Absent
MainChartsSetting['recovered_absent_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FFFF00', '#B7B3BE'],
    'chartType' : {'type': 'halfpie'},
    'legend':true
};
// NSS
MainChartsSetting['recovered_nss_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#9C800E','#B7B3BE'],
    'chartType' : {'type': 'halfpie'},
    'legend':true
};
// Refusal
MainChartsSetting['recovered_refusal_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FF0000','#B7B3BE'],
    'chartType' : {'type': 'halfpie'},
    'legend':true
};

// Refusal
MainChartsSetting['campaign_title'] = {
    'chartType' : {'type': 'html'}
};

//------------------ Trends Starts here ----------------------------------------------

// Ten campaign vaccinated children
MainChartsSetting['vac_child_trend'] = {
    'colors':['#048AFF', '#43AB0D'],
    'chartType':{'type':"column", 'stacking':'normal'}
};
// Ten campaign missed children
MainChartsSetting['missed_child_trend'] = {
    'colors':['#C99900', '#FFB32D'],
    'chartType':{'type':"column"}
};
// // Ten campaign missed by type percent chart
// MainChartsSetting['missed_by_type_trend'] = {
//     'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
//     'chartType':{'type':"column", 'stacking':'percent'}
// };
// Ten campaign absent children percent chart
MainChartsSetting['missed_recovered_trend'] = {
    'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};
// Ten campaign absent children percent chart
MainChartsSetting['absent_recovered_trend'] = {
    'colors': ['#B7B3BE', '#EAFF19', '#2DA810', '#45E490', '#048AFF'],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};
// Ten campaign nss children percent chart
MainChartsSetting['nss_recovered_trend'] = {
    'colors': ['#B7B3BE', '#9C800E', '#2DA810', '#45E490', '#048AFF'],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};
// Ten campaign refusal children percent chart
MainChartsSetting['refusal_recovered_trend'] = {
    'colors': ['#B7B3BE', '#FF0000', '#2DA810', '#45E490', '#048AFF'],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};
// Ten campaign missed recovery area chart
MainChartsSetting['missed_child_recovery_trend'] = {
    'colors': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
    'chartType':{'type':"area", 'stacking':'percent'}
};

export default MainChartsSetting;