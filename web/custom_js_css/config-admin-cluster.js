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
            myHeatMap($("#hidden-total-absent").val(), 'chart-cluster_heatmap', 'absent');
        }
        else if(type.trim() == "NSS")
            myHeatMap($("#hidden-total-nss").val(), 'chart-cluster_heatmap', 'NSS');

        else if(type.trim() == "Refusal")
            myHeatMap($("#hidden-total-refusal").val(), 'chart-cluster_heatmap', 'refusal');
        else if(type.trim() == "All Type")
            myHeatMap($("#hidden-total-remaining").val(), 'chart-cluster_heatmap', 'Total Remaining');


        $('.'+target).hide();
    });

    // load it for first time
    var selectedClusters = $('#filterCluster').val();
    if(selectedClusters.length > 0)
        makeAjaxRequest(null, config);
    // filter heatmap as per type (number/percent)
    $('.filter-heatmap-type').click(function (event) {
        event.preventDefault();
        var calcType = $(this).data('type');
        makeAjaxRequest(calcType);
    });

    // When filter button is clicked
    $('#filterButton').click(function () {
        makeAjaxRequest(null, config);
    })
});

function makeAjaxRequest(calcType, config) {

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
        'district': selectedDistricts, 'cluster':selectedClusters, 'calcType': calcType};

    $('.loading, .loading-top').show();

    $.ajax({

        url: Routing.generate('ajax_cluster_admin_data'),
        data: data,
        type: 'POST',
        success: function (data) {
            //console.log(data);
            $("#hidden-total-absent").val('');
            $("#hidden-total-remaining").val('');
            $("#hidden-total-refusal").val('');
            $("#hidden-total-nss").val('');
            var jsonData = JSON.parse(data);
            if(calcType === null || calcType === undefined) {
                myChartWrapper({
                        'type': "bar",
                        'stacking': 'percent'
                    }, "chart-missed_recovery", JSON.stringify(jsonData.lastCampBarChart),
                    {'yTitle': null, 'xTitle': null}, null, config['missed_recovery'].color, null, 'height');
            }

            $("#hidden-total-absent").val(JSON.stringify(jsonData.heatMapTotalAbsent));
            $("#hidden-total-remaining").val(JSON.stringify(jsonData.heatMapTotalRemaining));
            $("#hidden-total-refusal").val(JSON.stringify(jsonData.heatMapTotalRefusal));
            $("#hidden-total-nss").val(JSON.stringify(jsonData.heatMapTotalNSS));

            myHeatMap(JSON.stringify(jsonData.heatMapTotalRemaining), 'chart-cluster_heatmap');


            $('.loading, .loading-top').hide();
        },
        cache: false
    });

}
