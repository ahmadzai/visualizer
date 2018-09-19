import _ from 'underscore';
import Maps from './Maps';
import {CatchupMaps, CoverageMaps, MainMaps} from '../setting/';


class FilterMap {

    regionMapper = {
        'CR': [1, 2, 3, 4, 5, 8, 10, 22],
        'ER': [6, 7, 13, 14],
        'SR': [23, 24, 32, 33, 34],
        'SER': [11, 12, 25, 26],
        'NR': [18, 19, 20, 27, 28],
        'NER': [9, 15, 16, 17],
        'WR': [21, 29, 30, 31]
    };


    createMap = (mapType, indicator, source = "main") => {
        let data = this.getMapData();
        // to control the geo json to be loaded
        let geoType = mapType === "district"?'district':'province';
        // load the geoData based on the geoType
        let geoData = this.getGeoData(geoType);

        // if the requested map was for region
        if(mapType === "region") {
            data = this._regionData(indicator,  data);
        } else
            data = this._prepareData(geoType, indicator, data);
        // joinBy which is required for mapping data to geodata
        let joinBy = geoType==="district"?'dcode':'pcode';
        new Maps().createMap(
            this._prepareMapOptions(
                {geoData, data},
                {indicator, joinBy, mapType, source}
            )
        );


    };

    setMapData = (data) => {

        data = JSON.parse(data);
        this.data = data;
        console.log(data);

    };

    getMapData = () => { return this.data; };

    setGeoData = (data, source) => {
        if (source === 'district')
            this.district = data;
        else
            this.province = data;
    };

    getGeoData = (source) => {
        return source === 'district' ?
            this.district :
            this.province;
    };

    _regionData = (indicator, tmpData) => {

        let self = this;

        // find what regions are in the data
        let reg = _.map(tmpData, function (item) {
            return item.Region;
        });

        // extract only the keys from the regionMapper
        let regKeys = _.keys(this.regionMapper);

        // find the common regions
        let regions = _.intersection(regKeys, reg);
        // extract selected regions (with provinces) from the mapper
        let provinces = [];
        $.each(regions, function (key, value) {
            provinces[value] = self.regionMapper[value];

        });

        // generate the data to all provinces of the selected regions
        let modifiedData = [];
        // loop through selected regions
        for(let reg in provinces) {
            // loop through data
            $.each(tmpData, function (key, item) {

                if(item.Region === reg) {
                    // copy the same data for all provinces in one region
                    for(let index in provinces[reg]) {
                        let prov = provinces[reg];
                        // add new index PCODE in the data
                        modifiedData.push({pcode: prov[index], value:item[indicator]});
                    }
                }
            });
        }
        // return new data
        return modifiedData;

    };

    _prepareData(mapType, indicator, tmpData) {
        let modifiedData = [];
        $.each(tmpData, function (key, item) {
            let pdcode = mapType === "district" ? {dcode:item.DCODE} : {pcode:item.PCODE};
            modifiedData.push({...pdcode, value:item[indicator]});
        });
        return modifiedData;
    };

    _prepareMapOptions= (mapData, vars) => {
        let source = vars.source;
        // see which setting should be loaded
        let mapPage = MainMaps;
        if(source === "coverage_data")
            mapPage = CoverageMaps;
        else if(source === "catchup_data")
            mapPage = CatchupMaps;
        let setting = mapPage[vars.mapType];

        return {
            data: {
                geoJson: mapData.geoData,
                data: mapData.data,
                keys: [vars.joinBy, 'value'],
                joinBy: vars.joinBy,
                name: vars.indicator,
                dataLabel: "{point.properties.name}",
                classes: setting[vars.indicator].classes

            },
            title: "Map of the " + mapPage[vars.indicator].title + " Children",
            colors: mapPage[vars.indicator].colors,
            legend: {title:mapPage[vars.indicator].name},
            renderTo: 'map_container_1'


        };

    }




}

export default FilterMap;