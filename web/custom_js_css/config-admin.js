/**
 * Created by wakhan on 3/1/2018.
 */
$(function () {

    const config = new Array();

    // The first row of one campaign
    config['remaining_by_reason'] = {'color':['#FFFF00', '#C99900', '#FF0000']};
    config['remaining_all'] = {'chart':{'type':"column"}, 'color':['#FFB32D']};
    // Pie charts Row
    config['recovered_all'] = {'color':['#048AFF', '#40C97A', '#FFB32D']};
    config['recovered_absent'] = {'color':['#048AFF', '#40C97A', '#FFFF00']};
    config['recovered_nss'] = {'color':['#048AFF', '#40C97A', '#9C800E']};
    config['recovered_refusal'] = {'color':['#048AFF', '#40C97A', '#FF0000']};


    // 10 campaign vaccinated children column chart
    config['vac_child_10camp'] = {'chart':{'type':"column"}, 'color':['#048AFF']};
    // 10 campaign missed children column chart
    config['missed_10camp'] = {'chart':{'type':"column"}, 'color':['#C99900']};
    // 10 campaign missed by type stack column chart
    config['missed_type_10camp'] = {'chart':{'type':"column", 'stacking':'normal'}, 'color':['#FFFF00', '#C99900', '#FF0000']};
    // 10 Campaign absent percent stack chart
    config['ten_camp_absent'] = {'color': ['#EAFF19', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // 10 Campaign nss percent stack chart
    config['ten_camp_nss'] = {'color': ['#9C800E', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // 10 Campaign refusal percent stack chart
    config['ten_camp_refusal'] = {'color': ['#FF0000', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}};

    // 10 campaign missed recovery area percent chart
    config['recovered_10camp'] = {'color': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
                                  'chart':{'type':"area", 'stacking':'percent'}};

    // control the cluster link
    $('#filterDistrict').change(function () {
        //$('.btn-cluster').hide();
        var districts = $('#filterDistrict option:selected');

        var selectedDistricts = [];
        $(districts).each(function (index, districts) {

            selectedDistricts.push([$(districts).val()]);
        });

        if(selectedDistricts.length === 1) {
            var districtId = $('#filterDistrict').val();
            //console.log(districtId[0].indexOf("HR"));
            if(districtId[0].indexOf("HR") == -1) {
                var url = Routing.generate('cluster_admin_data', {'district': districtId})
                $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> Clusters' data for " +
                                                 $('#filterDistrict option:selected').attr('label'));
                $('.btn-cluster').attr("href", url);
                //$('.btn-cluster').show();
            }
        } else {
            $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> For clusters' data click here!");
            $('.btn-cluster').attr("href", Routing.generate('cluster_admin_data'));
        }
    })
    // first time load
    loadData(config);

    // filter button click
    $('#filterButton').click(function () {
        loadData(config);
    });
});

function loadData(config) {

    var campaigns = $('#filterCampaign').val();

    var region = $('#filterRegion').val();

    var provinces = $('#filterProvince').val();

    var districts = $('#filterDistrict').val();

    // check if a user didn't select anything, then return
    if(campaigns.length === 0 || campaigns === undefined) {
        window.alert("Please select at least one campaign");
        return;
    }

    var data = {'campaign':campaigns, 'region':region, 'province': provinces, 'district': districts};

    $('.loading, .loading-top').show();


    $.ajax({
        url: Routing.generate('ajax_filter_admin_data'),
        data: data,
        type: 'POST',
        success: function (data) {
            //console.log(data);
            var jsonData = JSON.parse(data);

            // set new title for the row
            $('.campaign-title').html(jsonData.campaign + " Campaign Data");

            // set the new info
            $('.info-row').html(jsonData.info);
            // set new table
            $('.table-campaign-info').html(jsonData.table.table);

            myPieChartWrapper('chart-remaining_by_reason', JSON.stringify(jsonData.lastCampPieData), null,
                                                config['remaining_by_reason'].color, null, 'donut', 'small');
            myChartWrapper({'type':"column"}, "chart-remaining_all", JSON.stringify(jsonData.lastCampVacData),
                {'yTitle':null, 'xTitle':null}, {'enabled':false}, config['remaining_all'].color);

            // Second Row of the last campaign charts
            myPieChartWrapper('chart-recovered_all', JSON.stringify(jsonData.recoveredAll), null,
                config['recovered_all'].color, null, 'halfpie', 'small');
            myPieChartWrapper('chart-recovered_absent', JSON.stringify(jsonData.recoveredAbsent), null,
                config['recovered_absent'].color, null, 'halfpie', 'small');
            myPieChartWrapper('chart-recovered_nss', JSON.stringify(jsonData.recoveredNSS), null,
                config['recovered_nss'].color, null, 'halfpie', 'small');
            myPieChartWrapper('chart-recovered_refusal', JSON.stringify(jsonData.recoveredRefusal), null,
                config['recovered_refusal'].color, null, 'halfpie', 'small');

            // Last 10 campaign trends
            myChartWrapper({'type':"column"}, "chart-vac_child_10camp", JSON.stringify(jsonData.chartVacChild10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['vac_child_10camp'].color);
            myChartWrapper({'type':"column"}, "chart-missed_10camp", JSON.stringify(jsonData.chartMissed10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['missed_10camp'].color);
            myChartWrapper({'type':"column", 'stacking':'normal'}, "chart-missed_type_10camp", JSON.stringify(jsonData.chartMissedType10camp),
                {'yTitle':null, 'xTitle':null}, null, config['missed_type_10camp'].color);
            // by reason row for 10 campaign
            myChartWrapper(config['ten_camp_absent'].chart, "chart-ten_camp_absent",  JSON.stringify(jsonData.chartAbsentRec10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_absent'].color);
            myChartWrapper(config['ten_camp_nss'].chart, "chart-ten_camp_nss",  JSON.stringify(jsonData.chartNSSRec10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_nss'].color);
            myChartWrapper(config['ten_camp_refusal'].chart, "chart-ten_camp_refusal",  JSON.stringify(jsonData.chartRefusalRec10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_refusal'].color);
            // area chart
            myChartWrapper(config['recovered_10camp'].chart, "chart-recovered_10camp", JSON.stringify(jsonData.last10CampRecovered),
                {'yTitle':null, 'xTitle':null}, null,
                config['recovered_10camp'].color);

            $('.loading, .loading-top').hide();
        },
        cache: false
    });

}