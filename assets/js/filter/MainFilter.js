import $ from "jquery";
import Filter from "./Filter";
import Routing from "../common/Routing";

class MainFilter {
    constructor() {

        let defaultDistrict =
            [
                {label: 'VHR districts', value: 'VHR'},
                {label: 'HR districts', value: 'HR'},
                {label: 'Non-V/HR districts', value: 'None'}
            ];

        $('#filterCampaign').multiselect({
            nonSelectedText: 'Campaigns',
            numberDisplayed: 2,
            maxHeight: 250,
        });

        $('#filterRegion').multiselect({
            nonSelectedText: 'Region',
            includeSelectAllOption: true,
            onSelectAll: function() {
                Filter.resetFilter('filterDistrict', defaultDistrict);
                var region = $('#filterRegion option:selected');
                var selectedRegions = [];
                $(region).each(function (index, region) {
                    selectedRegions.push([$(this).val()]);
                });

                if(selectedRegions.length > 0) {
                    var data = {"region": selectedRegions};
                    Filter.ajaxRequest('filter_province', data, 'filterProvince');
                }
            },
            onDeselectAll: function () {
                Filter.resetFilter('filterDistrict');
                Filter.resetFilter('filterProvince');
            },
            onChange: function(element, checked) {
                //console.log(element[0].value);
                Filter.resetFilter('filterDistrict', defaultDistrict);
                var region = $('#filterRegion option:selected');
                var selectedRegions = [];
                $(region).each(function (index, region) {
                    selectedRegions.push([$(this).val()]);
                });

                if(selectedRegions.length > 0) {
                    var data = {"region": selectedRegions};
                    Filter.ajaxRequest('filter_province', data, 'filterProvince');
                }
            }
        });

        $('#filterProvince').multiselect({
            nonSelectedText: 'Province',
            numberDisplayed: 2,
            maxHeight: 300,
            enableClickableOptGroups: true,
            onChange: function(element, checked) {
                Filter.resetFilter('filterDistrict', defaultDistrict);
                var province = $('#filterProvince option:selected');
                var selectedProvinces = [];
                $(province).each(function (index, province) {
                    selectedProvinces.push([$(this).val()]);
                });
                if(selectedProvinces.length > 0) {
                    var data = {"province": selectedProvinces, 'campaign':$('#filterCampaign').val()};
                    Filter.ajaxRequest('filter_district', data, 'filterDistrict');

                }
            }
        });

        $('#filterDistrict').multiselect({
            nonSelectedText: 'District',
            numberDisplayed: 2,
            maxHeight: 300,
            enableCaseInsensitiveFiltering: true,
            enableClickableOptGroups: true,
            onChange: function (element, checked) {
                var selectedDistricts = [];
                var district = $('#filterDistrict option:selected');
                //console.log(district);
                //if(district.indexOf('VHR')>-1 || district.indexOf('HR')>-1 || district.indexOf(null)>-1) {
                $(district).each(function (index, district) {
                    selectedDistricts.push([$(this).val()]);
                });

                var districts = selectedDistricts.join(',');
                districts = districts.split(',');
                if(districts.length > 1) {
                    if(districts.indexOf('VHR')>-1 || districts.indexOf('HR')>-1 ||
                        districts.indexOf('Non-V/HR districts')>-1) {
                        $.each(districts, function (index, value) {
                            if (value !== 'VHR' || value !== 'Non-V/HR districts' || value !== 'HR') {
                                $('#filterDistrict').multiselect('deselect', value, true);
                            }
                        })
                    }
                }

                let route = $('.btn-cluster').data('route');
                if(selectedDistricts.length === 1) {
                    var districtId = $('#filterDistrict').val();
                    if(districtId[0].indexOf("HR") == -1) {
                        var url = Routing.generate(route, {'district': districtId})
                        $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> Clusters' data for " +
                            $('#filterDistrict option:selected').attr('label'));
                        $('.btn-cluster').attr("href", url);
                    }
                } else {
                    $('.btn-cluster').html("<i class='fa fa-mail-forward'></i> For clusters' data click here!");
                    $('.btn-cluster').attr("href", Routing.generate(route));
                }

            }
        });
    }
}

export default MainFilter;