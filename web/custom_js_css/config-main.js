/**
 * Created by wakhan on 2/28/2018.
 */
$(function () {

    $('.loading, .loading-top').show();

    const config = new Array();
    // Ten campaign vaccinated children
    config['ten_camp_vac'] = {'color':['#048AFF', '#43AB0D'], 'chart':{'type':"column", 'stacking':'normal'}};
    // Ten campaign missed children
    config['ten_camp_missed'] = {'color':['#C99900', '#FFB32D'], 'chart':{'type':"column"} };
    // Ten campaign missed by type percent chart
    config['ten_camp_missed_type'] = {'color': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // Ten campaign absent children percent chart
    config['ten_camp_absent'] = {'color': ['#B7B3BE', '#EAFF19', '#2DA810', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // Ten campaign nss children percent chart
    config['ten_camp_nss'] = {'color': ['#B7B3BE', '#9C800E', '#2DA810', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // Ten campaign refusal children percent chart
    config['ten_camp_refusal'] = {'color': ['#B7B3BE', '#FF0000', '#2DA810', '#45E490', '#048AFF'],
        'chart':{'type':"column", 'stacking':'percent'}
    };
    // Ten campaign missed recovery area chart
    config['ten_camp_missed_vac'] = {'color': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
        'chart':{'type':"area", 'stacking':'percent'}
    };

    // ------------------- PIE chart One campaign --------------------------------------
    // All Type Missed
    config['recovered_all'] = {'color': ['#048AFF', '#45E490', '#2DA810', '#FFB32D', '#B7B3BE']};
    // Absent
    config['recovered_absent'] = {'color': ['#048AFF', '#45E490', '#2DA810', '#FFFF00', '#B7B3BE']};
    // NSS
    config['recovered_nss'] = {'color': ['#048AFF', '#45E490', '#2DA810', '#9C800E','#B7B3BE']};
    // Refusal
    config['recovered_refusal'] = {'color': ['#048AFF', '#45E490', '#2DA810', '#FF0000','#B7B3BE']};


    // Change the cluster level link as per district selection
    $('#filterDistrict').change(function () {
        //$('.btn-cluster').hide();
        var districts = $('#filterDistrict option:selected');

        var selectedDistricts = [];
        $(districts).each(function (index, districts) {

            selectedDistricts.push([$(districts).val()]);
        });

        if(selectedDistricts.length === 1) {
            var districtId = $('#filterDistrict').val();
            if(districtId[0].indexOf("HR") == -1) {
                var url = Routing.generate('cluster_main', {'district': districtId})
                $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> Clusters' data for " +
                    $('#filterDistrict option:selected').attr('label'));
                $('.btn-cluster').attr("href", url);
            }
        } else {
            $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> For clusters' data click here!");
            $('.btn-cluster').attr("href", Routing.generate('cluster_main'));
        }
    })

    // load charts for the first time

    loadData(config);

    // When filter button is clicked
    $('#filterButton').click(function () {
        loadData(config);
    })
});

function loadData(config) {
    var campaigns = $('#filterCampaign').val();

    var region = $('#filterRegion').val();

    var provinces = $('#filterProvince').val();

    var districts = $('#filterDistrict').val();

    // check if a user didn't select anything, then return
    if(campaigns === null || campaigns === undefined) {
        window.alert("Please select at least one campaign");
        return;
    }

    var data = {'campaign':campaigns,
                'region':region,
                'province': provinces,
                'district': districts
                };

    $('.loading, .loading-top').show();

    $.ajax({
        url: Routing.generate('ajax_filter_main'),
        data: data,
        type: 'POST',
        success: function (data) {
            //console.log(data);
            var jsonData = JSON.parse(data);
            // set new title for the row
            var title = jsonData.campaign === 0?"No data for the selected campaign":jsonData.campaign+" Camapign Data";
            $('.campaign-title').html(title);

            // Second Row of the last campaign charts
            myPieChartWrapper('chart-recovered_all', JSON.stringify(jsonData.recoveredAll), true,
                config['recovered_all'].color, null, 'halfpie');
            myPieChartWrapper('chart-recovered_absent', JSON.stringify(jsonData.recoveredAbsent), true,
                config['recovered_absent'].color, null, 'halfpie');
            myPieChartWrapper('chart-recovered_nss', JSON.stringify(jsonData.recoveredNSS), true,
                config['recovered_nss'].color, null, 'halfpie');
            myPieChartWrapper('chart-recovered_refusal', JSON.stringify(jsonData.recoveredRefusal), true,
                config['recovered_refusal'].color, null, 'halfpie');

            // Last 10 campaign trends
            myChartWrapper(config['ten_camp_vac'].chart, "chart-ten_camp_vac", JSON.stringify(jsonData.chartVacChild10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_vac'].color);
            myChartWrapper(config['ten_camp_missed'].chart, "chart-ten_camp_missed",  JSON.stringify(jsonData.chartMissed10Camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_missed'].color);
            myChartWrapper(config['ten_camp_missed_type'].chart, "chart-ten_camp_missed_type", JSON.stringify(jsonData.chartMissedType10camp),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_missed_type'].color);
            // last 10 campaign stack charts
            myChartWrapper(config['ten_camp_absent'].chart, "chart-ten_camp_absent", JSON.stringify(jsonData.tenCampAbsent),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_absent'].color);
            myChartWrapper(config['ten_camp_nss'].chart, "chart-ten_camp_nss", JSON.stringify(jsonData.tenCampNSS),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_nss'].color);
            myChartWrapper(config['ten_camp_refusal'].chart, "chart-ten_camp_refusal", JSON.stringify(jsonData.tenCampRefusal),
                {'yTitle':null, 'xTitle':null}, null, config['ten_camp_refusal'].color);
            // last 10 campaign area chart
            myChartWrapper(config['ten_camp_missed_vac'].chart, "chart-ten_camp_missed_vac", JSON.stringify(jsonData.last10CampRecovered),
                {'yTitle':null, 'xTitle':null}, null,
                config['ten_camp_missed_vac'].color);

            $('.loading, .loading-top').hide();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert("Something went wrong, while loading charts data, please try again later\n"+errorThrown);
            $('.loading, .loading-top').hide();
        },
        cache: false
    });

}
