'use strict';

import colors from './../colors';

const CatchupChartsSetting = {};

// The first row of one campaign
CatchupChartsSetting['missed_by_reason_pie_1'] = {
    'colors':[colors.REM_ABSENT, colors.REM_NSS, colors.REM_REFUSAL],
    'chartType':{'type':'donut'}, 'legend':false, 'area':'small'
};
CatchupChartsSetting['total_remaining_1'] = {'chartType':{'type':"column"},
    'colors':[colors.REM_MISSED], 'legend':{'enabled':false}};

CatchupChartsSetting['total_recovered_remaining_1'] = {'chartType':{'type':"column", 'stacking': 'normal'},
    'colors':[colors.REM_MISSED, colors.RECOVERED_CATCHUP],
    'legend':{'enabled':true, 'vAlign':'center', 'hAlign': 'left'},
    'menu':[{chart:'normal', title:'Normal Chart'},
        {chart: 'percent', title:'Percent Chart'}]
};

// The first row of one campaign
CatchupChartsSetting['campaign_title'] = {'chartType':{'type':'html'}};

CatchupChartsSetting['info_box'] = {'chartType':{'type':'html'}};

CatchupChartsSetting['info_table'] = {'chartType':{'type':'html'}};

CatchupChartsSetting['map_data'] = {'chartType': {'type': 'raw'}};

// Pie charts Row
CatchupChartsSetting['recovered_all_type_1'] = {'colors':[colors.RECOVERED_CATCHUP, colors.REM_MISSED],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_absent_1'] = {'colors':[colors.RECOVERED_CATCHUP, colors.REM_ABSENT],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_nss_1'] = {'colors':[colors.RECOVERED_CATCHUP, colors.REM_NSS],
    'chartType':{'type':'halfpie'}, 'legend':true
};
CatchupChartsSetting['recovered_refusal_1'] = {'colors':[colors.RECOVERED_CATCHUP, colors.REM_REFUSAL],
    'chartType':{'type':'halfpie'}, 'legend':true
};


// 10 campaign vaccinated children column chartType
CatchupChartsSetting['vac_child_trend'] = {'chartType':{'type':"column"}, 'colors':[colors.VACCINATED]};
// 10 campaign missed children column chartType
CatchupChartsSetting['missed_child_trend'] = {'chartType':{'type':"column"}, 'colors':[colors.REM_MISSED]};
// 10 campaign missed by type stack column chartType
CatchupChartsSetting['missed_by_type_trend'] = {
    'chartType':{'type':"column", 'stacking':'normal'},
    'colors':[colors.REM_ABSENT, colors.REM_NSS, colors.REM_REFUSAL],
    'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign absent percent stack chartType
CatchupChartsSetting['absent_recovered_trend'] = {
        'colors': [colors.REM_ABSENT, colors.RECOVERED_CATCHUP],
        'chartType':{'type':"column", 'stacking':'percent'},
        'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign nss percent stack chartType
CatchupChartsSetting['nss_recovered_trend'] = {
        'colors': [colors.REM_NSS, colors.RECOVERED_CATCHUP],
        'chartType':{'type':"column", 'stacking':'percent'},
        'menu':[{chart:'percent', title:'Percent Chart'},
            {chart: 'normal', title:'Normal Chart'}]
};
// 10 Campaign refusal percent stack chartType
CatchupChartsSetting['refusal_recovered_trend'] = {
    'colors': [colors.REM_REFUSAL, colors.RECOVERED_CATCHUP],
    'chartType':{'type':"column", 'stacking':'percent'},
    'menu':[{chart:'percent', title:'Percent Chart'},
        {chart: 'normal', title:'Normal Chart'}]
};

// 10 campaign missed recovery area percent chartType
CatchupChartsSetting['missed_child_recovery_trend'] = {
    'colors': [colors.REM_MISSED, colors.RECOVERED_ABSENT, colors.RECOVERED_NSS, colors.RECOVERED_REFUSAL],
    'chartType':{'type':"area", 'stacking':'percent'}};





export default CatchupChartsSetting;