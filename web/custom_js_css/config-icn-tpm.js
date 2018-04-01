$(function () {

    $('.loading').hide();

    $('#tbl-odk-data').dataTable({
    });

    $('#filterButton').click(function () {
        loadData(false);
    });

    $('#filterCampaign').change(function () {
        var campaigns = $(this).val();

        if(campaigns.length > 1) {
            $('.btn-cum-res').show();
        } else
            $('.btn-cum-res').hide();

    });

    $('.btn-cum-res').click(function () {
        loadData(true);
    });

    $('.btn-cum-res').hide();
});

function loadData(cumulative) {

    var campaigns = $('#filterCampaign').val();

    var region = $('#filterRegion').val();

    var provinces = $('#filterProvince').val();

    var districts = $('#filterDistrict').val();

    var clusters = $('#filterCluster').val();

    // check if a user didn't select anything, then return
    if(campaigns.length === 0 || campaigns === undefined) {
        window.alert("Please select at least one month");
        return;
    }

    //$('.btn-cum-res').hide();
    if(campaigns.length > 1) {
        $('.btn-cum-res').show();
    } else
        $('.btn-cum-res').hide();

    var data = {'campaign':campaigns, 'region':region, 'cluster':clusters,
        'province': provinces, 'district': districts, 'cumulative': cumulative};

    $('.loading, .loading-top').show();

    // data-route should be set somewhere in page
    // in an element which class should be route-url
    var route = $('.route-url').data('route');
    $.ajax({
        url: Routing.generate(route),
        data: data,
        type: 'GET',
        success: function (data) {
            // receiving html table
            $('#icn-table').html(data);
            $('#tbl-odk-data').dataTable({
                "scrollX": true,
                "pageLength": 15
            });
            $('.loading, .loading-top').hide();

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert("Something went wrong, while loading charts data, please try again later\n"+errorThrown);
        },
        cache: false
    });

}