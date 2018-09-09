'use strict';
// ======================================= Clusters Level Dashbaord ===============================
const CoverageCluster = {};
CoverageCluster['missed_recovery_chart_1'] = {
    'colors': ['#FFFF00', '#C99900', '#FF0000', '#048AFF'],
    'chartType':{'type':"bar", 'stacking':'percent'},
    'large':'height'
};
// Table
CoverageCluster['cluster_trend'] = {'chartType':{'type':'table'},
    'setting' : {
        "scrollX": true,
        'paging':false,
        'dom': 'frtipB',
        'buttons': [
            'copyHtml5', 'csvHtml5'
        ]
    }
};

export default CoverageCluster;