'use strict';
const CatchupChartsSetting = {};

// The first row of one campaign
CatchupChartsSetting['missed_by_reason_pie_1'] = {
    'colors':['#FFFF00', '#C99900', '#FF0000'],
    'chartType':{'type':'donut'}, 'legend':false, 'area':'small'
};
CatchupChartsSetting['total_remaining_1'] = {'chartType':{'type':"column"},
    'colors':['#FFB32D'], 'legend':{'enabled':false}};

// The first row of one campaign
CatchupChartsSetting['campaign_title'] = {'chartType':{'type':'html'}};

CatchupChartsSetting['info_box'] = {'chartType':{'type':'html'}};

CatchupChartsSetting['info_table'] = {'chartType':{'type':'html'}};

// Pie charts Row
CatchupChartsSetting['recovered_all_type_1'] = {'colors':['#048AFF', '#40C97A', '#FFB32D'],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_absent_1'] = {'colors':['#048AFF', '#40C97A', '#FFFF00'],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_nss_1'] = {'colors':['#048AFF', '#40C97A', '#9C800E'],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_refusal_1'] = {'colors':['#048AFF', '#40C97A', '#FF0000'],
    'chartType':{'type':'halfpie'}, 'legend':true
};


// 10 campaign vaccinated children column chartType
CatchupChartsSetting['vac_child_trend'] = {'chartType':{'type':"column"}, 'colors':['#048AFF']};
// 10 campaign missed children column chartType
CatchupChartsSetting['missed_child_trend'] = {'chartType':{'type':"column"}, 'colors':['#C99900']};
// 10 campaign missed by type stack column chartType
CatchupChartsSetting['missed_by_type_trend'] = {
    'chartType':{'type':"column", 'stacking':'normal'},
    'colors':['#FFFF00', '#C99900', '#FF0000'],
    'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign absent percent stack chartType
CatchupChartsSetting['absent_recovered_trend'] = {
        'colors': ['#EAFF19', '#45E490', '#048AFF'],
        'chartType':{'type':"column", 'stacking':'percent'},
        'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign nss percent stack chartType
CatchupChartsSetting['nss_recovered_trend'] = {
        'colors': ['#9C800E', '#45E490', '#048AFF'],
        'chartType':{'type':"column", 'stacking':'percent'},
        'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign refusal percent stack chartType
CatchupChartsSetting['refusal_recovered_trend'] = {
    'colors': ['#FF0000', '#45E490', '#048AFF'],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};

// 10 campaign missed recovery area percent chartType
CatchupChartsSetting['missed_child_recovery_trend'] = {'colors': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
    'chartType':{'type':"area", 'stacking':'percent'}};


export default CatchupChartsSetting;