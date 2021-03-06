/**
 * Created by wakhan on 2/28/2018.
 */
$(function () {

    $('.loading, .loading-top').show();

    const config = new Array();
    // One campaign recovery bar chart
    config['missed_recovery'] = {'color': ['#FFFF00', '#C99900', '#FF0000', '#048AFF'],
        'chart':{'type':"bar", 'stacking':'percent'}
    };

    // Filter Heatmap
    $(".filter-heatmap").click(function (event){
        event.preventDefault();
        var target = $(this).data('url');
        var targetCont = "chart-"+ target;
        $('.'+target).show();
        var type = $(this).text();
        if(type.trim() == "Absent")
        {
            makeAjaxRequest('normal', config, 'RemAbsent');
        }
        else if(type.trim() == "NSS")
            makeAjaxRequest('normal', config, 'RemNSS');

        else if(type.trim() == "Refusal")
            makeAjaxRequest('normal', config, 'RemRefusal');
           // myHeatMap($("#hidden-total-refusal").val(), 'chart-cluster_heatmap', 'refusal');
        else if(type.trim() == "All Type")
            makeAjaxRequest('normal', config, 'TotalRemaining');


        $('.'+target).hide();
    });

    // load it for first time
    var selectedClusters = $('#filterCluster').val();
    if(selectedClusters.length > 0) {
        makeAjaxRequest('main', config);
        makeAjaxRequest('normal', config);
    }
    // filter heatmap as per type (number/percent)
    $('.filter-heatmap-type').click(function (event) {
        event.preventDefault();
        var calcType = $(this).data('type');
        makeAjaxRequest(calcType);
    });

    // When filter button is clicked
    $('#filterButton').click(function () {
        // first time call (main),
        // only update the one campaign chart
        makeAjaxRequest('main', config);
        // another call to load the table
        makeAjaxRequest('normal', config);
    })
});

function makeAjaxRequest(calcType, config, selectType) {

    var selectedCampaigns = $('#filterCampaign').val();
    //var provinces = $('#filterProvince option:selected');
    var selectedProvinces = $('#filterProvince').val();
    //var districts = $('#filterDistrict option:selected');
    var selectedDistricts = $('#filterDistrict').val();
    //var clusters = $('#filterCluster option:selected');
    var selectedClusters = $('#filterCluster').val();

    if(selectedDistricts === null || selectedDistricts === undefined) {
        window.alert("Please select a district");
        return;
    }

    if(selectedClusters.length === 0 || selectedClusters === undefined) {
        window.alert("No cluster selected, or there is no data for any cluster for the selected campaigns");
        return;
    }

    var data = {'campaign':selectedCampaigns, 'province': selectedProvinces,
        'selectType': selectType === undefined ? 'TotalRemaining':selectType,
        'district': selectedDistricts, 'cluster':selectedClusters, 'calcType': calcType};

    $('.loading, .loading-top').toggle();

    $.ajax({

        url: Routing.generate('ajax_cluster_admin_data'),
        data: data,
        type: 'POST',
        success: function (data) {

            if(calcType === 'main') {
                var jsonData = JSON.parse(data);
                myChartWrapper({
                        'type': "bar",
                        'stacking': 'percent'
                    }, "chart-missed_recovery", JSON.stringify(jsonData.lastCampBarChart),
                    {'yTitle': null, 'xTitle': null}, null, config['missed_recovery'].color, null, 'height');
            } else {
                $('#chart-cluster_heatmap').html(data);
                $('#tbl-data').dataTable({
                    "scrollX": true,
                    'paging':false,
                    'dom': 'frtipB',
                    'buttons': [
                        'copy', 'csv', 'excel', 'print'
                    ]
                });
            }

            $('.loading, .loading-top').hide();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert("Something went wrong, while loading charts data, please try again later\n"+errorThrown);
            $('.loading, .loading-top').hide();
        },
        cache: false
    });

}
