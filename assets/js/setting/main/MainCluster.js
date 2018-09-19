'use strict';
// ======================================= Clusters Level Dashbaord ===============================
const MainCluster = {};
MainCluster['missed_recovery_chart_1'] = {
    'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
    'chartType':{'type':"bar", 'stacking':'percent'},
    'large':'height'
};
// Table
MainCluster['cluster_trend'] = {'chartType':{'type':'table'},
    'setting' : {
        "scrollX": true,
        'paging':false,
        'dom': 'frtipB',
        'buttons': [
            'copyHtml5', 'csvHtml5'
        ]
    }
};

export default MainCluster;