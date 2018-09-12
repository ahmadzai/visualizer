webpackJsonp([3],{

/***/ "./assets/js/ccs_sm_filter.js":
/*!************************************!*\
  !*** ./assets/js/ccs_sm_filter.js ***!
  \************************************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__filter_OdkFilter__ = __webpack_require__(/*! ./filter/OdkFilter */ "./assets/js/filter/OdkFilter.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__common_AjaxRequest__ = __webpack_require__(/*! ./common/AjaxRequest */ "./assets/js/common/AjaxRequest.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__setting___ = __webpack_require__(/*! ./setting/ */ "./assets/js/setting/index.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__common_Alerts__ = __webpack_require__(/*! ./common/Alerts */ "./assets/js/common/Alerts.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__filter_FilterListener__ = __webpack_require__(/*! ./filter/FilterListener */ "./assets/js/filter/FilterListener.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__filter_FilterControl__ = __webpack_require__(/*! ./filter/FilterControl */ "./assets/js/filter/FilterControl.js");


var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };









__WEBPACK_IMPORTED_MODULE_0_jquery___default()(function () {

    new __WEBPACK_IMPORTED_MODULE_1__filter_OdkFilter__["a" /* default */](); // initialize filter

    var listener = new __WEBPACK_IMPORTED_MODULE_5__filter_FilterListener__["a" /* default */]();
    var filterControl = new __WEBPACK_IMPORTED_MODULE_6__filter_FilterControl__["a" /* default */]();

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.loading').hide();

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.btn-cum-res').hide();

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#tbl-odk-data').DataTable({
        "lengthMenu": [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
        'scrollX': true
    });

    filterControl.setFilterState(listener.listenCcsSm()); // store the state of the filter

    // Filter Heatmap only table
    __WEBPACK_IMPORTED_MODULE_0_jquery___default()(".btn-cum-res").click(function (event) {
        event.preventDefault();
        doAjax(true, filterControl, listener);
    });

    // When filter button is clicked
    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterButton').click(function (event) {
        event.preventDefault();
        doAjax(false, filterControl, listener);
    });
});

function doAjax(cumulative, filterControl, listener) {

    var url = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.route-url').data('route'); // create url
    var apiCall = new __WEBPACK_IMPORTED_MODULE_2__common_AjaxRequest__["a" /* default */](); // create an object of ajax calls
    var filterData = listener.listenCcsSm();

    if (filterData.campaign.length === 0) {
        __WEBPACK_IMPORTED_MODULE_4__common_Alerts__["a" /* default */].error("Please select at least one month");
        return false;
    }

    if (cumulative) {
        filterData = _extends({}, filterData, { cumulative: true });
    }

    var filterState = filterControl.ccsSmFilterState(filterData);

    if (filterState === true) {
        apiCall.partiallyUpdate(url, __WEBPACK_IMPORTED_MODULE_3__setting___["b" /* CcsSmSetting */], filterData, 'loading');
    } else if (filterState === false) __WEBPACK_IMPORTED_MODULE_4__common_Alerts__["a" /* default */].filterInfo();
}

/***/ }),

/***/ "./assets/js/charts/Chart.js":
/*!***********************************!*\
  !*** ./assets/js/charts/Chart.js ***!
  \***********************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_highcharts__ = __webpack_require__(/*! highcharts */ "./node_modules/highcharts/highcharts.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_highcharts___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_highcharts__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ChartFactory__ = __webpack_require__(/*! ./ChartFactory */ "./assets/js/charts/ChartFactory.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__TableHtml__ = __webpack_require__(/*! ./TableHtml */ "./assets/js/charts/TableHtml.js");


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }






var Chart = function () {
    // Just to initialize one Factory
    function Chart() {
        _classCallCheck(this, Chart);

        this.factory = new __WEBPACK_IMPORTED_MODULE_1__ChartFactory__["a" /* default */]();
        this.dTitles = { xTitle: null, yTitle: null };
        this.dColors = __WEBPACK_IMPORTED_MODULE_0_highcharts___default.a.getOptions().colors;
        this.dLegend = { enabled: true, position: { vAlign: 'bottom', hAlign: 'center' } };
    }

    /**
     * Switch Function To call Different Functions Based on the Chart Type
     * In this Case ChartType must be defined in the provided object
     * @param args
     */


    _createClass(Chart, [{
        key: 'visualize',
        value: function visualize(args) {
            var type = args.chartType.type;
            switch (type) {
                case "line":
                case "spline":
                    this.lineChart(args);
                    break;
                case "bar":
                case "column":
                case "area":
                    this.columnBarChart(args);
                    break;
                case "pie":
                case "donut":
                case "halfpie":
                    this.pieDonutChart(args);
                    break;
                case "table":
                case "html":
                    __WEBPACK_IMPORTED_MODULE_2__TableHtml__["a" /* default */].tableHtml(args);
                    break;
            }
        }
    }, {
        key: 'multiAxisChart',
        value: function multiAxisChart() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: [], titles: titles, indicators: indicators, colors: colors, chartType: chartType };

            args.titles = args.hasOwnProperty('titles') ? args.titles : ['Title1', 'Title2', 'Title3'];
            args.indicators = args.hasOwnProperty('indicators') ? args.indicators : ['Indicator1', 'Indicator1', 'Indicator1'];
            args.colors = args.hasOwnProperty('colors') ? args.colors : ['#048aff', '#43AB0D', '#F00000'];
            args.chartType = args.hasOwnProperty('chartType') ? args.chartType : { 'type': 'multi' };

            args['legend'] = {
                layout: 'vertical',
                align: 'left',
                vAlign: 'top',
                x: 80,
                y: 55,
                color: '#FFFFFF'
            };

            args['axises'] = [{
                format: '',
                color: args.colors[1],
                opposite: true,
                title: args.titles[1]

            }, {
                format: '',
                color: colors[0],
                opposite: false,
                title: args.args.titles[0],
                lineWidth: 1
            }, {
                format: ' %',
                color: args.colors[2],
                opposite: true,
                title: args.titles[2],
                lineWidth: 0
            }];

            args['yAxises'] = [{
                color: args.colors[0],
                indicator: args.indicators[0],
                type: 'column',
                tooltip: '',
                yAxis: 1
            }, {
                color: args.colors[1],
                yAxis: 0,
                indicator: args.indicators[1],
                type: 'spline',
                tooltip: ''

            }, {
                color: args.colors[2],
                indicator: args.indicators[2],
                yAxis: 2,
                type: 'spline',
                tooltip: ' %'
            }];

            this.factory.createChart(args);
        }

        /**
         * @param args
         */

    }, {
        key: 'columnBarChart',
        value: function columnBarChart() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: [], chartType: chartType, combination: combination, colors: colors,
                titles: titles, legend: legend, menu: menu, large: large, yAxisFormatter: yAxisFormatter };

            args.chartType = args.hasOwnProperty('chartType') ? args.chartType : { type: 'column' };
            args.combination = args.hasOwnProperty('combination') ? args.combination : [];
            args.colors = args.hasOwnProperty('colors') ? args.colors : this.dColors;
            args.titles = args.hasOwnProperty('titles') ? args.titles : this.dTitles;
            args.legend = args.hasOwnProperty('legend') ? args.legend : this.dLegend;
            args.yAxisFormatter = args.hasOwnProperty('yAxisFormatter') ? args.yAxisFormatter : "";

            this.factory.createChart(args);
        }

        /**
         *
         * @param args
         */

    }, {
        key: 'lineChart',
        value: function lineChart() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: [], chartType: chartType, colors: colors, titles: titles, legend: legend, menu: menu };

            args.chartType = args.hasOwnProperty('chartType') ? args.chartType : { type: 'line' };
            args.colors = args.hasOwnProperty('colors') ? args.colors : this.dColors;
            args.titles = args.hasOwnProperty('titles') ? args.titles : this.dTitles;
            args.legend = args.hasOwnProperty('legend') ? args.legend : this.dLegend;
            args.menu = args.hasOwnProperty('chartType') ? args.menu : [{ chart: 'spline', title: 'Spline Chart' }, { chart: 'line', title: 'Line Chart' }];

            this.factory.createChart(args);
        }

        /**
         * @param args
         */

    }, {
        key: 'pieDonutChart',
        value: function pieDonutChart() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: [], legend: legend, colors: colors, chartType: chartType, area: area, menu: menu };

            args.colors = args.hasOwnProperty('colors') ? args.colors : this.dColors;
            args.legend = args.hasOwnProperty('legend') ? args.legend : false;
            args.area = args.hasOwnProperty('area') ? args.area : 'large';
            args.chartType = args.hasOwnProperty('chartType') ? args.chartType : { type: 'pie' };

            this.factory.createChart(args);
        }
    }, {
        key: 'areaChart',
        value: function areaChart() {
            //Todo: call columnBarChart() instead with chartType{type:'area'}
            alert('This function is not implemented yet\n' + 'call columnBarChart() instead with chartType{type:\'area\'}');
        }
    }, {
        key: 'heatmapChart',
        value: function heatmapChart() {
            //Todo: implement heat map
            alert('This function is not implemented yet\n');
        }
    }]);

    return Chart;
}();

/* harmony default export */ __webpack_exports__["a"] = (Chart);

/***/ }),

/***/ "./assets/js/charts/ChartFactory.js":
/*!******************************************!*\
  !*** ./assets/js/charts/ChartFactory.js ***!
  \******************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_highcharts__ = __webpack_require__(/*! highcharts */ "./node_modules/highcharts/highcharts.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_highcharts___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_highcharts__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_highcharts_modules_exporting__ = __webpack_require__(/*! highcharts/modules/exporting */ "./node_modules/highcharts/modules/exporting.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_highcharts_modules_exporting___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_highcharts_modules_exporting__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_highcharts_grouped_categories_grouped_categories__ = __webpack_require__(/*! highcharts-grouped-categories/grouped-categories */ "./node_modules/highcharts-grouped-categories/grouped-categories.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_highcharts_grouped_categories_grouped_categories___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_highcharts_grouped_categories_grouped_categories__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ChartHelper__ = __webpack_require__(/*! ./ChartHelper */ "./assets/js/charts/ChartHelper.js");


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }






__WEBPACK_IMPORTED_MODULE_3_highcharts_grouped_categories_grouped_categories__(__WEBPACK_IMPORTED_MODULE_1_highcharts___default.a);
__WEBPACK_IMPORTED_MODULE_2_highcharts_modules_exporting__(__WEBPACK_IMPORTED_MODULE_1_highcharts___default.a);



var ChartFactory = function () {
    function ChartFactory() {
        _classCallCheck(this, ChartFactory);
    }

    _createClass(ChartFactory, [{
        key: 'createChart',


        /**
         * Public funcation to create any type of chart
         * @param settings
         */
        value: function createChart(settings) {

            var type = settings.chartType.type;
            // create the general options
            var options = new __WEBPACK_IMPORTED_MODULE_4__ChartHelper__["a" /* default */]();
            // Common properties sets here
            options.chart.renderTo = settings.renderTo;
            options.chart.type = settings.chartType.type;
            options.colors = settings.colors;
            options.credits = { enabled: false }; // disable the credits link
            // Add Legend To Chartf
            if (settings.legend !== false || ['pie', 'donut', 'halfpie'].indexOf(type) !== -1) {
                options['legend'] = {
                    enabled: settings.legend.hasOwnProperty('enabled') ? settings.legend.enabled : false,
                    align: settings.legend.hasOwnProperty('hAlign') ? settings.legend.position.hAlign : 'center',
                    verticalAlign: settings.legend.hasOwnProperty('vAlign') ? settings.legend.position.vAlign : 'bottom',
                    itemStyle: {
                        fontWeight: 'normal',
                        fontSize: '100%'
                    }
                };
            }
            // Add Menu Items If It was set
            if (settings.menu !== undefined) {
                var menu = settings.menu;
                for (var i = 0; i < menu.length; i++) {
                    var menuItem = this._myMenuItems('', menu[i].chart, settings.renderTo, menu[i].title);
                    options.exporting.buttons.contextButton.menuItems.push(menuItem);
                }
            }
            // Add Label Toggle Menu
            var toggleMenu = this._dataLabelToggleMenu(settings.renderTo);
            options.exporting.buttons.contextButton.menuItems.push(toggleMenu);

            var data = settings.data; //JSON.parse(settings.data);
            options['title'] = { text: data.title, style: { fontSize: '100%' } };
            options['subtitle'] = { text: data.subTitle, verticalAlign: 'bottom', style: { fontSize: '80%' } };
            // Set the data in the setting again
            settings.data = data;

            switch (type) {
                case "bar":
                case "column":
                case "area":
                    options = this._createColumnChart(options, settings);
                    break;
                case "pie":
                case "donut":
                case "halfpie":
                    options = this._createPieChart(options, settings);
                    break;
                case "line":
                    options = this._createLineChart(options, settings);
                    break;
                case "heatmap":
                    options = this._createHeatMap(options, settings);
                    break;
                case "multi":
                    options = this._createMultiAxisChart(options, settings);
                    break;

            }
            // create the chart
            __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.chart(options);
        }
        //private function to create pie chart

    }, {
        key: '_createPieChart',
        value: function _createPieChart(options, settings) {
            // set the type of chart, static in this case
            var chart = {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                renderTo: settings.renderTo
            };
            options.chart = chart;

            var plot = {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: settings.legend !== true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme && __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme.contrastTextColor || 'black',
                            fontSize: '90%'
                        }
                    },
                    showInLegend: settings.legend
                }
            };
            var type = settings.chartType.type;
            // if type was halfpie
            if (type === 'halfpie') {
                plot.pie.startAngle = -90;
                plot.pie.endAngle = 90;
                plot.pie.center = ['50%', '75%'];
                plot.pie.dataLabels.distance = -30;
                plot.pie.dataLabels.format = '<b>{point.percentage:.1f}%</b>';
                options.chart.margin = -20;
                if (settings.legend) {
                    //options.chart.marginRight = 60;
                    options.legend = {
                        itemDistance: 8,
                        padding: 0,
                        margin: 0,
                        // align: 'right',
                        // verticalAlign: 'top',
                        // layout: 'vertical',
                        // y: 50,
                        itemStyle: {
                            fontWeight: 'normal',
                            fontSize: '90%'

                        }
                    };
                }
            } else if (type === 'donut') {
                plot.pie.innerSize = 100;
                plot.pie.depth = 45;
            }
            // if area was small
            if (settings.area === 'small') {
                plot.pie.dataLabels.distance = -20;
                plot.pie.dataLabels.format = '<b>{point.name}</b>';
                options.chart.margin = 0;
                options.chart.marginTop = 15;
            }

            options['plotOptions'] = plot;

            options['tooltip'] = {
                pointFormat: '{point.y}<b> ({point.percentage:.1f}%)</b>'
            };

            var series = settings.data.series === null || settings.data.series === undefined ? [] : settings.data.series;
            // set the data/series

            if (type === 'halfpie') series[0].innerSize = '50%';
            options.series = series;

            return options;
        }

        //private function to create multi-axises chart

    }, {
        key: '_createMultiAxisChart',
        value: function _createMultiAxisChart(options, settings) {

            options.chart['zoomType'] = 'xy';
            // the tooltip are shared for now
            options['tooltip'] = { shared: true };

            // check for the legend and replace it
            /*
             legend = {
             layout: 'text',
             align: 'text',
             vAlign: 'text',
             x: int,
             y: int,
             color: 'color'
             }
             */
            if (settings.hasOwnProperty('legend')) {
                //console.log("we are here in the legend");
                options['legend'] = {
                    layout: settings.legend.layout,
                    align: settings.legend.align,
                    verticalAlign: settings.legend.vAlign,
                    x: settings.legend.x,
                    y: settings.legend.y,
                    floating: true,
                    backgroundColor: __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme && __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme.legendBackgroundColor || '#FFFFFF'

                };
            }

            // check for the yAxises, should be set as plural
            /*
             yAxises = {
             format:'text',
             color:'color',
             opposite:'boolean',
             title:'text',
             indicator:'text',
             yAxis:number (1, 2),
             type: 'text',
             tooltip: 'suffix',
             marker: 'boolean'
               }
             */
            if (settings.hasOwnProperty('axises')) {
                var axises = settings.axises;
                var axis = [];
                for (var i = 0; i < axises.length; i++) {
                    var tmpAxis = {
                        labels: {
                            format: '{value}' + axises[i].format,
                            style: { color: axises[i].color }
                        },
                        title: {
                            text: axises[i].title,
                            style: { color: axises[i].color }
                        },
                        opposite: axises[i].opposite,
                        gridLineWidth: axises[i].lineWidth

                    };
                    axis.push(tmpAxis);
                }
                options['yAxis'] = axis;
            }

            // set the data of the chart to what assigned from TWIG
            var dataObj = settings.data;
            // set the dynamic categories
            options.xAxis.categories = dataObj.categories;
            // set the data/series
            var dataSeries = dataObj.series;
            //console.log(dataSeries);
            //console.log('We are here above the if');
            if (settings.hasOwnProperty('yAxises')) {
                //console.log('We are here in the right location');
                var newSeries = settings.yAxises;

                var series = [];
                for (var _i = 0; _i < newSeries.length; _i++) {

                    for (var x = 0; x < dataSeries.length; x++) {
                        if (dataSeries[x].name.toLowerCase() == newSeries[_i].indicator.toLowerCase()) {
                            var tempSeries = {};
                            tempSeries['name'] = dataSeries[x].name;
                            tempSeries['type'] = newSeries[_i].type;
                            tempSeries['data'] = dataSeries[x].data;
                            tempSeries['yAxis'] = newSeries[_i].yAxis;
                            tempSeries['tooltip'] = { valueSuffix: newSeries[_i].tooltip };
                            tempSeries['color'] = newSeries[_i].color;
                            series.push(tempSeries);
                        }
                    }
                }
                options.series = series;
            }

            return options;
        }
    }, {
        key: '_createAreaChart',
        value: function _createAreaChart(options, settings) {}

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */

    }, {
        key: '_createHeatMap',
        value: function _createHeatMap(options, settings) {

            var dataObj = settings.data;
            var chart = {
                type: 'heatmap',
                marginBottom: 80,
                plotBorderWidth: 1,
                renderTo: settings.renderTo,
                marginTop: 80
            };

            options.chart = chart;
            options.title.style.fontSize = '110%';

            var xAxis = dataObj.xAxis === undefined || dataObj.xAxis === null ? [] : dataObj.xAxis;
            options.xAxis = [{
                categories: xAxis,

                labels: {
                    style: {
                        fontSize: '80%'
                    },
                    //autoRotation: [-30],
                    autoRotation: false
                }
            }, {
                linkedTo: 0,
                opposite: true,
                categories: xAxis,
                labels: {
                    style: {
                        fontSize: '80%'
                    },
                    //autoRotation: [30],
                    autoRotation: false
                }
            }];

            options.yAxis = {
                categories: dataObj.yAxis === undefined || dataObj.yAxis === null ? [] : dataObj.yAxis,
                title: null,
                labels: {
                    style: {
                        fontSize: '80%'
                    }
                }
            };
            options.labels = {
                style: {
                    fontSize: '80%'
                }
            };

            options.colorAxis = {
                min: dataObj.stops === undefined || dataObj.stops === null ? 5 : dataObj.stops.minValue,
                max: dataObj.stops === undefined || dataObj.stops === null ? 20 : dataObj.stops.maxValue,
                tickInterval: 1,
                startOnTick: false,
                endOnTick: false,
                stops: [[0, dataObj.stops === undefined || dataObj.stops === null ? "#43AB0D" : dataObj.stops.minColor], [dataObj.stops === undefined || dataObj.stops === null ? 0.5 : dataObj.stops.midStop, dataObj.stops === undefined || dataObj.stops === null ? "#ffd927" : dataObj.stops.midColor], [1, dataObj.stops === undefined || dataObj.stops === null ? "#FF0000" : dataObj.stops.maxColor]]
            };

            options.legend.enabled = false;

            options.tooltip = {
                formatter: function formatter() {
                    return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> <br>' + tooltipTitle + ' children <br><b>' + this.point.value + '</b> in cluster <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
                }
            };
            //console.log(dataObj.data);
            options.series = [{
                name: dataObj.title,
                borderWidth: 1,
                turboThreshold: Number.MAX_VALUE,
                data: dataObj.data,
                dataLabels: {
                    enabled: true,
                    color: '#000000'
                }
            }];
            // Setting width and height of the container according to rows and columns
            var height = dataObj.yAxis === undefined ? '29px' : dataObj.yAxis.length * 29 + "px";
            var width = dataObj.xAxis === undefined ? '50px' : dataObj.xAxis.length * 50 + "px";
            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + settings.renderTo).css({ "height": height, "min-width": width });

            return options;
        }

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */

    }, {
        key: '_createLineChart',
        value: function _createLineChart(options, settings) {

            var plot = {};
            plot[settings.chartType.type === 'line' ? 'series' : 'spline'] = {
                label: {
                    connectorAllowed: false
                }
            };

            options['plotOptions'] = plot;
            // set the yAxis title
            options.yAxis.title.text = settings.titles.yTitle;
            var data = settings.data;
            // checking for undefined categories and series
            var categories = data.categories === null || data.categories === undefined ? [] : data.categories;
            var series = data.series === null || data.series === undefined ? [] : data.series;
            options.xAxis.categories = categories;
            options.series = series;

            return options;
        }

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */

    }, {
        key: '_createColumnChart',
        value: function _createColumnChart(options, settings) {

            var chartType = settings.chartType.type;

            if (chartType === 'area' && settings.chartType.hasOwnProperty('stacking') && settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: ' + '<b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
                    split: true
                };
            } else if (chartType === 'column' && settings.chartType.hasOwnProperty('stacking') && settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: ' + '<b>{point.y} ({point.percentage:.1f}%)</b><br/>'
                };
            } else if (chartType === 'bar' && settings.chartType.hasOwnProperty('stacking') && settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: ' + '<b>{point.y} ({point.percentage:.1f}%)</b><br/>'
                };
            }
            // set the plat option, empty, normal, percent
            var plot = {};
            plot[chartType] = {
                dataLabels: {
                    style: {
                        fontSize: '70%'
                    }
                }
            };
            if (settings.chartType.hasOwnProperty('stacking')) {
                plot[chartType] = {
                    stacking: settings.chartType.stacking,
                    dataLabels: {
                        style: {
                            fontSize: '70%'
                        }
                    }
                };
            }
            options['plotOptions'] = plot;
            // set the yAxis title
            options.yAxis.title.text = settings.titles.yTitle;
            // Label, not clear yet
            if (settings.hasOwnProperty('label')) {
                options['labels'] = {
                    items: [{
                        html: settings.label.title,
                        style: {
                            left: settings.label.position.left,
                            top: settings.label.position.top,
                            color: __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme && __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.theme.textColor || 'black'
                        }
                    }]
                };
            }
            // setting dynamic categories
            var data = settings.data;
            // checking for undefined categories and series
            var categories = data.categories === null || data.categories === undefined ? [] : data.categories;
            var series = data.series === null || data.series === undefined ? [] : data.series;
            options.xAxis.categories = categories;
            options.series = series;
            // change the formatter for yAxis if not empty
            if (settings.yAxisFormatter !== "") options.yAxis.labels = { format: '{value}' + settings.yAxisFormatter };

            // change the height of the renderTo (dynamic height)
            var height = categories.length === 0 ? 10 : categories.length;
            if (settings.large !== null && settings.large === 'height')
                //console.log(dataObj.categories);
                __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + settings.renderTo).css("height", height * 30 + "px");

            // check if the requested chart was a combined chart
            if (settings.hasOwnProperty('combination')) {
                var secondCharts = settings.combination;
                for (var i = 0; i < secondCharts.length; i++) {
                    var colors = options.colors;
                    if (secondCharts[i].hasOwnProperty('colors')) {
                        colors = secondCharts[i].colors;
                    }
                    var newSettings = secondChart(dataObj, secondCharts[i], colors);
                    options.series.push(newSettings);
                }
            }

            return options;
        }

        /**
         * @param sType
         * @param tType
         * @param renderTo
         * @param title
         * @returns {{text: *, onclick: onclick, separator: boolean}}
         * @private
         */

    }, {
        key: '_myMenuItems',
        value: function _myMenuItems(sType, tType, renderTo, title) {
            var self = this;
            var menuItem = {
                text: title,
                onclick: function onclick() {
                    self._changeChartType(sType, tType, renderTo);
                },
                separator: false
            };
            return menuItem;
        }

        /**
         * @param renderTo
         * @returns {{text: string, onclick: onclick, separator: boolean}}
         * @private
         */

    }, {
        key: '_dataLabelToggleMenu',
        value: function _dataLabelToggleMenu(renderTo) {
            var self = this;
            var menuItemLabel = {
                text: 'Toggle Labels',
                onclick: function onclick() {
                    self._toggleLabels(renderTo);
                },
                separator: false
            };
            return menuItemLabel;
        }

        /**
         * @param sType
         * @param type
         * @param renderTo
         * @private
         */

    }, {
        key: '_changeChartType',
        value: function _changeChartType(sType, type, renderTo) {
            var chart = __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.charts[document.querySelector('#' + renderTo).getAttribute('data-highcharts-chart')],
                s = chart.series,
                sLen = s.length;
            var inverted = false;
            var polar = false;
            if (type === 'pie' || type === 'halfpie' || type === 'donut') {
                console.log(chart);
                //chart.userOptions
            } else if (type === 'percent' || type === 'normal') {
                for (var i = 0; i < sLen; i++) {

                    s[i].update({
                        type: 'column',
                        stacking: type
                    }, false);
                }
            } else if (type === 'bar') {
                inverted = true;
            } else {
                for (var _i2 = 0; _i2 < sLen; _i2++) {

                    s[_i2].update({
                        type: type,
                        stacking: null
                    }, false);
                }
            }

            chart.update({
                chart: {
                    inverted: inverted,
                    polar: polar
                }
            });

            chart.redraw();
        }

        /**
         * @param renderTo
         * @private
         */

    }, {
        key: '_toggleLabels',
        value: function _toggleLabels(renderTo) {
            var chart = __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.charts[document.querySelector('#' + renderTo).getAttribute('data-highcharts-chart')],

            //let chart = chart,
            s = chart.series,
                sLen = s.length;
            //console.log("hello something" + s[0].dataLabels.enabled);
            for (var i = 0; i < sLen; i++) {
                s[i].update({
                    dataLabels: {
                        enabled: !s[i].options.dataLabels.enabled
                    }
                }, false);
            }
            chart.redraw();
        }

        /**
         * @param sData
         * @param sOptions
         * @param sColors
         * @returns {*}
         * @private
         */

    }, {
        key: '_secondChart',
        value: function _secondChart(sData, sOptions, sColors) {
            var data = sData;
            var mData = data.series;
            if (sOptions.type === 'pie' && sOptions.method === 'sum') {
                var tempData = [];
                for (var i = 0; i < mData.length; i++) {
                    var name = mData[i].name;
                    data = mData[i].data.reduce(function (prev, cur) {
                        return prev + cur;
                    });
                    var tmpObj = { name: name, y: data, color: sColors[i] };
                    //tmpObj[dataName] = dataData;
                    tempData.push(tmpObj);
                }
                // return pie chart
                return {
                    type: 'pie',
                    name: 'Total',
                    data: tempData,
                    center: [90, 10],
                    size: 100,
                    showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }

                };
            } else if (sOptions.type === 'spline') {
                var newData = [];
                var arrLength = mData[0].data.length;
                if (sOptions.method === 'average') {
                    //newData = null;
                    var avg = 0;
                    for (var _i3 = 0; _i3 < arrLength; _i3++) {
                        avg = 0;
                        for (var x = 0; x < mData.length; x++) {
                            var tmp = mData[x].data;
                            //console.log(tmp);
                            avg = avg + tmp[_i3];
                        }
                        newData.push(Math.round(avg / arrLength));
                    }
                } else if (sOptions.method === 'sum') {
                    //newData = null;
                    var sum = 0;
                    for (var _i4 = 0; _i4 < arrLength; _i4++) {
                        sum = 0;
                        for (var _x = 0; _x < mData.length; _x++) {
                            var _tmp = mData[_x].data;
                            //console.log(tmp);
                            sum = sum + _tmp[_i4];
                        }
                        newData.push(sum);
                    }
                }
                // return line chart
                return {
                    type: 'spline',
                    name: sOptions.method === 'sum' ? 'Total' : 'Average',
                    data: newData,
                    marker: {
                        lineWidth: 2,
                        lineColor: __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.getOptions().colors[3],
                        fillColor: '#ffffff'
                    },
                    color: __WEBPACK_IMPORTED_MODULE_1_highcharts___default.a.getOptions().colors[3]
                };
            }
        }
    }]);

    return ChartFactory;
}();

/* harmony default export */ __webpack_exports__["a"] = (ChartFactory);

/***/ }),

/***/ "./assets/js/charts/ChartHelper.js":
/*!*****************************************!*\
  !*** ./assets/js/charts/ChartHelper.js ***!
  \*****************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ChartHelper = function ChartHelper() {
    _classCallCheck(this, ChartHelper);

    this.chart = {
        renderTo: '',
        type: '',
        zoomType: 'xy'
    };

    this.lang = {
        printChart: 'Print chart',
        downloadPNG: 'Export PNG',
        downloadPDF: 'Export PDF'

    };

    this.exporting = {
        sourceWidth: 800,
        sourceHeight: 450,
        buttons: {
            contextButton: {
                menuItems: ['printChart', 'downloadPNG', 'downloadPDF']
            }
        }
    };
    this.xAxis = {
        categories: [],
        labels: {
            style: { fontSize: '75%' }
        }
    };
    this.yAxis = {
        title: {
            text: 'Chart title'
        },
        labels: {
            style: {
                fontSize: '75%'
            }
        }
    };
    this.colors = ['#FF0000', '#C99900', '#FFFF00'];
    this.series = [];
};

/* harmony default export */ __webpack_exports__["a"] = (ChartHelper);

/***/ }),

/***/ "./assets/js/charts/TableHtml.js":
/*!***************************************!*\
  !*** ./assets/js/charts/TableHtml.js ***!
  \***************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_datatables_net__ = __webpack_require__(/*! datatables.net */ "./node_modules/datatables.net/js/jquery.dataTables.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_datatables_net___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_datatables_net__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_datatables_net_bs_js_dataTables_bootstrap_min__ = __webpack_require__(/*! datatables.net-bs/js/dataTables.bootstrap.min */ "./node_modules/datatables.net-bs/js/dataTables.bootstrap.min.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_datatables_net_bs_js_dataTables_bootstrap_min___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_datatables_net_bs_js_dataTables_bootstrap_min__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_datatables_net_buttons_js_buttons_html5_js__ = __webpack_require__(/*! datatables.net-buttons/js/buttons.html5.js */ "./node_modules/datatables.net-buttons/js/buttons.html5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_datatables_net_buttons_js_buttons_html5_js___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_datatables_net_buttons_js_buttons_html5_js__);


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }






var TableHtml = function () {
    function TableHtml() {
        _classCallCheck(this, TableHtml);
    }

    _createClass(TableHtml, null, [{
        key: 'tableHtml',


        /**
         * Switch function, to call this function, args must have chartType.type key
         * @param args
         */
        value: function tableHtml(args) {
            var type = args.chartType.type;
            switch (type) {
                case "table":
                    this.table(args);
                    break;
                case "html":
                    this.htmlInfo(args);
                    break;
            }
        }
        /**
         * Container should always be an Id
         * @param args {renderTo, data, setting}
         */

    }, {
        key: 'table',
        value: function table() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: data, setting: {} };

            this.htmlInfo(args);
            // Table inside the container
            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + args.renderTo + ' table').DataTable(args.setting);
        }

        /**
         * Set HTML Text to container
         * @param args {renderTo, data}
         */

    }, {
        key: 'htmlInfo',
        value: function htmlInfo() {
            var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : { renderTo: renderTo, data: data };

            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + args.renderTo).html(args.data);
        }
    }]);

    return TableHtml;
}();

/* harmony default export */ __webpack_exports__["a"] = (TableHtml);

/***/ }),

/***/ "./assets/js/common/AjaxRequest.js":
/*!*****************************************!*\
  !*** ./assets/js/common/AjaxRequest.js ***!
  \*****************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Routing__ = __webpack_require__(/*! ./Routing */ "./assets/js/common/Routing.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__charts_Chart__ = __webpack_require__(/*! ../charts/Chart */ "./assets/js/charts/Chart.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__common_Alerts__ = __webpack_require__(/*! ../common/Alerts */ "./assets/js/common/Alerts.js");
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }






var AjaxRequest = function () {
    function AjaxRequest() {
        _classCallCheck(this, AjaxRequest);

        this.chart = new __WEBPACK_IMPORTED_MODULE_2__charts_Chart__["a" /* default */]();
    }

    _createClass(AjaxRequest, [{
        key: 'partiallyUpdate',
        value: function partiallyUpdate(url, charts, params) {
            var loaderClass = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;

            var self = this;
            if (loaderClass !== null) __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.' + loaderClass).show();

            var ajax = this.ajaxPromise(url, params);

            ajax.done(function (data) {
                for (var key in charts) {
                    if (data.hasOwnProperty(key)) {
                        charts[key].data = data[key];
                        charts[key].renderTo = key;
                        self.chart.visualize(charts[key]);
                    }
                }
                if (loaderClass !== null) __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.' + loaderClass).hide();
            }).fail(function (xhr) {
                __WEBPACK_IMPORTED_MODULE_3__common_Alerts__["a" /* default */].ajaxError(xhr);
            });
        }
    }, {
        key: 'updateAll',
        value: function updateAll(url, charts, param1, param2) {

            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.trend-loader, .info-loader, .loader').show();
            var self = this;
            var req1 = this.ajaxPromise(url, param2);
            var req2 = req1.then(function (data) {
                self.populateDashboard(_extends({}, charts), data);
                __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.info-loader').hide();
                return self.ajaxPromise(url, param1);
            });

            req2.done(function (data) {
                self.populateDashboard(_extends({}, charts), data);
                __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.trend-loader, .loader').hide();
            });
        }
    }, {
        key: 'populateDashboard',
        value: function populateDashboard(charts, data) {

            var self = this;
            __WEBPACK_IMPORTED_MODULE_0_jquery___default.a.each(charts, function (index, value) {
                if (data.hasOwnProperty(index)) {
                    this.data = data[index];
                    this.renderTo = index;
                    self.chart.visualize(this);
                }
            });
        }
    }, {
        key: 'ajaxPromise',
        value: function ajaxPromise(url, params) {
            var req = __WEBPACK_IMPORTED_MODULE_0_jquery___default.a.ajax({
                url: __WEBPACK_IMPORTED_MODULE_1__Routing__["a" /* default */].generate(url),
                data: params,
                method: 'post'
            });
            return req;
        }
    }]);

    return AjaxRequest;
}();

/* harmony default export */ __webpack_exports__["a"] = (AjaxRequest);

/***/ }),

/***/ "./assets/js/common/Alerts.js":
/*!************************************!*\
  !*** ./assets/js/common/Alerts.js ***!
  \************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_sweetalert2__ = __webpack_require__(/*! sweetalert2 */ "./node_modules/sweetalert2/dist/sweetalert2.all.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_sweetalert2___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_sweetalert2__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var Alerts = function () {
    function Alerts() {
        _classCallCheck(this, Alerts);
    }

    _createClass(Alerts, null, [{
        key: 'warning',
        value: function warning(message) {
            this.popup(message, 'warning', 'Warning!');
        }
    }, {
        key: 'error',
        value: function error(message) {
            this.popup(message, 'error', 'Error!');
        }
    }, {
        key: 'success',
        value: function success(message) {
            this.popup(message, 'success', 'Success!');
        }
    }, {
        key: 'info',
        value: function info(message) {
            this.popup(message, 'info', 'Info!');
        }
    }, {
        key: 'popup',
        value: function popup(message, type, title) {
            __WEBPACK_IMPORTED_MODULE_0_sweetalert2___default()({
                type: type,
                title: title,
                text: message,
                showConfirmButton: false,
                timer: 2000
            });
        }
    }, {
        key: 'clusterInfo',
        value: function clusterInfo() {
            __WEBPACK_IMPORTED_MODULE_0_sweetalert2___default()({
                title: '<strong>Guidance!</strong>',
                type: 'info',
                html: 'To see cluster level trends of any district please select a district first.' + 'To select a district, you have to select a province first. ' + 'When you select the province and district then click ' + '<span class="btn btn-xs bg-warning"><i class="fa fa-filter"></i> Filter</span> button.' + 'The page will automatically populate',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Got it!',
                confirmButtonAriaLabel: 'Got it!'
            });
        }
    }, {
        key: 'filterInfo',
        value: function filterInfo() {
            __WEBPACK_IMPORTED_MODULE_0_sweetalert2___default()({
                title: '<strong>Attention!</strong>',
                type: 'info',
                html: 'The current dashboard is already filtered. <br>' + 'Change the Filter and then click Filter button',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Got it!',
                confirmButtonAriaLabel: 'Got it!'
            });
        }
    }, {
        key: 'ajaxError',
        value: function ajaxError(error) {
            __WEBPACK_IMPORTED_MODULE_0_sweetalert2___default()({
                title: '<strong>Network Error!</strong>',
                type: 'error',
                html: error,
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: true,
                confirmButtonText: '<i class="fa fa-thumbs-down"></i> Tray Later!',
                confirmButtonAriaLabel: 'Try again!'
            });
        }
    }]);

    return Alerts;
}();

/* harmony default export */ __webpack_exports__["a"] = (Alerts);

/***/ }),

/***/ "./assets/js/common/Routing.js":
/*!*************************************!*\
  !*** ./assets/js/common/Routing.js ***!
  \*************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// Rely on the Routing JS on the Page

/* harmony default export */ __webpack_exports__["a"] = (window.Routing);

/***/ }),

/***/ "./assets/js/filter/Filter.js":
/*!************************************!*\
  !*** ./assets/js/filter/Filter.js ***!
  \************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_bootstrap_multiselect__ = __webpack_require__(/*! bootstrap-multiselect */ "./node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_bootstrap_multiselect___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_bootstrap_multiselect__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__common_Routing__ = __webpack_require__(/*! ../common/Routing */ "./assets/js/common/Routing.js");


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }





var Filter = function () {
    function Filter() {
        _classCallCheck(this, Filter);
    }

    _createClass(Filter, null, [{
        key: 'resetFilter',
        value: function resetFilter(filterName) {
            var data = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];

            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + filterName).multiselect('dataprovider', data);
        }

        // common ajax function to load the dynamic items

    }, {
        key: 'ajaxRequest',
        value: function ajaxRequest(url, postData, target) {
            __WEBPACK_IMPORTED_MODULE_0_jquery___default.a.ajax({
                url: __WEBPACK_IMPORTED_MODULE_2__common_Routing__["a" /* default */].generate(url),
                data: postData,
                type: 'POST',
                success: function success(data) {
                    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#' + target).multiselect('dataprovider', JSON.parse(data));
                }
            });
        }
    }]);

    return Filter;
}();

/* harmony default export */ __webpack_exports__["a"] = (Filter);

/***/ }),

/***/ "./assets/js/filter/FilterControl.js":
/*!*******************************************!*\
  !*** ./assets/js/filter/FilterControl.js ***!
  \*******************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_underscore__ = __webpack_require__(/*! underscore */ "./node_modules/underscore/underscore.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_underscore___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_underscore__);


var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var FilterControl = function () {
    function FilterControl() {
        _classCallCheck(this, FilterControl);
    }

    _createClass(FilterControl, [{
        key: 'setFilterState',
        value: function setFilterState(state) {
            this.state = _extends({}, state);
        }
    }, {
        key: 'checkFilterState',
        value: function checkFilterState(newState) {
            if (__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(this.state, newState)) {
                //Alerts.filterInfo();
                return false;
            }

            // cases what changed
            var multiCampaign = newState.campaign.length > 1;

            var regionChanged = !__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(newState.region, this.state.region);
            var provinceChanged = !__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(newState.province, this.state.province);
            var districtChanged = !__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(newState.district, this.state.district);

            var anyChange = regionChanged || provinceChanged || districtChanged;

            // set new state
            this.setFilterState(newState);

            if (anyChange) return 'both';else if (multiCampaign) return 'trend';else return 'info';
        }
    }, {
        key: 'checkClusterFilterState',
        value: function checkClusterFilterState(newState) {
            if (__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(this.state, newState)) {
                //Alerts.filterInfo();
                return false;
            }

            // cases what changed
            var multiCampaign = newState.campaign.length > 1;

            var provinceChanged = newState.province !== this.state.province;
            var districtChanged = newState.district !== this.state.district;
            var clusterChanged = !__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(newState.cluster, this.state.cluster);

            var anyChange = clusterChanged || provinceChanged || districtChanged;

            // set new state
            this.setFilterState(newState);

            if (anyChange) return 'both';else if (multiCampaign) return 'trend';else return 'info';
        }
    }, {
        key: 'ccsSmFilterState',
        value: function ccsSmFilterState(newState) {
            if (__WEBPACK_IMPORTED_MODULE_0_underscore___default.a.isEqual(this.state, newState)) {
                return false;
            } else {
                this.setFilterState(newState);
                return true;
            }
        }
    }]);

    return FilterControl;
}();

/* harmony default export */ __webpack_exports__["a"] = (FilterControl);

/***/ }),

/***/ "./assets/js/filter/FilterListener.js":
/*!********************************************!*\
  !*** ./assets/js/filter/FilterListener.js ***!
  \********************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__common_Alerts__ = __webpack_require__(/*! ../common/Alerts */ "./assets/js/common/Alerts.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/*
This Class is Just Used to Capture Value of the Filters (Dropdown)
And this can be used for all dashboard having Filter up to District Level
 */



var FilterListener = function () {
        function FilterListener() {
                _classCallCheck(this, FilterListener);
        }

        _createClass(FilterListener, [{
                key: 'listenMain',
                value: function listenMain() {

                        var campaigns = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val();

                        var region = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterRegion').val();

                        var provinces = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterProvince').val();

                        var districts = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').val();

                        var entity = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#ajaxUrl').data('source');

                        // // check if a user didn't select anything, then return
                        // if(campaigns === null || campaigns === undefined) {
                        //     Alerts.error("Please select at least one campaign");
                        //     return;
                        // }

                        //Todo: A bit more logic to control which api should be called
                        return {
                                'campaign': campaigns,
                                region: region,
                                'province': provinces,
                                'district': districts,
                                entity: entity
                        };
                }
        }, {
                key: 'listenCluster',
                value: function listenCluster() {
                        var selectedCampaigns = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val();
                        //var provinces = $('#filterProvince option:selected');
                        var selectedProvinces = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterProvince').val();
                        //var districts = $('#filterDistrict option:selected');
                        var selectedDistricts = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').val();
                        //var clusters = $('#filterCluster option:selected');
                        var selectedClusters = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCluster').val();

                        var entity = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#ajaxUrl').data('source');

                        return {
                                'campaign': selectedCampaigns,
                                'province': selectedProvinces,
                                'district': selectedDistricts,
                                'cluster': selectedClusters,
                                entity: entity
                        };
                }
        }, {
                key: 'listenCcsSm',
                value: function listenCcsSm() {

                        var campaigns = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val();

                        var provinces = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterProvince').val();

                        var districts = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').val();

                        var clusters = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCluster').val();

                        return {
                                'campaign': campaigns,
                                'cluster': clusters,
                                'province': provinces,
                                'district': districts
                        };
                }
        }]);

        return FilterListener;
}();

/* harmony default export */ __webpack_exports__["a"] = (FilterListener);

/***/ }),

/***/ "./assets/js/filter/OdkFilter.js":
/*!***************************************!*\
  !*** ./assets/js/filter/OdkFilter.js ***!
  \***************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Filter__ = __webpack_require__(/*! ./Filter */ "./assets/js/filter/Filter.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }




var OdkFilter = function OdkFilter() {
    _classCallCheck(this, OdkFilter);

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').multiselect({
        nonSelectedText: 'Months',
        numberDisplayed: 2,
        maxHeight: 250,
        onChange: function onChange(element, checked) {
            var campaigns = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val();
            //console.log(campaigns);
            campaigns.length > 1 ? __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.btn-cum-res').show() : __WEBPACK_IMPORTED_MODULE_0_jquery___default()('.btn-cum-res').hide();
        }
    });

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterProvince').multiselect({
        nonSelectedText: 'Province',
        numberDisplayed: 2,
        maxHeight: 300,
        enableClickableOptGroups: true,
        onChange: function onChange(element, checked) {
            __WEBPACK_IMPORTED_MODULE_1__Filter__["a" /* default */].resetFilter('filterDistrict');
            __WEBPACK_IMPORTED_MODULE_1__Filter__["a" /* default */].resetFilter('filterCluster');
            var province = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterProvince option:selected');
            var selectedProvinces = [];
            __WEBPACK_IMPORTED_MODULE_0_jquery___default()(province).each(function (index, province) {
                selectedProvinces.push([__WEBPACK_IMPORTED_MODULE_0_jquery___default()(this).val()]);
            });
            if (selectedProvinces.length > 0) {
                var source = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#icn_table').data('source');
                var data = {
                    "source": source,
                    "province": selectedProvinces,
                    "risk": false,
                    'campaign': __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val()
                };
                __WEBPACK_IMPORTED_MODULE_1__Filter__["a" /* default */].ajaxRequest('filter_district_odk', data, 'filterDistrict');
            }
        }
    });

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').multiselect({
        nonSelectedText: 'District',
        numberDisplayed: 2,
        maxHeight: 300,
        enableCaseInsensitiveFiltering: true,
        enableClickableOptGroups: true,
        onChange: function onChange(element, checked) {
            var selectedDistricts = [];
            var district = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict option:selected');
            __WEBPACK_IMPORTED_MODULE_1__Filter__["a" /* default */].resetFilter('filterCluster');

            __WEBPACK_IMPORTED_MODULE_0_jquery___default()(district).each(function (index, district) {
                selectedDistricts.push([__WEBPACK_IMPORTED_MODULE_0_jquery___default()(this).val()]);
            });

            var districts = selectedDistricts.join(',');
            districts = districts.split(',');
            if (districts.length > 1) {
                if (districts.indexOf('VHR') > -1 || districts.indexOf('HR') > -1 || districts.indexOf('Non-V/HR districts') > -1) {
                    __WEBPACK_IMPORTED_MODULE_0_jquery___default.a.each(districts, function (index, value) {
                        if (value !== 'VHR' || value !== 'Non-V/HR districts' || value !== 'HR') {
                            __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').multiselect('deselect', value, true);
                        }
                    });
                }
            }

            if (selectedDistricts.length > 0) {
                selectedDistricts = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterDistrict').val();
                var campaign = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCampaign').val();
                //console.log(campaign);
                var source = __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#icn_table').data('source');
                var data = {
                    "district": selectedDistricts,
                    'campaign': campaign,
                    'source': source
                };
                __WEBPACK_IMPORTED_MODULE_1__Filter__["a" /* default */].ajaxRequest('filter_cluster_odk', data, 'filterCluster');
            }
        }
    });

    __WEBPACK_IMPORTED_MODULE_0_jquery___default()('#filterCluster').multiselect({
        nonSelectedText: 'Clusters',
        numberDisplayed: 2,
        maxHeight: 500,
        enableCaseInsensitiveFiltering: true,
        enableClickableOptGroups: true,
        includeSelectAllOption: true,
        allSelectedText: 'All'
    });
};

/* harmony default export */ __webpack_exports__["a"] = (OdkFilter);

/***/ }),

/***/ "./assets/js/setting/Catchup.js":
/*!**************************************!*\
  !*** ./assets/js/setting/Catchup.js ***!
  \**************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";


var CatchupChartsSetting = {};

// The first row of one campaign
CatchupChartsSetting['missed_by_reason_pie_1'] = {
    'colors': ['#FFFF00', '#C99900', '#FF0000'],
    'chartType': { 'type': 'donut' }, 'legend': false, 'area': 'small'
};
CatchupChartsSetting['total_remaining_1'] = { 'chartType': { 'type': "column" },
    'colors': ['#FFB32D'], 'legend': { 'enabled': false } };

// The first row of one campaign
CatchupChartsSetting['campaign_title'] = { 'chartType': { 'type': 'html' } };

CatchupChartsSetting['info_box'] = { 'chartType': { 'type': 'html' } };

CatchupChartsSetting['info_table'] = { 'chartType': { 'type': 'html' } };

// Pie charts Row
CatchupChartsSetting['recovered_all_type_1'] = { 'colors': ['#048AFF', '#40C97A', '#FFB32D'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CatchupChartsSetting['recovered_absent_1'] = { 'colors': ['#048AFF', '#40C97A', '#FFFF00'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CatchupChartsSetting['recovered_nss_1'] = { 'colors': ['#048AFF', '#40C97A', '#9C800E'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CatchupChartsSetting['recovered_refusal_1'] = { 'colors': ['#048AFF', '#40C97A', '#FF0000'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};

// 10 campaign vaccinated children column chartType
CatchupChartsSetting['vac_child_trend'] = { 'chartType': { 'type': "column" }, 'colors': ['#048AFF'] };
// 10 campaign missed children column chartType
CatchupChartsSetting['missed_child_trend'] = { 'chartType': { 'type': "column" }, 'colors': ['#C99900'] };
// 10 campaign missed by type stack column chartType
CatchupChartsSetting['missed_by_type_trend'] = {
    'chartType': { 'type': "column", 'stacking': 'normal' },
    'colors': ['#FFFF00', '#C99900', '#FF0000'],
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// 10 Campaign absent percent stack chartType
CatchupChartsSetting['absent_recovered_trend'] = {
    'colors': ['#EAFF19', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// 10 Campaign nss percent stack chartType
CatchupChartsSetting['nss_recovered_trend'] = {
    'colors': ['#9C800E', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// 10 Campaign refusal percent stack chartType
CatchupChartsSetting['refusal_recovered_trend'] = {
    'colors': ['#FF0000', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};

// 10 campaign missed recovery area percent chartType
CatchupChartsSetting['missed_child_recovery_trend'] = { 'colors': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
    'chartType': { 'type': "area", 'stacking': 'percent' } };

/* harmony default export */ __webpack_exports__["a"] = (CatchupChartsSetting);

/***/ }),

/***/ "./assets/js/setting/CatchupCluster.js":
/*!*********************************************!*\
  !*** ./assets/js/setting/CatchupCluster.js ***!
  \*********************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// ======================================= Clusters Level Dashbaord ===============================

var CatchupCluster = {};
CatchupCluster['missed_recovery_chart_1'] = {
    'colors': ['#FFFF00', '#C99900', '#FF0000', '#048AFF'],
    'chartType': { 'type': "bar", 'stacking': 'percent' },
    'large': 'height'
};
// Table
CatchupCluster['cluster_trend'] = { 'chartType': { 'type': 'table' },
    'setting': {
        "scrollX": true,
        'paging': false,
        'dom': 'frtipB',
        'buttons': ['copyHtml5', 'csvHtml5']
    }
};

/* harmony default export */ __webpack_exports__["a"] = (CatchupCluster);

/***/ }),

/***/ "./assets/js/setting/CcsSmSetting.js":
/*!*******************************************!*\
  !*** ./assets/js/setting/CcsSmSetting.js ***!
  \*******************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// ======================================= Composite indicators ===============================

var CcsSmSetting = {};
// Table
CcsSmSetting['icn_table'] = { 'chartType': { 'type': 'table' },
    'setting': {
        "scrollX": true,
        "lengthMenu": [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]]
    }
};

/* harmony default export */ __webpack_exports__["a"] = (CcsSmSetting);

/***/ }),

/***/ "./assets/js/setting/Coverage.js":
/*!***************************************!*\
  !*** ./assets/js/setting/Coverage.js ***!
  \***************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";


var CoverageChartsSetting = {};

// The first row of one campaign
CoverageChartsSetting['missed_by_reason_pie_1'] = { 'colors': ['#FFFF00', '#C99900', '#FF0000'],
    'chartType': { 'type': 'donut' }, 'legend': false, 'area': 'small'
};
CoverageChartsSetting['vaccine_wastage_1'] = { 'chartType': { 'type': "column" },
    'colors': ['#FFB32D'], 'legend': { 'enabled': false } };

// The first row of one campaign
CoverageChartsSetting['campaign_title'] = { 'chartType': { 'type': 'html' } };

CoverageChartsSetting['info_box'] = { 'chartType': { 'type': 'html' } };

CoverageChartsSetting['info_table'] = { 'chartType': { 'type': 'html' } };

// Pie charts Row
CoverageChartsSetting['recovered_all_type_1'] = { 'colors': ['#048AFF', '#40C97A', '#FFB32D'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CoverageChartsSetting['recovered_absent_1'] = { 'colors': ['#048AFF', '#40C97A', '#FFFF00'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CoverageChartsSetting['recovered_nss_1'] = { 'colors': ['#048AFF', '#40C97A', '#9C800E'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};
CoverageChartsSetting['recovered_refusal_1'] = { 'colors': ['#048AFF', '#40C97A', '#FF0000'],
    'chartType': { 'type': 'halfpie' }, 'legend': true
};

// 10 campaign vaccinated children column chartType
CoverageChartsSetting['vac_child_trend'] = { 'chartType': { 'type': "column" }, 'colors': ['#048AFF'] };
// 10 campaign missed children column chartType
CoverageChartsSetting['missed_child_trend'] = { 'chartType': { 'type': "column" }, 'colors': ['#C99900'] };
// 10 campaign missed by type stack column chartType
CoverageChartsSetting['missed_by_type_trend'] = { 'chartType': { 'type': "column", 'stacking': 'normal' },
    'colors': ['#FFFF00', '#C99900', '#FF0000'] };
// 10 Campaign absent percent stack chartType
CoverageChartsSetting['absent_recovered_trend'] = { 'colors': ['#EAFF19', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' }
};
// 10 Campaign nss percent stack chartType
CoverageChartsSetting['nss_recovered_trend'] = { 'colors': ['#9C800E', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' }
};
// 10 Campaign refusal percent stack chartType
CoverageChartsSetting['refusal_recovered_trend'] = { 'colors': ['#FF0000', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' } };

// 10 campaign missed recovery area percent chartType
CoverageChartsSetting['missed_child_recovery_trend'] = {
    'colors': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
    'chartType': { 'type': "area", 'stacking': 'percent' }
};

/* harmony default export */ __webpack_exports__["a"] = (CoverageChartsSetting);

/***/ }),

/***/ "./assets/js/setting/CoverageCluster.js":
/*!**********************************************!*\
  !*** ./assets/js/setting/CoverageCluster.js ***!
  \**********************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// ======================================= Clusters Level Dashbaord ===============================

var CoverageCluster = {};
CoverageCluster['missed_recovery_chart_1'] = {
    'colors': ['#FFFF00', '#C99900', '#FF0000', '#048AFF'],
    'chartType': { 'type': "bar", 'stacking': 'percent' },
    'large': 'height'
};
// Table
CoverageCluster['cluster_trend'] = { 'chartType': { 'type': 'table' },
    'setting': {
        "scrollX": true,
        'paging': false,
        'dom': 'frtipB',
        'buttons': ['copyHtml5', 'csvHtml5']
    }
};

/* harmony default export */ __webpack_exports__["a"] = (CoverageCluster);

/***/ }),

/***/ "./assets/js/setting/MainCluster.js":
/*!******************************************!*\
  !*** ./assets/js/setting/MainCluster.js ***!
  \******************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// ======================================= Clusters Level Dashbaord ===============================

var MainCluster = {};
MainCluster['missed_recovery_chart_1'] = {
    'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
    'chartType': { 'type': "bar", 'stacking': 'percent' },
    'large': 'height'
};
// Table
MainCluster['cluster_trend'] = { 'chartType': { 'type': 'table' },
    'setting': {
        "scrollX": true,
        'paging': false,
        'dom': 'frtipB',
        'buttons': ['copyHtml5', 'csvHtml5']
    }
};

/* harmony default export */ __webpack_exports__["a"] = (MainCluster);

/***/ }),

/***/ "./assets/js/setting/MainDash.js":
/*!***************************************!*\
  !*** ./assets/js/setting/MainDash.js ***!
  \***************************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Catchup__ = __webpack_require__(/*! ./Catchup */ "./assets/js/setting/Catchup.js");


/*
Charts Settings for Main Dashboard
1. Object key will be equal to the JSON key returning by Server
2. Object key will also be equal to the container charts is rendering to
3. ChartType, Color and Other Required Properties of the Chart should be Cleared
4. You can also add 'data' property, however, that will be added/replaced by the actual data
----------------------- MultiAxisesChart ----------------------------------------------
object:        { renderTo, data:[], titles:[], indicators:[], colors:[], chartType:{}}

----------------------- Column, Bar, Stack, Area charts  with default Values ----------
object:        {renderTo, data: [], chartType:{type:'column'},
                  combination: [],
                  colors: Highcharts.getOptions().colors,
                  titles: {xTitle: null, yTitle: null},
                  legend: {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                  menu: [{chart:'percent', title:'Percent Stack'},
                      {chart: 'column', title:'Column Chart'}],
                  large:null,
                  yAxisFormatter:""}

---------------------  Pie, Donut Charts with Default Values -------------------------
object:         { renderTo, data : [], legend : false,
                  colors : Highcharts.getOptions().colors,
                  chartType : {type:'pie'}, area : 'large', menu : null }

--------------------- Line, Spline Charts with Default Values -----------------------
Arguments:     { renderTo, data : [], chartType : {type:'line'},
                 colors : Highcharts.getOptions().colors,
                 titles : {xTitle: null, yTitle: null},
                 legend : {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                 menu : [{chart:'spline', title:'Spline Chart'},
                     {chart: 'line', title:'Line Chart'}]
                }

--------------------- Table, HTML --------------------------------------------------
Arguments:      { renderTo, data : [], chartType : {type:'table/html'} }

 */



var MainChartsSetting = {};

// ------------------- PIE chart One campaign --------------------------------------

// The first row of one campaign
MainChartsSetting['missed_by_reason_pie_1'] = {
    'colors': ['#FFFF00', '#C99900', '#FF0000'],
    'chartType': { 'type': 'pie' }, 'legend': false, 'area': 'small'
};

MainChartsSetting['total_remaining_1'] = { 'chartType': { 'type': "column" },
    'colors': ['#FFB32D'], 'legend': { 'enabled': false } };

// The first row of one campaign
MainChartsSetting['campaign_title'] = { 'chartType': { 'type': 'html' } };

MainChartsSetting['info_box'] = { 'chartType': { 'type': 'html' } };

MainChartsSetting['info_table'] = { 'chartType': { 'type': 'html' } };

// All Type Missed
MainChartsSetting['recovered_all_type_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FFB32D', '#B7B3BE'],
    'chartType': { 'type': 'halfpie' },
    'legend': true
};
// Absent
MainChartsSetting['recovered_absent_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FFFF00', '#B7B3BE'],
    'chartType': { 'type': 'halfpie' },
    'legend': true
};
// NSS
MainChartsSetting['recovered_nss_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#9C800E', '#B7B3BE'],
    'chartType': { 'type': 'halfpie' },
    'legend': true
};
// Refusal
MainChartsSetting['recovered_refusal_1'] = {
    'colors': ['#048AFF', '#45E490', '#2DA810', '#FF0000', '#B7B3BE'],
    'chartType': { 'type': 'halfpie' },
    'legend': true
};

// Refusal
MainChartsSetting['campaign_title'] = {
    'chartType': { 'type': 'html' }
};

//------------------ Trends Starts here ----------------------------------------------

// Ten campaign vaccinated children
MainChartsSetting['vac_child_trend'] = {
    'colors': ['#048AFF', '#43AB0D'],
    'chartType': { 'type': "column", 'stacking': 'normal' }
};
// Ten campaign missed children
MainChartsSetting['missed_child_trend'] = {
    'colors': ['#C99900', '#FFB32D'],
    'chartType': { 'type': "column" }
};
// // Ten campaign missed by type percent chart
// MainChartsSetting['missed_by_type_trend'] = {
//     'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
//     'chartType':{'type':"column", 'stacking':'percent'}
// };
// Ten campaign absent children percent chart
MainChartsSetting['missed_recovered_trend'] = {
    'colors': ['#B7B3BE', '#FFB32D', '#2DA810', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// Ten campaign absent children percent chart
MainChartsSetting['absent_recovered_trend'] = {
    'colors': ['#B7B3BE', '#EAFF19', '#2DA810', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// Ten campaign nss children percent chart
MainChartsSetting['nss_recovered_trend'] = {
    'colors': ['#B7B3BE', '#9C800E', '#2DA810', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// Ten campaign refusal children percent chart
MainChartsSetting['refusal_recovered_trend'] = {
    'colors': ['#B7B3BE', '#FF0000', '#2DA810', '#45E490', '#048AFF'],
    'chartType': { 'type': "column", 'stacking': 'percent' },
    'menu': [{ chart: 'percent', title: 'Percent Chart' }, { chart: 'normal', title: 'Normal Chart' }]
};
// Ten campaign missed recovery area chart
MainChartsSetting['missed_child_recovery_trend'] = {
    'colors': ['#FFDE7B', '#33D3FF', '#42FFC0', '#40C97A'],
    'chartType': { 'type': "area", 'stacking': 'percent' }
};

/* harmony default export */ __webpack_exports__["a"] = (MainChartsSetting);

/***/ }),

/***/ "./assets/js/setting/index.js":
/*!************************************!*\
  !*** ./assets/js/setting/index.js ***!
  \************************************/
/*! exports provided: SettingMain, SettingCoverage, SettingCatchup, CatchupCluster, CoverageCluster, MainCluster, CcsSmSetting */
/*! exports used: CatchupCluster, CcsSmSetting, CoverageCluster, MainCluster, SettingCatchup, SettingCoverage, SettingMain */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__MainDash__ = __webpack_require__(/*! ./MainDash */ "./assets/js/setting/MainDash.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Coverage__ = __webpack_require__(/*! ./Coverage */ "./assets/js/setting/Coverage.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Catchup__ = __webpack_require__(/*! ./Catchup */ "./assets/js/setting/Catchup.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__CatchupCluster__ = __webpack_require__(/*! ./CatchupCluster */ "./assets/js/setting/CatchupCluster.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__CoverageCluster__ = __webpack_require__(/*! ./CoverageCluster */ "./assets/js/setting/CoverageCluster.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__MainCluster__ = __webpack_require__(/*! ./MainCluster */ "./assets/js/setting/MainCluster.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__CcsSmSetting__ = __webpack_require__(/*! ./CcsSmSetting */ "./assets/js/setting/CcsSmSetting.js");
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "g", function() { return __WEBPACK_IMPORTED_MODULE_0__MainDash__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "f", function() { return __WEBPACK_IMPORTED_MODULE_1__Coverage__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "e", function() { return __WEBPACK_IMPORTED_MODULE_2__Catchup__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return __WEBPACK_IMPORTED_MODULE_3__CatchupCluster__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "c", function() { return __WEBPACK_IMPORTED_MODULE_4__CoverageCluster__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "d", function() { return __WEBPACK_IMPORTED_MODULE_5__MainCluster__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "b", function() { return __WEBPACK_IMPORTED_MODULE_6__CcsSmSetting__["a"]; });












/***/ }),

/***/ "./node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js ***!
  \*****************************************************************************/
/*! dynamic exports provided */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(__webpack_provided_window_dot_jQuery) {/**
 * Bootstrap Multiselect (https://github.com/davidstutz/bootstrap-multiselect)
 * 
 * Apache License, Version 2.0:
 * Copyright (c) 2012 - 2015 David Stutz
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a
 * copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 * 
 * BSD 3-Clause License:
 * Copyright (c) 2012 - 2015 David Stutz
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *    - Redistributions of source code must retain the above copyright notice,
 *      this list of conditions and the following disclaimer.
 *    - Redistributions in binary form must reproduce the above copyright notice,
 *      this list of conditions and the following disclaimer in the documentation
 *      and/or other materials provided with the distribution.
 *    - Neither the name of David Stutz nor the names of its contributors may be
 *      used to endorse or promote products derived from this software without
 *      specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
!function ($) {
    "use strict";// jshint ;_;

    if (typeof ko !== 'undefined' && ko.bindingHandlers && !ko.bindingHandlers.multiselect) {
        ko.bindingHandlers.multiselect = {
            after: ['options', 'value', 'selectedOptions'],

            init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
                var $element = $(element);
                var config = ko.toJS(valueAccessor());

                $element.multiselect(config);

                if (allBindings.has('options')) {
                    var options = allBindings.get('options');
                    if (ko.isObservable(options)) {
                        ko.computed({
                            read: function() {
                                options();
                                setTimeout(function() {
                                    var ms = $element.data('multiselect');
                                    if (ms)
                                        ms.updateOriginalOptions();//Not sure how beneficial this is.
                                    $element.multiselect('rebuild');
                                }, 1);
                            },
                            disposeWhenNodeIsRemoved: element
                        });
                    }
                }

                //value and selectedOptions are two-way, so these will be triggered even by our own actions.
                //It needs some way to tell if they are triggered because of us or because of outside change.
                //It doesn't loop but it's a waste of processing.
                if (allBindings.has('value')) {
                    var value = allBindings.get('value');
                    if (ko.isObservable(value)) {
                        ko.computed({
                            read: function() {
                                value();
                                setTimeout(function() {
                                    $element.multiselect('refresh');
                                }, 1);
                            },
                            disposeWhenNodeIsRemoved: element
                        }).extend({ rateLimit: 100, notifyWhenChangesStop: true });
                    }
                }

                //Switched from arrayChange subscription to general subscription using 'refresh'.
                //Not sure performance is any better using 'select' and 'deselect'.
                if (allBindings.has('selectedOptions')) {
                    var selectedOptions = allBindings.get('selectedOptions');
                    if (ko.isObservable(selectedOptions)) {
                        ko.computed({
                            read: function() {
                                selectedOptions();
                                setTimeout(function() {
                                    $element.multiselect('refresh');
                                }, 1);
                            },
                            disposeWhenNodeIsRemoved: element
                        }).extend({ rateLimit: 100, notifyWhenChangesStop: true });
                    }
                }

                ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
                    $element.multiselect('destroy');
                });
            },

            update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
                var $element = $(element);
                var config = ko.toJS(valueAccessor());

                $element.multiselect('setOptions', config);
                $element.multiselect('rebuild');
            }
        };
    }

    function forEach(array, callback) {
        for (var index = 0; index < array.length; ++index) {
            callback(array[index], index);
        }
    }

    /**
     * Constructor to create a new multiselect using the given select.
     *
     * @param {jQuery} select
     * @param {Object} options
     * @returns {Multiselect}
     */
    function Multiselect(select, options) {

        this.$select = $(select);
        
        // Placeholder via data attributes
        if (this.$select.attr("data-placeholder")) {
            options.nonSelectedText = this.$select.data("placeholder");
        }
        
        this.options = this.mergeOptions($.extend({}, options, this.$select.data()));

        // Initialization.
        // We have to clone to create a new reference.
        this.originalOptions = this.$select.clone()[0].options;
        this.query = '';
        this.searchTimeout = null;
        this.lastToggledInput = null

        this.options.multiple = this.$select.attr('multiple') === "multiple";
        this.options.onChange = $.proxy(this.options.onChange, this);
        this.options.onDropdownShow = $.proxy(this.options.onDropdownShow, this);
        this.options.onDropdownHide = $.proxy(this.options.onDropdownHide, this);
        this.options.onDropdownShown = $.proxy(this.options.onDropdownShown, this);
        this.options.onDropdownHidden = $.proxy(this.options.onDropdownHidden, this);
        
        // Build select all if enabled.
        this.buildContainer();
        this.buildButton();
        this.buildDropdown();
        this.buildSelectAll();
        this.buildDropdownOptions();
        this.buildFilter();

        this.updateButtonText();
        this.updateSelectAll();

        if (this.options.disableIfEmpty && $('option', this.$select).length <= 0) {
            this.disable();
        }
        
        this.$select.hide().after(this.$container);
    };

    Multiselect.prototype = {

        defaults: {
            /**
             * Default text function will either print 'None selected' in case no
             * option is selected or a list of the selected options up to a length
             * of 3 selected options.
             * 
             * @param {jQuery} options
             * @param {jQuery} select
             * @returns {String}
             */
            buttonText: function(options, select) {
                if (options.length === 0) {
                    return this.nonSelectedText;
                }
                else if (this.allSelectedText 
                            && options.length === $('option', $(select)).length 
                            && $('option', $(select)).length !== 1 
                            && this.multiple) {

                    if (this.selectAllNumber) {
                        return this.allSelectedText + ' (' + options.length + ')';
                    }
                    else {
                        return this.allSelectedText;
                    }
                }
                else if (options.length > this.numberDisplayed) {
                    return options.length + ' ' + this.nSelectedText;
                }
                else {
                    var selected = '';
                    var delimiter = this.delimiterText;
                    
                    options.each(function() {
                        var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).text();
                        selected += label + delimiter;
                    });
                    
                    return selected.substr(0, selected.length - 2);
                }
            },
            /**
             * Updates the title of the button similar to the buttonText function.
             * 
             * @param {jQuery} options
             * @param {jQuery} select
             * @returns {@exp;selected@call;substr}
             */
            buttonTitle: function(options, select) {
                if (options.length === 0) {
                    return this.nonSelectedText;
                }
                else {
                    var selected = '';
                    var delimiter = this.delimiterText;
                    
                    options.each(function () {
                        var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).text();
                        selected += label + delimiter;
                    });
                    return selected.substr(0, selected.length - 2);
                }
            },
            /**
             * Create a label.
             *
             * @param {jQuery} element
             * @returns {String}
             */
            optionLabel: function(element){
                return $(element).attr('label') || $(element).text();
            },
            /**
             * Triggered on change of the multiselect.
             * 
             * Not triggered when selecting/deselecting options manually.
             * 
             * @param {jQuery} option
             * @param {Boolean} checked
             */
            onChange : function(option, checked) {

            },
            /**
             * Triggered when the dropdown is shown.
             *
             * @param {jQuery} event
             */
            onDropdownShow: function(event) {

            },
            /**
             * Triggered when the dropdown is hidden.
             *
             * @param {jQuery} event
             */
            onDropdownHide: function(event) {

            },
            /**
             * Triggered after the dropdown is shown.
             * 
             * @param {jQuery} event
             */
            onDropdownShown: function(event) {
                
            },
            /**
             * Triggered after the dropdown is hidden.
             * 
             * @param {jQuery} event
             */
            onDropdownHidden: function(event) {
                
            },
            /**
             * Triggered on select all.
             */
            onSelectAll: function() {
                
            },
            enableHTML: false,
            buttonClass: 'btn btn-default',
            inheritClass: false,
            buttonWidth: 'auto',
            buttonContainer: '<div class="btn-group" />',
            dropRight: false,
            selectedClass: 'active',
            // Maximum height of the dropdown menu.
            // If maximum height is exceeded a scrollbar will be displayed.
            maxHeight: false,
            checkboxName: false,
            includeSelectAllOption: false,
            includeSelectAllIfMoreThan: 0,
            selectAllText: ' Select all',
            selectAllValue: 'multiselect-all',
            selectAllName: false,
            selectAllNumber: true,
            enableFiltering: false,
            enableCaseInsensitiveFiltering: false,
            enableClickableOptGroups: false,
            filterPlaceholder: 'Search',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            includeFilterClearBtn: true,
            preventInputChangeEvent: false,
            nonSelectedText: 'None selected',
            nSelectedText: 'selected',
            allSelectedText: 'All selected',
            numberDisplayed: 3,
            disableIfEmpty: false,
            delimiterText: ', ',
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
                filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="glyphicon glyphicon-remove-circle"></i></button></span>',
                li: '<li><a tabindex="0"><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
            }
        },

        constructor: Multiselect,

        /**
         * Builds the container of the multiselect.
         */
        buildContainer: function() {
            this.$container = $(this.options.buttonContainer);
            this.$container.on('show.bs.dropdown', this.options.onDropdownShow);
            this.$container.on('hide.bs.dropdown', this.options.onDropdownHide);
            this.$container.on('shown.bs.dropdown', this.options.onDropdownShown);
            this.$container.on('hidden.bs.dropdown', this.options.onDropdownHidden);
        },

        /**
         * Builds the button of the multiselect.
         */
        buildButton: function() {
            this.$button = $(this.options.templates.button).addClass(this.options.buttonClass);
            if (this.$select.attr('class') && this.options.inheritClass) {
                this.$button.addClass(this.$select.attr('class'));
            }
            // Adopt active state.
            if (this.$select.prop('disabled')) {
                this.disable();
            }
            else {
                this.enable();
            }

            // Manually add button width if set.
            if (this.options.buttonWidth && this.options.buttonWidth !== 'auto') {
                this.$button.css({
                    'width' : this.options.buttonWidth,
                    'overflow' : 'hidden',
                    'text-overflow' : 'ellipsis'
                });
                this.$container.css({
                    'width': this.options.buttonWidth
                });
            }

            // Keep the tab index from the select.
            var tabindex = this.$select.attr('tabindex');
            if (tabindex) {
                this.$button.attr('tabindex', tabindex);
            }

            this.$container.prepend(this.$button);
        },

        /**
         * Builds the ul representing the dropdown menu.
         */
        buildDropdown: function() {

            // Build ul.
            this.$ul = $(this.options.templates.ul);

            if (this.options.dropRight) {
                this.$ul.addClass('pull-right');
            }

            // Set max height of dropdown menu to activate auto scrollbar.
            if (this.options.maxHeight) {
                // TODO: Add a class for this option to move the css declarations.
                this.$ul.css({
                    'max-height': this.options.maxHeight + 'px',
                    'overflow-y': 'auto',
                    'overflow-x': 'hidden'
                });
            }

            this.$container.append(this.$ul);
        },

        /**
         * Build the dropdown options and binds all nessecary events.
         * 
         * Uses createDivider and createOptionValue to create the necessary options.
         */
        buildDropdownOptions: function() {

            this.$select.children().each($.proxy(function(index, element) {

                var $element = $(element);
                // Support optgroups and options without a group simultaneously.
                var tag = $element.prop('tagName')
                    .toLowerCase();
            
                if ($element.prop('value') === this.options.selectAllValue) {
                    return;
                }

                if (tag === 'optgroup') {
                    this.createOptgroup(element);
                }
                else if (tag === 'option') {

                    if ($element.data('role') === 'divider') {
                        this.createDivider();
                    }
                    else {
                        this.createOptionValue(element);
                    }

                }

                // Other illegal tags will be ignored.
            }, this));

            // Bind the change event on the dropdown elements.
            $('li input', this.$ul).on('change', $.proxy(function(event) {
                var $target = $(event.target);

                var checked = $target.prop('checked') || false;
                var isSelectAllOption = $target.val() === this.options.selectAllValue;

                // Apply or unapply the configured selected class.
                if (this.options.selectedClass) {
                    if (checked) {
                        $target.closest('li')
                            .addClass(this.options.selectedClass);
                    }
                    else {
                        $target.closest('li')
                            .removeClass(this.options.selectedClass);
                    }
                }

                // Get the corresponding option.
                var value = $target.val();
                var $option = this.getOptionByValue(value);

                var $optionsNotThis = $('option', this.$select).not($option);
                var $checkboxesNotThis = $('input', this.$container).not($target);

                if (isSelectAllOption) {
                    if (checked) {
                        this.selectAll();
                    }
                    else {
                        this.deselectAll();
                    }
                }

                if(!isSelectAllOption){
                    if (checked) {
                        $option.prop('selected', true);

                        if (this.options.multiple) {
                            // Simply select additional option.
                            $option.prop('selected', true);
                        }
                        else {
                            // Unselect all other options and corresponding checkboxes.
                            if (this.options.selectedClass) {
                                $($checkboxesNotThis).closest('li').removeClass(this.options.selectedClass);
                            }

                            $($checkboxesNotThis).prop('checked', false);
                            $optionsNotThis.prop('selected', false);

                            // It's a single selection, so close.
                            this.$button.click();
                        }

                        if (this.options.selectedClass === "active") {
                            $optionsNotThis.closest("a").css("outline", "");
                        }
                    }
                    else {
                        // Unselect option.
                        $option.prop('selected', false);
                    }
                }

                this.$select.change();

                this.updateButtonText();
                this.updateSelectAll();

                this.options.onChange($option, checked);

                if(this.options.preventInputChangeEvent) {
                    return false;
                }
            }, this));

            $('li a', this.$ul).on('mousedown', function(e) {
                if (e.shiftKey) {
                    // Prevent selecting text by Shift+click
                    return false;
                }
            });
        
            $('li a', this.$ul).on('touchstart click', $.proxy(function(event) {
                event.stopPropagation();

                var $target = $(event.target);
                
                if (event.shiftKey && this.options.multiple) {
                    if($target.is("label")){ // Handles checkbox selection manually (see https://github.com/davidstutz/bootstrap-multiselect/issues/431)
                        event.preventDefault();
                        $target = $target.find("input");
                        $target.prop("checked", !$target.prop("checked"));
                    }
                    var checked = $target.prop('checked') || false;

                    if (this.lastToggledInput !== null && this.lastToggledInput !== $target) { // Make sure we actually have a range
                        var from = $target.closest("li").index();
                        var to = this.lastToggledInput.closest("li").index();
                        
                        if (from > to) { // Swap the indices
                            var tmp = to;
                            to = from;
                            from = tmp;
                        }
                        
                        // Make sure we grab all elements since slice excludes the last index
                        ++to;
                        
                        // Change the checkboxes and underlying options
                        var range = this.$ul.find("li").slice(from, to).find("input");
                        
                        range.prop('checked', checked);
                        
                        if (this.options.selectedClass) {
                            range.closest('li')
                                .toggleClass(this.options.selectedClass, checked);
                        }
                        
                        for (var i = 0, j = range.length; i < j; i++) {
                            var $checkbox = $(range[i]);

                            var $option = this.getOptionByValue($checkbox.val());

                            $option.prop('selected', checked);
                        }                   
                    }
                    
                    // Trigger the select "change" event
                    $target.trigger("change");
                }
                
                // Remembers last clicked option
                if($target.is("input") && !$target.closest("li").is(".multiselect-item")){
                    this.lastToggledInput = $target;
                }

                $target.blur();
            }, this));

            // Keyboard support.
            this.$container.off('keydown.multiselect').on('keydown.multiselect', $.proxy(function(event) {
                if ($('input[type="text"]', this.$container).is(':focus')) {
                    return;
                }

                if (event.keyCode === 9 && this.$container.hasClass('open')) {
                    this.$button.click();
                }
                else {
                    var $items = $(this.$container).find("li:not(.divider):not(.disabled) a").filter(":visible");

                    if (!$items.length) {
                        return;
                    }

                    var index = $items.index($items.filter(':focus'));

                    // Navigation up.
                    if (event.keyCode === 38 && index > 0) {
                        index--;
                    }
                    // Navigate down.
                    else if (event.keyCode === 40 && index < $items.length - 1) {
                        index++;
                    }
                    else if (!~index) {
                        index = 0;
                    }

                    var $current = $items.eq(index);
                    $current.focus();

                    if (event.keyCode === 32 || event.keyCode === 13) {
                        var $checkbox = $current.find('input');

                        $checkbox.prop("checked", !$checkbox.prop("checked"));
                        $checkbox.change();
                    }

                    event.stopPropagation();
                    event.preventDefault();
                }
            }, this));

            if(this.options.enableClickableOptGroups && this.options.multiple) {
                $('li.multiselect-group', this.$ul).on('click', $.proxy(function(event) {
                    event.stopPropagation();

                    var group = $(event.target).parent();

                    // Search all option in optgroup
                    var $options = group.nextUntil('li.multiselect-group');
                    var $visibleOptions = $options.filter(":visible:not(.disabled)");

                    // check or uncheck items
                    var allChecked = true;
                    var optionInputs = $visibleOptions.find('input');
                    optionInputs.each(function() {
                        allChecked = allChecked && $(this).prop('checked');
                    });

                    optionInputs.prop('checked', !allChecked).trigger('change');
               }, this));
            }
        },

        /**
         * Create an option using the given select option.
         *
         * @param {jQuery} element
         */
        createOptionValue: function(element) {
            var $element = $(element);
            if ($element.is(':selected')) {
                $element.prop('selected', true);
            }

            // Support the label attribute on options.
            var label = this.options.optionLabel(element);
            var value = $element.val();
            var inputType = this.options.multiple ? "checkbox" : "radio";

            var $li = $(this.options.templates.li);
            var $label = $('label', $li);
            $label.addClass(inputType);

            if (this.options.enableHTML) {
                $label.html(" " + label);
            }
            else {
                $label.text(" " + label);
            }
        
            var $checkbox = $('<input/>').attr('type', inputType);

            if (this.options.checkboxName) {
                $checkbox.attr('name', this.options.checkboxName);
            }
            $label.prepend($checkbox);

            var selected = $element.prop('selected') || false;
            $checkbox.val(value);

            if (value === this.options.selectAllValue) {
                $li.addClass("multiselect-item multiselect-all");
                $checkbox.parent().parent()
                    .addClass('multiselect-all');
            }

            $label.attr('title', $element.attr('title'));

            this.$ul.append($li);

            if ($element.is(':disabled')) {
                $checkbox.attr('disabled', 'disabled')
                    .prop('disabled', true)
                    .closest('a')
                    .attr("tabindex", "-1")
                    .closest('li')
                    .addClass('disabled');
            }

            $checkbox.prop('checked', selected);

            if (selected && this.options.selectedClass) {
                $checkbox.closest('li')
                    .addClass(this.options.selectedClass);
            }
        },

        /**
         * Creates a divider using the given select option.
         *
         * @param {jQuery} element
         */
        createDivider: function(element) {
            var $divider = $(this.options.templates.divider);
            this.$ul.append($divider);
        },

        /**
         * Creates an optgroup.
         *
         * @param {jQuery} group
         */
        createOptgroup: function(group) {
            var groupName = $(group).prop('label');

            // Add a header for the group.
            var $li = $(this.options.templates.liGroup);
            
            if (this.options.enableHTML) {
                $('label', $li).html(groupName);
            }
            else {
                $('label', $li).text(groupName);
            }
            
            if (this.options.enableClickableOptGroups) {
                $li.addClass('multiselect-group-clickable');
            }

            this.$ul.append($li);

            if ($(group).is(':disabled')) {
                $li.addClass('disabled');
            }

            // Add the options of the group.
            $('option', group).each($.proxy(function(index, element) {
                this.createOptionValue(element);
            }, this));
        },

        /**
         * Build the selct all.
         * 
         * Checks if a select all has already been created.
         */
        buildSelectAll: function() {
            if (typeof this.options.selectAllValue === 'number') {
                this.options.selectAllValue = this.options.selectAllValue.toString();
            }
            
            var alreadyHasSelectAll = this.hasSelectAll();

            if (!alreadyHasSelectAll && this.options.includeSelectAllOption && this.options.multiple
                    && $('option', this.$select).length > this.options.includeSelectAllIfMoreThan) {

                // Check whether to add a divider after the select all.
                if (this.options.includeSelectAllDivider) {
                    this.$ul.prepend($(this.options.templates.divider));
                }

                var $li = $(this.options.templates.li);
                $('label', $li).addClass("checkbox");
                
                if (this.options.enableHTML) {
                    $('label', $li).html(" " + this.options.selectAllText);
                }
                else {
                    $('label', $li).text(" " + this.options.selectAllText);
                }
                
                if (this.options.selectAllName) {
                    $('label', $li).prepend('<input type="checkbox" name="' + this.options.selectAllName + '" />');
                }
                else {
                    $('label', $li).prepend('<input type="checkbox" />');
                }
                
                var $checkbox = $('input', $li);
                $checkbox.val(this.options.selectAllValue);

                $li.addClass("multiselect-item multiselect-all");
                $checkbox.parent().parent()
                    .addClass('multiselect-all');

                this.$ul.prepend($li);

                $checkbox.prop('checked', false);
            }
        },

        /**
         * Builds the filter.
         */
        buildFilter: function() {

            // Build filter if filtering OR case insensitive filtering is enabled and the number of options exceeds (or equals) enableFilterLength.
            if (this.options.enableFiltering || this.options.enableCaseInsensitiveFiltering) {
                var enableFilterLength = Math.max(this.options.enableFiltering, this.options.enableCaseInsensitiveFiltering);

                if (this.$select.find('option').length >= enableFilterLength) {

                    this.$filter = $(this.options.templates.filter);
                    $('input', this.$filter).attr('placeholder', this.options.filterPlaceholder);
                    
                    // Adds optional filter clear button
                    if(this.options.includeFilterClearBtn){
                        var clearBtn = $(this.options.templates.filterClearBtn);
                        clearBtn.on('click', $.proxy(function(event){
                            clearTimeout(this.searchTimeout);
                            this.$filter.find('.multiselect-search').val('');
                            $('li', this.$ul).show().removeClass("filter-hidden");
                            this.updateSelectAll();
                        }, this));
                        this.$filter.find('.input-group').append(clearBtn);
                    }
                    
                    this.$ul.prepend(this.$filter);

                    this.$filter.val(this.query).on('click', function(event) {
                        event.stopPropagation();
                    }).on('input keydown', $.proxy(function(event) {
                        // Cancel enter key default behaviour
                        if (event.which === 13) {
                          event.preventDefault();
                        }
                        
                        // This is useful to catch "keydown" events after the browser has updated the control.
                        clearTimeout(this.searchTimeout);

                        this.searchTimeout = this.asyncFunction($.proxy(function() {

                            if (this.query !== event.target.value) {
                                this.query = event.target.value;

                                var currentGroup, currentGroupVisible;
                                $.each($('li', this.$ul), $.proxy(function(index, element) {
                                    var value = $('input', element).length > 0 ? $('input', element).val() : "";
                                    var text = $('label', element).text();

                                    var filterCandidate = '';
                                    if ((this.options.filterBehavior === 'text')) {
                                        filterCandidate = text;
                                    }
                                    else if ((this.options.filterBehavior === 'value')) {
                                        filterCandidate = value;
                                    }
                                    else if (this.options.filterBehavior === 'both') {
                                        filterCandidate = text + '\n' + value;
                                    }

                                    if (value !== this.options.selectAllValue && text) {
                                        // By default lets assume that element is not
                                        // interesting for this search.
                                        var showElement = false;

                                        if (this.options.enableCaseInsensitiveFiltering && filterCandidate.toLowerCase().indexOf(this.query.toLowerCase()) > -1) {
                                            showElement = true;
                                        }
                                        else if (filterCandidate.indexOf(this.query) > -1) {
                                            showElement = true;
                                        }

                                        // Toggle current element (group or group item) according to showElement boolean.
                                        $(element).toggle(showElement).toggleClass('filter-hidden', !showElement);
                                        
                                        // Differentiate groups and group items.
                                        if ($(element).hasClass('multiselect-group')) {
                                            // Remember group status.
                                            currentGroup = element;
                                            currentGroupVisible = showElement;
                                        }
                                        else {
                                            // Show group name when at least one of its items is visible.
                                            if (showElement) {
                                                $(currentGroup).show().removeClass('filter-hidden');
                                            }
                                            
                                            // Show all group items when group name satisfies filter.
                                            if (!showElement && currentGroupVisible) {
                                                $(element).show().removeClass('filter-hidden');
                                            }
                                        }
                                    }
                                }, this));
                            }

                            this.updateSelectAll();
                        }, this), 300, this);
                    }, this));
                }
            }
        },

        /**
         * Unbinds the whole plugin.
         */
        destroy: function() {
            this.$container.remove();
            this.$select.show();
            this.$select.data('multiselect', null);
        },

        /**
         * Refreshs the multiselect based on the selected options of the select.
         */
        refresh: function() {
            $('option', this.$select).each($.proxy(function(index, element) {
                var $input = $('li input', this.$ul).filter(function() {
                    return $(this).val() === $(element).val();
                });

                if ($(element).is(':selected')) {
                    $input.prop('checked', true);

                    if (this.options.selectedClass) {
                        $input.closest('li')
                            .addClass(this.options.selectedClass);
                    }
                }
                else {
                    $input.prop('checked', false);

                    if (this.options.selectedClass) {
                        $input.closest('li')
                            .removeClass(this.options.selectedClass);
                    }
                }

                if ($(element).is(":disabled")) {
                    $input.attr('disabled', 'disabled')
                        .prop('disabled', true)
                        .closest('li')
                        .addClass('disabled');
                }
                else {
                    $input.prop('disabled', false)
                        .closest('li')
                        .removeClass('disabled');
                }
            }, this));

            this.updateButtonText();
            this.updateSelectAll();
        },

        /**
         * Select all options of the given values.
         * 
         * If triggerOnChange is set to true, the on change event is triggered if
         * and only if one value is passed.
         * 
         * @param {Array} selectValues
         * @param {Boolean} triggerOnChange
         */
        select: function(selectValues, triggerOnChange) {
            if(!$.isArray(selectValues)) {
                selectValues = [selectValues];
            }

            for (var i = 0; i < selectValues.length; i++) {
                var value = selectValues[i];

                if (value === null || value === undefined) {
                    continue;
                }

                var $option = this.getOptionByValue(value);
                var $checkbox = this.getInputByValue(value);

                if($option === undefined || $checkbox === undefined) {
                    continue;
                }
                
                if (!this.options.multiple) {
                    this.deselectAll(false);
                }
                
                if (this.options.selectedClass) {
                    $checkbox.closest('li')
                        .addClass(this.options.selectedClass);
                }

                $checkbox.prop('checked', true);
                $option.prop('selected', true);
                
                if (triggerOnChange) {
                    this.options.onChange($option, true);
                }
            }

            this.updateButtonText();
            this.updateSelectAll();
        },

        /**
         * Clears all selected items.
         */
        clearSelection: function () {
            this.deselectAll(false);
            this.updateButtonText();
            this.updateSelectAll();
        },

        /**
         * Deselects all options of the given values.
         * 
         * If triggerOnChange is set to true, the on change event is triggered, if
         * and only if one value is passed.
         * 
         * @param {Array} deselectValues
         * @param {Boolean} triggerOnChange
         */
        deselect: function(deselectValues, triggerOnChange) {
            if(!$.isArray(deselectValues)) {
                deselectValues = [deselectValues];
            }

            for (var i = 0; i < deselectValues.length; i++) {
                var value = deselectValues[i];

                if (value === null || value === undefined) {
                    continue;
                }

                var $option = this.getOptionByValue(value);
                var $checkbox = this.getInputByValue(value);

                if($option === undefined || $checkbox === undefined) {
                    continue;
                }

                if (this.options.selectedClass) {
                    $checkbox.closest('li')
                        .removeClass(this.options.selectedClass);
                }

                $checkbox.prop('checked', false);
                $option.prop('selected', false);
                
                if (triggerOnChange) {
                    this.options.onChange($option, false);
                }
            }

            this.updateButtonText();
            this.updateSelectAll();
        },
        
        /**
         * Selects all enabled & visible options.
         *
         * If justVisible is true or not specified, only visible options are selected.
         *
         * @param {Boolean} justVisible
         * @param {Boolean} triggerOnSelectAll
         */
        selectAll: function (justVisible, triggerOnSelectAll) {
            var justVisible = typeof justVisible === 'undefined' ? true : justVisible;
            var allCheckboxes = $("li input[type='checkbox']:enabled", this.$ul);
            var visibleCheckboxes = allCheckboxes.filter(":visible");
            var allCheckboxesCount = allCheckboxes.length;
            var visibleCheckboxesCount = visibleCheckboxes.length;
            
            if(justVisible) {
                visibleCheckboxes.prop('checked', true);
                $("li:not(.divider):not(.disabled)", this.$ul).filter(":visible").addClass(this.options.selectedClass);
            }
            else {
                allCheckboxes.prop('checked', true);
                $("li:not(.divider):not(.disabled)", this.$ul).addClass(this.options.selectedClass);
            }
                
            if (allCheckboxesCount === visibleCheckboxesCount || justVisible === false) {
                $("option:enabled", this.$select).prop('selected', true);
            }
            else {
                var values = visibleCheckboxes.map(function() {
                    return $(this).val();
                }).get();
                
                $("option:enabled", this.$select).filter(function(index) {
                    return $.inArray($(this).val(), values) !== -1;
                }).prop('selected', true);
            }
            
            if (triggerOnSelectAll) {
                this.options.onSelectAll();
            }
        },

        /**
         * Deselects all options.
         * 
         * If justVisible is true or not specified, only visible options are deselected.
         * 
         * @param {Boolean} justVisible
         */
        deselectAll: function (justVisible) {
            var justVisible = typeof justVisible === 'undefined' ? true : justVisible;
            
            if(justVisible) {              
                var visibleCheckboxes = $("li input[type='checkbox']:not(:disabled)", this.$ul).filter(":visible");
                visibleCheckboxes.prop('checked', false);
                
                var values = visibleCheckboxes.map(function() {
                    return $(this).val();
                }).get();
                
                $("option:enabled", this.$select).filter(function(index) {
                    return $.inArray($(this).val(), values) !== -1;
                }).prop('selected', false);
                
                if (this.options.selectedClass) {
                    $("li:not(.divider):not(.disabled)", this.$ul).filter(":visible").removeClass(this.options.selectedClass);
                }
            }
            else {
                $("li input[type='checkbox']:enabled", this.$ul).prop('checked', false);
                $("option:enabled", this.$select).prop('selected', false);
                
                if (this.options.selectedClass) {
                    $("li:not(.divider):not(.disabled)", this.$ul).removeClass(this.options.selectedClass);
                }
            }
        },

        /**
         * Rebuild the plugin.
         * 
         * Rebuilds the dropdown, the filter and the select all option.
         */
        rebuild: function() {
            this.$ul.html('');

            // Important to distinguish between radios and checkboxes.
            this.options.multiple = this.$select.attr('multiple') === "multiple";

            this.buildSelectAll();
            this.buildDropdownOptions();
            this.buildFilter();

            this.updateButtonText();
            this.updateSelectAll();
            
            if (this.options.disableIfEmpty && $('option', this.$select).length <= 0) {
                this.disable();
            }
            else {
                this.enable();
            }
            
            if (this.options.dropRight) {
                this.$ul.addClass('pull-right');
            }
        },

        /**
         * The provided data will be used to build the dropdown.
         */
        dataprovider: function(dataprovider) {
            
            var groupCounter = 0;
            var $select = this.$select.empty();
            
            $.each(dataprovider, function (index, option) {
                var $tag;
                
                if ($.isArray(option.children)) { // create optiongroup tag
                    groupCounter++;
                    
                    $tag = $('<optgroup/>').attr({
                        label: option.label || 'Group ' + groupCounter,
                        disabled: !!option.disabled
                    });
                    
                    forEach(option.children, function(subOption) { // add children option tags
                        $tag.append($('<option/>').attr({
                            value: subOption.value,
                            label: subOption.label || subOption.value,
                            title: subOption.title,
                            selected: !!subOption.selected,
                            disabled: !!subOption.disabled
                        }));
                    });
                }
                else {
                    $tag = $('<option/>').attr({
                        value: option.value,
                        label: option.label || option.value,
                        title: option.title,
                        selected: !!option.selected,
                        disabled: !!option.disabled
                    });
                }
                
                $select.append($tag);
            });
            
            this.rebuild();
        },

        /**
         * Enable the multiselect.
         */
        enable: function() {
            this.$select.prop('disabled', false);
            this.$button.prop('disabled', false)
                .removeClass('disabled');
        },

        /**
         * Disable the multiselect.
         */
        disable: function() {
            this.$select.prop('disabled', true);
            this.$button.prop('disabled', true)
                .addClass('disabled');
        },

        /**
         * Set the options.
         *
         * @param {Array} options
         */
        setOptions: function(options) {
            this.options = this.mergeOptions(options);
        },

        /**
         * Merges the given options with the default options.
         *
         * @param {Array} options
         * @returns {Array}
         */
        mergeOptions: function(options) {
            return $.extend(true, {}, this.defaults, this.options, options);
        },

        /**
         * Checks whether a select all checkbox is present.
         *
         * @returns {Boolean}
         */
        hasSelectAll: function() {
            return $('li.multiselect-all', this.$ul).length > 0;
        },

        /**
         * Updates the select all checkbox based on the currently displayed and selected checkboxes.
         */
        updateSelectAll: function() {
            if (this.hasSelectAll()) {
                var allBoxes = $("li:not(.multiselect-item):not(.filter-hidden) input:enabled", this.$ul);
                var allBoxesLength = allBoxes.length;
                var checkedBoxesLength = allBoxes.filter(":checked").length;
                var selectAllLi  = $("li.multiselect-all", this.$ul);
                var selectAllInput = selectAllLi.find("input");
                
                if (checkedBoxesLength > 0 && checkedBoxesLength === allBoxesLength) {
                    selectAllInput.prop("checked", true);
                    selectAllLi.addClass(this.options.selectedClass);
                    this.options.onSelectAll();
                }
                else {
                    selectAllInput.prop("checked", false);
                    selectAllLi.removeClass(this.options.selectedClass);
                }
            }
        },

        /**
         * Update the button text and its title based on the currently selected options.
         */
        updateButtonText: function() {
            var options = this.getSelected();
            
            // First update the displayed button text.
            if (this.options.enableHTML) {
                $('.multiselect .multiselect-selected-text', this.$container).html(this.options.buttonText(options, this.$select));
            }
            else {
                $('.multiselect .multiselect-selected-text', this.$container).text(this.options.buttonText(options, this.$select));
            }
            
            // Now update the title attribute of the button.
            $('.multiselect', this.$container).attr('title', this.options.buttonTitle(options, this.$select));
        },

        /**
         * Get all selected options.
         *
         * @returns {jQUery}
         */
        getSelected: function() {
            return $('option', this.$select).filter(":selected");
        },

        /**
         * Gets a select option by its value.
         *
         * @param {String} value
         * @returns {jQuery}
         */
        getOptionByValue: function (value) {

            var options = $('option', this.$select);
            var valueToCompare = value.toString();

            for (var i = 0; i < options.length; i = i + 1) {
                var option = options[i];
                if (option.value === valueToCompare) {
                    return $(option);
                }
            }
        },

        /**
         * Get the input (radio/checkbox) by its value.
         *
         * @param {String} value
         * @returns {jQuery}
         */
        getInputByValue: function (value) {

            var checkboxes = $('li input', this.$ul);
            var valueToCompare = value.toString();

            for (var i = 0; i < checkboxes.length; i = i + 1) {
                var checkbox = checkboxes[i];
                if (checkbox.value === valueToCompare) {
                    return $(checkbox);
                }
            }
        },

        /**
         * Used for knockout integration.
         */
        updateOriginalOptions: function() {
            this.originalOptions = this.$select.clone()[0].options;
        },

        asyncFunction: function(callback, timeout, self) {
            var args = Array.prototype.slice.call(arguments, 3);
            return setTimeout(function() {
                callback.apply(self || window, args);
            }, timeout);
        },

        setAllSelectedText: function(allSelectedText) {
            this.options.allSelectedText = allSelectedText;
            this.updateButtonText();
        }
    };

    $.fn.multiselect = function(option, parameter, extraOptions) {
        return this.each(function() {
            var data = $(this).data('multiselect');
            var options = typeof option === 'object' && option;

            // Initialize the multiselect.
            if (!data) {
                data = new Multiselect(this, options);
                $(this).data('multiselect', data);
            }

            // Call multiselect method.
            if (typeof option === 'string') {
                data[option](parameter, extraOptions);
                
                if (option === 'destroy') {
                    $(this).data('multiselect', false);
                }
            }
        });
    };

    $.fn.multiselect.Constructor = Multiselect;

    $(function() {
        $("select[data-role=multiselect]").multiselect();
    });

}(__webpack_provided_window_dot_jQuery);

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/highcharts-grouped-categories/grouped-categories.js":
/*!**************************************************************************!*\
  !*** ./node_modules/highcharts-grouped-categories/grouped-categories.js ***!
  \**************************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

/* global Highcharts module window:true */
(function (factory) {
	if (typeof module === 'object' && module.exports) {
		module.exports = factory;
	} else {
		factory(Highcharts);
	}
}(function (HC) {
	'use strict';
	/**
	 * Grouped Categories v1.1.3 (2017-03-27)
	 *
	 * (c) 2012-2016 Black Label
	 *
	 * License: Creative Commons Attribution (CC)
	 */

	/* jshint expr:true, boss:true */
	var UNDEFINED = void 0,
		mathRound = Math.round,
		mathMin = Math.min,
		mathMax = Math.max,
		merge = HC.merge,
		pick = HC.pick,
		each = HC.each,
		// #74, since Highcharts 4.1.10 HighchartsAdapter is only provided by the Highcharts Standalone Framework
		inArray = (window.HighchartsAdapter && window.HighchartsAdapter.inArray) || HC.inArray,

		// cache prototypes
		axisProto = HC.Axis.prototype,
		tickProto = HC.Tick.prototype,

		// cache original methods
		protoAxisInit = axisProto.init,
		protoAxisRender = axisProto.render,
		protoAxisSetCategories = axisProto.setCategories,
		protoTickGetLabelSize = tickProto.getLabelSize,
		protoTickAddLabel = tickProto.addLabel,
		protoTickDestroy = tickProto.destroy,
		protoTickRender = tickProto.render;

	function deepClone(thing) {
		return JSON.parse(JSON.stringify(thing));
	}

	function Category(obj, parent) {
		this.userOptions = deepClone(obj);
		this.name = obj.name || obj;
		this.parent = parent;

		return this;
	}

	Category.prototype.toString = function () {
		var parts = [],
			cat = this;

		while (cat) {
			parts.push(cat.name);
			cat = cat.parent;
		}

		return parts.join(', ');
	};

	// returns sum of an array
	function sum(arr) {
		var l = arr.length,
			x = 0;

		while (l--) {
			x += arr[l];
		}

		return x;
	}

	// Adds category leaf to array
	function addLeaf(out, cat, parent) {
		out.unshift(new Category(cat, parent));

		while (parent) {
			parent.leaves = parent.leaves ? (parent.leaves + 1) : 1;
			parent = parent.parent;
		}
	}

	// Builds reverse category tree
	function buildTree(cats, out, options, parent, depth) {
		var len = cats.length,
			cat;

		depth = depth ? depth : 0;
		options.depth = options.depth ? options.depth : 0;

		while (len--) {
			cat = cats[len];
			
			if (cat.categories) {
				if (parent) {
					cat.parent = parent;
				}
				buildTree(cat.categories, out, options, cat, depth + 1);
			} else {
				addLeaf(out, cat, parent);
			}
		}
		options.depth = mathMax(options.depth, depth);
	}

	// Pushes part of grid to path
	function addGridPart(path, d, width) {
		// Based on crispLine from HC (#65)
		if (d[0] === d[2]) {
			d[0] = d[2] = mathRound(d[0]) - (width % 2 / 2);
		}
		if (d[1] === d[3]) {
			d[1] = d[3] = mathRound(d[1]) + (width % 2 / 2);
		}

		path.push(
			'M',
			d[0], d[1],
			'L',
			d[2], d[3]
		);
	}

	// Returns tick position
	function tickPosition(tick, pos) {
		return tick.getPosition(tick.axis.horiz, pos, tick.axis.tickmarkOffset);
	}

	function walk(arr, key, fn) {
		var l = arr.length,
			children;

		while (l--) {
			children = arr[l][key];

			if (children) {
				walk(children, key, fn);
			}
			fn(arr[l]);
		}
	}

	//
	// Axis prototype
	//

	axisProto.init = function (chart, options) {
		// default behaviour
		protoAxisInit.call(this, chart, options);

		if (typeof options === 'object' && options.categories) {
			this.setupGroups(options);
		}
	};

	// setup required axis options
	axisProto.setupGroups = function (options) {
		var categories = deepClone(options.categories),
			reverseTree = [],
			stats = {},
			labelOptions = this.options.labels,
			userAttr = labelOptions.groupedOptions,
			css = labelOptions.style;

		// build categories tree
		buildTree(categories, reverseTree, stats);

		// set axis properties
		this.categoriesTree = categories;
		this.categories = reverseTree;
		this.isGrouped = stats.depth !== 0;
		this.labelsDepth = stats.depth;
		this.labelsSizes = [];
		this.labelsGridPath = [];
		this.tickLength = options.tickLength || this.tickLength || null;
		// #66: tickWidth for x axis defaults to 1, for y to 0
		this.tickWidth = pick(options.tickWidth, this.isXAxis ? 1 : 0);
		this.directionFactor = [-1, 1, 1, -1][this.side];
		this.options.lineWidth = pick(options.lineWidth, 1);
		// #85: align labels vertically
		this.groupFontHeights = [];
		for (var i = 0; i <= stats.depth; i++) {
			var hasOptions = userAttr && userAttr[i - 1],
				mergedCSS = hasOptions && userAttr[i - 1].style ? merge(css, userAttr[i - 1].style) : css;
			this.groupFontHeights[i] = Math.round(this.chart.renderer.fontMetrics(mergedCSS ? mergedCSS.fontSize : 0).b * 0.3);
		}
	};


	axisProto.render = function () {
		// clear grid path
		if (this.isGrouped) {
			this.labelsGridPath = [];
		}

		// cache original tick length
		if (this.originalTickLength === UNDEFINED) {
			this.originalTickLength = this.options.tickLength;
		}

		// use default tickLength for not-grouped axis
		// and generate grid on grouped axes,
		// use tiny number to force highcharts to hide tick
		this.options.tickLength = this.isGrouped ? 0.001 : this.originalTickLength;

		protoAxisRender.call(this);

		if (!this.isGrouped) {
			if (this.labelsGrid) {
				this.labelsGrid.attr({
					visibility: 'hidden'
				});
			}
			return false;
		}

		var axis = this,
			options = axis.options,
			top = axis.top,
			left = axis.left,
			right = left + axis.width,
			bottom = top + axis.height,
			visible = axis.hasVisibleSeries || axis.hasData,
			depth = axis.labelsDepth,
			grid = axis.labelsGrid,
			horiz = axis.horiz,
			d = axis.labelsGridPath,
			i = options.drawHorizontalBorders === false ? (depth + 1) : 0,
			offset = axis.opposite ? (horiz ? top : right) : (horiz ? bottom : left),
			tickWidth = axis.tickWidth,
			part;

		if (axis.userTickLength) {
			depth -= 1;
		}

		// render grid path for the first time
		if (!grid) {
			grid = axis.labelsGrid = axis.chart.renderer.path()
			.attr({
				// #58: use tickWidth/tickColor instead of lineWidth/lineColor:
				strokeWidth: tickWidth, // < 4.0.3
				'stroke-width': tickWidth, // 4.0.3+ #30
				stroke: options.tickColor || '' // for styled mode (tickColor === undefined)
			})
			.add(axis.axisGroup);
			// for styled mode - add class
			if (!options.tickColor) {
				grid.addClass('highcharts-tick');
			}
		}

		// go through every level and draw horizontal grid line
		while (i <= depth) {
			offset += axis.groupSize(i);

			part = horiz ?
				[left, offset, right, offset] :
				[offset, top, offset, bottom];

			addGridPart(d, part, tickWidth);
			i++;
		}

		// draw grid path
		grid.attr({
			d: d,
			visibility: visible ? 'visible' : 'hidden'
		});

		axis.labelGroup.attr({
			visibility: visible ? 'visible' : 'hidden'
		});


		walk(axis.categoriesTree, 'categories', function (group) {
			var tick = group.tick;

			if (!tick) {
				return false;
			}
			if (tick.startAt + tick.leaves - 1 < axis.min || tick.startAt > axis.max) {
				tick.label.hide();
				tick.destroyed = 0;
			} else {
				tick.label.attr({
					visibility: visible ? 'visible' : 'hidden'
				});
			}
			return true;
		});
		return true;
	};

	axisProto.setCategories = function (newCategories, doRedraw) {
		if (this.categories) {
			this.cleanGroups();
		}
		this.setupGroups({
			categories: newCategories
		});
		this.categories = this.userOptions.categories = newCategories;
		protoAxisSetCategories.call(this, this.categories, doRedraw);
	};

	// cleans old categories
	axisProto.cleanGroups = function () {
		var ticks = this.ticks,
			n;

		for (n in ticks) {
			if (ticks[n].parent) {
				delete ticks[n].parent;
			}
		}
		walk(this.categoriesTree, 'categories', function (group) {
			var tick = group.tick;
			
			if (!tick) {
				return false;
			}
			tick.label.destroy();
			
			each(tick, function (v, i) {
				delete tick[i];
			});
			delete group.tick;
			
			return true;
		});
		this.labelsGrid = null;
	};

	// keeps size of each categories level
	axisProto.groupSize = function (level, position) {
		var positions = this.labelsSizes,
			direction = this.directionFactor,
			groupedOptions = this.options.labels.groupedOptions ? this.options.labels.groupedOptions[level - 1] : false,
			userXY = 0;

		if (groupedOptions) {
			if (direction === -1) {
				userXY = groupedOptions.x ? groupedOptions.x : 0;
			} else {
				userXY = groupedOptions.y ? groupedOptions.y : 0;
			}
		}

		if (position !== UNDEFINED) {
			positions[level] = mathMax(positions[level] || 0, position + 10 + Math.abs(userXY));
		}

		if (level === true) {
			return sum(positions) * direction;
		} else if (positions[level]) {
			return positions[level] * direction;
		}

		return 0;
	};

	//
	// Tick prototype
	//

	// Override methods prototypes
	tickProto.addLabel = function () {
		var tick = this,
			axis = tick.axis,
			category;
		
		protoTickAddLabel.call(tick);
		
		if (!axis.categories || !(category = axis.categories[tick.pos])) {
			return false;
		}
		
		// set label text - but applied after formatter #46
		if (tick.label) {
			tick.label.attr('text', tick.axis.labelFormatter.call({
				axis: axis,
				chart: axis.chart,
				isFirst: tick.isFirst,
				isLast: tick.isLast,
				value: category.name,
				pos: tick.pos
			}));
		}
		
		// create elements for parent categories
		if (axis.isGrouped && axis.options.labels.enabled) {
			tick.addGroupedLabels(category);
		}
		return true;
	};

	// render ancestor label
	tickProto.addGroupedLabels = function (category) {
		var tick = this,
			axis = this.axis,
			chart = axis.chart,
			options = axis.options.labels,
			useHTML = options.useHTML,
			css = options.style,
			userAttr = options.groupedOptions,
			attr = {
				align: 'center',
				rotation: options.rotation,
				x: 0,
				y: 0
			},
			size = axis.horiz ? 'height' : 'width',
			depth = 0,
			label;


		while (tick) {
			if (depth > 0 && !category.tick) {
				// render label element
				this.value = category.name;
				var name = options.formatter ? options.formatter.call(this, category) : category.name,
					hasOptions = userAttr && userAttr[depth - 1],
					mergedAttrs = hasOptions ? merge(attr, userAttr[depth - 1]) : attr,
					mergedCSS = hasOptions && userAttr[depth - 1].style ? merge(css, userAttr[depth - 1].style) : css;

				// #63: style is passed in CSS and not as an attribute
				delete mergedAttrs.style;

				label = chart.renderer.text(name, 0, 0, useHTML)
					.attr(mergedAttrs)
					.css(mergedCSS)
					.add(axis.labelGroup);

				// tick properties
				tick.startAt = this.pos;
				tick.childCount = category.categories.length;
				tick.leaves = category.leaves;
				tick.visible = this.childCount;
				tick.label = label;
				tick.labelOffsets = {
					x: mergedAttrs.x,
					y: mergedAttrs.y
				};

				// link tick with category
				category.tick = tick;
			}

			// set level size, #93
			if (tick) {
				axis.groupSize(depth, tick.label.getBBox()[size]);
			}

			// go up to the parent category
			category = category.parent;

			if (category) {
				tick = tick.parent = category.tick || {};
			} else {
				tick = null;
			}

			depth++;
		}
	};

	// set labels position & render categories grid
	tickProto.render = function (index, old, opacity) {
		protoTickRender.call(this, index, old, opacity);

		var treeCat = this.axis.categories[this.pos];

		if (!this.axis.isGrouped || !treeCat || this.pos > this.axis.max) {
			return;
		}

		var tick = this,
			group = tick,
			axis = tick.axis,
			tickPos = tick.pos,
			isFirst = tick.isFirst,
			max = axis.max,
			min = axis.min,
			horiz = axis.horiz,
			grid = axis.labelsGridPath,
			size = axis.groupSize(0),
			tickWidth = axis.tickWidth,
			xy = tickPosition(tick, tickPos),
			start = horiz ? xy.y : xy.x,
			baseLine = axis.chart.renderer.fontMetrics(axis.options.labels.style ? axis.options.labels.style.fontSize : 0).b,
			depth = 1,
			reverseCrisp = ((horiz && xy.x === axis.pos + axis.len) || (!horiz && xy.y === axis.pos)) ? -1 : 0, // adjust grid lines for edges
			gridAttrs,
			lvlSize,
			minPos,
			maxPos,
			attrs,
			bBox;

		// render grid for "normal" categories (first-level), render left grid line only for the first category
		if (isFirst) {
			gridAttrs = horiz ?
				[axis.left, xy.y, axis.left, xy.y + axis.groupSize(true)] : axis.isXAxis ?
					[xy.x, axis.top, xy.x + axis.groupSize(true), axis.top] : [xy.x, axis.top + axis.len, xy.x + axis.groupSize(true), axis.top + axis.len];

			addGridPart(grid, gridAttrs, tickWidth);
		}

		if (horiz && axis.left < xy.x) {
			addGridPart(grid, [xy.x - reverseCrisp, xy.y, xy.x - reverseCrisp, xy.y + size], tickWidth);
		} else if (!horiz && axis.top <= xy.y) {
			addGridPart(grid, [xy.x, xy.y + reverseCrisp, xy.x + size, xy.y + reverseCrisp], tickWidth);
		}

		size = start + size;

		function fixOffset(tCat) {
			var ret = 0;
			if (isFirst) {
				ret = inArray(tCat.name, tCat.parent.categories);
				ret = ret < 0 ? 0 : ret;
				return ret;
			}
			return ret;
		}


		while (group.parent) {
			group = group.parent;
			
			var fix = fixOffset(treeCat),
				userX = group.labelOffsets.x,
				userY = group.labelOffsets.y;
			
			minPos = tickPosition(tick, mathMax(group.startAt - 1, min - 1));
			maxPos = tickPosition(tick, mathMin(group.startAt + group.leaves - 1 - fix, max));
			bBox = group.label.getBBox(true);
			lvlSize = axis.groupSize(depth);
			// check if on the edge to adjust
			reverseCrisp = ((horiz && maxPos.x === axis.pos + axis.len) || (!horiz && maxPos.y === axis.pos)) ? -1 : 0;
			
			attrs = horiz ? {
				x: (minPos.x + maxPos.x) / 2 + userX,
				y: size + axis.groupFontHeights[depth] + lvlSize / 2 + userY / 2
			} : {
				x: size + lvlSize / 2 + userX,
				y: (minPos.y + maxPos.y - bBox.height) / 2 + baseLine + userY
			};
			
			if (!isNaN(attrs.x) && !isNaN(attrs.y)) {
				group.label.attr(attrs);

				if (grid) {
					if (horiz && axis.left < maxPos.x) {
						addGridPart(grid, [maxPos.x - reverseCrisp, size, maxPos.x - reverseCrisp, size + lvlSize], tickWidth);
					} else if (!horiz && axis.top <= maxPos.y) {
						addGridPart(grid, [size, maxPos.y + reverseCrisp, size + lvlSize, maxPos.y + reverseCrisp], tickWidth);
					}
				}
			}

			size += lvlSize;
			depth++;
		}
	};

	tickProto.destroy = function () {
		var group = this.parent;

		while (group) {
			group.destroyed = group.destroyed ? (group.destroyed + 1) : 1;
			group = group.parent;
		}

		protoTickDestroy.call(this);
	};

	// return size of the label (height for horizontal, width for vertical axes)
	tickProto.getLabelSize = function () {
		if (this.axis.isGrouped === true) {
			// #72, getBBox might need recalculating when chart is tall
			var size = protoTickGetLabelSize.call(this) + 10,
				topLabelSize = this.axis.labelsSizes[0];
			if (topLabelSize < size) {
				this.axis.labelsSizes[0] = size;
			}
			return sum(this.axis.labelsSizes);
		}
		return protoTickGetLabelSize.call(this);
	};

}));

/***/ }),

/***/ "./node_modules/highcharts/highcharts.js":
/*!***********************************************!*\
  !*** ./node_modules/highcharts/highcharts.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! exports used: default */
/***/ (function(module, exports) {

/*
 Highcharts JS v6.1.1 (2018-06-27)

 (c) 2009-2016 Torstein Honsi

 License: www.highcharts.com/license
*/
(function(T,K){"object"===typeof module&&module.exports?module.exports=T.document?K(T):K:T.Highcharts=K(T)})("undefined"!==typeof window?window:this,function(T){var K=function(){var a="undefined"===typeof T?window:T,C=a.document,E=a.navigator&&a.navigator.userAgent||"",F=C&&C.createElementNS&&!!C.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect,n=/(edge|msie|trident)/i.test(E)&&!a.opera,h=-1!==E.indexOf("Firefox"),e=-1!==E.indexOf("Chrome"),u=h&&4>parseInt(E.split("Firefox/")[1],
10);return a.Highcharts?a.Highcharts.error(16,!0):{product:"Highcharts",version:"6.1.1",deg2rad:2*Math.PI/360,doc:C,hasBidiBug:u,hasTouch:C&&void 0!==C.documentElement.ontouchstart,isMS:n,isWebKit:-1!==E.indexOf("AppleWebKit"),isFirefox:h,isChrome:e,isSafari:!e&&-1!==E.indexOf("Safari"),isTouchDevice:/(Mobile|Android|Windows Phone)/.test(E),SVG_NS:"http://www.w3.org/2000/svg",chartCount:0,seriesTypes:{},symbolSizes:{},svg:F,win:a,marginNames:["plotTop","marginRight","marginBottom","plotLeft"],noop:function(){},
charts:[]}}();(function(a){a.timers=[];var C=a.charts,E=a.doc,F=a.win;a.error=function(n,h){n=a.isNumber(n)?"Highcharts error #"+n+": www.highcharts.com/errors/"+n:n;if(h)throw Error(n);F.console&&console.log(n)};a.Fx=function(a,h,e){this.options=h;this.elem=a;this.prop=e};a.Fx.prototype={dSetter:function(){var a=this.paths[0],h=this.paths[1],e=[],u=this.now,y=a.length,q;if(1===u)e=this.toD;else if(y===h.length&&1>u)for(;y--;)q=parseFloat(a[y]),e[y]=isNaN(q)?h[y]:u*parseFloat(h[y]-q)+q;else e=h;this.elem.attr("d",
e,null,!0)},update:function(){var a=this.elem,h=this.prop,e=this.now,u=this.options.step;if(this[h+"Setter"])this[h+"Setter"]();else a.attr?a.element&&a.attr(h,e,null,!0):a.style[h]=e+this.unit;u&&u.call(a,e,this)},run:function(n,h,e){var u=this,y=u.options,q=function(a){return q.stopped?!1:u.step(a)},x=F.requestAnimationFrame||function(a){setTimeout(a,13)},f=function(){for(var c=0;c<a.timers.length;c++)a.timers[c]()||a.timers.splice(c--,1);a.timers.length&&x(f)};n!==h||this.elem["forceAnimate:"+
this.prop]?(this.startTime=+new Date,this.start=n,this.end=h,this.unit=e,this.now=this.start,this.pos=0,q.elem=this.elem,q.prop=this.prop,q()&&1===a.timers.push(q)&&x(f)):(delete y.curAnim[this.prop],y.complete&&0===a.keys(y.curAnim).length&&y.complete.call(this.elem))},step:function(n){var h=+new Date,e,u=this.options,y=this.elem,q=u.complete,x=u.duration,f=u.curAnim;y.attr&&!y.element?n=!1:n||h>=x+this.startTime?(this.now=this.end,this.pos=1,this.update(),e=f[this.prop]=!0,a.objectEach(f,function(a){!0!==
a&&(e=!1)}),e&&q&&q.call(y),n=!1):(this.pos=u.easing((h-this.startTime)/x),this.now=this.start+(this.end-this.start)*this.pos,this.update(),n=!0);return n},initPath:function(n,h,e){function u(a){var d,l;for(b=a.length;b--;)d="M"===a[b]||"L"===a[b],l=/[a-zA-Z]/.test(a[b+3]),d&&l&&a.splice(b+1,0,a[b+1],a[b+2],a[b+1],a[b+2])}function y(a,d){for(;a.length<l;){a[0]=d[l-a.length];var c=a.slice(0,r);[].splice.apply(a,[0,0].concat(c));v&&(c=a.slice(a.length-r),[].splice.apply(a,[a.length,0].concat(c)),b--)}a[0]=
"M"}function q(a,b){for(var c=(l-a.length)/r;0<c&&c--;)d=a.slice().splice(a.length/p-r,r*p),d[0]=b[l-r-c*r],k&&(d[r-6]=d[r-2],d[r-5]=d[r-1]),[].splice.apply(a,[a.length/p,0].concat(d)),v&&c--}h=h||"";var x,f=n.startX,c=n.endX,k=-1<h.indexOf("C"),r=k?7:3,l,d,b;h=h.split(" ");e=e.slice();var v=n.isArea,p=v?2:1,I;k&&(u(h),u(e));if(f&&c){for(b=0;b<f.length;b++)if(f[b]===c[0]){x=b;break}else if(f[0]===c[c.length-f.length+b]){x=b;I=!0;break}void 0===x&&(h=[])}h.length&&a.isNumber(x)&&(l=e.length+x*p*r,
I?(y(h,e),q(e,h)):(y(e,h),q(h,e)));return[h,e]}};a.Fx.prototype.fillSetter=a.Fx.prototype.strokeSetter=function(){this.elem.attr(this.prop,a.color(this.start).tweenTo(a.color(this.end),this.pos),null,!0)};a.merge=function(){var n,h=arguments,e,u={},y=function(e,n){"object"!==typeof e&&(e={});a.objectEach(n,function(f,c){!a.isObject(f,!0)||a.isClass(f)||a.isDOMElement(f)?e[c]=n[c]:e[c]=y(e[c]||{},f)});return e};!0===h[0]&&(u=h[1],h=Array.prototype.slice.call(h,2));e=h.length;for(n=0;n<e;n++)u=y(u,
h[n]);return u};a.pInt=function(a,h){return parseInt(a,h||10)};a.isString=function(a){return"string"===typeof a};a.isArray=function(a){a=Object.prototype.toString.call(a);return"[object Array]"===a||"[object Array Iterator]"===a};a.isObject=function(n,h){return!!n&&"object"===typeof n&&(!h||!a.isArray(n))};a.isDOMElement=function(n){return a.isObject(n)&&"number"===typeof n.nodeType};a.isClass=function(n){var h=n&&n.constructor;return!(!a.isObject(n,!0)||a.isDOMElement(n)||!h||!h.name||"Object"===
h.name)};a.isNumber=function(a){return"number"===typeof a&&!isNaN(a)&&Infinity>a&&-Infinity<a};a.erase=function(a,h){for(var e=a.length;e--;)if(a[e]===h){a.splice(e,1);break}};a.defined=function(a){return void 0!==a&&null!==a};a.attr=function(n,h,e){var u;a.isString(h)?a.defined(e)?n.setAttribute(h,e):n&&n.getAttribute&&((u=n.getAttribute(h))||"class"!==h||(u=n.getAttribute(h+"Name"))):a.defined(h)&&a.isObject(h)&&a.objectEach(h,function(a,e){n.setAttribute(e,a)});return u};a.splat=function(n){return a.isArray(n)?
n:[n]};a.syncTimeout=function(a,h,e){if(h)return setTimeout(a,h,e);a.call(0,e)};a.clearTimeout=function(n){a.defined(n)&&clearTimeout(n)};a.extend=function(a,h){var e;a||(a={});for(e in h)a[e]=h[e];return a};a.pick=function(){var a=arguments,h,e,u=a.length;for(h=0;h<u;h++)if(e=a[h],void 0!==e&&null!==e)return e};a.css=function(n,h){a.isMS&&!a.svg&&h&&void 0!==h.opacity&&(h.filter="alpha(opacity\x3d"+100*h.opacity+")");a.extend(n.style,h)};a.createElement=function(n,h,e,u,y){n=E.createElement(n);var q=
a.css;h&&a.extend(n,h);y&&q(n,{padding:0,border:"none",margin:0});e&&q(n,e);u&&u.appendChild(n);return n};a.extendClass=function(n,h){var e=function(){};e.prototype=new n;a.extend(e.prototype,h);return e};a.pad=function(a,h,e){return Array((h||2)+1-String(a).replace("-","").length).join(e||0)+a};a.relativeLength=function(a,h,e){return/%$/.test(a)?h*parseFloat(a)/100+(e||0):parseFloat(a)};a.wrap=function(a,h,e){var n=a[h];a[h]=function(){var a=Array.prototype.slice.call(arguments),q=arguments,x=this;
x.proceed=function(){n.apply(x,arguments.length?arguments:q)};a.unshift(n);a=e.apply(this,a);x.proceed=null;return a}};a.formatSingle=function(n,h,e){var u=/\.([0-9])/,y=a.defaultOptions.lang;/f$/.test(n)?(e=(e=n.match(u))?e[1]:-1,null!==h&&(h=a.numberFormat(h,e,y.decimalPoint,-1<n.indexOf(",")?y.thousandsSep:""))):h=(e||a.time).dateFormat(n,h);return h};a.format=function(n,h,e){for(var u="{",y=!1,q,x,f,c,k=[],r;n;){u=n.indexOf(u);if(-1===u)break;q=n.slice(0,u);if(y){q=q.split(":");x=q.shift().split(".");
c=x.length;r=h;for(f=0;f<c;f++)r&&(r=r[x[f]]);q.length&&(r=a.formatSingle(q.join(":"),r,e));k.push(r)}else k.push(q);n=n.slice(u+1);u=(y=!y)?"}":"{"}k.push(n);return k.join("")};a.getMagnitude=function(a){return Math.pow(10,Math.floor(Math.log(a)/Math.LN10))};a.normalizeTickInterval=function(n,h,e,u,y){var q,x=n;e=a.pick(e,1);q=n/e;h||(h=y?[1,1.2,1.5,2,2.5,3,4,5,6,8,10]:[1,2,2.5,5,10],!1===u&&(1===e?h=a.grep(h,function(a){return 0===a%1}):.1>=e&&(h=[1/e])));for(u=0;u<h.length&&!(x=h[u],y&&x*e>=n||
!y&&q<=(h[u]+(h[u+1]||h[u]))/2);u++);return x=a.correctFloat(x*e,-Math.round(Math.log(.001)/Math.LN10))};a.stableSort=function(a,h){var e=a.length,n,y;for(y=0;y<e;y++)a[y].safeI=y;a.sort(function(a,e){n=h(a,e);return 0===n?a.safeI-e.safeI:n});for(y=0;y<e;y++)delete a[y].safeI};a.arrayMin=function(a){for(var h=a.length,e=a[0];h--;)a[h]<e&&(e=a[h]);return e};a.arrayMax=function(a){for(var h=a.length,e=a[0];h--;)a[h]>e&&(e=a[h]);return e};a.destroyObjectProperties=function(n,h){a.objectEach(n,function(a,
u){a&&a!==h&&a.destroy&&a.destroy();delete n[u]})};a.discardElement=function(n){var h=a.garbageBin;h||(h=a.createElement("div"));n&&h.appendChild(n);h.innerHTML=""};a.correctFloat=function(a,h){return parseFloat(a.toPrecision(h||14))};a.setAnimation=function(n,h){h.renderer.globalAnimation=a.pick(n,h.options.chart.animation,!0)};a.animObject=function(n){return a.isObject(n)?a.merge(n):{duration:n?500:0}};a.timeUnits={millisecond:1,second:1E3,minute:6E4,hour:36E5,day:864E5,week:6048E5,month:24192E5,
year:314496E5};a.numberFormat=function(n,h,e,u){n=+n||0;h=+h;var y=a.defaultOptions.lang,q=(n.toString().split(".")[1]||"").split("e")[0].length,x,f,c=n.toString().split("e");-1===h?h=Math.min(q,20):a.isNumber(h)?h&&c[1]&&0>c[1]&&(x=h+ +c[1],0<=x?(c[0]=(+c[0]).toExponential(x).split("e")[0],h=x):(c[0]=c[0].split(".")[0]||0,n=20>h?(c[0]*Math.pow(10,c[1])).toFixed(h):0,c[1]=0)):h=2;f=(Math.abs(c[1]?c[0]:n)+Math.pow(10,-Math.max(h,q)-1)).toFixed(h);q=String(a.pInt(f));x=3<q.length?q.length%3:0;e=a.pick(e,
y.decimalPoint);u=a.pick(u,y.thousandsSep);n=(0>n?"-":"")+(x?q.substr(0,x)+u:"");n+=q.substr(x).replace(/(\d{3})(?=\d)/g,"$1"+u);h&&(n+=e+f.slice(-h));c[1]&&0!==+n&&(n+="e"+c[1]);return n};Math.easeInOutSine=function(a){return-.5*(Math.cos(Math.PI*a)-1)};a.getStyle=function(n,h,e){if("width"===h)return Math.max(0,Math.min(n.offsetWidth,n.scrollWidth)-a.getStyle(n,"padding-left")-a.getStyle(n,"padding-right"));if("height"===h)return Math.max(0,Math.min(n.offsetHeight,n.scrollHeight)-a.getStyle(n,"padding-top")-
a.getStyle(n,"padding-bottom"));F.getComputedStyle||a.error(27,!0);if(n=F.getComputedStyle(n,void 0))n=n.getPropertyValue(h),a.pick(e,"opacity"!==h)&&(n=a.pInt(n));return n};a.inArray=function(n,h,e){return(a.indexOfPolyfill||Array.prototype.indexOf).call(h,n,e)};a.grep=function(n,h){return(a.filterPolyfill||Array.prototype.filter).call(n,h)};a.find=Array.prototype.find?function(a,h){return a.find(h)}:function(a,h){var e,u=a.length;for(e=0;e<u;e++)if(h(a[e],e))return a[e]};a.some=function(n,h,e){return(a.somePolyfill||
Array.prototype.some).call(n,h,e)};a.map=function(a,h){for(var e=[],u=0,y=a.length;u<y;u++)e[u]=h.call(a[u],a[u],u,a);return e};a.keys=function(n){return(a.keysPolyfill||Object.keys).call(void 0,n)};a.reduce=function(n,h,e){return(a.reducePolyfill||Array.prototype.reduce).apply(n,2<arguments.length?[h,e]:[h])};a.offset=function(a){var h=E.documentElement;a=a.parentElement||a.parentNode?a.getBoundingClientRect():{top:0,left:0};return{top:a.top+(F.pageYOffset||h.scrollTop)-(h.clientTop||0),left:a.left+
(F.pageXOffset||h.scrollLeft)-(h.clientLeft||0)}};a.stop=function(n,h){for(var e=a.timers.length;e--;)a.timers[e].elem!==n||h&&h!==a.timers[e].prop||(a.timers[e].stopped=!0)};a.each=function(n,h,e){return(a.forEachPolyfill||Array.prototype.forEach).call(n,h,e)};a.objectEach=function(a,h,e){for(var u in a)a.hasOwnProperty(u)&&h.call(e||a[u],a[u],u,a)};a.addEvent=function(n,h,e,u){var y,q=n.addEventListener||a.addEventListenerPolyfill;y="function"===typeof n&&n.prototype?n.prototype.protoEvents=n.prototype.protoEvents||
{}:n.hcEvents=n.hcEvents||{};a.Point&&n instanceof a.Point&&n.series&&n.series.chart&&(n.series.chart.runTrackerClick=!0);q&&q.call(n,h,e,!1);y[h]||(y[h]=[]);y[h].push(e);u&&a.isNumber(u.order)&&(e.order=u.order,y[h].sort(function(a,f){return a.order-f.order}));return function(){a.removeEvent(n,h,e)}};a.removeEvent=function(n,h,e){function u(f,c){var k=n.removeEventListener||a.removeEventListenerPolyfill;k&&k.call(n,f,c,!1)}function y(f){var c,k;n.nodeName&&(h?(c={},c[h]=!0):c=f,a.objectEach(c,function(a,
c){if(f[c])for(k=f[c].length;k--;)u(c,f[c][k])}))}var q,x;a.each(["protoEvents","hcEvents"],function(f){var c=n[f];c&&(h?(q=c[h]||[],e?(x=a.inArray(e,q),-1<x&&(q.splice(x,1),c[h]=q),u(h,e)):(y(c),c[h]=[])):(y(c),n[f]={}))})};a.fireEvent=function(n,h,e,u){var y,q,x,f,c;e=e||{};E.createEvent&&(n.dispatchEvent||n.fireEvent)?(y=E.createEvent("Events"),y.initEvent(h,!0,!0),a.extend(y,e),n.dispatchEvent?n.dispatchEvent(y):n.fireEvent(h,y)):a.each(["protoEvents","hcEvents"],function(k){if(n[k])for(q=n[k][h]||
[],x=q.length,e.target||a.extend(e,{preventDefault:function(){e.defaultPrevented=!0},target:n,type:h}),f=0;f<x;f++)(c=q[f])&&!1===c.call(n,e)&&e.preventDefault()});u&&!e.defaultPrevented&&u.call(n,e)};a.animate=function(n,h,e){var u,y="",q,x,f;a.isObject(e)||(f=arguments,e={duration:f[2],easing:f[3],complete:f[4]});a.isNumber(e.duration)||(e.duration=400);e.easing="function"===typeof e.easing?e.easing:Math[e.easing]||Math.easeInOutSine;e.curAnim=a.merge(h);a.objectEach(h,function(c,f){a.stop(n,f);
x=new a.Fx(n,e,f);q=null;"d"===f?(x.paths=x.initPath(n,n.d,h.d),x.toD=h.d,u=0,q=1):n.attr?u=n.attr(f):(u=parseFloat(a.getStyle(n,f))||0,"opacity"!==f&&(y="px"));q||(q=c);q&&q.match&&q.match("px")&&(q=q.replace(/px/g,""));x.run(u,q,y)})};a.seriesType=function(n,h,e,u,y){var q=a.getOptions(),x=a.seriesTypes;q.plotOptions[n]=a.merge(q.plotOptions[h],e);x[n]=a.extendClass(x[h]||function(){},u);x[n].prototype.type=n;y&&(x[n].prototype.pointClass=a.extendClass(a.Point,y));return x[n]};a.uniqueKey=function(){var a=
Math.random().toString(36).substring(2,9),h=0;return function(){return"highcharts-"+a+"-"+h++}}();F.jQuery&&(F.jQuery.fn.highcharts=function(){var n=[].slice.call(arguments);if(this[0])return n[0]?(new (a[a.isString(n[0])?n.shift():"Chart"])(this[0],n[0],n[1]),this):C[a.attr(this[0],"data-highcharts-chart")]})})(K);(function(a){var C=a.each,E=a.isNumber,F=a.map,n=a.merge,h=a.pInt;a.Color=function(e){if(!(this instanceof a.Color))return new a.Color(e);this.init(e)};a.Color.prototype={parsers:[{regex:/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]?(?:\.[0-9]+)?)\s*\)/,
parse:function(a){return[h(a[1]),h(a[2]),h(a[3]),parseFloat(a[4],10)]}},{regex:/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/,parse:function(a){return[h(a[1]),h(a[2]),h(a[3]),1]}}],names:{none:"rgba(255,255,255,0)",white:"#ffffff",black:"#000000"},init:function(e){var h,y,q,x;if((this.input=e=this.names[e&&e.toLowerCase?e.toLowerCase():""]||e)&&e.stops)this.stops=F(e.stops,function(f){return new a.Color(f[1])});else if(e&&e.charAt&&"#"===e.charAt()&&(h=e.length,e=parseInt(e.substr(1),
16),7===h?y=[(e&16711680)>>16,(e&65280)>>8,e&255,1]:4===h&&(y=[(e&3840)>>4|(e&3840)>>8,(e&240)>>4|e&240,(e&15)<<4|e&15,1])),!y)for(q=this.parsers.length;q--&&!y;)x=this.parsers[q],(h=x.regex.exec(e))&&(y=x.parse(h));this.rgba=y||[]},get:function(a){var e=this.input,h=this.rgba,q;this.stops?(q=n(e),q.stops=[].concat(q.stops),C(this.stops,function(e,f){q.stops[f]=[q.stops[f][0],e.get(a)]})):q=h&&E(h[0])?"rgb"===a||!a&&1===h[3]?"rgb("+h[0]+","+h[1]+","+h[2]+")":"a"===a?h[3]:"rgba("+h.join(",")+")":e;
return q},brighten:function(a){var e,y=this.rgba;if(this.stops)C(this.stops,function(e){e.brighten(a)});else if(E(a)&&0!==a)for(e=0;3>e;e++)y[e]+=h(255*a),0>y[e]&&(y[e]=0),255<y[e]&&(y[e]=255);return this},setOpacity:function(a){this.rgba[3]=a;return this},tweenTo:function(a,h){var e=this.rgba,q=a.rgba;q.length&&e&&e.length?(a=1!==q[3]||1!==e[3],h=(a?"rgba(":"rgb(")+Math.round(q[0]+(e[0]-q[0])*(1-h))+","+Math.round(q[1]+(e[1]-q[1])*(1-h))+","+Math.round(q[2]+(e[2]-q[2])*(1-h))+(a?","+(q[3]+(e[3]-
q[3])*(1-h)):"")+")"):h=a.input||"none";return h}};a.color=function(e){return new a.Color(e)}})(K);(function(a){var C,E,F=a.addEvent,n=a.animate,h=a.attr,e=a.charts,u=a.color,y=a.css,q=a.createElement,x=a.defined,f=a.deg2rad,c=a.destroyObjectProperties,k=a.doc,r=a.each,l=a.extend,d=a.erase,b=a.grep,v=a.hasTouch,p=a.inArray,I=a.isArray,t=a.isFirefox,L=a.isMS,A=a.isObject,H=a.isString,m=a.isWebKit,D=a.merge,B=a.noop,M=a.objectEach,G=a.pick,g=a.pInt,w=a.removeEvent,P=a.stop,Q=a.svg,J=a.SVG_NS,O=a.symbolSizes,
N=a.win;C=a.SVGElement=function(){return this};l(C.prototype,{opacity:1,SVG_NS:J,textProps:"direction fontSize fontWeight fontFamily fontStyle color lineHeight width textAlign textDecoration textOverflow textOutline".split(" "),init:function(a,g){this.element="span"===g?q(g):k.createElementNS(this.SVG_NS,g);this.renderer=a},animate:function(z,g,b){g=a.animObject(G(g,this.renderer.globalAnimation,!0));0!==g.duration?(b&&(g.complete=b),n(this,z,g)):(this.attr(z,null,b),g.step&&g.step.call(this));return this},
complexColor:function(z,g,b){var d=this.renderer,w,c,l,J,f,m,B,k,R,v,p,t=[],A;a.fireEvent(this.renderer,"complexColor",{args:arguments},function(){z.radialGradient?c="radialGradient":z.linearGradient&&(c="linearGradient");c&&(l=z[c],f=d.gradients,B=z.stops,v=b.radialReference,I(l)&&(z[c]=l={x1:l[0],y1:l[1],x2:l[2],y2:l[3],gradientUnits:"userSpaceOnUse"}),"radialGradient"===c&&v&&!x(l.gradientUnits)&&(J=l,l=D(l,d.getRadialAttr(v,J),{gradientUnits:"userSpaceOnUse"})),M(l,function(a,z){"id"!==z&&t.push(z,
a)}),M(B,function(a){t.push(a)}),t=t.join(","),f[t]?p=f[t].attr("id"):(l.id=p=a.uniqueKey(),f[t]=m=d.createElement(c).attr(l).add(d.defs),m.radAttr=J,m.stops=[],r(B,function(z){0===z[1].indexOf("rgba")?(w=a.color(z[1]),k=w.get("rgb"),R=w.get("a")):(k=z[1],R=1);z=d.createElement("stop").attr({offset:z[0],"stop-color":k,"stop-opacity":R}).add(m);m.stops.push(z)})),A="url("+d.url+"#"+p+")",b.setAttribute(g,A),b.gradient=t,z.toString=function(){return A})})},applyTextOutline:function(z){var g=this.element,
b,w,c,l,m;-1!==z.indexOf("contrast")&&(z=z.replace(/contrast/g,this.renderer.getContrast(g.style.fill)));z=z.split(" ");w=z[z.length-1];if((c=z[0])&&"none"!==c&&a.svg){this.fakeTS=!0;z=[].slice.call(g.getElementsByTagName("tspan"));this.ySetter=this.xSetter;c=c.replace(/(^[\d\.]+)(.*?)$/g,function(a,z,g){return 2*z+g});for(m=z.length;m--;)b=z[m],"highcharts-text-outline"===b.getAttribute("class")&&d(z,g.removeChild(b));l=g.firstChild;r(z,function(a,z){0===z&&(a.setAttribute("x",g.getAttribute("x")),
z=g.getAttribute("y"),a.setAttribute("y",z||0),null===z&&g.setAttribute("y",0));a=a.cloneNode(1);h(a,{"class":"highcharts-text-outline",fill:w,stroke:w,"stroke-width":c,"stroke-linejoin":"round"});g.insertBefore(a,l)})}},attr:function(a,g,b,d){var z,w=this.element,c,l=this,m,f;"string"===typeof a&&void 0!==g&&(z=a,a={},a[z]=g);"string"===typeof a?l=(this[a+"Getter"]||this._defaultGetter).call(this,a,w):(M(a,function(z,g){m=!1;d||P(this,g);this.symbolName&&/^(x|y|width|height|r|start|end|innerR|anchorX|anchorY)$/.test(g)&&
(c||(this.symbolAttr(a),c=!0),m=!0);!this.rotation||"x"!==g&&"y"!==g||(this.doTransform=!0);m||(f=this[g+"Setter"]||this._defaultSetter,f.call(this,z,g,w),this.shadows&&/^(width|height|visibility|x|y|d|transform|cx|cy|r)$/.test(g)&&this.updateShadows(g,z,f))},this),this.afterSetters());b&&b.call(this);return l},afterSetters:function(){this.doTransform&&(this.updateTransform(),this.doTransform=!1)},updateShadows:function(a,g,b){for(var z=this.shadows,d=z.length;d--;)b.call(z[d],"height"===a?Math.max(g-
(z[d].cutHeight||0),0):"d"===a?this.d:g,a,z[d])},addClass:function(a,g){var z=this.attr("class")||"";-1===z.indexOf(a)&&(g||(a=(z+(z?" ":"")+a).replace("  "," ")),this.attr("class",a));return this},hasClass:function(a){return-1!==p(a,(this.attr("class")||"").split(" "))},removeClass:function(a){return this.attr("class",(this.attr("class")||"").replace(a,""))},symbolAttr:function(a){var z=this;r("x y r start end width height innerR anchorX anchorY".split(" "),function(g){z[g]=G(a[g],z[g])});z.attr({d:z.renderer.symbols[z.symbolName](z.x,
z.y,z.width,z.height,z)})},clip:function(a){return this.attr("clip-path",a?"url("+this.renderer.url+"#"+a.id+")":"none")},crisp:function(a,g){var z;g=g||a.strokeWidth||0;z=Math.round(g)%2/2;a.x=Math.floor(a.x||this.x||0)+z;a.y=Math.floor(a.y||this.y||0)+z;a.width=Math.floor((a.width||this.width||0)-2*z);a.height=Math.floor((a.height||this.height||0)-2*z);x(a.strokeWidth)&&(a.strokeWidth=g);return a},css:function(a){var z=this.styles,b={},d=this.element,w,c="",m,f=!z,J=["textOutline","textOverflow",
"width"];a&&a.color&&(a.fill=a.color);z&&M(a,function(a,g){a!==z[g]&&(b[g]=a,f=!0)});f&&(z&&(a=l(z,b)),a&&(null===a.width||"auto"===a.width?delete this.textWidth:"text"===d.nodeName.toLowerCase()&&a.width&&(w=this.textWidth=g(a.width))),this.styles=a,w&&!Q&&this.renderer.forExport&&delete a.width,d.namespaceURI===this.SVG_NS?(m=function(a,z){return"-"+z.toLowerCase()},M(a,function(a,z){-1===p(z,J)&&(c+=z.replace(/([A-Z])/g,m)+":"+a+";")}),c&&h(d,"style",c)):y(d,a),this.added&&("text"===this.element.nodeName&&
this.renderer.buildText(this),a&&a.textOutline&&this.applyTextOutline(a.textOutline)));return this},strokeWidth:function(){return this["stroke-width"]||0},on:function(a,g){var z=this,b=z.element;v&&"click"===a?(b.ontouchstart=function(a){z.touchEventFired=Date.now();a.preventDefault();g.call(b,a)},b.onclick=function(a){(-1===N.navigator.userAgent.indexOf("Android")||1100<Date.now()-(z.touchEventFired||0))&&g.call(b,a)}):b["on"+a]=g;return this},setRadialReference:function(a){var z=this.renderer.gradients[this.element.gradient];
this.element.radialReference=a;z&&z.radAttr&&z.animate(this.renderer.getRadialAttr(a,z.radAttr));return this},translate:function(a,g){return this.attr({translateX:a,translateY:g})},invert:function(a){this.inverted=a;this.updateTransform();return this},updateTransform:function(){var a=this.translateX||0,g=this.translateY||0,b=this.scaleX,d=this.scaleY,w=this.inverted,c=this.rotation,l=this.matrix,m=this.element;w&&(a+=this.width,g+=this.height);a=["translate("+a+","+g+")"];x(l)&&a.push("matrix("+l.join(",")+
")");w?a.push("rotate(90) scale(-1,1)"):c&&a.push("rotate("+c+" "+G(this.rotationOriginX,m.getAttribute("x"),0)+" "+G(this.rotationOriginY,m.getAttribute("y")||0)+")");(x(b)||x(d))&&a.push("scale("+G(b,1)+" "+G(d,1)+")");a.length&&m.setAttribute("transform",a.join(" "))},toFront:function(){var a=this.element;a.parentNode.appendChild(a);return this},align:function(a,g,b){var z,w,c,l,m={};w=this.renderer;c=w.alignedObjects;var f,J;if(a){if(this.alignOptions=a,this.alignByTranslate=g,!b||H(b))this.alignTo=
z=b||"renderer",d(c,this),c.push(this),b=null}else a=this.alignOptions,g=this.alignByTranslate,z=this.alignTo;b=G(b,w[z],w);z=a.align;w=a.verticalAlign;c=(b.x||0)+(a.x||0);l=(b.y||0)+(a.y||0);"right"===z?f=1:"center"===z&&(f=2);f&&(c+=(b.width-(a.width||0))/f);m[g?"translateX":"x"]=Math.round(c);"bottom"===w?J=1:"middle"===w&&(J=2);J&&(l+=(b.height-(a.height||0))/J);m[g?"translateY":"y"]=Math.round(l);this[this.placed?"animate":"attr"](m);this.placed=!0;this.alignAttr=m;return this},getBBox:function(a,
g){var z,b=this.renderer,d,w=this.element,c=this.styles,m,J=this.textStr,B,k=b.cache,v=b.cacheKeys,p;g=G(g,this.rotation);d=g*f;m=c&&c.fontSize;x(J)&&(p=J.toString(),-1===p.indexOf("\x3c")&&(p=p.replace(/[0-9]/g,"0")),p+=["",g||0,m,this.textWidth,c&&c.textOverflow].join());p&&!a&&(z=k[p]);if(!z){if(w.namespaceURI===this.SVG_NS||b.forExport){try{(B=this.fakeTS&&function(a){r(w.querySelectorAll(".highcharts-text-outline"),function(z){z.style.display=a})})&&B("none"),z=w.getBBox?l({},w.getBBox()):{width:w.offsetWidth,
height:w.offsetHeight},B&&B("")}catch(W){}if(!z||0>z.width)z={width:0,height:0}}else z=this.htmlGetBBox();b.isSVG&&(a=z.width,b=z.height,c&&"11px"===c.fontSize&&17===Math.round(b)&&(z.height=b=14),g&&(z.width=Math.abs(b*Math.sin(d))+Math.abs(a*Math.cos(d)),z.height=Math.abs(b*Math.cos(d))+Math.abs(a*Math.sin(d))));if(p&&0<z.height){for(;250<v.length;)delete k[v.shift()];k[p]||v.push(p);k[p]=z}}return z},show:function(a){return this.attr({visibility:a?"inherit":"visible"})},hide:function(){return this.attr({visibility:"hidden"})},
fadeOut:function(a){var z=this;z.animate({opacity:0},{duration:a||150,complete:function(){z.attr({y:-9999})}})},add:function(a){var z=this.renderer,g=this.element,b;a&&(this.parentGroup=a);this.parentInverted=a&&a.inverted;void 0!==this.textStr&&z.buildText(this);this.added=!0;if(!a||a.handleZ||this.zIndex)b=this.zIndexSetter();b||(a?a.element:z.box).appendChild(g);if(this.onAdd)this.onAdd();return this},safeRemoveChild:function(a){var z=a.parentNode;z&&z.removeChild(a)},destroy:function(){var a=
this,g=a.element||{},b=a.renderer.isSVG&&"SPAN"===g.nodeName&&a.parentGroup,w=g.ownerSVGElement,c=a.clipPath;g.onclick=g.onmouseout=g.onmouseover=g.onmousemove=g.point=null;P(a);c&&w&&(r(w.querySelectorAll("[clip-path],[CLIP-PATH]"),function(a){var g=a.getAttribute("clip-path"),z=c.element.id;(-1<g.indexOf("(#"+z+")")||-1<g.indexOf('("#'+z+'")'))&&a.removeAttribute("clip-path")}),a.clipPath=c.destroy());if(a.stops){for(w=0;w<a.stops.length;w++)a.stops[w]=a.stops[w].destroy();a.stops=null}a.safeRemoveChild(g);
for(a.destroyShadows();b&&b.div&&0===b.div.childNodes.length;)g=b.parentGroup,a.safeRemoveChild(b.div),delete b.div,b=g;a.alignTo&&d(a.renderer.alignedObjects,a);M(a,function(g,z){delete a[z]});return null},shadow:function(a,g,b){var z=[],d,w,c=this.element,l,m,f,J;if(!a)this.destroyShadows();else if(!this.shadows){m=G(a.width,3);f=(a.opacity||.15)/m;J=this.parentInverted?"(-1,-1)":"("+G(a.offsetX,1)+", "+G(a.offsetY,1)+")";for(d=1;d<=m;d++)w=c.cloneNode(0),l=2*m+1-2*d,h(w,{isShadow:"true",stroke:a.color||
"#000000","stroke-opacity":f*d,"stroke-width":l,transform:"translate"+J,fill:"none"}),b&&(h(w,"height",Math.max(h(w,"height")-l,0)),w.cutHeight=l),g?g.element.appendChild(w):c.parentNode&&c.parentNode.insertBefore(w,c),z.push(w);this.shadows=z}return this},destroyShadows:function(){r(this.shadows||[],function(a){this.safeRemoveChild(a)},this);this.shadows=void 0},xGetter:function(a){"circle"===this.element.nodeName&&("x"===a?a="cx":"y"===a&&(a="cy"));return this._defaultGetter(a)},_defaultGetter:function(a){a=
G(this[a+"Value"],this[a],this.element?this.element.getAttribute(a):null,0);/^[\-0-9\.]+$/.test(a)&&(a=parseFloat(a));return a},dSetter:function(a,g,b){a&&a.join&&(a=a.join(" "));/(NaN| {2}|^$)/.test(a)&&(a="M 0 0");this[g]!==a&&(b.setAttribute(g,a),this[g]=a)},dashstyleSetter:function(a){var b,z=this["stroke-width"];"inherit"===z&&(z=1);if(a=a&&a.toLowerCase()){a=a.replace("shortdashdotdot","3,1,1,1,1,1,").replace("shortdashdot","3,1,1,1").replace("shortdot","1,1,").replace("shortdash","3,1,").replace("longdash",
"8,3,").replace(/dot/g,"1,3,").replace("dash","4,3,").replace(/,$/,"").split(",");for(b=a.length;b--;)a[b]=g(a[b])*z;a=a.join(",").replace(/NaN/g,"none");this.element.setAttribute("stroke-dasharray",a)}},alignSetter:function(a){this.alignValue=a;this.element.setAttribute("text-anchor",{left:"start",center:"middle",right:"end"}[a])},opacitySetter:function(a,g,b){this[g]=a;b.setAttribute(g,a)},titleSetter:function(a){var g=this.element.getElementsByTagName("title")[0];g||(g=k.createElementNS(this.SVG_NS,
"title"),this.element.appendChild(g));g.firstChild&&g.removeChild(g.firstChild);g.appendChild(k.createTextNode(String(G(a),"").replace(/<[^>]*>/g,"").replace(/&lt;/g,"\x3c").replace(/&gt;/g,"\x3e")))},textSetter:function(a){a!==this.textStr&&(delete this.bBox,this.textStr=a,this.added&&this.renderer.buildText(this))},fillSetter:function(a,g,b){"string"===typeof a?b.setAttribute(g,a):a&&this.complexColor(a,g,b)},visibilitySetter:function(a,g,b){"inherit"===a?b.removeAttribute(g):this[g]!==a&&b.setAttribute(g,
a);this[g]=a},zIndexSetter:function(a,b){var d=this.renderer,w=this.parentGroup,z=(w||d).element||d.box,c,l=this.element,m,f,d=z===d.box;c=this.added;var J;x(a)?(l.setAttribute("data-z-index",a),a=+a,this[b]===a&&(c=!1)):x(this[b])&&l.removeAttribute("data-z-index");this[b]=a;if(c){(a=this.zIndex)&&w&&(w.handleZ=!0);b=z.childNodes;for(J=b.length-1;0<=J&&!m;J--)if(w=b[J],c=w.getAttribute("data-z-index"),f=!x(c),w!==l)if(0>a&&f&&!d&&!J)z.insertBefore(l,b[J]),m=!0;else if(g(c)<=a||f&&(!x(a)||0<=a))z.insertBefore(l,
b[J+1]||null),m=!0;m||(z.insertBefore(l,b[d?3:0]||null),m=!0)}return m},_defaultSetter:function(a,g,b){b.setAttribute(g,a)}});C.prototype.yGetter=C.prototype.xGetter;C.prototype.translateXSetter=C.prototype.translateYSetter=C.prototype.rotationSetter=C.prototype.verticalAlignSetter=C.prototype.rotationOriginXSetter=C.prototype.rotationOriginYSetter=C.prototype.scaleXSetter=C.prototype.scaleYSetter=C.prototype.matrixSetter=function(a,g){this[g]=a;this.doTransform=!0};C.prototype["stroke-widthSetter"]=
C.prototype.strokeSetter=function(a,g,b){this[g]=a;this.stroke&&this["stroke-width"]?(C.prototype.fillSetter.call(this,this.stroke,"stroke",b),b.setAttribute("stroke-width",this["stroke-width"]),this.hasStroke=!0):"stroke-width"===g&&0===a&&this.hasStroke&&(b.removeAttribute("stroke"),this.hasStroke=!1)};E=a.SVGRenderer=function(){this.init.apply(this,arguments)};l(E.prototype,{Element:C,SVG_NS:J,init:function(a,g,b,d,w,c){var z;d=this.createElement("svg").attr({version:"1.1","class":"highcharts-root"}).css(this.getStyle(d));
z=d.element;a.appendChild(z);h(a,"dir","ltr");-1===a.innerHTML.indexOf("xmlns")&&h(z,"xmlns",this.SVG_NS);this.isSVG=!0;this.box=z;this.boxWrapper=d;this.alignedObjects=[];this.url=(t||m)&&k.getElementsByTagName("base").length?N.location.href.replace(/#.*?$/,"").replace(/<[^>]*>/g,"").replace(/([\('\)])/g,"\\$1").replace(/ /g,"%20"):"";this.createElement("desc").add().element.appendChild(k.createTextNode("Created with Highcharts 6.1.1"));this.defs=this.createElement("defs").add();this.allowHTML=c;
this.forExport=w;this.gradients={};this.cache={};this.cacheKeys=[];this.imgCount=0;this.setSize(g,b,!1);var l;t&&a.getBoundingClientRect&&(g=function(){y(a,{left:0,top:0});l=a.getBoundingClientRect();y(a,{left:Math.ceil(l.left)-l.left+"px",top:Math.ceil(l.top)-l.top+"px"})},g(),this.unSubPixelFix=F(N,"resize",g))},getStyle:function(a){return this.style=l({fontFamily:'"Lucida Grande", "Lucida Sans Unicode", Arial, Helvetica, sans-serif',fontSize:"12px"},a)},setStyle:function(a){this.boxWrapper.css(this.getStyle(a))},
isHidden:function(){return!this.boxWrapper.getBBox().width},destroy:function(){var a=this.defs;this.box=null;this.boxWrapper=this.boxWrapper.destroy();c(this.gradients||{});this.gradients=null;a&&(this.defs=a.destroy());this.unSubPixelFix&&this.unSubPixelFix();return this.alignedObjects=null},createElement:function(a){var g=new this.Element;g.init(this,a);return g},draw:B,getRadialAttr:function(a,g){return{cx:a[0]-a[2]/2+g.cx*a[2],cy:a[1]-a[2]/2+g.cy*a[2],r:g.r*a[2]}},getSpanWidth:function(a){return a.getBBox(!0).width},
applyEllipsis:function(a,g,b,d){var w=a.rotation,c=b,z,l=0,m=b.length,J=function(a){g.removeChild(g.firstChild);a&&g.appendChild(k.createTextNode(a))},f;a.rotation=0;c=this.getSpanWidth(a,g);if(f=c>d){for(;l<=m;)z=Math.ceil((l+m)/2),c=b.substring(0,z)+"\u2026",J(c),c=this.getSpanWidth(a,g),l===m?l=m+1:c>d?m=z-1:l=z;0===m&&J("")}a.rotation=w;return f},escapes:{"\x26":"\x26amp;","\x3c":"\x26lt;","\x3e":"\x26gt;","'":"\x26#39;",'"':"\x26quot;"},buildText:function(a){var d=a.element,w=this,c=w.forExport,
l=G(a.textStr,"").toString(),z=-1!==l.indexOf("\x3c"),m=d.childNodes,f,B=h(d,"x"),v=a.styles,D=a.textWidth,t=v&&v.lineHeight,A=v&&v.textOutline,e=v&&"ellipsis"===v.textOverflow,P=v&&"nowrap"===v.whiteSpace,O=v&&v.fontSize,q,x,I=m.length,v=D&&!a.added&&this.box,H=function(a){var b;b=/(px|em)$/.test(a&&a.style.fontSize)?a.style.fontSize:O||w.style.fontSize||12;return t?g(t):w.fontMetrics(b,a.getAttribute("style")?a:d).h},N=function(a,g){M(w.escapes,function(b,d){g&&-1!==p(b,g)||(a=a.toString().replace(new RegExp(b,
"g"),d))});return a},u=function(a,g){var b;b=a.indexOf("\x3c");a=a.substring(b,a.indexOf("\x3e")-b);b=a.indexOf(g+"\x3d");if(-1!==b&&(b=b+g.length+1,g=a.charAt(b),'"'===g||"'"===g))return a=a.substring(b+1),a.substring(0,a.indexOf(g))};q=[l,e,P,t,A,O,D].join();if(q!==a.textCache){for(a.textCache=q;I--;)d.removeChild(m[I]);z||A||e||D||-1!==l.indexOf(" ")?(v&&v.appendChild(d),l=z?l.replace(/<(b|strong)>/g,'\x3cspan style\x3d"font-weight:bold"\x3e').replace(/<(i|em)>/g,'\x3cspan style\x3d"font-style:italic"\x3e').replace(/<a/g,
"\x3cspan").replace(/<\/(b|strong|i|em|a)>/g,"\x3c/span\x3e").split(/<br.*?>/g):[l],l=b(l,function(a){return""!==a}),r(l,function(g,b){var l,z=0;g=g.replace(/^\s+|\s+$/g,"").replace(/<span/g,"|||\x3cspan").replace(/<\/span>/g,"\x3c/span\x3e|||");l=g.split("|||");r(l,function(g){if(""!==g||1===l.length){var m={},v=k.createElementNS(w.SVG_NS,"tspan"),p,r;(p=u(g,"class"))&&h(v,"class",p);if(p=u(g,"style"))p=p.replace(/(;| |^)color([ :])/,"$1fill$2"),h(v,"style",p);(r=u(g,"href"))&&!c&&(h(v,"onclick",
'location.href\x3d"'+r+'"'),h(v,"class","highcharts-anchor"),y(v,{cursor:"pointer"}));g=N(g.replace(/<[a-zA-Z\/](.|\n)*?>/g,"")||" ");if(" "!==g){v.appendChild(k.createTextNode(g));z?m.dx=0:b&&null!==B&&(m.x=B);h(v,m);d.appendChild(v);!z&&x&&(!Q&&c&&y(v,{display:"block"}),h(v,"dy",H(v)));if(D){m=g.replace(/([^\^])-/g,"$1- ").split(" ");r=1<l.length||b||1<m.length&&!P;var t=[],A,O=H(v),q=a.rotation;for(e&&(f=w.applyEllipsis(a,v,g,D));!e&&r&&(m.length||t.length);)a.rotation=0,A=w.getSpanWidth(a,v),
g=A>D,void 0===f&&(f=g),g&&1!==m.length?(v.removeChild(v.firstChild),t.unshift(m.pop())):(m=t,t=[],m.length&&!P&&(v=k.createElementNS(J,"tspan"),h(v,{dy:O,x:B}),p&&h(v,"style",p),d.appendChild(v)),A>D&&(D=A+1)),m.length&&v.appendChild(k.createTextNode(m.join(" ").replace(/- /g,"-")));a.rotation=q}z++}}});x=x||d.childNodes.length}),e&&f&&a.attr("title",N(a.textStr,["\x26lt;","\x26gt;"])),v&&v.removeChild(d),A&&a.applyTextOutline&&a.applyTextOutline(A)):d.appendChild(k.createTextNode(N(l)))}},getContrast:function(a){a=
u(a).rgba;return 510<a[0]+a[1]+a[2]?"#000000":"#FFFFFF"},button:function(a,g,b,d,w,c,m,f,J){var z=this.label(a,g,b,J,null,null,null,null,"button"),v=0;z.attr(D({padding:8,r:2},w));var B,k,p,r;w=D({fill:"#f7f7f7",stroke:"#cccccc","stroke-width":1,style:{color:"#333333",cursor:"pointer",fontWeight:"normal"}},w);B=w.style;delete w.style;c=D(w,{fill:"#e6e6e6"},c);k=c.style;delete c.style;m=D(w,{fill:"#e6ebf5",style:{color:"#000000",fontWeight:"bold"}},m);p=m.style;delete m.style;f=D(w,{style:{color:"#cccccc"}},
f);r=f.style;delete f.style;F(z.element,L?"mouseover":"mouseenter",function(){3!==v&&z.setState(1)});F(z.element,L?"mouseout":"mouseleave",function(){3!==v&&z.setState(v)});z.setState=function(a){1!==a&&(z.state=v=a);z.removeClass(/highcharts-button-(normal|hover|pressed|disabled)/).addClass("highcharts-button-"+["normal","hover","pressed","disabled"][a||0]);z.attr([w,c,m,f][a||0]).css([B,k,p,r][a||0])};z.attr(w).css(l({cursor:"default"},B));return z.on("click",function(a){3!==v&&d.call(z,a)})},crispLine:function(a,
g){a[1]===a[4]&&(a[1]=a[4]=Math.round(a[1])-g%2/2);a[2]===a[5]&&(a[2]=a[5]=Math.round(a[2])+g%2/2);return a},path:function(a){var g={fill:"none"};I(a)?g.d=a:A(a)&&l(g,a);return this.createElement("path").attr(g)},circle:function(a,g,b){a=A(a)?a:{x:a,y:g,r:b};g=this.createElement("circle");g.xSetter=g.ySetter=function(a,g,b){b.setAttribute("c"+g,a)};return g.attr(a)},arc:function(a,g,b,d,w,c){A(a)?(d=a,g=d.y,b=d.r,a=d.x):d={innerR:d,start:w,end:c};a=this.symbol("arc",a,g,b,b,d);a.r=b;return a},rect:function(a,
g,b,d,w,c){w=A(a)?a.r:w;var l=this.createElement("rect");a=A(a)?a:void 0===a?{}:{x:a,y:g,width:Math.max(b,0),height:Math.max(d,0)};void 0!==c&&(a.strokeWidth=c,a=l.crisp(a));a.fill="none";w&&(a.r=w);l.rSetter=function(a,g,b){h(b,{rx:a,ry:a})};return l.attr(a)},setSize:function(a,g,b){var d=this.alignedObjects,w=d.length;this.width=a;this.height=g;for(this.boxWrapper.animate({width:a,height:g},{step:function(){this.attr({viewBox:"0 0 "+this.attr("width")+" "+this.attr("height")})},duration:G(b,!0)?
void 0:0});w--;)d[w].align()},g:function(a){var g=this.createElement("g");return a?g.attr({"class":"highcharts-"+a}):g},image:function(a,g,b,d,w,c){var m={preserveAspectRatio:"none"},f,J=function(a,g){a.setAttributeNS?a.setAttributeNS("http://www.w3.org/1999/xlink","href",g):a.setAttribute("hc-svg-href",g)},z=function(g){J(f.element,a);c.call(f,g)};1<arguments.length&&l(m,{x:g,y:b,width:d,height:w});f=this.createElement("image").attr(m);c?(J(f.element,"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw\x3d\x3d"),
m=new N.Image,F(m,"load",z),m.src=a,m.complete&&z({})):J(f.element,a);return f},symbol:function(a,g,b,d,w,c){var m=this,f,J=/^url\((.*?)\)$/,z=J.test(a),v=!z&&(this.symbols[a]?a:"circle"),B=v&&this.symbols[v],p=x(g)&&B&&B.call(this.symbols,Math.round(g),Math.round(b),d,w,c),D,t;B?(f=this.path(p),f.attr("fill","none"),l(f,{symbolName:v,x:g,y:b,width:d,height:w}),c&&l(f,c)):z&&(D=a.match(J)[1],f=this.image(D),f.imgwidth=G(O[D]&&O[D].width,c&&c.width),f.imgheight=G(O[D]&&O[D].height,c&&c.height),t=function(){f.attr({width:f.width,
height:f.height})},r(["width","height"],function(a){f[a+"Setter"]=function(a,g){var b={},d=this["img"+g],w="width"===g?"translateX":"translateY";this[g]=a;x(d)&&(this.element&&this.element.setAttribute(g,d),this.alignByTranslate||(b[w]=((this[g]||0)-d)/2,this.attr(b)))}}),x(g)&&f.attr({x:g,y:b}),f.isImg=!0,x(f.imgwidth)&&x(f.imgheight)?t():(f.attr({width:0,height:0}),q("img",{onload:function(){var a=e[m.chartIndex];0===this.width&&(y(this,{position:"absolute",top:"-999em"}),k.body.appendChild(this));
O[D]={width:this.width,height:this.height};f.imgwidth=this.width;f.imgheight=this.height;f.element&&t();this.parentNode&&this.parentNode.removeChild(this);m.imgCount--;if(!m.imgCount&&a&&a.onload)a.onload()},src:D}),this.imgCount++));return f},symbols:{circle:function(a,g,b,d){return this.arc(a+b/2,g+d/2,b/2,d/2,{start:0,end:2*Math.PI,open:!1})},square:function(a,g,b,d){return["M",a,g,"L",a+b,g,a+b,g+d,a,g+d,"Z"]},triangle:function(a,g,b,d){return["M",a+b/2,g,"L",a+b,g+d,a,g+d,"Z"]},"triangle-down":function(a,
g,b,d){return["M",a,g,"L",a+b,g,a+b/2,g+d,"Z"]},diamond:function(a,g,b,d){return["M",a+b/2,g,"L",a+b,g+d/2,a+b/2,g+d,a,g+d/2,"Z"]},arc:function(a,g,b,d,w){var c=w.start,l=w.r||b,m=w.r||d||b,f=w.end-.001;b=w.innerR;d=G(w.open,.001>Math.abs(w.end-w.start-2*Math.PI));var J=Math.cos(c),v=Math.sin(c),z=Math.cos(f),f=Math.sin(f);w=.001>w.end-c-Math.PI?0:1;l=["M",a+l*J,g+m*v,"A",l,m,0,w,1,a+l*z,g+m*f];x(b)&&l.push(d?"M":"L",a+b*z,g+b*f,"A",b,b,0,w,0,a+b*J,g+b*v);l.push(d?"":"Z");return l},callout:function(a,
g,b,d,w){var c=Math.min(w&&w.r||0,b,d),l=c+6,m=w&&w.anchorX;w=w&&w.anchorY;var f;f=["M",a+c,g,"L",a+b-c,g,"C",a+b,g,a+b,g,a+b,g+c,"L",a+b,g+d-c,"C",a+b,g+d,a+b,g+d,a+b-c,g+d,"L",a+c,g+d,"C",a,g+d,a,g+d,a,g+d-c,"L",a,g+c,"C",a,g,a,g,a+c,g];m&&m>b?w>g+l&&w<g+d-l?f.splice(13,3,"L",a+b,w-6,a+b+6,w,a+b,w+6,a+b,g+d-c):f.splice(13,3,"L",a+b,d/2,m,w,a+b,d/2,a+b,g+d-c):m&&0>m?w>g+l&&w<g+d-l?f.splice(33,3,"L",a,w+6,a-6,w,a,w-6,a,g+c):f.splice(33,3,"L",a,d/2,m,w,a,d/2,a,g+c):w&&w>d&&m>a+l&&m<a+b-l?f.splice(23,
3,"L",m+6,g+d,m,g+d+6,m-6,g+d,a+c,g+d):w&&0>w&&m>a+l&&m<a+b-l&&f.splice(3,3,"L",m-6,g,m,g-6,m+6,g,b-c,g);return f}},clipRect:function(g,b,d,w){var c=a.uniqueKey(),l=this.createElement("clipPath").attr({id:c}).add(this.defs);g=this.rect(g,b,d,w,0).add(l);g.id=c;g.clipPath=l;g.count=0;return g},text:function(a,g,b,d){var w={};if(d&&(this.allowHTML||!this.forExport))return this.html(a,g,b);w.x=Math.round(g||0);b&&(w.y=Math.round(b));if(a||0===a)w.text=a;a=this.createElement("text").attr(w);d||(a.xSetter=
function(a,g,b){var d=b.getElementsByTagName("tspan"),w,c=b.getAttribute(g),l;for(l=0;l<d.length;l++)w=d[l],w.getAttribute(g)===c&&w.setAttribute(g,a);b.setAttribute(g,a)});return a},fontMetrics:function(a,b){a=a||b&&b.style&&b.style.fontSize||this.style&&this.style.fontSize;a=/px/.test(a)?g(a):/em/.test(a)?parseFloat(a)*(b?this.fontMetrics(null,b.parentNode).f:16):12;b=24>a?a+3:Math.round(1.2*a);return{h:b,b:Math.round(.8*b),f:a}},rotCorr:function(a,g,b){var d=a;g&&b&&(d=Math.max(d*Math.cos(g*f),
4));return{x:-a/3*Math.sin(g*f),y:d}},label:function(g,b,d,c,m,f,J,v,B){var k=this,p=k.g("button"!==B&&"label"),t=p.text=k.text("",0,0,J).attr({zIndex:1}),z,A,Q=0,e=3,P=0,h,O,q,G,I,H={},N,y,M=/^url\((.*?)\)$/.test(c),u=M,n,L,R,U;B&&p.addClass("highcharts-"+B);u=M;n=function(){return(N||0)%2/2};L=function(){var a=t.element.style,g={};A=(void 0===h||void 0===O||I)&&x(t.textStr)&&t.getBBox();p.width=(h||A.width||0)+2*e+P;p.height=(O||A.height||0)+2*e;y=e+k.fontMetrics(a&&a.fontSize,t).b;u&&(z||(p.box=
z=k.symbols[c]||M?k.symbol(c):k.rect(),z.addClass(("button"===B?"":"highcharts-label-box")+(B?" highcharts-"+B+"-box":"")),z.add(p),a=n(),g.x=a,g.y=(v?-y:0)+a),g.width=Math.round(p.width),g.height=Math.round(p.height),z.attr(l(g,H)),H={})};R=function(){var a=P+e,g;g=v?0:y;x(h)&&A&&("center"===I||"right"===I)&&(a+={center:.5,right:1}[I]*(h-A.width));if(a!==t.x||g!==t.y)t.attr("x",a),t.hasBoxWidthChanged&&(A=t.getBBox(!0),L()),void 0!==g&&t.attr("y",g);t.x=a;t.y=g};U=function(a,g){z?z.attr(a,g):H[a]=
g};p.onAdd=function(){t.add(p);p.attr({text:g||0===g?g:"",x:b,y:d});z&&x(m)&&p.attr({anchorX:m,anchorY:f})};p.widthSetter=function(g){h=a.isNumber(g)?g:null};p.heightSetter=function(a){O=a};p["text-alignSetter"]=function(a){I=a};p.paddingSetter=function(a){x(a)&&a!==e&&(e=p.padding=a,R())};p.paddingLeftSetter=function(a){x(a)&&a!==P&&(P=a,R())};p.alignSetter=function(a){a={left:0,center:.5,right:1}[a];a!==Q&&(Q=a,A&&p.attr({x:q}))};p.textSetter=function(a){void 0!==a&&t.textSetter(a);L();R()};p["stroke-widthSetter"]=
function(a,g){a&&(u=!0);N=this["stroke-width"]=a;U(g,a)};p.strokeSetter=p.fillSetter=p.rSetter=function(a,g){"r"!==g&&("fill"===g&&a&&(u=!0),p[g]=a);U(g,a)};p.anchorXSetter=function(a,g){m=p.anchorX=a;U(g,Math.round(a)-n()-q)};p.anchorYSetter=function(a,g){f=p.anchorY=a;U(g,a-G)};p.xSetter=function(a){p.x=a;Q&&(a-=Q*((h||A.width)+2*e),p["forceAnimate:x"]=!0);q=Math.round(a);p.attr("translateX",q)};p.ySetter=function(a){G=p.y=Math.round(a);p.attr("translateY",G)};var S=p.css;return l(p,{css:function(a){if(a){var g=
{};a=D(a);r(p.textProps,function(b){void 0!==a[b]&&(g[b]=a[b],delete a[b])});t.css(g);"width"in g&&L()}return S.call(p,a)},getBBox:function(){return{width:A.width+2*e,height:A.height+2*e,x:A.x-e,y:A.y-e}},shadow:function(a){a&&(L(),z&&z.shadow(a));return p},destroy:function(){w(p.element,"mouseenter");w(p.element,"mouseleave");t&&(t=t.destroy());z&&(z=z.destroy());C.prototype.destroy.call(p);p=k=L=R=U=null}})}});a.Renderer=E})(K);(function(a){var C=a.attr,E=a.createElement,F=a.css,n=a.defined,h=a.each,
e=a.extend,u=a.isFirefox,y=a.isMS,q=a.isWebKit,x=a.pick,f=a.pInt,c=a.SVGRenderer,k=a.win,r=a.wrap;e(a.SVGElement.prototype,{htmlCss:function(a){var d=this.element;if(d=a&&"SPAN"===d.tagName&&a.width)delete a.width,this.textWidth=d,this.htmlUpdateTransform();a&&"ellipsis"===a.textOverflow&&(a.whiteSpace="nowrap",a.overflow="hidden");this.styles=e(this.styles,a);F(this.element,a);return this},htmlGetBBox:function(){var a=this.element;return{x:a.offsetLeft,y:a.offsetTop,width:a.offsetWidth,height:a.offsetHeight}},
htmlUpdateTransform:function(){if(this.added){var a=this.renderer,d=this.element,b=this.translateX||0,c=this.translateY||0,p=this.x||0,k=this.y||0,t=this.textAlign||"left",r={left:0,center:.5,right:1}[t],A=this.styles,e=A&&A.whiteSpace;F(d,{marginLeft:b,marginTop:c});this.shadows&&h(this.shadows,function(a){F(a,{marginLeft:b+1,marginTop:c+1})});this.inverted&&h(d.childNodes,function(b){a.invertChild(b,d)});if("SPAN"===d.tagName){var A=this.rotation,m=this.textWidth&&f(this.textWidth),D=[A,t,d.innerHTML,
this.textWidth,this.textAlign].join(),B;(B=m!==this.oldTextWidth)&&!(B=m>this.oldTextWidth)&&((B=this.textPxLength)||(F(d,{width:"",whiteSpace:e||"nowrap"}),B=d.offsetWidth),B=B>m);B&&/[ \-]/.test(d.textContent||d.innerText)?(F(d,{width:m+"px",display:"block",whiteSpace:e||"normal"}),this.oldTextWidth=m,this.hasBoxWidthChanged=!0):this.hasBoxWidthChanged=!1;D!==this.cTT&&(e=a.fontMetrics(d.style.fontSize).b,n(A)&&A!==(this.oldRotation||0)&&this.setSpanRotation(A,r,e),this.getSpanCorrection(!n(A)&&
this.textPxLength||d.offsetWidth,e,r,A,t));F(d,{left:p+(this.xCorr||0)+"px",top:k+(this.yCorr||0)+"px"});this.cTT=D;this.oldRotation=A}}else this.alignOnAdd=!0},setSpanRotation:function(a,d,b){var c={},l=this.renderer.getTransformKey();c[l]=c.transform="rotate("+a+"deg)";c[l+(u?"Origin":"-origin")]=c.transformOrigin=100*d+"% "+b+"px";F(this.element,c)},getSpanCorrection:function(a,d,b){this.xCorr=-a*b;this.yCorr=-d}});e(c.prototype,{getTransformKey:function(){return y&&!/Edge/.test(k.navigator.userAgent)?
"-ms-transform":q?"-webkit-transform":u?"MozTransform":k.opera?"-o-transform":""},html:function(a,d,b){var c=this.createElement("span"),l=c.element,f=c.renderer,k=f.isSVG,q=function(a,b){h(["opacity","visibility"],function(d){r(a,d+"Setter",function(a,d,c,m){a.call(this,d,c,m);b[c]=d})});a.addedSetters=!0};c.textSetter=function(a){a!==l.innerHTML&&delete this.bBox;this.textStr=a;l.innerHTML=x(a,"");c.doTransform=!0};k&&q(c,c.element.style);c.xSetter=c.ySetter=c.alignSetter=c.rotationSetter=function(a,
b){"align"===b&&(b="textAlign");c[b]=a;c.doTransform=!0};c.afterSetters=function(){this.doTransform&&(this.htmlUpdateTransform(),this.doTransform=!1)};c.attr({text:a,x:Math.round(d),y:Math.round(b)}).css({fontFamily:this.style.fontFamily,fontSize:this.style.fontSize,position:"absolute"});l.style.whiteSpace="nowrap";c.css=c.htmlCss;k&&(c.add=function(a){var b,d=f.box.parentNode,p=[];if(this.parentGroup=a){if(b=a.div,!b){for(;a;)p.push(a),a=a.parentGroup;h(p.reverse(),function(a){function m(g,b){a[b]=
g;"translateX"===b?l.left=g+"px":l.top=g+"px";a.doTransform=!0}var l,g=C(a.element,"class");g&&(g={className:g});b=a.div=a.div||E("div",g,{position:"absolute",left:(a.translateX||0)+"px",top:(a.translateY||0)+"px",display:a.display,opacity:a.opacity,pointerEvents:a.styles&&a.styles.pointerEvents},b||d);l=b.style;e(a,{classSetter:function(a){return function(g){this.element.setAttribute("class",g);a.className=g}}(b),on:function(){p[0].div&&c.on.apply({element:p[0].div},arguments);return a},translateXSetter:m,
translateYSetter:m});a.addedSetters||q(a,l)})}}else b=d;b.appendChild(l);c.added=!0;c.alignOnAdd&&c.htmlUpdateTransform();return c});return c}})})(K);(function(a){var C=a.defined,E=a.each,F=a.extend,n=a.merge,h=a.pick,e=a.timeUnits,u=a.win;a.Time=function(a){this.update(a,!1)};a.Time.prototype={defaultOptions:{},update:function(e){var q=h(e&&e.useUTC,!0),x=this;this.options=e=n(!0,this.options||{},e);this.Date=e.Date||u.Date;this.timezoneOffset=(this.useUTC=q)&&e.timezoneOffset;this.getTimezoneOffset=
this.timezoneOffsetFunction();(this.variableTimezone=!(q&&!e.getTimezoneOffset&&!e.timezone))||this.timezoneOffset?(this.get=function(a,c){var f=c.getTime(),r=f-x.getTimezoneOffset(c);c.setTime(r);a=c["getUTC"+a]();c.setTime(f);return a},this.set=function(f,c,k){var r;if(-1!==a.inArray(f,["Milliseconds","Seconds","Minutes"]))c["set"+f](k);else r=x.getTimezoneOffset(c),r=c.getTime()-r,c.setTime(r),c["setUTC"+f](k),f=x.getTimezoneOffset(c),r=c.getTime()+f,c.setTime(r)}):q?(this.get=function(a,c){return c["getUTC"+
a]()},this.set=function(a,c,k){return c["setUTC"+a](k)}):(this.get=function(a,c){return c["get"+a]()},this.set=function(a,c,k){return c["set"+a](k)})},makeTime:function(e,q,x,f,c,k){var r,l,d;this.useUTC?(r=this.Date.UTC.apply(0,arguments),l=this.getTimezoneOffset(r),r+=l,d=this.getTimezoneOffset(r),l!==d?r+=d-l:l-36E5!==this.getTimezoneOffset(r-36E5)||a.isSafari||(r-=36E5)):r=(new this.Date(e,q,h(x,1),h(f,0),h(c,0),h(k,0))).getTime();return r},timezoneOffsetFunction:function(){var e=this,h=this.options,
x=u.moment;if(!this.useUTC)return function(a){return 6E4*(new Date(a)).getTimezoneOffset()};if(h.timezone){if(x)return function(a){return 6E4*-x.tz(a,h.timezone).utcOffset()};a.error(25)}return this.useUTC&&h.getTimezoneOffset?function(a){return 6E4*h.getTimezoneOffset(a)}:function(){return 6E4*(e.timezoneOffset||0)}},dateFormat:function(e,h,x){if(!a.defined(h)||isNaN(h))return a.defaultOptions.lang.invalidDate||"";e=a.pick(e,"%Y-%m-%d %H:%M:%S");var f=this,c=new this.Date(h),k=this.get("Hours",c),
r=this.get("Day",c),l=this.get("Date",c),d=this.get("Month",c),b=this.get("FullYear",c),v=a.defaultOptions.lang,p=v.weekdays,q=v.shortWeekdays,t=a.pad,c=a.extend({a:q?q[r]:p[r].substr(0,3),A:p[r],d:t(l),e:t(l,2," "),w:r,b:v.shortMonths[d],B:v.months[d],m:t(d+1),o:d+1,y:b.toString().substr(2,2),Y:b,H:t(k),k:k,I:t(k%12||12),l:k%12||12,M:t(f.get("Minutes",c)),p:12>k?"AM":"PM",P:12>k?"am":"pm",S:t(c.getSeconds()),L:t(Math.round(h%1E3),3)},a.dateFormats);a.objectEach(c,function(a,b){for(;-1!==e.indexOf("%"+
b);)e=e.replace("%"+b,"function"===typeof a?a.call(f,h):a)});return x?e.substr(0,1).toUpperCase()+e.substr(1):e},getTimeTicks:function(a,q,x,f){var c=this,k=[],r={},l,d=new c.Date(q),b=a.unitRange,v=a.count||1,p;if(C(q)){c.set("Milliseconds",d,b>=e.second?0:v*Math.floor(c.get("Milliseconds",d)/v));b>=e.second&&c.set("Seconds",d,b>=e.minute?0:v*Math.floor(c.get("Seconds",d)/v));b>=e.minute&&c.set("Minutes",d,b>=e.hour?0:v*Math.floor(c.get("Minutes",d)/v));b>=e.hour&&c.set("Hours",d,b>=e.day?0:v*Math.floor(c.get("Hours",
d)/v));b>=e.day&&c.set("Date",d,b>=e.month?1:v*Math.floor(c.get("Date",d)/v));b>=e.month&&(c.set("Month",d,b>=e.year?0:v*Math.floor(c.get("Month",d)/v)),l=c.get("FullYear",d));b>=e.year&&c.set("FullYear",d,l-l%v);b===e.week&&c.set("Date",d,c.get("Date",d)-c.get("Day",d)+h(f,1));l=c.get("FullYear",d);f=c.get("Month",d);var I=c.get("Date",d),t=c.get("Hours",d);q=d.getTime();c.variableTimezone&&(p=x-q>4*e.month||c.getTimezoneOffset(q)!==c.getTimezoneOffset(x));d=d.getTime();for(q=1;d<x;)k.push(d),d=
b===e.year?c.makeTime(l+q*v,0):b===e.month?c.makeTime(l,f+q*v):!p||b!==e.day&&b!==e.week?p&&b===e.hour&&1<v?c.makeTime(l,f,I,t+q*v):d+b*v:c.makeTime(l,f,I+q*v*(b===e.day?1:7)),q++;k.push(d);b<=e.hour&&1E4>k.length&&E(k,function(a){0===a%18E5&&"000000000"===c.dateFormat("%H%M%S%L",a)&&(r[a]="day")})}k.info=F(a,{higherRanks:r,totalRange:b*v});return k}}})(K);(function(a){var C=a.color,E=a.merge;a.defaultOptions={colors:"#7cb5ec #434348 #90ed7d #f7a35c #8085e9 #f15c80 #e4d354 #2b908f #f45b5b #91e8e1".split(" "),
symbols:["circle","diamond","square","triangle","triangle-down"],lang:{loading:"Loading...",months:"January February March April May June July August September October November December".split(" "),shortMonths:"Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),weekdays:"Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),decimalPoint:".",numericSymbols:"kMGTPE".split(""),resetZoom:"Reset zoom",resetZoomTitle:"Reset zoom level 1:1",thousandsSep:" "},global:{},time:a.Time.prototype.defaultOptions,
chart:{borderRadius:0,defaultSeriesType:"line",ignoreHiddenSeries:!0,spacing:[10,10,15,10],resetZoomButton:{theme:{zIndex:6},position:{align:"right",x:-10,y:10}},width:null,height:null,borderColor:"#335cad",backgroundColor:"#ffffff",plotBorderColor:"#cccccc"},title:{text:"Chart title",align:"center",margin:15,widthAdjust:-44},subtitle:{text:"",align:"center",widthAdjust:-44},plotOptions:{},labels:{style:{position:"absolute",color:"#333333"}},legend:{enabled:!0,align:"center",alignColumns:!0,layout:"horizontal",
labelFormatter:function(){return this.name},borderColor:"#999999",borderRadius:0,navigation:{activeColor:"#003399",inactiveColor:"#cccccc"},itemStyle:{color:"#333333",fontSize:"12px",fontWeight:"bold",textOverflow:"ellipsis"},itemHoverStyle:{color:"#000000"},itemHiddenStyle:{color:"#cccccc"},shadow:!1,itemCheckboxStyle:{position:"absolute",width:"13px",height:"13px"},squareSymbol:!0,symbolPadding:5,verticalAlign:"bottom",x:0,y:0,title:{style:{fontWeight:"bold"}}},loading:{labelStyle:{fontWeight:"bold",
position:"relative",top:"45%"},style:{position:"absolute",backgroundColor:"#ffffff",opacity:.5,textAlign:"center"}},tooltip:{enabled:!0,animation:a.svg,borderRadius:3,dateTimeLabelFormats:{millisecond:"%A, %b %e, %H:%M:%S.%L",second:"%A, %b %e, %H:%M:%S",minute:"%A, %b %e, %H:%M",hour:"%A, %b %e, %H:%M",day:"%A, %b %e, %Y",week:"Week from %A, %b %e, %Y",month:"%B %Y",year:"%Y"},footerFormat:"",padding:8,snap:a.isTouchDevice?25:10,backgroundColor:C("#f7f7f7").setOpacity(.85).get(),borderWidth:1,headerFormat:'\x3cspan style\x3d"font-size: 10px"\x3e{point.key}\x3c/span\x3e\x3cbr/\x3e',
pointFormat:'\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e {series.name}: \x3cb\x3e{point.y}\x3c/b\x3e\x3cbr/\x3e',shadow:!0,style:{color:"#333333",cursor:"default",fontSize:"12px",pointerEvents:"none",whiteSpace:"nowrap"}},credits:{enabled:!0,href:"http://www.highcharts.com",position:{align:"right",x:-10,verticalAlign:"bottom",y:-5},style:{cursor:"pointer",color:"#999999",fontSize:"9px"},text:"Highcharts.com"}};a.setOptions=function(C){a.defaultOptions=E(!0,a.defaultOptions,C);a.time.update(E(a.defaultOptions.global,
a.defaultOptions.time),!1);return a.defaultOptions};a.getOptions=function(){return a.defaultOptions};a.defaultPlotOptions=a.defaultOptions.plotOptions;a.time=new a.Time(E(a.defaultOptions.global,a.defaultOptions.time));a.dateFormat=function(C,n,h){return a.time.dateFormat(C,n,h)}})(K);(function(a){var C=a.correctFloat,E=a.defined,F=a.destroyObjectProperties,n=a.fireEvent,h=a.isNumber,e=a.merge,u=a.pick,y=a.deg2rad;a.Tick=function(a,e,f,c){this.axis=a;this.pos=e;this.type=f||"";this.isNewLabel=this.isNew=
!0;f||c||this.addLabel()};a.Tick.prototype={addLabel:function(){var a=this.axis,h=a.options,f=a.chart,c=a.categories,k=a.names,r=this.pos,l=h.labels,d=a.tickPositions,b=r===d[0],v=r===d[d.length-1],k=c?u(c[r],k[r],r):r,c=this.label,d=d.info,p;a.isDatetimeAxis&&d&&(p=h.dateTimeLabelFormats[d.higherRanks[r]||d.unitName]);this.isFirst=b;this.isLast=v;h=a.labelFormatter.call({axis:a,chart:f,isFirst:b,isLast:v,dateTimeLabelFormat:p,value:a.isLog?C(a.lin2log(k)):k,pos:r});if(E(c))c&&c.attr({text:h});else{if(this.label=
c=E(h)&&l.enabled?f.renderer.text(h,0,0,l.useHTML).css(e(l.style)).add(a.labelGroup):null)c.textPxLength=c.getBBox().width;this.rotation=0}},getLabelSize:function(){return this.label?this.label.getBBox()[this.axis.horiz?"height":"width"]:0},handleOverflow:function(a){var e=this.axis,f=e.options.labels,c=a.x,k=e.chart.chartWidth,r=e.chart.spacing,l=u(e.labelLeft,Math.min(e.pos,r[3])),r=u(e.labelRight,Math.max(e.isRadial?0:e.pos+e.len,k-r[1])),d=this.label,b=this.rotation,v={left:0,center:.5,right:1}[e.labelAlign||
d.attr("align")],p=d.getBBox().width,h=e.getSlotWidth(this),t=h,q=1,A,H={};if(b||!1===f.overflow)0>b&&c-v*p<l?A=Math.round(c/Math.cos(b*y)-l):0<b&&c+v*p>r&&(A=Math.round((k-c)/Math.cos(b*y)));else if(k=c+(1-v)*p,c-v*p<l?t=a.x+t*(1-v)-l:k>r&&(t=r-a.x+t*v,q=-1),t=Math.min(h,t),t<h&&"center"===e.labelAlign&&(a.x+=q*(h-t-v*(h-Math.min(p,t)))),p>t||e.autoRotation&&(d.styles||{}).width)A=t;A&&(H.width=A,(f.style||{}).textOverflow||(H.textOverflow="ellipsis"),d.css(H))},getPosition:function(e,h,f,c){var k=
this.axis,r=k.chart,l=c&&r.oldChartHeight||r.chartHeight;e={x:e?a.correctFloat(k.translate(h+f,null,null,c)+k.transB):k.left+k.offset+(k.opposite?(c&&r.oldChartWidth||r.chartWidth)-k.right-k.left:0),y:e?l-k.bottom+k.offset-(k.opposite?k.height:0):a.correctFloat(l-k.translate(h+f,null,null,c)-k.transB)};n(this,"afterGetPosition",{pos:e});return e},getLabelPosition:function(a,e,f,c,k,r,l,d){var b=this.axis,v=b.transA,p=b.reversed,h=b.staggerLines,t=b.tickRotCorr||{x:0,y:0},q=k.y,A=c||b.reserveSpaceDefault?
0:-b.labelOffset*("center"===b.labelAlign?.5:1),x={};E(q)||(q=0===b.side?f.rotation?-8:-f.getBBox().height:2===b.side?t.y+8:Math.cos(f.rotation*y)*(t.y-f.getBBox(!1,0).height/2));a=a+k.x+A+t.x-(r&&c?r*v*(p?-1:1):0);e=e+q-(r&&!c?r*v*(p?1:-1):0);h&&(f=l/(d||1)%h,b.opposite&&(f=h-f-1),e+=b.labelOffset/h*f);x.x=a;x.y=Math.round(e);n(this,"afterGetLabelPosition",{pos:x});return x},getMarkPath:function(a,e,f,c,k,r){return r.crispLine(["M",a,e,"L",a+(k?0:-f),e+(k?f:0)],c)},renderGridLine:function(a,e,f){var c=
this.axis,k=c.options,r=this.gridLine,l={},d=this.pos,b=this.type,v=c.tickmarkOffset,p=c.chart.renderer,h=b?b+"Grid":"grid",t=k[h+"LineWidth"],q=k[h+"LineColor"],k=k[h+"LineDashStyle"];r||(l.stroke=q,l["stroke-width"]=t,k&&(l.dashstyle=k),b||(l.zIndex=1),a&&(l.opacity=0),this.gridLine=r=p.path().attr(l).addClass("highcharts-"+(b?b+"-":"")+"grid-line").add(c.gridGroup));if(!a&&r&&(a=c.getPlotLinePath(d+v,r.strokeWidth()*f,a,!0)))r[this.isNew?"attr":"animate"]({d:a,opacity:e})},renderMark:function(a,
e,f){var c=this.axis,k=c.options,r=c.chart.renderer,l=this.type,d=l?l+"Tick":"tick",b=c.tickSize(d),v=this.mark,p=!v,h=a.x;a=a.y;var t=u(k[d+"Width"],!l&&c.isXAxis?1:0),k=k[d+"Color"];b&&(c.opposite&&(b[0]=-b[0]),p&&(this.mark=v=r.path().addClass("highcharts-"+(l?l+"-":"")+"tick").add(c.axisGroup),v.attr({stroke:k,"stroke-width":t})),v[p?"attr":"animate"]({d:this.getMarkPath(h,a,b[0],v.strokeWidth()*f,c.horiz,r),opacity:e}))},renderLabel:function(a,e,f,c){var k=this.axis,r=k.horiz,l=k.options,d=this.label,
b=l.labels,v=b.step,k=k.tickmarkOffset,p=!0,I=a.x;a=a.y;d&&h(I)&&(d.xy=a=this.getLabelPosition(I,a,d,r,b,k,c,v),this.isFirst&&!this.isLast&&!u(l.showFirstLabel,1)||this.isLast&&!this.isFirst&&!u(l.showLastLabel,1)?p=!1:!r||b.step||b.rotation||e||0===f||this.handleOverflow(a),v&&c%v&&(p=!1),p&&h(a.y)?(a.opacity=f,d[this.isNewLabel?"attr":"animate"](a),this.isNewLabel=!1):(d.attr("y",-9999),this.isNewLabel=!0))},render:function(e,h,f){var c=this.axis,k=c.horiz,r=this.getPosition(k,this.pos,c.tickmarkOffset,
h),l=r.x,d=r.y,c=k&&l===c.pos+c.len||!k&&d===c.pos?-1:1;f=u(f,1);this.isActive=!0;this.renderGridLine(h,f,c);this.renderMark(r,f,c);this.renderLabel(r,h,f,e);this.isNew=!1;a.fireEvent(this,"afterRender")},destroy:function(){F(this,this.axis)}}})(K);var V=function(a){var C=a.addEvent,E=a.animObject,F=a.arrayMax,n=a.arrayMin,h=a.color,e=a.correctFloat,u=a.defaultOptions,y=a.defined,q=a.deg2rad,x=a.destroyObjectProperties,f=a.each,c=a.extend,k=a.fireEvent,r=a.format,l=a.getMagnitude,d=a.grep,b=a.inArray,
v=a.isArray,p=a.isNumber,I=a.isString,t=a.merge,L=a.normalizeTickInterval,A=a.objectEach,H=a.pick,m=a.removeEvent,D=a.splat,B=a.syncTimeout,M=a.Tick,G=function(){this.init.apply(this,arguments)};a.extend(G.prototype,{defaultOptions:{dateTimeLabelFormats:{millisecond:"%H:%M:%S.%L",second:"%H:%M:%S",minute:"%H:%M",hour:"%H:%M",day:"%e. %b",week:"%e. %b",month:"%b '%y",year:"%Y"},endOnTick:!1,labels:{enabled:!0,style:{color:"#666666",cursor:"default",fontSize:"11px"},x:0},maxPadding:.01,minorTickLength:2,
minorTickPosition:"outside",minPadding:.01,startOfWeek:1,startOnTick:!1,tickLength:10,tickmarkPlacement:"between",tickPixelInterval:100,tickPosition:"outside",title:{align:"middle",style:{color:"#666666"}},type:"linear",minorGridLineColor:"#f2f2f2",minorGridLineWidth:1,minorTickColor:"#999999",lineColor:"#ccd6eb",lineWidth:1,gridLineColor:"#e6e6e6",tickColor:"#ccd6eb"},defaultYAxisOptions:{endOnTick:!0,tickPixelInterval:72,showLastLabel:!0,labels:{x:-8},maxPadding:.05,minPadding:.05,startOnTick:!0,
title:{rotation:270,text:"Values"},stackLabels:{allowOverlap:!1,enabled:!1,formatter:function(){return a.numberFormat(this.total,-1)},style:{fontSize:"11px",fontWeight:"bold",color:"#000000",textOutline:"1px contrast"}},gridLineWidth:1,lineWidth:0},defaultLeftAxisOptions:{labels:{x:-15},title:{rotation:270}},defaultRightAxisOptions:{labels:{x:15},title:{rotation:90}},defaultBottomAxisOptions:{labels:{autoRotation:[-45],x:0},title:{rotation:0}},defaultTopAxisOptions:{labels:{autoRotation:[-45],x:0},
title:{rotation:0}},init:function(a,d){var g=d.isX,w=this;w.chart=a;w.horiz=a.inverted&&!w.isZAxis?!g:g;w.isXAxis=g;w.coll=w.coll||(g?"xAxis":"yAxis");k(this,"init",{userOptions:d});w.opposite=d.opposite;w.side=d.side||(w.horiz?w.opposite?0:2:w.opposite?1:3);w.setOptions(d);var c=this.options,m=c.type;w.labelFormatter=c.labels.formatter||w.defaultLabelFormatter;w.userOptions=d;w.minPixelPadding=0;w.reversed=c.reversed;w.visible=!1!==c.visible;w.zoomEnabled=!1!==c.zoomEnabled;w.hasNames="category"===
m||!0===c.categories;w.categories=c.categories||w.hasNames;w.names||(w.names=[],w.names.keys={});w.plotLinesAndBandsGroups={};w.isLog="logarithmic"===m;w.isDatetimeAxis="datetime"===m;w.positiveValuesOnly=w.isLog&&!w.allowNegativeLog;w.isLinked=y(c.linkedTo);w.ticks={};w.labelEdge=[];w.minorTicks={};w.plotLinesAndBands=[];w.alternateBands={};w.len=0;w.minRange=w.userMinRange=c.minRange||c.maxZoom;w.range=c.range;w.offset=c.offset||0;w.stacks={};w.oldStacks={};w.stacksTouched=0;w.max=null;w.min=null;
w.crosshair=H(c.crosshair,D(a.options.tooltip.crosshairs)[g?0:1],!1);d=w.options.events;-1===b(w,a.axes)&&(g?a.axes.splice(a.xAxis.length,0,w):a.axes.push(w),a[w.coll].push(w));w.series=w.series||[];a.inverted&&!w.isZAxis&&g&&void 0===w.reversed&&(w.reversed=!0);A(d,function(a,g){C(w,g,a)});w.lin2log=c.linearToLogConverter||w.lin2log;w.isLog&&(w.val2lin=w.log2lin,w.lin2val=w.lin2log);k(this,"afterInit")},setOptions:function(a){this.options=t(this.defaultOptions,"yAxis"===this.coll&&this.defaultYAxisOptions,
[this.defaultTopAxisOptions,this.defaultRightAxisOptions,this.defaultBottomAxisOptions,this.defaultLeftAxisOptions][this.side],t(u[this.coll],a));k(this,"afterSetOptions",{userOptions:a})},defaultLabelFormatter:function(){var g=this.axis,b=this.value,d=g.chart.time,c=g.categories,m=this.dateTimeLabelFormat,l=u.lang,f=l.numericSymbols,l=l.numericSymbolMagnitude||1E3,p=f&&f.length,k,v=g.options.labels.format,g=g.isLog?Math.abs(b):g.tickInterval;if(v)k=r(v,this,d);else if(c)k=b;else if(m)k=d.dateFormat(m,
b);else if(p&&1E3<=g)for(;p--&&void 0===k;)d=Math.pow(l,p+1),g>=d&&0===10*b%d&&null!==f[p]&&0!==b&&(k=a.numberFormat(b/d,-1)+f[p]);void 0===k&&(k=1E4<=Math.abs(b)?a.numberFormat(b,-1):a.numberFormat(b,-1,void 0,""));return k},getSeriesExtremes:function(){var a=this,b=a.chart;k(this,"getSeriesExtremes",null,function(){a.hasVisibleSeries=!1;a.dataMin=a.dataMax=a.threshold=null;a.softThreshold=!a.isXAxis;a.buildStacks&&a.buildStacks();f(a.series,function(g){if(g.visible||!b.options.chart.ignoreHiddenSeries){var w=
g.options,c=w.threshold,m;a.hasVisibleSeries=!0;a.positiveValuesOnly&&0>=c&&(c=null);if(a.isXAxis)w=g.xData,w.length&&(g=n(w),m=F(w),p(g)||g instanceof Date||(w=d(w,p),g=n(w),m=F(w)),w.length&&(a.dataMin=Math.min(H(a.dataMin,w[0],g),g),a.dataMax=Math.max(H(a.dataMax,w[0],m),m)));else if(g.getExtremes(),m=g.dataMax,g=g.dataMin,y(g)&&y(m)&&(a.dataMin=Math.min(H(a.dataMin,g),g),a.dataMax=Math.max(H(a.dataMax,m),m)),y(c)&&(a.threshold=c),!w.softThreshold||a.positiveValuesOnly)a.softThreshold=!1}})});
k(this,"afterGetSeriesExtremes")},translate:function(a,b,d,c,m,l){var g=this.linkedParent||this,w=1,f=0,J=c?g.oldTransA:g.transA;c=c?g.oldMin:g.min;var k=g.minPixelPadding;m=(g.isOrdinal||g.isBroken||g.isLog&&m)&&g.lin2val;J||(J=g.transA);d&&(w*=-1,f=g.len);g.reversed&&(w*=-1,f-=w*(g.sector||g.len));b?(a=(a*w+f-k)/J+c,m&&(a=g.lin2val(a))):(m&&(a=g.val2lin(a)),a=p(c)?w*(a-c)*J+f+w*k+(p(l)?J*l:0):void 0);return a},toPixels:function(a,b){return this.translate(a,!1,!this.horiz,null,!0)+(b?0:this.pos)},
toValue:function(a,b){return this.translate(a-(b?0:this.pos),!0,!this.horiz,null,!0)},getPlotLinePath:function(a,b,d,c,m){var g=this.chart,w=this.left,l=this.top,f,J,k=d&&g.oldChartHeight||g.chartHeight,v=d&&g.oldChartWidth||g.chartWidth,e;f=this.transB;var B=function(a,g,b){if(a<g||a>b)c?a=Math.min(Math.max(g,a),b):e=!0;return a};m=H(m,this.translate(a,null,null,d));m=Math.min(Math.max(-1E5,m),1E5);a=d=Math.round(m+f);f=J=Math.round(k-m-f);p(m)?this.horiz?(f=l,J=k-this.bottom,a=d=B(a,w,w+this.width)):
(a=w,d=v-this.right,f=J=B(f,l,l+this.height)):(e=!0,c=!1);return e&&!c?null:g.renderer.crispLine(["M",a,f,"L",d,J],b||1)},getLinearTickPositions:function(a,b,d){var g,w=e(Math.floor(b/a)*a);d=e(Math.ceil(d/a)*a);var c=[],m;e(w+a)===w&&(m=20);if(this.single)return[b];for(b=w;b<=d;){c.push(b);b=e(b+a,m);if(b===g)break;g=b}return c},getMinorTickInterval:function(){var a=this.options;return!0===a.minorTicks?H(a.minorTickInterval,"auto"):!1===a.minorTicks?null:a.minorTickInterval},getMinorTickPositions:function(){var a=
this,b=a.options,d=a.tickPositions,c=a.minorTickInterval,m=[],l=a.pointRangePadding||0,p=a.min-l,l=a.max+l,k=l-p;if(k&&k/c<a.len/3)if(a.isLog)f(this.paddedTicks,function(g,b,d){b&&m.push.apply(m,a.getLogTickPositions(c,d[b-1],d[b],!0))});else if(a.isDatetimeAxis&&"auto"===this.getMinorTickInterval())m=m.concat(a.getTimeTicks(a.normalizeTimeTickInterval(c),p,l,b.startOfWeek));else for(b=p+(d[0]-p)%c;b<=l&&b!==m[0];b+=c)m.push(b);0!==m.length&&a.trimTicks(m);return m},adjustForMinRange:function(){var a=
this.options,b=this.min,d=this.max,c,m,l,p,k,v,e,B;this.isXAxis&&void 0===this.minRange&&!this.isLog&&(y(a.min)||y(a.max)?this.minRange=null:(f(this.series,function(a){v=a.xData;for(p=e=a.xIncrement?1:v.length-1;0<p;p--)if(k=v[p]-v[p-1],void 0===l||k<l)l=k}),this.minRange=Math.min(5*l,this.dataMax-this.dataMin)));d-b<this.minRange&&(m=this.dataMax-this.dataMin>=this.minRange,B=this.minRange,c=(B-d+b)/2,c=[b-c,H(a.min,b-c)],m&&(c[2]=this.isLog?this.log2lin(this.dataMin):this.dataMin),b=F(c),d=[b+B,
H(a.max,b+B)],m&&(d[2]=this.isLog?this.log2lin(this.dataMax):this.dataMax),d=n(d),d-b<B&&(c[0]=d-B,c[1]=H(a.min,d-B),b=F(c)));this.min=b;this.max=d},getClosest:function(){var a;this.categories?a=1:f(this.series,function(g){var b=g.closestPointRange,d=g.visible||!g.chart.options.chart.ignoreHiddenSeries;!g.noSharedTooltip&&y(b)&&d&&(a=y(a)?Math.min(a,b):b)});return a},nameToX:function(a){var g=v(this.categories),d=g?this.categories:this.names,c=a.options.x,m;a.series.requireSorting=!1;y(c)||(c=!1===
this.options.uniqueNames?a.series.autoIncrement():g?b(a.name,d):H(d.keys[a.name],-1));-1===c?g||(m=d.length):m=c;void 0!==m&&(this.names[m]=a.name,this.names.keys[a.name]=m);return m},updateNames:function(){var g=this,b=this.names;0<b.length&&(f(a.keys(b.keys),function(a){delete b.keys[a]}),b.length=0,this.minRange=this.userMinRange,f(this.series||[],function(a){a.xIncrement=null;if(!a.points||a.isDirtyData)a.processData(),a.generatePoints();f(a.points,function(b,d){var c;b.options&&(c=g.nameToX(b),
void 0!==c&&c!==b.x&&(b.x=c,a.xData[d]=c))})}))},setAxisTranslation:function(a){var b=this,g=b.max-b.min,d=b.axisPointRange||0,c,m=0,l=0,p=b.linkedParent,v=!!b.categories,B=b.transA,e=b.isXAxis;if(e||v||d)c=b.getClosest(),p?(m=p.minPointOffset,l=p.pointRangePadding):f(b.series,function(a){var g=v?1:e?H(a.options.pointRange,c,0):b.axisPointRange||0;a=a.options.pointPlacement;d=Math.max(d,g);b.single||(m=Math.max(m,I(a)?0:g/2),l=Math.max(l,"on"===a?0:g))}),p=b.ordinalSlope&&c?b.ordinalSlope/c:1,b.minPointOffset=
m*=p,b.pointRangePadding=l*=p,b.pointRange=Math.min(d,g),e&&(b.closestPointRange=c);a&&(b.oldTransA=B);b.translationSlope=b.transA=B=b.options.staticScale||b.len/(g+l||1);b.transB=b.horiz?b.left:b.bottom;b.minPixelPadding=B*m;k(this,"afterSetAxisTranslation")},minFromRange:function(){return this.max-this.range},setTickInterval:function(b){var g=this,d=g.chart,c=g.options,m=g.isLog,v=g.isDatetimeAxis,B=g.isXAxis,t=g.isLinked,r=c.maxPadding,D=c.minPadding,h=c.tickInterval,A=c.tickPixelInterval,G=g.categories,
I=p(g.threshold)?g.threshold:null,x=g.softThreshold,q,u,M,n;v||G||t||this.getTickAmount();M=H(g.userMin,c.min);n=H(g.userMax,c.max);t?(g.linkedParent=d[g.coll][c.linkedTo],d=g.linkedParent.getExtremes(),g.min=H(d.min,d.dataMin),g.max=H(d.max,d.dataMax),c.type!==g.linkedParent.options.type&&a.error(11,1)):(!x&&y(I)&&(g.dataMin>=I?(q=I,D=0):g.dataMax<=I&&(u=I,r=0)),g.min=H(M,q,g.dataMin),g.max=H(n,u,g.dataMax));m&&(g.positiveValuesOnly&&!b&&0>=Math.min(g.min,H(g.dataMin,g.min))&&a.error(10,1),g.min=
e(g.log2lin(g.min),15),g.max=e(g.log2lin(g.max),15));g.range&&y(g.max)&&(g.userMin=g.min=M=Math.max(g.dataMin,g.minFromRange()),g.userMax=n=g.max,g.range=null);k(g,"foundExtremes");g.beforePadding&&g.beforePadding();g.adjustForMinRange();!(G||g.axisPointRange||g.usePercentage||t)&&y(g.min)&&y(g.max)&&(d=g.max-g.min)&&(!y(M)&&D&&(g.min-=d*D),!y(n)&&r&&(g.max+=d*r));p(c.softMin)&&!p(g.userMin)&&(g.min=Math.min(g.min,c.softMin));p(c.softMax)&&!p(g.userMax)&&(g.max=Math.max(g.max,c.softMax));p(c.floor)&&
(g.min=Math.max(g.min,c.floor));p(c.ceiling)&&(g.max=Math.min(g.max,c.ceiling));x&&y(g.dataMin)&&(I=I||0,!y(M)&&g.min<I&&g.dataMin>=I?g.min=I:!y(n)&&g.max>I&&g.dataMax<=I&&(g.max=I));g.tickInterval=g.min===g.max||void 0===g.min||void 0===g.max?1:t&&!h&&A===g.linkedParent.options.tickPixelInterval?h=g.linkedParent.tickInterval:H(h,this.tickAmount?(g.max-g.min)/Math.max(this.tickAmount-1,1):void 0,G?1:(g.max-g.min)*A/Math.max(g.len,A));B&&!b&&f(g.series,function(a){a.processData(g.min!==g.oldMin||g.max!==
g.oldMax)});g.setAxisTranslation(!0);g.beforeSetTickPositions&&g.beforeSetTickPositions();g.postProcessTickInterval&&(g.tickInterval=g.postProcessTickInterval(g.tickInterval));g.pointRange&&!h&&(g.tickInterval=Math.max(g.pointRange,g.tickInterval));b=H(c.minTickInterval,g.isDatetimeAxis&&g.closestPointRange);!h&&g.tickInterval<b&&(g.tickInterval=b);v||m||h||(g.tickInterval=L(g.tickInterval,null,l(g.tickInterval),H(c.allowDecimals,!(.5<g.tickInterval&&5>g.tickInterval&&1E3<g.max&&9999>g.max)),!!this.tickAmount));
this.tickAmount||(g.tickInterval=g.unsquish());this.setTickPositions()},setTickPositions:function(){var a=this.options,b,d=a.tickPositions;b=this.getMinorTickInterval();var c=a.tickPositioner,m=a.startOnTick,l=a.endOnTick;this.tickmarkOffset=this.categories&&"between"===a.tickmarkPlacement&&1===this.tickInterval?.5:0;this.minorTickInterval="auto"===b&&this.tickInterval?this.tickInterval/5:b;this.single=this.min===this.max&&y(this.min)&&!this.tickAmount&&(parseInt(this.min,10)===this.min||!1!==a.allowDecimals);
this.tickPositions=b=d&&d.slice();!b&&(b=this.isDatetimeAxis?this.getTimeTicks(this.normalizeTimeTickInterval(this.tickInterval,a.units),this.min,this.max,a.startOfWeek,this.ordinalPositions,this.closestPointRange,!0):this.isLog?this.getLogTickPositions(this.tickInterval,this.min,this.max):this.getLinearTickPositions(this.tickInterval,this.min,this.max),b.length>this.len&&(b=[b[0],b.pop()],b[0]===b[1]&&(b.length=1)),this.tickPositions=b,c&&(c=c.apply(this,[this.min,this.max])))&&(this.tickPositions=
b=c);this.paddedTicks=b.slice(0);this.trimTicks(b,m,l);this.isLinked||(this.single&&2>b.length&&(this.min-=.5,this.max+=.5),d||c||this.adjustTickAmount());k(this,"afterSetTickPositions")},trimTicks:function(a,b,d){var g=a[0],c=a[a.length-1],m=this.minPointOffset||0;if(!this.isLinked){if(b&&-Infinity!==g)this.min=g;else for(;this.min-m>a[0];)a.shift();if(d)this.max=c;else for(;this.max+m<a[a.length-1];)a.pop();0===a.length&&y(g)&&!this.options.tickPositions&&a.push((c+g)/2)}},alignToOthers:function(){var a=
{},b,d=this.options;!1===this.chart.options.chart.alignTicks||!1===d.alignTicks||!1===d.startOnTick||!1===d.endOnTick||this.isLog||f(this.chart[this.coll],function(g){var d=g.options,d=[g.horiz?d.left:d.top,d.width,d.height,d.pane].join();g.series.length&&(a[d]?b=!0:a[d]=1)});return b},getTickAmount:function(){var a=this.options,b=a.tickAmount,d=a.tickPixelInterval;!y(a.tickInterval)&&this.len<d&&!this.isRadial&&!this.isLog&&a.startOnTick&&a.endOnTick&&(b=2);!b&&this.alignToOthers()&&(b=Math.ceil(this.len/
d)+1);4>b&&(this.finalTickAmt=b,b=5);this.tickAmount=b},adjustTickAmount:function(){var a=this.tickInterval,b=this.tickPositions,d=this.tickAmount,c=this.finalTickAmt,m=b&&b.length,l=H(this.threshold,this.softThreshold?0:null);if(this.hasData()){if(m<d){for(;b.length<d;)b.length%2||this.min===l?b.push(e(b[b.length-1]+a)):b.unshift(e(b[0]-a));this.transA*=(m-1)/(d-1);this.min=b[0];this.max=b[b.length-1]}else m>d&&(this.tickInterval*=2,this.setTickPositions());if(y(c)){for(a=d=b.length;a--;)(3===c&&
1===a%2||2>=c&&0<a&&a<d-1)&&b.splice(a,1);this.finalTickAmt=void 0}}},setScale:function(){var a,b;this.oldMin=this.min;this.oldMax=this.max;this.oldAxisLength=this.len;this.setAxisSize();b=this.len!==this.oldAxisLength;f(this.series,function(b){if(b.isDirtyData||b.isDirty||b.xAxis.isDirty)a=!0});b||a||this.isLinked||this.forceRedraw||this.userMin!==this.oldUserMin||this.userMax!==this.oldUserMax||this.alignToOthers()?(this.resetStacks&&this.resetStacks(),this.forceRedraw=!1,this.getSeriesExtremes(),
this.setTickInterval(),this.oldUserMin=this.userMin,this.oldUserMax=this.userMax,this.isDirty||(this.isDirty=b||this.min!==this.oldMin||this.max!==this.oldMax)):this.cleanStacks&&this.cleanStacks();k(this,"afterSetScale")},setExtremes:function(a,b,d,m,l){var g=this,p=g.chart;d=H(d,!0);f(g.series,function(a){delete a.kdTree});l=c(l,{min:a,max:b});k(g,"setExtremes",l,function(){g.userMin=a;g.userMax=b;g.eventArgs=l;d&&p.redraw(m)})},zoom:function(a,b){var g=this.dataMin,d=this.dataMax,c=this.options,
m=Math.min(g,H(c.min,g)),c=Math.max(d,H(c.max,d));if(a!==this.min||b!==this.max)this.allowZoomOutside||(y(g)&&(a<m&&(a=m),a>c&&(a=c)),y(d)&&(b<m&&(b=m),b>c&&(b=c))),this.displayBtn=void 0!==a||void 0!==b,this.setExtremes(a,b,!1,void 0,{trigger:"zoom"});return!0},setAxisSize:function(){var b=this.chart,d=this.options,c=d.offsets||[0,0,0,0],m=this.horiz,l=this.width=Math.round(a.relativeLength(H(d.width,b.plotWidth-c[3]+c[1]),b.plotWidth)),f=this.height=Math.round(a.relativeLength(H(d.height,b.plotHeight-
c[0]+c[2]),b.plotHeight)),p=this.top=Math.round(a.relativeLength(H(d.top,b.plotTop+c[0]),b.plotHeight,b.plotTop)),d=this.left=Math.round(a.relativeLength(H(d.left,b.plotLeft+c[3]),b.plotWidth,b.plotLeft));this.bottom=b.chartHeight-f-p;this.right=b.chartWidth-l-d;this.len=Math.max(m?l:f,0);this.pos=m?d:p},getExtremes:function(){var a=this.isLog;return{min:a?e(this.lin2log(this.min)):this.min,max:a?e(this.lin2log(this.max)):this.max,dataMin:this.dataMin,dataMax:this.dataMax,userMin:this.userMin,userMax:this.userMax}},
getThreshold:function(a){var b=this.isLog,g=b?this.lin2log(this.min):this.min,b=b?this.lin2log(this.max):this.max;null===a||-Infinity===a?a=g:Infinity===a?a=b:g>a?a=g:b<a&&(a=b);return this.translate(a,0,1,0,1)},autoLabelAlign:function(a){a=(H(a,0)-90*this.side+720)%360;return 15<a&&165>a?"right":195<a&&345>a?"left":"center"},tickSize:function(a){var b=this.options,g=b[a+"Length"],d=H(b[a+"Width"],"tick"===a&&this.isXAxis?1:0);if(d&&g)return"inside"===b[a+"Position"]&&(g=-g),[g,d]},labelMetrics:function(){var a=
this.tickPositions&&this.tickPositions[0]||0;return this.chart.renderer.fontMetrics(this.options.labels.style&&this.options.labels.style.fontSize,this.ticks[a]&&this.ticks[a].label)},unsquish:function(){var a=this.options.labels,b=this.horiz,d=this.tickInterval,c=d,m=this.len/(((this.categories?1:0)+this.max-this.min)/d),l,p=a.rotation,k=this.labelMetrics(),v,B=Number.MAX_VALUE,t,r=function(a){a/=m||1;a=1<a?Math.ceil(a):1;return e(a*d)};b?(t=!a.staggerLines&&!a.step&&(y(p)?[p]:m<H(a.autoRotationLimit,
80)&&a.autoRotation))&&f(t,function(a){var b;if(a===p||a&&-90<=a&&90>=a)v=r(Math.abs(k.h/Math.sin(q*a))),b=v+Math.abs(a/360),b<B&&(B=b,l=a,c=v)}):a.step||(c=r(k.h));this.autoRotation=t;this.labelRotation=H(l,p);return c},getSlotWidth:function(){var a=this.chart,b=this.horiz,d=this.options.labels,c=Math.max(this.tickPositions.length-(this.categories?0:1),1),m=a.margin[3];return b&&2>(d.step||0)&&!d.rotation&&(this.staggerLines||1)*this.len/c||!b&&(d.style&&parseInt(d.style.width,10)||m&&m-a.spacing[3]||
.33*a.chartWidth)},renderUnsquish:function(){var a=this.chart,b=a.renderer,d=this.tickPositions,c=this.ticks,m=this.options.labels,l=m&&m.style||{},p=this.horiz,k=this.getSlotWidth(),v=Math.max(1,Math.round(k-2*(m.padding||5))),B={},e=this.labelMetrics(),t=m.style&&m.style.textOverflow,r,D,h=0,A;I(m.rotation)||(B.rotation=m.rotation||0);f(d,function(a){(a=c[a])&&a.label&&a.label.textPxLength>h&&(h=a.label.textPxLength)});this.maxLabelLength=h;if(this.autoRotation)h>v&&h>e.h?B.rotation=this.labelRotation:
this.labelRotation=0;else if(k&&(r=v,!t))for(D="clip",v=d.length;!p&&v--;)if(A=d[v],A=c[A].label)A.styles&&"ellipsis"===A.styles.textOverflow?A.css({textOverflow:"clip"}):A.textPxLength>k&&A.css({width:k+"px"}),A.getBBox().height>this.len/d.length-(e.h-e.f)&&(A.specificTextOverflow="ellipsis");B.rotation&&(r=h>.5*a.chartHeight?.33*a.chartHeight:a.chartHeight,t||(D="ellipsis"));if(this.labelAlign=m.align||this.autoLabelAlign(this.labelRotation))B.align=this.labelAlign;f(d,function(a){var b=(a=c[a])&&
a.label,g=l.width,d={};b&&(b.attr(B),r&&!g&&"nowrap"!==l.whiteSpace&&(r<b.textPxLength||"SPAN"===b.element.tagName)?(d.width=r,t||(d.textOverflow=b.specificTextOverflow||D),b.css(d)):b.styles&&b.styles.width&&!d.width&&!g&&b.css({width:null}),delete b.specificTextOverflow,a.rotation=B.rotation)});this.tickRotCorr=b.rotCorr(e.b,this.labelRotation||0,0!==this.side)},hasData:function(){return this.hasVisibleSeries||y(this.min)&&y(this.max)&&this.tickPositions&&0<this.tickPositions.length},addTitle:function(a){var b=
this.chart.renderer,g=this.horiz,d=this.opposite,c=this.options.title,m;this.axisTitle||((m=c.textAlign)||(m=(g?{low:"left",middle:"center",high:"right"}:{low:d?"right":"left",middle:"center",high:d?"left":"right"})[c.align]),this.axisTitle=b.text(c.text,0,0,c.useHTML).attr({zIndex:7,rotation:c.rotation||0,align:m}).addClass("highcharts-axis-title").css(t(c.style)).add(this.axisGroup),this.axisTitle.isNew=!0);c.style.width||this.isRadial||this.axisTitle.css({width:this.len});this.axisTitle[a?"show":
"hide"](!0)},generateTick:function(a){var b=this.ticks;b[a]?b[a].addLabel():b[a]=new M(this,a)},getOffset:function(){var a=this,b=a.chart,d=b.renderer,c=a.options,m=a.tickPositions,l=a.ticks,p=a.horiz,k=a.side,v=b.inverted&&!a.isZAxis?[1,0,3,2][k]:k,B,e,t=0,r,D=0,h=c.title,G=c.labels,I=0,x=b.axisOffset,b=b.clipOffset,q=[-1,1,1,-1][k],M=c.className,u=a.axisParent,n=this.tickSize("tick");B=a.hasData();a.showAxis=e=B||H(c.showEmpty,!0);a.staggerLines=a.horiz&&G.staggerLines;a.axisGroup||(a.gridGroup=
d.g("grid").attr({zIndex:c.gridZIndex||1}).addClass("highcharts-"+this.coll.toLowerCase()+"-grid "+(M||"")).add(u),a.axisGroup=d.g("axis").attr({zIndex:c.zIndex||2}).addClass("highcharts-"+this.coll.toLowerCase()+" "+(M||"")).add(u),a.labelGroup=d.g("axis-labels").attr({zIndex:G.zIndex||7}).addClass("highcharts-"+a.coll.toLowerCase()+"-labels "+(M||"")).add(u));B||a.isLinked?(f(m,function(b,g){a.generateTick(b,g)}),a.renderUnsquish(),a.reserveSpaceDefault=0===k||2===k||{1:"left",3:"right"}[k]===a.labelAlign,
H(G.reserveSpace,"center"===a.labelAlign?!0:null,a.reserveSpaceDefault)&&f(m,function(a){I=Math.max(l[a].getLabelSize(),I)}),a.staggerLines&&(I*=a.staggerLines),a.labelOffset=I*(a.opposite?-1:1)):A(l,function(a,b){a.destroy();delete l[b]});h&&h.text&&!1!==h.enabled&&(a.addTitle(e),e&&!1!==h.reserveSpace&&(a.titleOffset=t=a.axisTitle.getBBox()[p?"height":"width"],r=h.offset,D=y(r)?0:H(h.margin,p?5:10)));a.renderLine();a.offset=q*H(c.offset,x[k]);a.tickRotCorr=a.tickRotCorr||{x:0,y:0};d=0===k?-a.labelMetrics().h:
2===k?a.tickRotCorr.y:0;D=Math.abs(I)+D;I&&(D=D-d+q*(p?H(G.y,a.tickRotCorr.y+8*q):G.x));a.axisTitleMargin=H(r,D);x[k]=Math.max(x[k],a.axisTitleMargin+t+q*a.offset,D,B&&m.length&&n?n[0]+q*a.offset:0);c=c.offset?0:2*Math.floor(a.axisLine.strokeWidth()/2);b[v]=Math.max(b[v],c)},getLinePath:function(a){var b=this.chart,g=this.opposite,d=this.offset,c=this.horiz,m=this.left+(g?this.width:0)+d,d=b.chartHeight-this.bottom-(g?this.height:0)+d;g&&(a*=-1);return b.renderer.crispLine(["M",c?this.left:m,c?d:
this.top,"L",c?b.chartWidth-this.right:m,c?d:b.chartHeight-this.bottom],a)},renderLine:function(){this.axisLine||(this.axisLine=this.chart.renderer.path().addClass("highcharts-axis-line").add(this.axisGroup),this.axisLine.attr({stroke:this.options.lineColor,"stroke-width":this.options.lineWidth,zIndex:7}))},getTitlePosition:function(){var a=this.horiz,b=this.left,d=this.top,c=this.len,m=this.options.title,l=a?b:d,f=this.opposite,p=this.offset,k=m.x||0,v=m.y||0,B=this.axisTitle,e=this.chart.renderer.fontMetrics(m.style&&
m.style.fontSize,B),B=Math.max(B.getBBox(null,0).height-e.h-1,0),c={low:l+(a?0:c),middle:l+c/2,high:l+(a?c:0)}[m.align],b=(a?d+this.height:b)+(a?1:-1)*(f?-1:1)*this.axisTitleMargin+[-B,B,e.f,-B][this.side];return{x:a?c+k:b+(f?this.width:0)+p+k,y:a?b+v-(f?this.height:0)+p:c+v}},renderMinorTick:function(a){var b=this.chart.hasRendered&&p(this.oldMin),d=this.minorTicks;d[a]||(d[a]=new M(this,a,"minor"));b&&d[a].isNew&&d[a].render(null,!0);d[a].render(null,!1,1)},renderTick:function(a,b){var d=this.isLinked,
g=this.ticks,c=this.chart.hasRendered&&p(this.oldMin);if(!d||a>=this.min&&a<=this.max)g[a]||(g[a]=new M(this,a)),c&&g[a].isNew&&g[a].render(b,!0,.1),g[a].render(b)},render:function(){var b=this,d=b.chart,c=b.options,m=b.isLog,l=b.isLinked,v=b.tickPositions,e=b.axisTitle,t=b.ticks,r=b.minorTicks,D=b.alternateBands,h=c.stackLabels,G=c.alternateGridColor,I=b.tickmarkOffset,x=b.axisLine,q=b.showAxis,H=E(d.renderer.globalAnimation),u,n;b.labelEdge.length=0;b.overlap=!1;f([t,r,D],function(a){A(a,function(a){a.isActive=
!1})});if(b.hasData()||l)b.minorTickInterval&&!b.categories&&f(b.getMinorTickPositions(),function(a){b.renderMinorTick(a)}),v.length&&(f(v,function(a,d){b.renderTick(a,d)}),I&&(0===b.min||b.single)&&(t[-1]||(t[-1]=new M(b,-1,null,!0)),t[-1].render(-1))),G&&f(v,function(c,g){n=void 0!==v[g+1]?v[g+1]+I:b.max-I;0===g%2&&c<b.max&&n<=b.max+(d.polar?-I:I)&&(D[c]||(D[c]=new a.PlotLineOrBand(b)),u=c+I,D[c].options={from:m?b.lin2log(u):u,to:m?b.lin2log(n):n,color:G},D[c].render(),D[c].isActive=!0)}),b._addedPlotLB||
(f((c.plotLines||[]).concat(c.plotBands||[]),function(a){b.addPlotBandOrLine(a)}),b._addedPlotLB=!0);f([t,r,D],function(a){var b,c=[],g=H.duration;A(a,function(a,b){a.isActive||(a.render(b,!1,0),a.isActive=!1,c.push(b))});B(function(){for(b=c.length;b--;)a[c[b]]&&!a[c[b]].isActive&&(a[c[b]].destroy(),delete a[c[b]])},a!==D&&d.hasRendered&&g?g:0)});x&&(x[x.isPlaced?"animate":"attr"]({d:this.getLinePath(x.strokeWidth())}),x.isPlaced=!0,x[q?"show":"hide"](!0));e&&q&&(c=b.getTitlePosition(),p(c.y)?(e[e.isNew?
"attr":"animate"](c),e.isNew=!1):(e.attr("y",-9999),e.isNew=!0));h&&h.enabled&&b.renderStackTotals();b.isDirty=!1;k(this,"afterRender")},redraw:function(){this.visible&&(this.render(),f(this.plotLinesAndBands,function(a){a.render()}));f(this.series,function(a){a.isDirty=!0})},keepProps:"extKey hcEvents names series userMax userMin".split(" "),destroy:function(a){var d=this,c=d.stacks,g=d.plotLinesAndBands,l;k(this,"destroy",{keepEvents:a});a||m(d);A(c,function(a,b){x(a);c[b]=null});f([d.ticks,d.minorTicks,
d.alternateBands],function(a){x(a)});if(g)for(a=g.length;a--;)g[a].destroy();f("stackTotalGroup axisLine axisTitle axisGroup gridGroup labelGroup cross".split(" "),function(a){d[a]&&(d[a]=d[a].destroy())});for(l in d.plotLinesAndBandsGroups)d.plotLinesAndBandsGroups[l]=d.plotLinesAndBandsGroups[l].destroy();A(d,function(a,c){-1===b(c,d.keepProps)&&delete d[c]})},drawCrosshair:function(a,b){var d,c=this.crosshair,g=H(c.snap,!0),m,l=this.cross;k(this,"drawCrosshair",{e:a,point:b});a||(a=this.cross&&
this.cross.e);if(this.crosshair&&!1!==(y(b)||!g)){g?y(b)&&(m=H(b.crosshairPos,this.isXAxis?b.plotX:this.len-b.plotY)):m=a&&(this.horiz?a.chartX-this.pos:this.len-a.chartY+this.pos);y(m)&&(d=this.getPlotLinePath(b&&(this.isXAxis?b.x:H(b.stackY,b.y)),null,null,null,m)||null);if(!y(d)){this.hideCrosshair();return}g=this.categories&&!this.isRadial;l||(this.cross=l=this.chart.renderer.path().addClass("highcharts-crosshair highcharts-crosshair-"+(g?"category ":"thin ")+c.className).attr({zIndex:H(c.zIndex,
2)}).add(),l.attr({stroke:c.color||(g?h("#ccd6eb").setOpacity(.25).get():"#cccccc"),"stroke-width":H(c.width,1)}).css({"pointer-events":"none"}),c.dashStyle&&l.attr({dashstyle:c.dashStyle}));l.show().attr({d:d});g&&!c.width&&l.attr({"stroke-width":this.transA});this.cross.e=a}else this.hideCrosshair();k(this,"afterDrawCrosshair",{e:a,point:b})},hideCrosshair:function(){this.cross&&this.cross.hide()}});return a.Axis=G}(K);(function(a){var C=a.Axis,E=a.getMagnitude,F=a.normalizeTickInterval,n=a.timeUnits;
C.prototype.getTimeTicks=function(){return this.chart.time.getTimeTicks.apply(this.chart.time,arguments)};C.prototype.normalizeTimeTickInterval=function(a,e){var h=e||[["millisecond",[1,2,5,10,20,25,50,100,200,500]],["second",[1,2,5,10,15,30]],["minute",[1,2,5,10,15,30]],["hour",[1,2,3,4,6,8,12]],["day",[1,2]],["week",[1,2]],["month",[1,2,3,4,6]],["year",null]];e=h[h.length-1];var y=n[e[0]],q=e[1],x;for(x=0;x<h.length&&!(e=h[x],y=n[e[0]],q=e[1],h[x+1]&&a<=(y*q[q.length-1]+n[h[x+1][0]])/2);x++);y===
n.year&&a<5*y&&(q=[1,2,5]);a=F(a/y,q,"year"===e[0]?Math.max(E(a/y),1):1);return{unitRange:y,count:a,unitName:e[0]}}})(K);(function(a){var C=a.Axis,E=a.getMagnitude,F=a.map,n=a.normalizeTickInterval,h=a.pick;C.prototype.getLogTickPositions=function(a,u,y,q){var e=this.options,f=this.len,c=[];q||(this._minorAutoInterval=null);if(.5<=a)a=Math.round(a),c=this.getLinearTickPositions(a,u,y);else if(.08<=a)for(var f=Math.floor(u),k,r,l,d,b,e=.3<a?[1,2,4]:.15<a?[1,2,4,6,8]:[1,2,3,4,5,6,7,8,9];f<y+1&&!b;f++)for(r=
e.length,k=0;k<r&&!b;k++)l=this.log2lin(this.lin2log(f)*e[k]),l>u&&(!q||d<=y)&&void 0!==d&&c.push(d),d>y&&(b=!0),d=l;else u=this.lin2log(u),y=this.lin2log(y),a=q?this.getMinorTickInterval():e.tickInterval,a=h("auto"===a?null:a,this._minorAutoInterval,e.tickPixelInterval/(q?5:1)*(y-u)/((q?f/this.tickPositions.length:f)||1)),a=n(a,null,E(a)),c=F(this.getLinearTickPositions(a,u,y),this.log2lin),q||(this._minorAutoInterval=a/5);q||(this.tickInterval=a);return c};C.prototype.log2lin=function(a){return Math.log(a)/
Math.LN10};C.prototype.lin2log=function(a){return Math.pow(10,a)}})(K);(function(a,C){var E=a.arrayMax,F=a.arrayMin,n=a.defined,h=a.destroyObjectProperties,e=a.each,u=a.erase,y=a.merge,q=a.pick;a.PlotLineOrBand=function(a,f){this.axis=a;f&&(this.options=f,this.id=f.id)};a.PlotLineOrBand.prototype={render:function(){var e=this,f=e.axis,c=f.horiz,k=e.options,r=k.label,l=e.label,d=k.to,b=k.from,v=k.value,p=n(b)&&n(d),h=n(v),t=e.svgElem,u=!t,A=[],H=k.color,m=q(k.zIndex,0),D=k.events,A={"class":"highcharts-plot-"+
(p?"band ":"line ")+(k.className||"")},B={},M=f.chart.renderer,G=p?"bands":"lines";f.isLog&&(b=f.log2lin(b),d=f.log2lin(d),v=f.log2lin(v));h?(A.stroke=H,A["stroke-width"]=k.width,k.dashStyle&&(A.dashstyle=k.dashStyle)):p&&(H&&(A.fill=H),k.borderWidth&&(A.stroke=k.borderColor,A["stroke-width"]=k.borderWidth));B.zIndex=m;G+="-"+m;(H=f.plotLinesAndBandsGroups[G])||(f.plotLinesAndBandsGroups[G]=H=M.g("plot-"+G).attr(B).add());u&&(e.svgElem=t=M.path().attr(A).add(H));if(h)A=f.getPlotLinePath(v,t.strokeWidth());
else if(p)A=f.getPlotBandPath(b,d,k);else return;u&&A&&A.length?(t.attr({d:A}),D&&a.objectEach(D,function(a,b){t.on(b,function(a){D[b].apply(e,[a])})})):t&&(A?(t.show(),t.animate({d:A})):(t.hide(),l&&(e.label=l=l.destroy())));r&&n(r.text)&&A&&A.length&&0<f.width&&0<f.height&&!A.isFlat?(r=y({align:c&&p&&"center",x:c?!p&&4:10,verticalAlign:!c&&p&&"middle",y:c?p?16:10:p?6:-4,rotation:c&&!p&&90},r),this.renderLabel(r,A,p,m)):l&&l.hide();return e},renderLabel:function(a,f,c,k){var e=this.label,l=this.axis.chart.renderer;
e||(e={align:a.textAlign||a.align,rotation:a.rotation,"class":"highcharts-plot-"+(c?"band":"line")+"-label "+(a.className||"")},e.zIndex=k,this.label=e=l.text(a.text,0,0,a.useHTML).attr(e).add(),e.css(a.style));k=f.xBounds||[f[1],f[4],c?f[6]:f[1]];f=f.yBounds||[f[2],f[5],c?f[7]:f[2]];c=F(k);l=F(f);e.align(a,!1,{x:c,y:l,width:E(k)-c,height:E(f)-l});e.show()},destroy:function(){u(this.axis.plotLinesAndBands,this);delete this.axis;h(this)}};a.extend(C.prototype,{getPlotBandPath:function(a,f){var c=this.getPlotLinePath(f,
null,null,!0),k=this.getPlotLinePath(a,null,null,!0),e=[],l=this.horiz,d=1,b;a=a<this.min&&f<this.min||a>this.max&&f>this.max;if(k&&c)for(a&&(b=k.toString()===c.toString(),d=0),a=0;a<k.length;a+=6)l&&c[a+1]===k[a+1]?(c[a+1]+=d,c[a+4]+=d):l||c[a+2]!==k[a+2]||(c[a+2]+=d,c[a+5]+=d),e.push("M",k[a+1],k[a+2],"L",k[a+4],k[a+5],c[a+4],c[a+5],c[a+1],c[a+2],"z"),e.isFlat=b;return e},addPlotBand:function(a){return this.addPlotBandOrLine(a,"plotBands")},addPlotLine:function(a){return this.addPlotBandOrLine(a,
"plotLines")},addPlotBandOrLine:function(e,f){var c=(new a.PlotLineOrBand(this,e)).render(),k=this.userOptions;c&&(f&&(k[f]=k[f]||[],k[f].push(e)),this.plotLinesAndBands.push(c));return c},removePlotBandOrLine:function(a){for(var f=this.plotLinesAndBands,c=this.options,k=this.userOptions,r=f.length;r--;)f[r].id===a&&f[r].destroy();e([c.plotLines||[],k.plotLines||[],c.plotBands||[],k.plotBands||[]],function(c){for(r=c.length;r--;)c[r].id===a&&u(c,c[r])})},removePlotBand:function(a){this.removePlotBandOrLine(a)},
removePlotLine:function(a){this.removePlotBandOrLine(a)}})})(K,V);(function(a){var C=a.doc,E=a.each,F=a.extend,n=a.format,h=a.isNumber,e=a.map,u=a.merge,y=a.pick,q=a.splat,x=a.syncTimeout,f=a.timeUnits;a.Tooltip=function(){this.init.apply(this,arguments)};a.Tooltip.prototype={init:function(a,f){this.chart=a;this.options=f;this.crosshairs=[];this.now={x:0,y:0};this.isHidden=!0;this.split=f.split&&!a.inverted;this.shared=f.shared||this.split;this.outside=f.outside&&!this.split},cleanSplit:function(a){E(this.chart.series,
function(c){var f=c&&c.tt;f&&(!f.isActive||a?c.tt=f.destroy():f.isActive=!1)})},getLabel:function(){var c=this.chart.renderer,f=this.options,e;this.label||(this.outside&&(this.container=e=a.doc.createElement("div"),e.className="highcharts-tooltip-container",a.css(e,{position:"absolute",top:"1px",pointerEvents:"none"}),a.doc.body.appendChild(e),this.renderer=c=new a.Renderer(e,0,0)),this.split?this.label=c.g("tooltip"):(this.label=c.label("",0,0,f.shape||"callout",null,null,f.useHTML,null,"tooltip").attr({padding:f.padding,
r:f.borderRadius}),this.label.attr({fill:f.backgroundColor,"stroke-width":f.borderWidth}).css(f.style).shadow(f.shadow)),this.outside&&(this.label.attr({x:this.distance,y:this.distance}),this.label.xSetter=function(a){e.style.left=a+"px"},this.label.ySetter=function(a){e.style.top=a+"px"}),this.label.attr({zIndex:8}).add());return this.label},update:function(a){this.destroy();u(!0,this.chart.options.tooltip.userOptions,a);this.init(this.chart,u(!0,this.options,a))},destroy:function(){this.label&&
(this.label=this.label.destroy());this.split&&this.tt&&(this.cleanSplit(this.chart,!0),this.tt=this.tt.destroy());this.renderer&&(this.renderer=this.renderer.destroy(),a.discardElement(this.container));a.clearTimeout(this.hideTimer);a.clearTimeout(this.tooltipTimeout)},move:function(c,f,e,l){var d=this,b=d.now,v=!1!==d.options.animation&&!d.isHidden&&(1<Math.abs(c-b.x)||1<Math.abs(f-b.y)),p=d.followPointer||1<d.len;F(b,{x:v?(2*b.x+c)/3:c,y:v?(b.y+f)/2:f,anchorX:p?void 0:v?(2*b.anchorX+e)/3:e,anchorY:p?
void 0:v?(b.anchorY+l)/2:l});d.getLabel().attr(b);v&&(a.clearTimeout(this.tooltipTimeout),this.tooltipTimeout=setTimeout(function(){d&&d.move(c,f,e,l)},32))},hide:function(c){var f=this;a.clearTimeout(this.hideTimer);c=y(c,this.options.hideDelay,500);this.isHidden||(this.hideTimer=x(function(){f.getLabel()[c?"fadeOut":"hide"]();f.isHidden=!0},c))},getAnchor:function(a,f){var c,l=this.chart,d=l.inverted,b=l.plotTop,v=l.plotLeft,p=0,k=0,t,h;a=q(a);c=a[0].tooltipPos;this.followPointer&&f&&(void 0===
f.chartX&&(f=l.pointer.normalize(f)),c=[f.chartX-l.plotLeft,f.chartY-b]);c||(E(a,function(a){t=a.series.yAxis;h=a.series.xAxis;p+=a.plotX+(!d&&h?h.left-v:0);k+=(a.plotLow?(a.plotLow+a.plotHigh)/2:a.plotY)+(!d&&t?t.top-b:0)}),p/=a.length,k/=a.length,c=[d?l.plotWidth-k:p,this.shared&&!d&&1<a.length&&f?f.chartY-b:d?l.plotHeight-p:k]);return e(c,Math.round)},getPosition:function(a,f,e){var c=this.chart,d=this.distance,b={},v=c.inverted&&e.h||0,p,k=this.outside,t=k?C.documentElement.clientWidth-2*d:c.chartWidth,
h=k?Math.max(C.body.scrollHeight,C.documentElement.scrollHeight,C.body.offsetHeight,C.documentElement.offsetHeight,C.documentElement.clientHeight):c.chartHeight,A=c.pointer.chartPosition,r=["y",h,f,(k?A.top-d:0)+e.plotY+c.plotTop,k?0:c.plotTop,k?h:c.plotTop+c.plotHeight],m=["x",t,a,(k?A.left-d:0)+e.plotX+c.plotLeft,k?0:c.plotLeft,k?t:c.plotLeft+c.plotWidth],D=!this.followPointer&&y(e.ttBelow,!c.inverted===!!e.negative),B=function(a,c,g,m,l,f){var p=g<m-d,e=m+d+g<c,B=m-d-g;m+=d;if(D&&e)b[a]=m;else if(!D&&
p)b[a]=B;else if(p)b[a]=Math.min(f-g,0>B-v?B:B-v);else if(e)b[a]=Math.max(l,m+v+g>c?m:m+v);else return!1},q=function(a,c,g,m){var l;m<d||m>c-d?l=!1:b[a]=m<g/2?1:m>c-g/2?c-g-2:m-g/2;return l},G=function(a){var b=r;r=m;m=b;p=a},g=function(){!1!==B.apply(0,r)?!1!==q.apply(0,m)||p||(G(!0),g()):p?b.x=b.y=0:(G(!0),g())};(c.inverted||1<this.len)&&G();g();return b},defaultFormatter:function(a){var c=this.points||q(this),f;f=[a.tooltipFooterHeaderFormatter(c[0])];f=f.concat(a.bodyFormatter(c));f.push(a.tooltipFooterHeaderFormatter(c[0],
!0));return f},refresh:function(c,f){var e,l=this.options,d,b=c,v,p={},k=[];e=l.formatter||this.defaultFormatter;var p=this.shared,t;l.enabled&&(a.clearTimeout(this.hideTimer),this.followPointer=q(b)[0].series.tooltipOptions.followPointer,v=this.getAnchor(b,f),f=v[0],d=v[1],!p||b.series&&b.series.noSharedTooltip?p=b.getLabelConfig():(E(b,function(a){a.setState("hover");k.push(a.getLabelConfig())}),p={x:b[0].category,y:b[0].y},p.points=k,b=b[0]),this.len=k.length,p=e.call(p,this),t=b.series,this.distance=
y(t.tooltipOptions.distance,16),!1===p?this.hide():(e=this.getLabel(),this.isHidden&&e.attr({opacity:1}).show(),this.split?this.renderSplit(p,q(c)):(l.style.width||e.css({width:this.chart.spacingBox.width}),e.attr({text:p&&p.join?p.join(""):p}),e.removeClass(/highcharts-color-[\d]+/g).addClass("highcharts-color-"+y(b.colorIndex,t.colorIndex)),e.attr({stroke:l.borderColor||b.color||t.color||"#666666"}),this.updatePosition({plotX:f,plotY:d,negative:b.negative,ttBelow:b.ttBelow,h:v[2]||0})),this.isHidden=
!1))},renderSplit:function(c,f){var e=this,l=[],d=this.chart,b=d.renderer,v=!0,p=this.options,k=0,t=this.getLabel();a.isString(c)&&(c=[!1,c]);E(c.slice(0,f.length+1),function(a,c){if(!1!==a){c=f[c-1]||{isHeader:!0,plotX:f[0].plotX};var h=c.series||e,m=h.tt,D=c.series||{},B="highcharts-color-"+y(c.colorIndex,D.colorIndex,"none");m||(h.tt=m=b.label(null,null,null,"callout",null,null,p.useHTML).addClass("highcharts-tooltip-box "+B).attr({padding:p.padding,r:p.borderRadius,fill:p.backgroundColor,stroke:p.borderColor||
c.color||D.color||"#333333","stroke-width":p.borderWidth}).add(t));m.isActive=!0;m.attr({text:a});m.css(p.style).shadow(p.shadow);a=m.getBBox();D=a.width+m.strokeWidth();c.isHeader?(k=a.height,D=Math.max(0,Math.min(c.plotX+d.plotLeft-D/2,d.chartWidth-D))):D=c.plotX+d.plotLeft-y(p.distance,16)-D;0>D&&(v=!1);a=(c.series&&c.series.yAxis&&c.series.yAxis.pos)+(c.plotY||0);a-=d.plotTop;l.push({target:c.isHeader?d.plotHeight+k:a,rank:c.isHeader?1:0,size:h.tt.getBBox().height+1,point:c,x:D,tt:m})}});this.cleanSplit();
a.distribute(l,d.plotHeight+k);E(l,function(a){var b=a.point,c=b.series;a.tt.attr({visibility:void 0===a.pos?"hidden":"inherit",x:v||b.isHeader?a.x:b.plotX+d.plotLeft+y(p.distance,16),y:a.pos+d.plotTop,anchorX:b.isHeader?b.plotX+d.plotLeft:b.plotX+c.xAxis.pos,anchorY:b.isHeader?a.pos+d.plotTop-15:b.plotY+c.yAxis.pos})})},updatePosition:function(a){var c=this.chart,f=this.getLabel(),l=(this.options.positioner||this.getPosition).call(this,f.width,f.height,a),d=a.plotX+c.plotLeft;a=a.plotY+c.plotTop;
var b;this.outside&&(b=(this.options.borderWidth||0)+2*this.distance,this.renderer.setSize(f.width+b,f.height+b,!1),d+=c.pointer.chartPosition.left-l.x,a+=c.pointer.chartPosition.top-l.y);this.move(Math.round(l.x),Math.round(l.y||0),d,a)},getDateFormat:function(a,e,h,l){var d=this.chart.time,b=d.dateFormat("%m-%d %H:%M:%S.%L",e),c,p,k={millisecond:15,second:12,minute:9,hour:6,day:3},t="millisecond";for(p in f){if(a===f.week&&+d.dateFormat("%w",e)===h&&"00:00:00.000"===b.substr(6)){p="week";break}if(f[p]>
a){p=t;break}if(k[p]&&b.substr(k[p])!=="01-01 00:00:00.000".substr(k[p]))break;"week"!==p&&(t=p)}p&&(c=l[p]);return c},getXDateFormat:function(a,f,e){f=f.dateTimeLabelFormats;var c=e&&e.closestPointRange;return(c?this.getDateFormat(c,a.x,e.options.startOfWeek,f):f.day)||f.year},tooltipFooterHeaderFormatter:function(a,f){f=f?"footer":"header";var c=a.series,l=c.tooltipOptions,d=l.xDateFormat,b=c.xAxis,e=b&&"datetime"===b.options.type&&h(a.key),p=l[f+"Format"];e&&!d&&(d=this.getXDateFormat(a,l,b));
e&&d&&E(a.point&&a.point.tooltipDateKeys||["key"],function(a){p=p.replace("{point."+a+"}","{point."+a+":"+d+"}")});return n(p,{point:a,series:c},this.chart.time)},bodyFormatter:function(a){return e(a,function(a){var c=a.series.tooltipOptions;return(c[(a.point.formatPrefix||"point")+"Formatter"]||a.point.tooltipFormatter).call(a.point,c[(a.point.formatPrefix||"point")+"Format"])})}}})(K);(function(a){var C=a.addEvent,E=a.attr,F=a.charts,n=a.color,h=a.css,e=a.defined,u=a.each,y=a.extend,q=a.find,x=
a.fireEvent,f=a.isNumber,c=a.isObject,k=a.offset,r=a.pick,l=a.splat,d=a.Tooltip;a.Pointer=function(a,d){this.init(a,d)};a.Pointer.prototype={init:function(a,c){this.options=c;this.chart=a;this.runChartClick=c.chart.events&&!!c.chart.events.click;this.pinchDown=[];this.lastValidTouch={};d&&(a.tooltip=new d(a,c.tooltip),this.followTouchMove=r(c.tooltip.followTouchMove,!0));this.setDOMEvents()},zoomOption:function(a){var b=this.chart,d=b.options.chart,c=d.zoomType||"",b=b.inverted;/touch/.test(a.type)&&
(c=r(d.pinchType,c));this.zoomX=a=/x/.test(c);this.zoomY=c=/y/.test(c);this.zoomHor=a&&!b||c&&b;this.zoomVert=c&&!b||a&&b;this.hasZoom=a||c},normalize:function(a,c){var b;b=a.touches?a.touches.length?a.touches.item(0):a.changedTouches[0]:a;c||(this.chartPosition=c=k(this.chart.container));return y(a,{chartX:Math.round(b.pageX-c.left),chartY:Math.round(b.pageY-c.top)})},getCoordinates:function(a){var b={xAxis:[],yAxis:[]};u(this.chart.axes,function(c){b[c.isXAxis?"xAxis":"yAxis"].push({axis:c,value:c.toValue(a[c.horiz?
"chartX":"chartY"])})});return b},findNearestKDPoint:function(a,d,f){var b;u(a,function(a){var l=!(a.noSharedTooltip&&d)&&0>a.options.findNearestPointBy.indexOf("y");a=a.searchPoint(f,l);if((l=c(a,!0))&&!(l=!c(b,!0)))var l=b.distX-a.distX,e=b.dist-a.dist,p=(a.series.group&&a.series.group.zIndex)-(b.series.group&&b.series.group.zIndex),l=0<(0!==l&&d?l:0!==e?e:0!==p?p:b.series.index>a.series.index?-1:1);l&&(b=a)});return b},getPointFromEvent:function(a){a=a.target;for(var b;a&&!b;)b=a.point,a=a.parentNode;
return b},getChartCoordinatesFromPoint:function(a,c){var b=a.series,d=b.xAxis,b=b.yAxis,f=r(a.clientX,a.plotX),l=a.shapeArgs;if(d&&b)return c?{chartX:d.len+d.pos-f,chartY:b.len+b.pos-a.plotY}:{chartX:f+d.pos,chartY:a.plotY+b.pos};if(l&&l.x&&l.y)return{chartX:l.x,chartY:l.y}},getHoverData:function(b,d,f,l,e,k,h){var p,m=[],v=h&&h.isBoosting;l=!(!l||!b);h=d&&!d.stickyTracking?[d]:a.grep(f,function(a){return a.visible&&!(!e&&a.directTouch)&&r(a.options.enableMouseTracking,!0)&&a.stickyTracking});d=(p=
l?b:this.findNearestKDPoint(h,e,k))&&p.series;p&&(e&&!d.noSharedTooltip?(h=a.grep(f,function(a){return a.visible&&!(!e&&a.directTouch)&&r(a.options.enableMouseTracking,!0)&&!a.noSharedTooltip}),u(h,function(a){var b=q(a.points,function(a){return a.x===p.x&&!a.isNull});c(b)&&(v&&(b=a.getPoint(b)),m.push(b))})):m.push(p));return{hoverPoint:p,hoverSeries:d,hoverPoints:m}},runPointActions:function(b,d){var c=this.chart,f=c.tooltip&&c.tooltip.options.enabled?c.tooltip:void 0,l=f?f.shared:!1,e=d||c.hoverPoint,
k=e&&e.series||c.hoverSeries,k=this.getHoverData(e,k,c.series,!!d||k&&k.directTouch&&this.isDirectTouch,l,b,{isBoosting:c.isBoosting}),h,e=k.hoverPoint;h=k.hoverPoints;d=(k=k.hoverSeries)&&k.tooltipOptions.followPointer;l=l&&k&&!k.noSharedTooltip;if(e&&(e!==c.hoverPoint||f&&f.isHidden)){u(c.hoverPoints||[],function(b){-1===a.inArray(b,h)&&b.setState()});u(h||[],function(a){a.setState("hover")});if(c.hoverSeries!==k)k.onMouseOver();c.hoverPoint&&c.hoverPoint.firePointEvent("mouseOut");if(!e.series)return;
e.firePointEvent("mouseOver");c.hoverPoints=h;c.hoverPoint=e;f&&f.refresh(l?h:e,b)}else d&&f&&!f.isHidden&&(e=f.getAnchor([{}],b),f.updatePosition({plotX:e[0],plotY:e[1]}));this.unDocMouseMove||(this.unDocMouseMove=C(c.container.ownerDocument,"mousemove",function(b){var c=F[a.hoverChartIndex];if(c)c.pointer.onDocumentMouseMove(b)}));u(c.axes,function(c){var d=r(c.crosshair.snap,!0),m=d?a.find(h,function(a){return a.series[c.coll]===c}):void 0;m||!d?c.drawCrosshair(b,m):c.hideCrosshair()})},reset:function(a,
c){var b=this.chart,d=b.hoverSeries,f=b.hoverPoint,e=b.hoverPoints,k=b.tooltip,h=k&&k.shared?e:f;a&&h&&u(l(h),function(b){b.series.isCartesian&&void 0===b.plotX&&(a=!1)});if(a)k&&h&&(k.refresh(h),f&&(f.setState(f.state,!0),u(b.axes,function(a){a.crosshair&&a.drawCrosshair(null,f)})));else{if(f)f.onMouseOut();e&&u(e,function(a){a.setState()});if(d)d.onMouseOut();k&&k.hide(c);this.unDocMouseMove&&(this.unDocMouseMove=this.unDocMouseMove());u(b.axes,function(a){a.hideCrosshair()});this.hoverX=b.hoverPoints=
b.hoverPoint=null}},scaleGroups:function(a,c){var b=this.chart,d;u(b.series,function(f){d=a||f.getPlotBox();f.xAxis&&f.xAxis.zoomEnabled&&f.group&&(f.group.attr(d),f.markerGroup&&(f.markerGroup.attr(d),f.markerGroup.clip(c?b.clipRect:null)),f.dataLabelsGroup&&f.dataLabelsGroup.attr(d))});b.clipRect.attr(c||b.clipBox)},dragStart:function(a){var b=this.chart;b.mouseIsDown=a.type;b.cancelClick=!1;b.mouseDownX=this.mouseDownX=a.chartX;b.mouseDownY=this.mouseDownY=a.chartY},drag:function(a){var b=this.chart,
c=b.options.chart,d=a.chartX,f=a.chartY,l=this.zoomHor,e=this.zoomVert,k=b.plotLeft,m=b.plotTop,h=b.plotWidth,B=b.plotHeight,r,G=this.selectionMarker,g=this.mouseDownX,w=this.mouseDownY,q=c.panKey&&a[c.panKey+"Key"];G&&G.touch||(d<k?d=k:d>k+h&&(d=k+h),f<m?f=m:f>m+B&&(f=m+B),this.hasDragged=Math.sqrt(Math.pow(g-d,2)+Math.pow(w-f,2)),10<this.hasDragged&&(r=b.isInsidePlot(g-k,w-m),b.hasCartesianSeries&&(this.zoomX||this.zoomY)&&r&&!q&&!G&&(this.selectionMarker=G=b.renderer.rect(k,m,l?1:h,e?1:B,0).attr({fill:c.selectionMarkerFill||
n("#335cad").setOpacity(.25).get(),"class":"highcharts-selection-marker",zIndex:7}).add()),G&&l&&(d-=g,G.attr({width:Math.abs(d),x:(0<d?0:d)+g})),G&&e&&(d=f-w,G.attr({height:Math.abs(d),y:(0<d?0:d)+w})),r&&!G&&c.panning&&b.pan(a,c.panning)))},drop:function(a){var b=this,c=this.chart,d=this.hasPinched;if(this.selectionMarker){var l={originalEvent:a,xAxis:[],yAxis:[]},k=this.selectionMarker,r=k.attr?k.attr("x"):k.x,q=k.attr?k.attr("y"):k.y,m=k.attr?k.attr("width"):k.width,D=k.attr?k.attr("height"):
k.height,B;if(this.hasDragged||d)u(c.axes,function(c){if(c.zoomEnabled&&e(c.min)&&(d||b[{xAxis:"zoomX",yAxis:"zoomY"}[c.coll]])){var f=c.horiz,g="touchend"===a.type?c.minPixelPadding:0,k=c.toValue((f?r:q)+g),f=c.toValue((f?r+m:q+D)-g);l[c.coll].push({axis:c,min:Math.min(k,f),max:Math.max(k,f)});B=!0}}),B&&x(c,"selection",l,function(a){c.zoom(y(a,d?{animation:!1}:null))});f(c.index)&&(this.selectionMarker=this.selectionMarker.destroy());d&&this.scaleGroups()}c&&f(c.index)&&(h(c.container,{cursor:c._cursor}),
c.cancelClick=10<this.hasDragged,c.mouseIsDown=this.hasDragged=this.hasPinched=!1,this.pinchDown=[])},onContainerMouseDown:function(a){a=this.normalize(a);2!==a.button&&(this.zoomOption(a),a.preventDefault&&a.preventDefault(),this.dragStart(a))},onDocumentMouseUp:function(b){F[a.hoverChartIndex]&&F[a.hoverChartIndex].pointer.drop(b)},onDocumentMouseMove:function(a){var b=this.chart,c=this.chartPosition;a=this.normalize(a,c);!c||this.inClass(a.target,"highcharts-tracker")||b.isInsidePlot(a.chartX-
b.plotLeft,a.chartY-b.plotTop)||this.reset()},onContainerMouseLeave:function(b){var c=F[a.hoverChartIndex];c&&(b.relatedTarget||b.toElement)&&(c.pointer.reset(),c.pointer.chartPosition=null)},onContainerMouseMove:function(b){var c=this.chart;e(a.hoverChartIndex)&&F[a.hoverChartIndex]&&F[a.hoverChartIndex].mouseIsDown||(a.hoverChartIndex=c.index);b=this.normalize(b);b.returnValue=!1;"mousedown"===c.mouseIsDown&&this.drag(b);!this.inClass(b.target,"highcharts-tracker")&&!c.isInsidePlot(b.chartX-c.plotLeft,
b.chartY-c.plotTop)||c.openMenu||this.runPointActions(b)},inClass:function(a,c){for(var b;a;){if(b=E(a,"class")){if(-1!==b.indexOf(c))return!0;if(-1!==b.indexOf("highcharts-container"))return!1}a=a.parentNode}},onTrackerMouseOut:function(a){var b=this.chart.hoverSeries;a=a.relatedTarget||a.toElement;this.isDirectTouch=!1;if(!(!b||!a||b.stickyTracking||this.inClass(a,"highcharts-tooltip")||this.inClass(a,"highcharts-series-"+b.index)&&this.inClass(a,"highcharts-tracker")))b.onMouseOut()},onContainerClick:function(a){var b=
this.chart,c=b.hoverPoint,d=b.plotLeft,f=b.plotTop;a=this.normalize(a);b.cancelClick||(c&&this.inClass(a.target,"highcharts-tracker")?(x(c.series,"click",y(a,{point:c})),b.hoverPoint&&c.firePointEvent("click",a)):(y(a,this.getCoordinates(a)),b.isInsidePlot(a.chartX-d,a.chartY-f)&&x(b,"click",a)))},setDOMEvents:function(){var b=this,c=b.chart.container,d=c.ownerDocument;c.onmousedown=function(a){b.onContainerMouseDown(a)};c.onmousemove=function(a){b.onContainerMouseMove(a)};c.onclick=function(a){b.onContainerClick(a)};
this.unbindContainerMouseLeave=C(c,"mouseleave",b.onContainerMouseLeave);a.unbindDocumentMouseUp||(a.unbindDocumentMouseUp=C(d,"mouseup",b.onDocumentMouseUp));a.hasTouch&&(c.ontouchstart=function(a){b.onContainerTouchStart(a)},c.ontouchmove=function(a){b.onContainerTouchMove(a)},a.unbindDocumentTouchEnd||(a.unbindDocumentTouchEnd=C(d,"touchend",b.onDocumentTouchEnd)))},destroy:function(){var b=this;b.unDocMouseMove&&b.unDocMouseMove();this.unbindContainerMouseLeave();a.chartCount||(a.unbindDocumentMouseUp&&
(a.unbindDocumentMouseUp=a.unbindDocumentMouseUp()),a.unbindDocumentTouchEnd&&(a.unbindDocumentTouchEnd=a.unbindDocumentTouchEnd()));clearInterval(b.tooltipTimeout);a.objectEach(b,function(a,c){b[c]=null})}}})(K);(function(a){var C=a.charts,E=a.each,F=a.extend,n=a.map,h=a.noop,e=a.pick;F(a.Pointer.prototype,{pinchTranslate:function(a,e,h,n,f,c){this.zoomHor&&this.pinchTranslateDirection(!0,a,e,h,n,f,c);this.zoomVert&&this.pinchTranslateDirection(!1,a,e,h,n,f,c)},pinchTranslateDirection:function(a,
e,h,n,f,c,k,r){var l=this.chart,d=a?"x":"y",b=a?"X":"Y",v="chart"+b,p=a?"width":"height",q=l["plot"+(a?"Left":"Top")],t,u,A=r||1,y=l.inverted,m=l.bounds[a?"h":"v"],D=1===e.length,B=e[0][v],M=h[0][v],G=!D&&e[1][v],g=!D&&h[1][v],w;h=function(){!D&&20<Math.abs(B-G)&&(A=r||Math.abs(M-g)/Math.abs(B-G));u=(q-M)/A+B;t=l["plot"+(a?"Width":"Height")]/A};h();e=u;e<m.min?(e=m.min,w=!0):e+t>m.max&&(e=m.max-t,w=!0);w?(M-=.8*(M-k[d][0]),D||(g-=.8*(g-k[d][1])),h()):k[d]=[M,g];y||(c[d]=u-q,c[p]=t);c=y?1/A:A;f[p]=
t;f[d]=e;n[y?a?"scaleY":"scaleX":"scale"+b]=A;n["translate"+b]=c*q+(M-c*B)},pinch:function(a){var u=this,q=u.chart,x=u.pinchDown,f=a.touches,c=f.length,k=u.lastValidTouch,r=u.hasZoom,l=u.selectionMarker,d={},b=1===c&&(u.inClass(a.target,"highcharts-tracker")&&q.runTrackerClick||u.runChartClick),v={};1<c&&(u.initiated=!0);r&&u.initiated&&!b&&a.preventDefault();n(f,function(a){return u.normalize(a)});"touchstart"===a.type?(E(f,function(a,b){x[b]={chartX:a.chartX,chartY:a.chartY}}),k.x=[x[0].chartX,
x[1]&&x[1].chartX],k.y=[x[0].chartY,x[1]&&x[1].chartY],E(q.axes,function(a){if(a.zoomEnabled){var b=q.bounds[a.horiz?"h":"v"],c=a.minPixelPadding,d=a.toPixels(e(a.options.min,a.dataMin)),f=a.toPixels(e(a.options.max,a.dataMax)),l=Math.max(d,f);b.min=Math.min(a.pos,Math.min(d,f)-c);b.max=Math.max(a.pos+a.len,l+c)}}),u.res=!0):u.followTouchMove&&1===c?this.runPointActions(u.normalize(a)):x.length&&(l||(u.selectionMarker=l=F({destroy:h,touch:!0},q.plotBox)),u.pinchTranslate(x,f,d,l,v,k),u.hasPinched=
r,u.scaleGroups(d,v),u.res&&(u.res=!1,this.reset(!1,0)))},touch:function(h,n){var q=this.chart,u,f;if(q.index!==a.hoverChartIndex)this.onContainerMouseLeave({relatedTarget:!0});a.hoverChartIndex=q.index;1===h.touches.length?(h=this.normalize(h),(f=q.isInsidePlot(h.chartX-q.plotLeft,h.chartY-q.plotTop))&&!q.openMenu?(n&&this.runPointActions(h),"touchmove"===h.type&&(n=this.pinchDown,u=n[0]?4<=Math.sqrt(Math.pow(n[0].chartX-h.chartX,2)+Math.pow(n[0].chartY-h.chartY,2)):!1),e(u,!0)&&this.pinch(h)):n&&
this.reset()):2===h.touches.length&&this.pinch(h)},onContainerTouchStart:function(a){this.zoomOption(a);this.touch(a,!0)},onContainerTouchMove:function(a){this.touch(a)},onDocumentTouchEnd:function(e){C[a.hoverChartIndex]&&C[a.hoverChartIndex].pointer.drop(e)}})})(K);(function(a){var C=a.addEvent,E=a.charts,F=a.css,n=a.doc,h=a.extend,e=a.noop,u=a.Pointer,y=a.removeEvent,q=a.win,x=a.wrap;if(!a.hasTouch&&(q.PointerEvent||q.MSPointerEvent)){var f={},c=!!q.PointerEvent,k=function(){var c=[];c.item=function(a){return this[a]};
a.objectEach(f,function(a){c.push({pageX:a.pageX,pageY:a.pageY,target:a.target})});return c},r=function(c,d,b,f){"touch"!==c.pointerType&&c.pointerType!==c.MSPOINTER_TYPE_TOUCH||!E[a.hoverChartIndex]||(f(c),f=E[a.hoverChartIndex].pointer,f[d]({type:b,target:c.currentTarget,preventDefault:e,touches:k()}))};h(u.prototype,{onContainerPointerDown:function(a){r(a,"onContainerTouchStart","touchstart",function(a){f[a.pointerId]={pageX:a.pageX,pageY:a.pageY,target:a.currentTarget}})},onContainerPointerMove:function(a){r(a,
"onContainerTouchMove","touchmove",function(a){f[a.pointerId]={pageX:a.pageX,pageY:a.pageY};f[a.pointerId].target||(f[a.pointerId].target=a.currentTarget)})},onDocumentPointerUp:function(a){r(a,"onDocumentTouchEnd","touchend",function(a){delete f[a.pointerId]})},batchMSEvents:function(a){a(this.chart.container,c?"pointerdown":"MSPointerDown",this.onContainerPointerDown);a(this.chart.container,c?"pointermove":"MSPointerMove",this.onContainerPointerMove);a(n,c?"pointerup":"MSPointerUp",this.onDocumentPointerUp)}});
x(u.prototype,"init",function(a,c,b){a.call(this,c,b);this.hasZoom&&F(c.container,{"-ms-touch-action":"none","touch-action":"none"})});x(u.prototype,"setDOMEvents",function(a){a.apply(this);(this.hasZoom||this.followTouchMove)&&this.batchMSEvents(C)});x(u.prototype,"destroy",function(a){this.batchMSEvents(y);a.call(this)})}})(K);(function(a){var C=a.addEvent,E=a.css,F=a.discardElement,n=a.defined,h=a.each,e=a.fireEvent,u=a.isFirefox,y=a.marginNames,q=a.merge,x=a.pick,f=a.setAnimation,c=a.stableSort,
k=a.win,r=a.wrap;a.Legend=function(a,c){this.init(a,c)};a.Legend.prototype={init:function(a,c){this.chart=a;this.setOptions(c);c.enabled&&(this.render(),C(this.chart,"endResize",function(){this.legend.positionCheckboxes()}),this.proximate?this.unchartrender=C(this.chart,"render",function(){this.legend.proximatePositions();this.legend.positionItems()}):this.unchartrender&&this.unchartrender())},setOptions:function(a){var c=x(a.padding,8);this.options=a;this.itemStyle=a.itemStyle;this.itemHiddenStyle=
q(this.itemStyle,a.itemHiddenStyle);this.itemMarginTop=a.itemMarginTop||0;this.padding=c;this.initialItemY=c-5;this.symbolWidth=x(a.symbolWidth,16);this.pages=[];this.proximate="proximate"===a.layout&&!this.chart.inverted},update:function(a,c){var b=this.chart;this.setOptions(q(!0,this.options,a));this.destroy();b.isDirtyLegend=b.isDirtyBox=!0;x(c,!0)&&b.redraw();e(this,"afterUpdate")},colorizeItem:function(a,c){a.legendGroup[c?"removeClass":"addClass"]("highcharts-legend-item-hidden");var b=this.options,
d=a.legendItem,f=a.legendLine,l=a.legendSymbol,k=this.itemHiddenStyle.color,b=c?b.itemStyle.color:k,h=c?a.color||k:k,r=a.options&&a.options.marker,q={fill:h};d&&d.css({fill:b,color:b});f&&f.attr({stroke:h});l&&(r&&l.isMarker&&(q=a.pointAttribs(),c||(q.stroke=q.fill=k)),l.attr(q));e(this,"afterColorizeItem",{item:a,visible:c})},positionItems:function(){h(this.allItems,this.positionItem,this);this.chart.isResizing||this.positionCheckboxes()},positionItem:function(a){var c=this.options,b=c.symbolPadding,
c=!c.rtl,f=a._legendItemPos,l=f[0],f=f[1],e=a.checkbox;if((a=a.legendGroup)&&a.element)a[n(a.translateY)?"animate":"attr"]({translateX:c?l:this.legendWidth-l-2*b-4,translateY:f});e&&(e.x=l,e.y=f)},destroyItem:function(a){var c=a.checkbox;h(["legendItem","legendLine","legendSymbol","legendGroup"],function(b){a[b]&&(a[b]=a[b].destroy())});c&&F(a.checkbox)},destroy:function(){function a(a){this[a]&&(this[a]=this[a].destroy())}h(this.getAllItems(),function(c){h(["legendItem","legendGroup"],a,c)});h("clipRect up down pager nav box title group".split(" "),
a,this);this.display=null},positionCheckboxes:function(){var a=this.group&&this.group.alignAttr,c,b=this.clipHeight||this.legendHeight,f=this.titleHeight;a&&(c=a.translateY,h(this.allItems,function(d){var l=d.checkbox,e;l&&(e=c+f+l.y+(this.scrollOffset||0)+3,E(l,{left:a.translateX+d.checkboxOffset+l.x-20+"px",top:e+"px",display:e>c-6&&e<c+b-6?"":"none"}))},this))},renderTitle:function(){var a=this.options,c=this.padding,b=a.title,f=0;b.text&&(this.title||(this.title=this.chart.renderer.label(b.text,
c-3,c-4,null,null,null,a.useHTML,null,"legend-title").attr({zIndex:1}).css(b.style).add(this.group)),a=this.title.getBBox(),f=a.height,this.offsetWidth=a.width,this.contentGroup.attr({translateY:f}));this.titleHeight=f},setText:function(c){var d=this.options;c.legendItem.attr({text:d.labelFormat?a.format(d.labelFormat,c,this.chart.time):d.labelFormatter.call(c)})},renderItem:function(a){var c=this.chart,b=c.renderer,f=this.options,e=this.symbolWidth,l=f.symbolPadding,k=this.itemStyle,h=this.itemHiddenStyle,
r="horizontal"===f.layout?x(f.itemDistance,20):0,n=!f.rtl,m=a.legendItem,D=!a.series,B=!D&&a.series.drawLegendSymbol?a.series:a,u=B.options,u=this.createCheckboxForItem&&u&&u.showCheckbox,r=e+l+r+(u?20:0),G=f.useHTML,g=a.options.className;m||(a.legendGroup=b.g("legend-item").addClass("highcharts-"+B.type+"-series highcharts-color-"+a.colorIndex+(g?" "+g:"")+(D?" highcharts-series-"+a.index:"")).attr({zIndex:1}).add(this.scrollGroup),a.legendItem=m=b.text("",n?e+l:-l,this.baseline||0,G).css(q(a.visible?
k:h)).attr({align:n?"left":"right",zIndex:2}).add(a.legendGroup),this.baseline||(e=k.fontSize,this.fontMetrics=b.fontMetrics(e,m),this.baseline=this.fontMetrics.f+3+this.itemMarginTop,m.attr("y",this.baseline)),this.symbolHeight=f.symbolHeight||this.fontMetrics.f,B.drawLegendSymbol(this,a),this.setItemEvents&&this.setItemEvents(a,m,G),u&&this.createCheckboxForItem(a));this.colorizeItem(a,a.visible);k.width||m.css({width:(f.itemWidth||f.width||c.spacingBox.width)-r});this.setText(a);c=m.getBBox();
a.itemWidth=a.checkboxOffset=f.itemWidth||a.legendItemWidth||c.width+r;this.maxItemWidth=Math.max(this.maxItemWidth,a.itemWidth);this.totalItemWidth+=a.itemWidth;this.itemHeight=a.itemHeight=Math.round(a.legendItemHeight||c.height||this.symbolHeight)},layoutItem:function(a){var c=this.options,b=this.padding,f="horizontal"===c.layout,e=a.itemHeight,l=c.itemMarginBottom||0,k=this.itemMarginTop,h=f?x(c.itemDistance,20):0,r=c.width,q=r||this.chart.spacingBox.width-2*b-c.x,c=c.alignColumns&&this.totalItemWidth>
q?this.maxItemWidth:a.itemWidth;f&&this.itemX-b+c>q&&(this.itemX=b,this.itemY+=k+this.lastLineHeight+l,this.lastLineHeight=0);this.lastItemY=k+this.itemY+l;this.lastLineHeight=Math.max(e,this.lastLineHeight);a._legendItemPos=[this.itemX,this.itemY];f?this.itemX+=c:(this.itemY+=k+e+l,this.lastLineHeight=e);this.offsetWidth=r||Math.max((f?this.itemX-b-(a.checkbox?0:h):c)+b,this.offsetWidth)},getAllItems:function(){var a=[];h(this.chart.series,function(c){var b=c&&c.options;c&&x(b.showInLegend,n(b.linkedTo)?
!1:void 0,!0)&&(a=a.concat(c.legendItems||("point"===b.legendType?c.data:c)))});e(this,"afterGetAllItems",{allItems:a});return a},getAlignment:function(){var a=this.options;return this.proximate?a.align.charAt(0)+"tv":a.floating?"":a.align.charAt(0)+a.verticalAlign.charAt(0)+a.layout.charAt(0)},adjustMargins:function(a,c){var b=this.chart,d=this.options,f=this.getAlignment();f&&h([/(lth|ct|rth)/,/(rtv|rm|rbv)/,/(rbh|cb|lbh)/,/(lbv|lm|ltv)/],function(e,l){e.test(f)&&!n(a[l])&&(b[y[l]]=Math.max(b[y[l]],
b.legend[(l+1)%2?"legendHeight":"legendWidth"]+[1,-1,-1,1][l]*d[l%2?"x":"y"]+x(d.margin,12)+c[l]+(0===l&&void 0!==b.options.title.margin?b.titleOffset+b.options.title.margin:0)))})},proximatePositions:function(){var c=this.chart,d=[],b="left"===this.options.align;h(this.allItems,function(f){var e,l;e=b;f.xAxis&&f.points&&(f.xAxis.options.reversed&&(e=!e),e=a.find(e?f.points:f.points.slice(0).reverse(),function(b){return a.isNumber(b.plotY)}),l=f.legendGroup.getBBox().height,d.push({target:f.visible?
e.plotY-.3*l:c.plotHeight,size:l,item:f}))},this);a.distribute(d,c.plotHeight);h(d,function(a){a.item._legendItemPos[1]=c.plotTop-c.spacing[0]+a.pos})},render:function(){var a=this.chart,d=a.renderer,b=this.group,f,e,k,r=this.box,n=this.options,A=this.padding;this.itemX=A;this.itemY=this.initialItemY;this.lastItemY=this.offsetWidth=0;b||(this.group=b=d.g("legend").attr({zIndex:7}).add(),this.contentGroup=d.g().attr({zIndex:1}).add(b),this.scrollGroup=d.g().add(this.contentGroup));this.renderTitle();
f=this.getAllItems();c(f,function(a,b){return(a.options&&a.options.legendIndex||0)-(b.options&&b.options.legendIndex||0)});n.reversed&&f.reverse();this.allItems=f;this.display=e=!!f.length;this.itemHeight=this.totalItemWidth=this.maxItemWidth=this.lastLineHeight=0;h(f,this.renderItem,this);h(f,this.layoutItem,this);f=(n.width||this.offsetWidth)+A;k=this.lastItemY+this.lastLineHeight+this.titleHeight;k=this.handleOverflow(k);k+=A;r||(this.box=r=d.rect().addClass("highcharts-legend-box").attr({r:n.borderRadius}).add(b),
r.isNew=!0);r.attr({stroke:n.borderColor,"stroke-width":n.borderWidth||0,fill:n.backgroundColor||"none"}).shadow(n.shadow);0<f&&0<k&&(r[r.isNew?"attr":"animate"](r.crisp.call({},{x:0,y:0,width:f,height:k},r.strokeWidth())),r.isNew=!1);r[e?"show":"hide"]();this.legendWidth=f;this.legendHeight=k;e&&(d=a.spacingBox,/(lth|ct|rth)/.test(this.getAlignment())&&(d=q(d,{y:d.y+a.titleOffset+a.options.title.margin})),b.align(q(n,{width:f,height:k,verticalAlign:this.proximate?"top":n.verticalAlign}),!0,d));this.proximate||
this.positionItems()},handleOverflow:function(a){var c=this,b=this.chart,f=b.renderer,e=this.options,l=e.y,k=this.padding,b=b.spacingBox.height+("top"===e.verticalAlign?-l:l)-k,l=e.maxHeight,r,A=this.clipRect,q=e.navigation,m=x(q.animation,!0),D=q.arrowSize||12,B=this.nav,n=this.pages,G,g=this.allItems,w=function(a){"number"===typeof a?A.attr({height:a}):A&&(c.clipRect=A.destroy(),c.contentGroup.clip());c.contentGroup.div&&(c.contentGroup.div.style.clip=a?"rect("+k+"px,9999px,"+(k+a)+"px,0)":"auto")};
"horizontal"!==e.layout||"middle"===e.verticalAlign||e.floating||(b/=2);l&&(b=Math.min(b,l));n.length=0;a>b&&!1!==q.enabled?(this.clipHeight=r=Math.max(b-20-this.titleHeight-k,0),this.currentPage=x(this.currentPage,1),this.fullHeight=a,h(g,function(a,b){var c=a._legendItemPos[1],d=Math.round(a.legendItem.getBBox().height),f=n.length;if(!f||c-n[f-1]>r&&(G||c)!==n[f-1])n.push(G||c),f++;a.pageIx=f-1;G&&(g[b-1].pageIx=f-1);b===g.length-1&&c+d-n[f-1]>r&&(n.push(c),a.pageIx=f);c!==G&&(G=c)}),A||(A=c.clipRect=
f.clipRect(0,k,9999,0),c.contentGroup.clip(A)),w(r),B||(this.nav=B=f.g().attr({zIndex:1}).add(this.group),this.up=f.symbol("triangle",0,0,D,D).on("click",function(){c.scroll(-1,m)}).add(B),this.pager=f.text("",15,10).addClass("highcharts-legend-navigation").css(q.style).add(B),this.down=f.symbol("triangle-down",0,0,D,D).on("click",function(){c.scroll(1,m)}).add(B)),c.scroll(0),a=b):B&&(w(),this.nav=B.destroy(),this.scrollGroup.attr({translateY:1}),this.clipHeight=0);return a},scroll:function(a,c){var b=
this.pages,d=b.length;a=this.currentPage+a;var e=this.clipHeight,l=this.options.navigation,k=this.pager,h=this.padding;a>d&&(a=d);0<a&&(void 0!==c&&f(c,this.chart),this.nav.attr({translateX:h,translateY:e+this.padding+7+this.titleHeight,visibility:"visible"}),this.up.attr({"class":1===a?"highcharts-legend-nav-inactive":"highcharts-legend-nav-active"}),k.attr({text:a+"/"+d}),this.down.attr({x:18+this.pager.getBBox().width,"class":a===d?"highcharts-legend-nav-inactive":"highcharts-legend-nav-active"}),
this.up.attr({fill:1===a?l.inactiveColor:l.activeColor}).css({cursor:1===a?"default":"pointer"}),this.down.attr({fill:a===d?l.inactiveColor:l.activeColor}).css({cursor:a===d?"default":"pointer"}),this.scrollOffset=-b[a-1]+this.initialItemY,this.scrollGroup.animate({translateY:this.scrollOffset}),this.currentPage=a,this.positionCheckboxes())}};a.LegendSymbolMixin={drawRectangle:function(a,c){var b=a.symbolHeight,d=a.options.squareSymbol;c.legendSymbol=this.chart.renderer.rect(d?(a.symbolWidth-b)/2:
0,a.baseline-b+1,d?b:a.symbolWidth,b,x(a.options.symbolRadius,b/2)).addClass("highcharts-point").attr({zIndex:3}).add(c.legendGroup)},drawLineMarker:function(a){var c=this.options,b=c.marker,f=a.symbolWidth,e=a.symbolHeight,k=e/2,l=this.chart.renderer,h=this.legendGroup;a=a.baseline-Math.round(.3*a.fontMetrics.b);var r;r={"stroke-width":c.lineWidth||0};c.dashStyle&&(r.dashstyle=c.dashStyle);this.legendLine=l.path(["M",0,a,"L",f,a]).addClass("highcharts-graph").attr(r).add(h);b&&!1!==b.enabled&&f&&
(c=Math.min(x(b.radius,k),k),0===this.symbol.indexOf("url")&&(b=q(b,{width:e,height:e}),c=0),this.legendSymbol=b=l.symbol(this.symbol,f/2-c,a-c,2*c,2*c,b).addClass("highcharts-point").add(h),b.isMarker=!0)}};(/Trident\/7\.0/.test(k.navigator.userAgent)||u)&&r(a.Legend.prototype,"positionItem",function(a,c){var b=this,d=function(){c._legendItemPos&&a.call(b,c)};d();setTimeout(d)})})(K);(function(a){var C=a.addEvent,E=a.animate,F=a.animObject,n=a.attr,h=a.doc,e=a.Axis,u=a.createElement,y=a.defaultOptions,
q=a.discardElement,x=a.charts,f=a.css,c=a.defined,k=a.each,r=a.extend,l=a.find,d=a.fireEvent,b=a.grep,v=a.isNumber,p=a.isObject,I=a.isString,t=a.Legend,L=a.marginNames,A=a.merge,H=a.objectEach,m=a.Pointer,D=a.pick,B=a.pInt,M=a.removeEvent,G=a.seriesTypes,g=a.splat,w=a.syncTimeout,P=a.win,Q=a.Chart=function(){this.getArgs.apply(this,arguments)};a.chart=function(a,b,c){return new Q(a,b,c)};r(Q.prototype,{callbacks:[],getArgs:function(){var a=[].slice.call(arguments);if(I(a[0])||a[0].nodeName)this.renderTo=
a.shift();this.init(a[0],a[1])},init:function(b,c){var g,f,m=b.series,e=b.plotOptions||{};d(this,"init",{args:arguments},function(){b.series=null;g=A(y,b);for(f in g.plotOptions)g.plotOptions[f].tooltip=e[f]&&A(e[f].tooltip)||void 0;g.tooltip.userOptions=b.chart&&b.chart.forExport&&b.tooltip.userOptions||b.tooltip;g.series=b.series=m;this.userOptions=b;var k=g.chart,l=k.events;this.margin=[];this.spacing=[];this.bounds={h:{},v:{}};this.labelCollectors=[];this.callback=c;this.isResizing=0;this.options=
g;this.axes=[];this.series=[];this.time=b.time&&a.keys(b.time).length?new a.Time(b.time):a.time;this.hasCartesianSeries=k.showAxes;var h=this;h.index=x.length;x.push(h);a.chartCount++;l&&H(l,function(a,b){C(h,b,a)});h.xAxis=[];h.yAxis=[];h.pointCount=h.colorCounter=h.symbolCounter=0;d(h,"afterInit");h.firstRender()})},initSeries:function(b){var c=this.options.chart;(c=G[b.type||c.type||c.defaultSeriesType])||a.error(17,!0);c=new c;c.init(this,b);return c},orderSeries:function(a){var b=this.series;
for(a=a||0;a<b.length;a++)b[a]&&(b[a].index=a,b[a].name=b[a].getName())},isInsidePlot:function(a,b,c){var d=c?b:a;a=c?a:b;return 0<=d&&d<=this.plotWidth&&0<=a&&a<=this.plotHeight},redraw:function(b){d(this,"beforeRedraw");var c=this.axes,g=this.series,f=this.pointer,m=this.legend,e=this.isDirtyLegend,l,h,B=this.hasCartesianSeries,D=this.isDirtyBox,w,p=this.renderer,t=p.isHidden(),G=[];this.setResponsive&&this.setResponsive(!1);a.setAnimation(b,this);t&&this.temporaryDisplay();this.layOutTitles();
for(b=g.length;b--;)if(w=g[b],w.options.stacking&&(l=!0,w.isDirty)){h=!0;break}if(h)for(b=g.length;b--;)w=g[b],w.options.stacking&&(w.isDirty=!0);k(g,function(a){a.isDirty&&"point"===a.options.legendType&&(a.updateTotals&&a.updateTotals(),e=!0);a.isDirtyData&&d(a,"updatedData")});e&&m.options.enabled&&(m.render(),this.isDirtyLegend=!1);l&&this.getStacks();B&&k(c,function(a){a.updateNames();a.setScale()});this.getMargins();B&&(k(c,function(a){a.isDirty&&(D=!0)}),k(c,function(a){var b=a.min+","+a.max;
a.extKey!==b&&(a.extKey=b,G.push(function(){d(a,"afterSetExtremes",r(a.eventArgs,a.getExtremes()));delete a.eventArgs}));(D||l)&&a.redraw()}));D&&this.drawChartBox();d(this,"predraw");k(g,function(a){(D||a.isDirty)&&a.visible&&a.redraw();a.isDirtyData=!1});f&&f.reset(!0);p.draw();d(this,"redraw");d(this,"render");t&&this.temporaryDisplay(!0);k(G,function(a){a.call()})},get:function(a){function b(b){return b.id===a||b.options&&b.options.id===a}var c,d=this.series,g;c=l(this.axes,b)||l(this.series,
b);for(g=0;!c&&g<d.length;g++)c=l(d[g].points||[],b);return c},getAxes:function(){var a=this,b=this.options,c=b.xAxis=g(b.xAxis||{}),b=b.yAxis=g(b.yAxis||{});d(this,"getAxes");k(c,function(a,b){a.index=b;a.isX=!0});k(b,function(a,b){a.index=b});c=c.concat(b);k(c,function(b){new e(a,b)});d(this,"afterGetAxes")},getSelectedPoints:function(){var a=[];k(this.series,function(c){a=a.concat(b(c.data||[],function(a){return a.selected}))});return a},getSelectedSeries:function(){return b(this.series,function(a){return a.selected})},
setTitle:function(a,b,c){var d=this,g=d.options,f;f=g.title=A({style:{color:"#333333",fontSize:g.isStock?"16px":"18px"}},g.title,a);g=g.subtitle=A({style:{color:"#666666"}},g.subtitle,b);k([["title",a,f],["subtitle",b,g]],function(a,b){var c=a[0],g=d[c],f=a[1];a=a[2];g&&f&&(d[c]=g=g.destroy());a&&!g&&(d[c]=d.renderer.text(a.text,0,0,a.useHTML).attr({align:a.align,"class":"highcharts-"+c,zIndex:a.zIndex||4}).add(),d[c].update=function(a){d.setTitle(!b&&a,b&&a)},d[c].css(a.style))});d.layOutTitles(c)},
layOutTitles:function(a){var b=0,c,d=this.renderer,g=this.spacingBox;k(["title","subtitle"],function(a){var c=this[a],f=this.options[a];a="title"===a?-3:f.verticalAlign?0:b+2;var m;c&&(m=f.style.fontSize,m=d.fontMetrics(m,c).b,c.css({width:(f.width||g.width+f.widthAdjust)+"px"}).align(r({y:a+m},f),!1,"spacingBox"),f.floating||f.verticalAlign||(b=Math.ceil(b+c.getBBox(f.useHTML).height)))},this);c=this.titleOffset!==b;this.titleOffset=b;!this.isDirtyBox&&c&&(this.isDirtyBox=this.isDirtyLegend=c,this.hasRendered&&
D(a,!0)&&this.isDirtyBox&&this.redraw())},getChartSize:function(){var b=this.options.chart,d=b.width,b=b.height,g=this.renderTo;c(d)||(this.containerWidth=a.getStyle(g,"width"));c(b)||(this.containerHeight=a.getStyle(g,"height"));this.chartWidth=Math.max(0,d||this.containerWidth||600);this.chartHeight=Math.max(0,a.relativeLength(b,this.chartWidth)||(1<this.containerHeight?this.containerHeight:400))},temporaryDisplay:function(b){var c=this.renderTo;if(b)for(;c&&c.style;)c.hcOrigStyle&&(a.css(c,c.hcOrigStyle),
delete c.hcOrigStyle),c.hcOrigDetached&&(h.body.removeChild(c),c.hcOrigDetached=!1),c=c.parentNode;else for(;c&&c.style;){h.body.contains(c)||c.parentNode||(c.hcOrigDetached=!0,h.body.appendChild(c));if("none"===a.getStyle(c,"display",!1)||c.hcOricDetached)c.hcOrigStyle={display:c.style.display,height:c.style.height,overflow:c.style.overflow},b={display:"block",overflow:"hidden"},c!==this.renderTo&&(b.height=0),a.css(c,b),c.offsetWidth||c.style.setProperty("display","block","important");c=c.parentNode;
if(c===h.body)break}},setClassName:function(a){this.container.className="highcharts-container "+(a||"")},getContainer:function(){var b,c=this.options,g=c.chart,f,m;b=this.renderTo;var e=a.uniqueKey(),k;b||(this.renderTo=b=g.renderTo);I(b)&&(this.renderTo=b=h.getElementById(b));b||a.error(13,!0);f=B(n(b,"data-highcharts-chart"));v(f)&&x[f]&&x[f].hasRendered&&x[f].destroy();n(b,"data-highcharts-chart",this.index);b.innerHTML="";g.skipClone||b.offsetWidth||this.temporaryDisplay();this.getChartSize();
f=this.chartWidth;m=this.chartHeight;k=r({position:"relative",overflow:"hidden",width:f+"px",height:m+"px",textAlign:"left",lineHeight:"normal",zIndex:0,"-webkit-tap-highlight-color":"rgba(0,0,0,0)"},g.style);this.container=b=u("div",{id:e},k,b);this._cursor=b.style.cursor;this.renderer=new (a[g.renderer]||a.Renderer)(b,f,m,null,g.forExport,c.exporting&&c.exporting.allowHTML);this.setClassName(g.className);this.renderer.setStyle(g.style);this.renderer.chartIndex=this.index;d(this,"afterGetContainer")},
getMargins:function(a){var b=this.spacing,g=this.margin,f=this.titleOffset;this.resetMargins();f&&!c(g[0])&&(this.plotTop=Math.max(this.plotTop,f+this.options.title.margin+b[0]));this.legend&&this.legend.display&&this.legend.adjustMargins(g,b);d(this,"getMargins");a||this.getAxisMargins()},getAxisMargins:function(){var a=this,b=a.axisOffset=[0,0,0,0],d=a.margin;a.hasCartesianSeries&&k(a.axes,function(a){a.visible&&a.getOffset()});k(L,function(g,f){c(d[f])||(a[g]+=b[f])});a.setChartSize()},reflow:function(b){var d=
this,g=d.options.chart,f=d.renderTo,m=c(g.width)&&c(g.height),e=g.width||a.getStyle(f,"width"),g=g.height||a.getStyle(f,"height"),f=b?b.target:P;if(!m&&!d.isPrinting&&e&&g&&(f===P||f===h)){if(e!==d.containerWidth||g!==d.containerHeight)a.clearTimeout(d.reflowTimeout),d.reflowTimeout=w(function(){d.container&&d.setSize(void 0,void 0,!1)},b?100:0);d.containerWidth=e;d.containerHeight=g}},setReflow:function(a){var b=this;!1===a||this.unbindReflow?!1===a&&this.unbindReflow&&(this.unbindReflow=this.unbindReflow()):
(this.unbindReflow=C(P,"resize",function(a){b.reflow(a)}),C(this,"destroy",this.unbindReflow))},setSize:function(b,c,g){var m=this,e=m.renderer;m.isResizing+=1;a.setAnimation(g,m);m.oldChartHeight=m.chartHeight;m.oldChartWidth=m.chartWidth;void 0!==b&&(m.options.chart.width=b);void 0!==c&&(m.options.chart.height=c);m.getChartSize();b=e.globalAnimation;(b?E:f)(m.container,{width:m.chartWidth+"px",height:m.chartHeight+"px"},b);m.setChartSize(!0);e.setSize(m.chartWidth,m.chartHeight,g);k(m.axes,function(a){a.isDirty=
!0;a.setScale()});m.isDirtyLegend=!0;m.isDirtyBox=!0;m.layOutTitles();m.getMargins();m.redraw(g);m.oldChartHeight=null;d(m,"resize");w(function(){m&&d(m,"endResize",null,function(){--m.isResizing})},F(b).duration)},setChartSize:function(a){var b=this.inverted,c=this.renderer,g=this.chartWidth,f=this.chartHeight,m=this.options.chart,e=this.spacing,l=this.clipOffset,h,B,D,r;this.plotLeft=h=Math.round(this.plotLeft);this.plotTop=B=Math.round(this.plotTop);this.plotWidth=D=Math.max(0,Math.round(g-h-this.marginRight));
this.plotHeight=r=Math.max(0,Math.round(f-B-this.marginBottom));this.plotSizeX=b?r:D;this.plotSizeY=b?D:r;this.plotBorderWidth=m.plotBorderWidth||0;this.spacingBox=c.spacingBox={x:e[3],y:e[0],width:g-e[3]-e[1],height:f-e[0]-e[2]};this.plotBox=c.plotBox={x:h,y:B,width:D,height:r};g=2*Math.floor(this.plotBorderWidth/2);b=Math.ceil(Math.max(g,l[3])/2);c=Math.ceil(Math.max(g,l[0])/2);this.clipBox={x:b,y:c,width:Math.floor(this.plotSizeX-Math.max(g,l[1])/2-b),height:Math.max(0,Math.floor(this.plotSizeY-
Math.max(g,l[2])/2-c))};a||k(this.axes,function(a){a.setAxisSize();a.setAxisTranslation()});d(this,"afterSetChartSize",{skipAxes:a})},resetMargins:function(){var a=this,b=a.options.chart;k(["margin","spacing"],function(c){var d=b[c],g=p(d)?d:[d,d,d,d];k(["Top","Right","Bottom","Left"],function(d,f){a[c][f]=D(b[c+d],g[f])})});k(L,function(b,c){a[b]=D(a.margin[c],a.spacing[c])});a.axisOffset=[0,0,0,0];a.clipOffset=[0,0,0,0]},drawChartBox:function(){var a=this.options.chart,b=this.renderer,c=this.chartWidth,
g=this.chartHeight,f=this.chartBackground,m=this.plotBackground,e=this.plotBorder,k,l=this.plotBGImage,h=a.backgroundColor,B=a.plotBackgroundColor,D=a.plotBackgroundImage,r,w=this.plotLeft,p=this.plotTop,t=this.plotWidth,G=this.plotHeight,A=this.plotBox,n=this.clipRect,q=this.clipBox,v="animate";f||(this.chartBackground=f=b.rect().addClass("highcharts-background").add(),v="attr");k=a.borderWidth||0;r=k+(a.shadow?8:0);h={fill:h||"none"};if(k||f["stroke-width"])h.stroke=a.borderColor,h["stroke-width"]=
k;f.attr(h).shadow(a.shadow);f[v]({x:r/2,y:r/2,width:c-r-k%2,height:g-r-k%2,r:a.borderRadius});v="animate";m||(v="attr",this.plotBackground=m=b.rect().addClass("highcharts-plot-background").add());m[v](A);m.attr({fill:B||"none"}).shadow(a.plotShadow);D&&(l?l.animate(A):this.plotBGImage=b.image(D,w,p,t,G).add());n?n.animate({width:q.width,height:q.height}):this.clipRect=b.clipRect(q);v="animate";e||(v="attr",this.plotBorder=e=b.rect().addClass("highcharts-plot-border").attr({zIndex:1}).add());e.attr({stroke:a.plotBorderColor,
"stroke-width":a.plotBorderWidth||0,fill:"none"});e[v](e.crisp({x:w,y:p,width:t,height:G},-e.strokeWidth()));this.isDirtyBox=!1;d(this,"afterDrawChartBox")},propFromSeries:function(){var a=this,b=a.options.chart,c,d=a.options.series,g,f;k(["inverted","angular","polar"],function(m){c=G[b.type||b.defaultSeriesType];f=b[m]||c&&c.prototype[m];for(g=d&&d.length;!f&&g--;)(c=G[d[g].type])&&c.prototype[m]&&(f=!0);a[m]=f})},linkSeries:function(){var a=this,b=a.series;k(b,function(a){a.linkedSeries.length=
0});k(b,function(b){var c=b.options.linkedTo;I(c)&&(c=":previous"===c?a.series[b.index-1]:a.get(c))&&c.linkedParent!==b&&(c.linkedSeries.push(b),b.linkedParent=c,b.visible=D(b.options.visible,c.options.visible,b.visible))});d(this,"afterLinkSeries")},renderSeries:function(){k(this.series,function(a){a.translate();a.render()})},renderLabels:function(){var a=this,b=a.options.labels;b.items&&k(b.items,function(c){var d=r(b.style,c.style),g=B(d.left)+a.plotLeft,f=B(d.top)+a.plotTop+12;delete d.left;delete d.top;
a.renderer.text(c.html,g,f).attr({zIndex:2}).css(d).add()})},render:function(){var a=this.axes,b=this.renderer,c=this.options,d,g,f;this.setTitle();this.legend=new t(this,c.legend);this.getStacks&&this.getStacks();this.getMargins(!0);this.setChartSize();c=this.plotWidth;d=this.plotHeight=Math.max(this.plotHeight-21,0);k(a,function(a){a.setScale()});this.getAxisMargins();g=1.1<c/this.plotWidth;f=1.05<d/this.plotHeight;if(g||f)k(a,function(a){(a.horiz&&g||!a.horiz&&f)&&a.setTickInterval(!0)}),this.getMargins();
this.drawChartBox();this.hasCartesianSeries&&k(a,function(a){a.visible&&a.render()});this.seriesGroup||(this.seriesGroup=b.g("series-group").attr({zIndex:3}).add());this.renderSeries();this.renderLabels();this.addCredits();this.setResponsive&&this.setResponsive();this.hasRendered=!0},addCredits:function(a){var b=this;a=A(!0,this.options.credits,a);a.enabled&&!this.credits&&(this.credits=this.renderer.text(a.text+(this.mapCredits||""),0,0).addClass("highcharts-credits").on("click",function(){a.href&&
(P.location.href=a.href)}).attr({align:a.position.align,zIndex:8}).css(a.style).add().align(a.position),this.credits.update=function(a){b.credits=b.credits.destroy();b.addCredits(a)})},destroy:function(){var b=this,c=b.axes,g=b.series,f=b.container,m,e=f&&f.parentNode;d(b,"destroy");b.renderer.forExport?a.erase(x,b):x[b.index]=void 0;a.chartCount--;b.renderTo.removeAttribute("data-highcharts-chart");M(b);for(m=c.length;m--;)c[m]=c[m].destroy();this.scroller&&this.scroller.destroy&&this.scroller.destroy();
for(m=g.length;m--;)g[m]=g[m].destroy();k("title subtitle chartBackground plotBackground plotBGImage plotBorder seriesGroup clipRect credits pointer rangeSelector legend resetZoomButton tooltip renderer".split(" "),function(a){var c=b[a];c&&c.destroy&&(b[a]=c.destroy())});f&&(f.innerHTML="",M(f),e&&q(f));H(b,function(a,c){delete b[c]})},firstRender:function(){var a=this,b=a.options;if(!a.isReadyToRender||a.isReadyToRender()){a.getContainer();a.resetMargins();a.setChartSize();a.propFromSeries();a.getAxes();
k(b.series||[],function(b){a.initSeries(b)});a.linkSeries();d(a,"beforeRender");m&&(a.pointer=new m(a,b));a.render();if(!a.renderer.imgCount&&a.onload)a.onload();a.temporaryDisplay(!0)}},onload:function(){k([this.callback].concat(this.callbacks),function(a){a&&void 0!==this.index&&a.apply(this,[this])},this);d(this,"load");d(this,"render");c(this.index)&&this.setReflow(this.options.chart.reflow);this.onload=null}})})(K);(function(a){var C=a.addEvent,E=a.Chart,F=a.each;C(E,"afterSetChartSize",function(n){var h=
this.options.chart.scrollablePlotArea;(h=h&&h.minWidth)&&!this.renderer.forExport&&(this.scrollablePixels=h=Math.max(0,h-this.chartWidth))&&(this.plotWidth+=h,this.clipBox.width+=h,n.skipAxes||F(this.axes,function(e){1===e.side?e.getPlotLinePath=function(){var h=this.right,n;this.right=h-e.chart.scrollablePixels;n=a.Axis.prototype.getPlotLinePath.apply(this,arguments);this.right=h;return n}:(e.setAxisSize(),e.setAxisTranslation())}))});C(E,"render",function(){this.scrollablePixels?(this.setUpScrolling&&
this.setUpScrolling(),this.applyFixed()):this.fixedDiv&&this.applyFixed()});E.prototype.setUpScrolling=function(){this.scrollingContainer=a.createElement("div",{className:"highcharts-scrolling"},{overflowX:"auto",WebkitOverflowScrolling:"touch"},this.renderTo);this.innerContainer=a.createElement("div",{className:"highcharts-inner-container"},null,this.scrollingContainer);this.innerContainer.appendChild(this.container);this.setUpScrolling=null};E.prototype.applyFixed=function(){var n=this.container,
h,e,u=!this.fixedDiv;u&&(this.fixedDiv=a.createElement("div",{className:"highcharts-fixed"},{position:"absolute",overflow:"hidden",pointerEvents:"none",zIndex:2},null,!0),this.renderTo.insertBefore(this.fixedDiv,this.renderTo.firstChild),this.fixedRenderer=h=new a.Renderer(this.fixedDiv,0,0),this.scrollableMask=h.path().attr({fill:a.color(this.options.chart.backgroundColor||"#fff").setOpacity(.85).get(),zIndex:-1}).addClass("highcharts-scrollable-mask").add(),a.each([this.inverted?".highcharts-xaxis":
".highcharts-yaxis",this.inverted?".highcharts-xaxis-labels":".highcharts-yaxis-labels",".highcharts-contextbutton",".highcharts-credits",".highcharts-legend",".highcharts-subtitle",".highcharts-title"],function(e){a.each(n.querySelectorAll(e),function(a){h.box.appendChild(a);a.style.pointerEvents="auto"})}));this.fixedRenderer.setSize(this.chartWidth,this.chartHeight);e=this.chartWidth+this.scrollablePixels;this.container.style.width=e+"px";this.renderer.boxWrapper.attr({width:e,height:this.chartHeight,
viewBox:[0,0,e,this.chartHeight].join(" ")});u&&(e=this.options.chart.scrollablePlotArea,e.scrollPositionX&&(this.scrollingContainer.scrollLeft=this.scrollablePixels*e.scrollPositionX));u=this.axisOffset;e=this.plotTop-u[0]-1;var u=this.plotTop+this.plotHeight+u[2],y=this.plotLeft+this.plotWidth-this.scrollablePixels;this.scrollableMask.attr({d:this.scrollablePixels?["M",0,e,"L",this.plotLeft-1,e,"L",this.plotLeft-1,u,"L",0,u,"Z","M",y,e,"L",this.chartWidth,e,"L",this.chartWidth,u,"L",y,u,"Z"]:["M",
0,0]})}})(K);(function(a){var C,E=a.each,F=a.extend,n=a.erase,h=a.fireEvent,e=a.format,u=a.isArray,y=a.isNumber,q=a.pick,x=a.removeEvent;a.Point=C=function(){};a.Point.prototype={init:function(a,c,e){this.series=a;this.color=a.color;this.applyOptions(c,e);a.options.colorByPoint?(c=a.options.colors||a.chart.options.colors,this.color=this.color||c[a.colorCounter],c=c.length,e=a.colorCounter,a.colorCounter++,a.colorCounter===c&&(a.colorCounter=0)):e=a.colorIndex;this.colorIndex=q(this.colorIndex,e);
a.chart.pointCount++;h(this,"afterInit");return this},applyOptions:function(a,c){var f=this.series,e=f.options.pointValKey||f.pointValKey;a=C.prototype.optionsToObject.call(this,a);F(this,a);this.options=this.options?F(this.options,a):a;a.group&&delete this.group;e&&(this.y=this[e]);this.isNull=q(this.isValid&&!this.isValid(),null===this.x||!y(this.y,!0));this.selected&&(this.state="select");"name"in this&&void 0===c&&f.xAxis&&f.xAxis.hasNames&&(this.x=f.xAxis.nameToX(this));void 0===this.x&&f&&(this.x=
void 0===c?f.autoIncrement(this):c);return this},setNestedProperty:function(f,c,e){e=e.split(".");a.reduce(e,function(f,e,d,b){f[e]=b.length-1===d?c:a.isObject(f[e],!0)?f[e]:{};return f[e]},f);return f},optionsToObject:function(f){var c={},e=this.series,h=e.options.keys,l=h||e.pointArrayMap||["y"],d=l.length,b=0,n=0;if(y(f)||null===f)c[l[0]]=f;else if(u(f))for(!h&&f.length>d&&(e=typeof f[0],"string"===e?c.name=f[0]:"number"===e&&(c.x=f[0]),b++);n<d;)h&&void 0===f[b]||(0<l[n].indexOf(".")?a.Point.prototype.setNestedProperty(c,
f[b],l[n]):c[l[n]]=f[b]),b++,n++;else"object"===typeof f&&(c=f,f.dataLabels&&(e._hasPointLabels=!0),f.marker&&(e._hasPointMarkers=!0));return c},getClassName:function(){return"highcharts-point"+(this.selected?" highcharts-point-select":"")+(this.negative?" highcharts-negative":"")+(this.isNull?" highcharts-null-point":"")+(void 0!==this.colorIndex?" highcharts-color-"+this.colorIndex:"")+(this.options.className?" "+this.options.className:"")+(this.zone&&this.zone.className?" "+this.zone.className.replace("highcharts-negative",
""):"")},getZone:function(){var a=this.series,c=a.zones,a=a.zoneAxis||"y",e=0,h;for(h=c[e];this[a]>=h.value;)h=c[++e];this.nonZonedColor||(this.nonZonedColor=this.color);this.color=h&&h.color&&!this.options.color?h.color:this.nonZonedColor;return h},destroy:function(){var a=this.series.chart,c=a.hoverPoints,e;a.pointCount--;c&&(this.setState(),n(c,this),c.length||(a.hoverPoints=null));if(this===a.hoverPoint)this.onMouseOut();if(this.graphic||this.dataLabel)x(this),this.destroyElements();this.legendItem&&
a.legend.destroyItem(this);for(e in this)this[e]=null},destroyElements:function(){for(var a=["graphic","dataLabel","dataLabelUpper","connector","shadowGroup"],c,e=6;e--;)c=a[e],this[c]&&(this[c]=this[c].destroy())},getLabelConfig:function(){return{x:this.category,y:this.y,color:this.color,colorIndex:this.colorIndex,key:this.name||this.category,series:this.series,point:this,percentage:this.percentage,total:this.total||this.stackTotal}},tooltipFormatter:function(a){var c=this.series,f=c.tooltipOptions,
h=q(f.valueDecimals,""),l=f.valuePrefix||"",d=f.valueSuffix||"";E(c.pointArrayMap||["y"],function(b){b="{point."+b;if(l||d)a=a.replace(RegExp(b+"}","g"),l+b+"}"+d);a=a.replace(RegExp(b+"}","g"),b+":,."+h+"f}")});return e(a,{point:this,series:this.series},c.chart.time)},firePointEvent:function(a,c,e){var f=this,l=this.series.options;(l.point.events[a]||f.options&&f.options.events&&f.options.events[a])&&this.importEvents();"click"===a&&l.allowPointSelect&&(e=function(a){f.select&&f.select(null,a.ctrlKey||
a.metaKey||a.shiftKey)});h(this,a,c,e)},visible:!0}})(K);(function(a){var C=a.addEvent,E=a.animObject,F=a.arrayMax,n=a.arrayMin,h=a.correctFloat,e=a.defaultOptions,u=a.defaultPlotOptions,y=a.defined,q=a.each,x=a.erase,f=a.extend,c=a.fireEvent,k=a.grep,r=a.isArray,l=a.isNumber,d=a.isString,b=a.merge,v=a.objectEach,p=a.pick,I=a.removeEvent,t=a.splat,L=a.SVGElement,A=a.syncTimeout,H=a.win;a.Series=a.seriesType("line",null,{lineWidth:2,allowPointSelect:!1,showCheckbox:!1,animation:{duration:1E3},events:{},
marker:{lineWidth:0,lineColor:"#ffffff",enabledThreshold:2,radius:4,states:{normal:{animation:!0},hover:{animation:{duration:50},enabled:!0,radiusPlus:2,lineWidthPlus:1},select:{fillColor:"#cccccc",lineColor:"#000000",lineWidth:2}}},point:{events:{}},dataLabels:{align:"center",formatter:function(){return null===this.y?"":a.numberFormat(this.y,-1)},style:{fontSize:"11px",fontWeight:"bold",color:"contrast",textOutline:"1px contrast"},verticalAlign:"bottom",x:0,y:0,padding:5},cropThreshold:300,pointRange:0,
softThreshold:!0,states:{normal:{animation:!0},hover:{animation:{duration:50},lineWidthPlus:1,marker:{},halo:{size:10,opacity:.25}},select:{marker:{}}},stickyTracking:!0,turboThreshold:1E3,findNearestPointBy:"x"},{isCartesian:!0,pointClass:a.Point,sorted:!0,requireSorting:!0,directTouch:!1,axisTypes:["xAxis","yAxis"],colorCounter:0,parallelArrays:["x","y"],coll:"series",init:function(a,b){var d=this,m,e=a.series,g;d.chart=a;d.options=b=d.setOptions(b);d.linkedSeries=[];d.bindAxes();f(d,{name:b.name,
state:"",visible:!1!==b.visible,selected:!0===b.selected});m=b.events;v(m,function(a,b){C(d,b,a)});if(m&&m.click||b.point&&b.point.events&&b.point.events.click||b.allowPointSelect)a.runTrackerClick=!0;d.getColor();d.getSymbol();q(d.parallelArrays,function(a){d[a+"Data"]=[]});d.setData(b.data,!1);d.isCartesian&&(a.hasCartesianSeries=!0);e.length&&(g=e[e.length-1]);d._i=p(g&&g._i,-1)+1;a.orderSeries(this.insert(e));c(this,"afterInit")},insert:function(a){var b=this.options.index,c;if(l(b)){for(c=a.length;c--;)if(b>=
p(a[c].options.index,a[c]._i)){a.splice(c+1,0,this);break}-1===c&&a.unshift(this);c+=1}else a.push(this);return p(c,a.length-1)},bindAxes:function(){var b=this,c=b.options,d=b.chart,f;q(b.axisTypes||[],function(m){q(d[m],function(a){f=a.options;if(c[m]===f.index||void 0!==c[m]&&c[m]===f.id||void 0===c[m]&&0===f.index)b.insert(a.series),b[m]=a,a.isDirty=!0});b[m]||b.optionalAxis===m||a.error(18,!0)})},updateParallelArrays:function(a,b){var c=a.series,d=arguments,f=l(b)?function(d){var g="y"===d&&c.toYData?
c.toYData(a):a[d];c[d+"Data"][b]=g}:function(a){Array.prototype[b].apply(c[a+"Data"],Array.prototype.slice.call(d,2))};q(c.parallelArrays,f)},autoIncrement:function(){var a=this.options,b=this.xIncrement,c,d=a.pointIntervalUnit,f=this.chart.time,b=p(b,a.pointStart,0);this.pointInterval=c=p(this.pointInterval,a.pointInterval,1);d&&(a=new f.Date(b),"day"===d?f.set("Date",a,f.get("Date",a)+c):"month"===d?f.set("Month",a,f.get("Month",a)+c):"year"===d&&f.set("FullYear",a,f.get("FullYear",a)+c),c=a.getTime()-
b);this.xIncrement=b+c;return b},setOptions:function(a){var d=this.chart,f=d.options,m=f.plotOptions,h=(d.userOptions||{}).plotOptions||{},g=m[this.type];this.userOptions=a;d=b(g,m.series,a);this.tooltipOptions=b(e.tooltip,e.plotOptions.series&&e.plotOptions.series.tooltip,e.plotOptions[this.type].tooltip,f.tooltip.userOptions,m.series&&m.series.tooltip,m[this.type].tooltip,a.tooltip);this.stickyTracking=p(a.stickyTracking,h[this.type]&&h[this.type].stickyTracking,h.series&&h.series.stickyTracking,
this.tooltipOptions.shared&&!this.noSharedTooltip?!0:d.stickyTracking);null===g.marker&&delete d.marker;this.zoneAxis=d.zoneAxis;a=this.zones=(d.zones||[]).slice();!d.negativeColor&&!d.negativeFillColor||d.zones||a.push({value:d[this.zoneAxis+"Threshold"]||d.threshold||0,className:"highcharts-negative",color:d.negativeColor,fillColor:d.negativeFillColor});a.length&&y(a[a.length-1].value)&&a.push({color:this.color,fillColor:this.fillColor});c(this,"afterSetOptions",{options:d});return d},getName:function(){return this.name||
"Series "+(this.index+1)},getCyclic:function(a,b,c){var d,f=this.chart,g=this.userOptions,m=a+"Index",e=a+"Counter",h=c?c.length:p(f.options.chart[a+"Count"],f[a+"Count"]);b||(d=p(g[m],g["_"+m]),y(d)||(f.series.length||(f[e]=0),g["_"+m]=d=f[e]%h,f[e]+=1),c&&(b=c[d]));void 0!==d&&(this[m]=d);this[a]=b},getColor:function(){this.options.colorByPoint?this.options.color=null:this.getCyclic("color",this.options.color||u[this.type].color,this.chart.options.colors)},getSymbol:function(){this.getCyclic("symbol",
this.options.marker.symbol,this.chart.options.symbols)},drawLegendSymbol:a.LegendSymbolMixin.drawLineMarker,updateData:function(b){var c=this.options,d=this.points,f=[],m,g,e,h=this.requireSorting;q(b,function(b){var g;g=a.defined(b)&&this.pointClass.prototype.optionsToObject.call({series:this},b).x;l(g)&&(g=a.inArray(g,this.xData,e),-1===g?f.push(b):b!==c.data[g]?(d[g].update(b,!1,null,!1),d[g].touched=!0,h&&(e=g)):d[g]&&(d[g].touched=!0),m=!0)},this);if(m)for(b=d.length;b--;)g=d[b],g.touched||g.remove(!1),
g.touched=!1;else if(b.length===d.length)q(b,function(a,b){d[b].update&&a!==c.data[b]&&d[b].update(a,!1,null,!1)});else return!1;q(f,function(a){this.addPoint(a,!1)},this);return!0},setData:function(b,c,f,e){var m=this,g=m.points,h=g&&g.length||0,k,B=m.options,D=m.chart,t=null,A=m.xAxis,n=B.turboThreshold,v=this.xData,u=this.yData,y=(k=m.pointArrayMap)&&k.length,H;b=b||[];k=b.length;c=p(c,!0);!1!==e&&k&&h&&!m.cropped&&!m.hasGroupedData&&m.visible&&(H=this.updateData(b));if(!H){m.xIncrement=null;m.colorCounter=
0;q(this.parallelArrays,function(a){m[a+"Data"].length=0});if(n&&k>n){for(f=0;null===t&&f<k;)t=b[f],f++;if(l(t))for(f=0;f<k;f++)v[f]=this.autoIncrement(),u[f]=b[f];else if(r(t))if(y)for(f=0;f<k;f++)t=b[f],v[f]=t[0],u[f]=t.slice(1,y+1);else for(f=0;f<k;f++)t=b[f],v[f]=t[0],u[f]=t[1];else a.error(12)}else for(f=0;f<k;f++)void 0!==b[f]&&(t={series:m},m.pointClass.prototype.applyOptions.apply(t,[b[f]]),m.updateParallelArrays(t,f));u&&d(u[0])&&a.error(14,!0);m.data=[];m.options.data=m.userOptions.data=
b;for(f=h;f--;)g[f]&&g[f].destroy&&g[f].destroy();A&&(A.minRange=A.userMinRange);m.isDirty=D.isDirtyBox=!0;m.isDirtyData=!!g;f=!1}"point"===B.legendType&&(this.processData(),this.generatePoints());c&&D.redraw(f)},processData:function(b){var c=this.xData,d=this.yData,f=c.length,m;m=0;var g,e,h=this.xAxis,l,k=this.options;l=k.cropThreshold;var p=this.getExtremesFromAll||k.getExtremesFromAll,r=this.isCartesian,k=h&&h.val2lin,t=h&&h.isLog,A=this.requireSorting,n,q;if(r&&!this.isDirty&&!h.isDirty&&!this.yAxis.isDirty&&
!b)return!1;h&&(b=h.getExtremes(),n=b.min,q=b.max);if(r&&this.sorted&&!p&&(!l||f>l||this.forceCrop))if(c[f-1]<n||c[0]>q)c=[],d=[];else if(c[0]<n||c[f-1]>q)m=this.cropData(this.xData,this.yData,n,q),c=m.xData,d=m.yData,m=m.start,g=!0;for(l=c.length||1;--l;)f=t?k(c[l])-k(c[l-1]):c[l]-c[l-1],0<f&&(void 0===e||f<e)?e=f:0>f&&A&&(a.error(15),A=!1);this.cropped=g;this.cropStart=m;this.processedXData=c;this.processedYData=d;this.closestPointRange=e},cropData:function(a,b,c,d,f){var g=a.length,m=0,e=g,h;f=
p(f,this.cropShoulder,1);for(h=0;h<g;h++)if(a[h]>=c){m=Math.max(0,h-f);break}for(c=h;c<g;c++)if(a[c]>d){e=c+f;break}return{xData:a.slice(m,e),yData:b.slice(m,e),start:m,end:e}},generatePoints:function(){var a=this.options,b=a.data,c=this.data,d,f=this.processedXData,g=this.processedYData,e=this.pointClass,h=f.length,l=this.cropStart||0,k,p=this.hasGroupedData,a=a.keys,r,n=[],A;c||p||(c=[],c.length=b.length,c=this.data=c);a&&p&&(this.options.keys=!1);for(A=0;A<h;A++)k=l+A,p?(r=(new e).init(this,[f[A]].concat(t(g[A]))),
r.dataGroup=this.groupMap[A]):(r=c[k])||void 0===b[k]||(c[k]=r=(new e).init(this,b[k],f[A])),r&&(r.index=k,n[A]=r);this.options.keys=a;if(c&&(h!==(d=c.length)||p))for(A=0;A<d;A++)A!==l||p||(A+=h),c[A]&&(c[A].destroyElements(),c[A].plotX=void 0);this.data=c;this.points=n},getExtremes:function(a){var b=this.yAxis,c=this.processedXData,d,f=[],g=0;d=this.xAxis.getExtremes();var m=d.min,e=d.max,h,k,p=this.requireSorting?1:0,t,A;a=a||this.stackedYData||this.processedYData||[];d=a.length;for(A=0;A<d;A++)if(k=
c[A],t=a[A],h=(l(t,!0)||r(t))&&(!b.positiveValuesOnly||t.length||0<t),k=this.getExtremesFromAll||this.options.getExtremesFromAll||this.cropped||(c[A+p]||k)>=m&&(c[A-p]||k)<=e,h&&k)if(h=t.length)for(;h--;)"number"===typeof t[h]&&(f[g++]=t[h]);else f[g++]=t;this.dataMin=n(f);this.dataMax=F(f)},translate:function(){this.processedXData||this.processData();this.generatePoints();var a=this.options,b=a.stacking,d=this.xAxis,f=d.categories,e=this.yAxis,g=this.points,k=g.length,r=!!this.modifyValue,t=a.pointPlacement,
A="between"===t||l(t),n=a.threshold,q=a.startFromThreshold?n:0,v,u,H,x,I=Number.MAX_VALUE;"between"===t&&(t=.5);l(t)&&(t*=p(a.pointRange||d.pointRange));for(a=0;a<k;a++){var L=g[a],C=L.x,E=L.y;u=L.low;var F=b&&e.stacks[(this.negStacks&&E<(q?0:n)?"-":"")+this.stackKey],K;e.positiveValuesOnly&&null!==E&&0>=E&&(L.isNull=!0);L.plotX=v=h(Math.min(Math.max(-1E5,d.translate(C,0,0,0,1,t,"flags"===this.type)),1E5));b&&this.visible&&!L.isNull&&F&&F[C]&&(x=this.getStackIndicator(x,C,this.index),K=F[C],E=K.points[x.key],
u=E[0],E=E[1],u===q&&x.key===F[C].base&&(u=p(l(n)&&n,e.min)),e.positiveValuesOnly&&0>=u&&(u=null),L.total=L.stackTotal=K.total,L.percentage=K.total&&L.y/K.total*100,L.stackY=E,K.setOffset(this.pointXOffset||0,this.barW||0));L.yBottom=y(u)?Math.min(Math.max(-1E5,e.translate(u,0,1,0,1)),1E5):null;r&&(E=this.modifyValue(E,L));L.plotY=u="number"===typeof E&&Infinity!==E?Math.min(Math.max(-1E5,e.translate(E,0,1,0,1)),1E5):void 0;L.isInside=void 0!==u&&0<=u&&u<=e.len&&0<=v&&v<=d.len;L.clientX=A?h(d.translate(C,
0,0,0,1,t)):v;L.negative=L.y<(n||0);L.category=f&&void 0!==f[L.x]?f[L.x]:L.x;L.isNull||(void 0!==H&&(I=Math.min(I,Math.abs(v-H))),H=v);L.zone=this.zones.length&&L.getZone()}this.closestPointRangePx=I;c(this,"afterTranslate")},getValidPoints:function(a,b){var c=this.chart;return k(a||this.points||[],function(a){return b&&!c.isInsidePlot(a.plotX,a.plotY,c.inverted)?!1:!a.isNull})},setClip:function(a){var b=this.chart,c=this.options,d=b.renderer,f=b.inverted,g=this.clipBox,e=g||b.clipBox,m=this.sharedClipKey||
["_sharedClip",a&&a.duration,a&&a.easing,e.height,c.xAxis,c.yAxis].join(),h=b[m],l=b[m+"m"];h||(a&&(e.width=0,f&&(e.x=b.plotSizeX),b[m+"m"]=l=d.clipRect(f?b.plotSizeX+99:-99,f?-b.plotLeft:-b.plotTop,99,f?b.chartWidth:b.chartHeight)),b[m]=h=d.clipRect(e),h.count={length:0});a&&!h.count[this.index]&&(h.count[this.index]=!0,h.count.length+=1);!1!==c.clip&&(this.group.clip(a||g?h:b.clipRect),this.markerGroup.clip(l),this.sharedClipKey=m);a||(h.count[this.index]&&(delete h.count[this.index],--h.count.length),
0===h.count.length&&m&&b[m]&&(g||(b[m]=b[m].destroy()),b[m+"m"]&&(b[m+"m"]=b[m+"m"].destroy())))},animate:function(a){var b=this.chart,c=E(this.options.animation),d;a?this.setClip(c):(d=this.sharedClipKey,(a=b[d])&&a.animate({width:b.plotSizeX,x:0},c),b[d+"m"]&&b[d+"m"].animate({width:b.plotSizeX+99,x:0},c),this.animate=null)},afterAnimate:function(){this.setClip();c(this,"afterAnimate");this.finishedAnimating=!0},drawPoints:function(){var a=this.points,b=this.chart,c,d,f,g,e=this.options.marker,
h,l,k,r=this[this.specialGroup]||this.markerGroup,t,A=p(e.enabled,this.xAxis.isRadial?!0:null,this.closestPointRangePx>=e.enabledThreshold*e.radius);if(!1!==e.enabled||this._hasPointMarkers)for(c=0;c<a.length;c++)d=a[c],g=d.graphic,h=d.marker||{},l=!!d.marker,f=A&&void 0===h.enabled||h.enabled,k=d.isInside,f&&!d.isNull?(f=p(h.symbol,this.symbol),t=this.markerAttribs(d,d.selected&&"select"),g?g[k?"show":"hide"](!0).animate(t):k&&(0<t.width||d.hasImage)&&(d.graphic=g=b.renderer.symbol(f,t.x,t.y,t.width,
t.height,l?h:e).add(r)),g&&g.attr(this.pointAttribs(d,d.selected&&"select")),g&&g.addClass(d.getClassName(),!0)):g&&(d.graphic=g.destroy())},markerAttribs:function(a,b){var c=this.options.marker,d=a.marker||{},f=d.symbol||c.symbol,g=p(d.radius,c.radius);b&&(c=c.states[b],b=d.states&&d.states[b],g=p(b&&b.radius,c&&c.radius,g+(c&&c.radiusPlus||0)));a.hasImage=f&&0===f.indexOf("url");a.hasImage&&(g=0);a={x:Math.floor(a.plotX)-g,y:a.plotY-g};g&&(a.width=a.height=2*g);return a},pointAttribs:function(a,
b){var c=this.options.marker,d=a&&a.options,f=d&&d.marker||{},g=this.color,e=d&&d.color,m=a&&a.color,d=p(f.lineWidth,c.lineWidth);a=a&&a.zone&&a.zone.color;g=e||a||m||g;a=f.fillColor||c.fillColor||g;g=f.lineColor||c.lineColor||g;b&&(c=c.states[b],b=f.states&&f.states[b]||{},d=p(b.lineWidth,c.lineWidth,d+p(b.lineWidthPlus,c.lineWidthPlus,0)),a=b.fillColor||c.fillColor||a,g=b.lineColor||c.lineColor||g);return{stroke:g,"stroke-width":d,fill:a}},destroy:function(){var b=this,d=b.chart,f=/AppleWebKit\/533/.test(H.navigator.userAgent),
e,h,g=b.data||[],l,k;c(b,"destroy");I(b);q(b.axisTypes||[],function(a){(k=b[a])&&k.series&&(x(k.series,b),k.isDirty=k.forceRedraw=!0)});b.legendItem&&b.chart.legend.destroyItem(b);for(h=g.length;h--;)(l=g[h])&&l.destroy&&l.destroy();b.points=null;a.clearTimeout(b.animationTimeout);v(b,function(a,b){a instanceof L&&!a.survive&&(e=f&&"group"===b?"hide":"destroy",a[e]())});d.hoverSeries===b&&(d.hoverSeries=null);x(d.series,b);d.orderSeries();v(b,function(a,c){delete b[c]})},getGraphPath:function(a,b,
c){var d=this,f=d.options,g=f.step,e,m=[],h=[],l;a=a||d.points;(e=a.reversed)&&a.reverse();(g={right:1,center:2}[g]||g&&3)&&e&&(g=4-g);!f.connectNulls||b||c||(a=this.getValidPoints(a));q(a,function(e,k){var p=e.plotX,r=e.plotY,t=a[k-1];(e.leftCliff||t&&t.rightCliff)&&!c&&(l=!0);e.isNull&&!y(b)&&0<k?l=!f.connectNulls:e.isNull&&!b?l=!0:(0===k||l?k=["M",e.plotX,e.plotY]:d.getPointSpline?k=d.getPointSpline(a,e,k):g?(k=1===g?["L",t.plotX,r]:2===g?["L",(t.plotX+p)/2,t.plotY,"L",(t.plotX+p)/2,r]:["L",p,
t.plotY],k.push("L",p,r)):k=["L",p,r],h.push(e.x),g&&(h.push(e.x),2===g&&h.push(e.x)),m.push.apply(m,k),l=!1)});m.xMap=h;return d.graphPath=m},drawGraph:function(){var a=this,b=this.options,c=(this.gappedPath||this.getGraphPath).call(this),d=[["graph","highcharts-graph",b.lineColor||this.color,b.dashStyle]],d=a.getZonesGraphs(d);q(d,function(d,f){var g=d[0],e=a[g];e?(e.endX=a.preventGraphAnimation?null:c.xMap,e.animate({d:c})):c.length&&(a[g]=a.chart.renderer.path(c).addClass(d[1]).attr({zIndex:1}).add(a.group),
e={stroke:d[2],"stroke-width":b.lineWidth,fill:a.fillGraph&&a.color||"none"},d[3]?e.dashstyle=d[3]:"square"!==b.linecap&&(e["stroke-linecap"]=e["stroke-linejoin"]="round"),e=a[g].attr(e).shadow(2>f&&b.shadow));e&&(e.startX=c.xMap,e.isArea=c.isArea)})},getZonesGraphs:function(a){q(this.zones,function(b,c){a.push(["zone-graph-"+c,"highcharts-graph highcharts-zone-graph-"+c+" "+(b.className||""),b.color||this.color,b.dashStyle||this.options.dashStyle])},this);return a},applyZones:function(){var a=this,
b=this.chart,c=b.renderer,d=this.zones,f,g,e=this.clips||[],h,l=this.graph,k=this.area,r=Math.max(b.chartWidth,b.chartHeight),t=this[(this.zoneAxis||"y")+"Axis"],A,n,v=b.inverted,u,H,y,x,I=!1;d.length&&(l||k)&&t&&void 0!==t.min&&(n=t.reversed,u=t.horiz,l&&!this.showLine&&l.hide(),k&&k.hide(),A=t.getExtremes(),q(d,function(d,m){f=n?u?b.plotWidth:0:u?0:t.toPixels(A.min);f=Math.min(Math.max(p(g,f),0),r);g=Math.min(Math.max(Math.round(t.toPixels(p(d.value,A.max),!0)),0),r);I&&(f=g=t.toPixels(A.max));
H=Math.abs(f-g);y=Math.min(f,g);x=Math.max(f,g);t.isXAxis?(h={x:v?x:y,y:0,width:H,height:r},u||(h.x=b.plotHeight-h.x)):(h={x:0,y:v?x:y,width:r,height:H},u&&(h.y=b.plotWidth-h.y));v&&c.isVML&&(h=t.isXAxis?{x:0,y:n?y:x,height:h.width,width:b.chartWidth}:{x:h.y-b.plotLeft-b.spacingBox.x,y:0,width:h.height,height:b.chartHeight});e[m]?e[m].animate(h):(e[m]=c.clipRect(h),l&&a["zone-graph-"+m].clip(e[m]),k&&a["zone-area-"+m].clip(e[m]));I=d.value>A.max;a.resetZones&&0===g&&(g=void 0)}),this.clips=e)},invertGroups:function(a){function b(){q(["group",
"markerGroup"],function(b){c[b]&&(d.renderer.isVML&&c[b].attr({width:c.yAxis.len,height:c.xAxis.len}),c[b].width=c.yAxis.len,c[b].height=c.xAxis.len,c[b].invert(a))})}var c=this,d=c.chart,f;c.xAxis&&(f=C(d,"resize",b),C(c,"destroy",f),b(a),c.invertGroups=b)},plotGroup:function(a,b,c,d,f){var g=this[a],e=!g;e&&(this[a]=g=this.chart.renderer.g().attr({zIndex:d||.1}).add(f));g.addClass("highcharts-"+b+" highcharts-series-"+this.index+" highcharts-"+this.type+"-series "+(y(this.colorIndex)?"highcharts-color-"+
this.colorIndex+" ":"")+(this.options.className||"")+(g.hasClass("highcharts-tracker")?" highcharts-tracker":""),!0);g.attr({visibility:c})[e?"attr":"animate"](this.getPlotBox());return g},getPlotBox:function(){var a=this.chart,b=this.xAxis,c=this.yAxis;a.inverted&&(b=c,c=this.xAxis);return{translateX:b?b.left:a.plotLeft,translateY:c?c.top:a.plotTop,scaleX:1,scaleY:1}},render:function(){var a=this,b=a.chart,d,f=a.options,e=!!a.animate&&b.renderer.isSVG&&E(f.animation).duration,g=a.visible?"inherit":
"hidden",h=f.zIndex,l=a.hasRendered,k=b.seriesGroup,r=b.inverted;d=a.plotGroup("group","series",g,h,k);a.markerGroup=a.plotGroup("markerGroup","markers",g,h,k);e&&a.animate(!0);d.inverted=a.isCartesian?r:!1;a.drawGraph&&(a.drawGraph(),a.applyZones());a.drawDataLabels&&a.drawDataLabels();a.visible&&a.drawPoints();a.drawTracker&&!1!==a.options.enableMouseTracking&&a.drawTracker();a.invertGroups(r);!1===f.clip||a.sharedClipKey||l||d.clip(b.clipRect);e&&a.animate();l||(a.animationTimeout=A(function(){a.afterAnimate()},
e));a.isDirty=!1;a.hasRendered=!0;c(a,"afterRender")},redraw:function(){var a=this.chart,b=this.isDirty||this.isDirtyData,c=this.group,d=this.xAxis,f=this.yAxis;c&&(a.inverted&&c.attr({width:a.plotWidth,height:a.plotHeight}),c.animate({translateX:p(d&&d.left,a.plotLeft),translateY:p(f&&f.top,a.plotTop)}));this.translate();this.render();b&&delete this.kdTree},kdAxisArray:["clientX","plotY"],searchPoint:function(a,b){var c=this.xAxis,d=this.yAxis,f=this.chart.inverted;return this.searchKDTree({clientX:f?
c.len-a.chartY+c.pos:a.chartX-c.pos,plotY:f?d.len-a.chartX+d.pos:a.chartY-d.pos},b)},buildKDTree:function(){function a(c,d,f){var g,e;if(e=c&&c.length)return g=b.kdAxisArray[d%f],c.sort(function(a,b){return a[g]-b[g]}),e=Math.floor(e/2),{point:c[e],left:a(c.slice(0,e),d+1,f),right:a(c.slice(e+1),d+1,f)}}this.buildingKdTree=!0;var b=this,c=-1<b.options.findNearestPointBy.indexOf("y")?2:1;delete b.kdTree;A(function(){b.kdTree=a(b.getValidPoints(null,!b.directTouch),c,c);b.buildingKdTree=!1},b.options.kdNow?
0:1)},searchKDTree:function(a,b){function c(a,b,h,m){var l=b.point,k=d.kdAxisArray[h%m],r,p,t=l;p=y(a[f])&&y(l[f])?Math.pow(a[f]-l[f],2):null;r=y(a[g])&&y(l[g])?Math.pow(a[g]-l[g],2):null;r=(p||0)+(r||0);l.dist=y(r)?Math.sqrt(r):Number.MAX_VALUE;l.distX=y(p)?Math.sqrt(p):Number.MAX_VALUE;k=a[k]-l[k];r=0>k?"left":"right";p=0>k?"right":"left";b[r]&&(r=c(a,b[r],h+1,m),t=r[e]<t[e]?r:l);b[p]&&Math.sqrt(k*k)<t[e]&&(a=c(a,b[p],h+1,m),t=a[e]<t[e]?a:t);return t}var d=this,f=this.kdAxisArray[0],g=this.kdAxisArray[1],
e=b?"distX":"dist";b=-1<d.options.findNearestPointBy.indexOf("y")?2:1;this.kdTree||this.buildingKdTree||this.buildKDTree();if(this.kdTree)return c(a,this.kdTree,b,b)}})})(K);(function(a){var C=a.Axis,E=a.Chart,F=a.correctFloat,n=a.defined,h=a.destroyObjectProperties,e=a.each,u=a.format,y=a.objectEach,q=a.pick,x=a.Series;a.StackItem=function(a,c,e,h,l){var d=a.chart.inverted;this.axis=a;this.isNegative=e;this.options=c;this.x=h;this.total=null;this.points={};this.stack=l;this.rightCliff=this.leftCliff=
0;this.alignOptions={align:c.align||(d?e?"left":"right":"center"),verticalAlign:c.verticalAlign||(d?"middle":e?"bottom":"top"),y:q(c.y,d?4:e?14:-6),x:q(c.x,d?e?-6:6:0)};this.textAlign=c.textAlign||(d?e?"right":"left":"center")};a.StackItem.prototype={destroy:function(){h(this,this.axis)},render:function(a){var c=this.axis.chart,f=this.options,e=f.format,e=e?u(e,this,c.time):f.formatter.call(this);this.label?this.label.attr({text:e,visibility:"hidden"}):this.label=c.renderer.text(e,null,null,f.useHTML).css(f.style).attr({align:this.textAlign,
rotation:f.rotation,visibility:"hidden"}).add(a)},setOffset:function(a,c){var f=this.axis,e=f.chart,h=f.translate(f.usePercentage?100:this.total,0,0,0,1),d=f.translate(0),d=Math.abs(h-d);a=e.xAxis[0].translate(this.x)+a;f=this.getStackBox(e,this,a,h,c,d,f);if(c=this.label)c.align(this.alignOptions,null,f),f=c.alignAttr,c[!1===this.options.crop||e.isInsidePlot(f.x,f.y)?"show":"hide"](!0)},getStackBox:function(a,c,e,h,l,d,b){var f=c.axis.reversed,k=a.inverted;a=b.height+b.pos-(k?a.plotLeft:a.plotTop);
c=c.isNegative&&!f||!c.isNegative&&f;return{x:k?c?h:h-d:e,y:k?a-e-l:c?a-h-d:a-h,width:k?d:l,height:k?l:d}}};E.prototype.getStacks=function(){var a=this;e(a.yAxis,function(a){a.stacks&&a.hasVisibleSeries&&(a.oldStacks=a.stacks)});e(a.series,function(c){!c.options.stacking||!0!==c.visible&&!1!==a.options.chart.ignoreHiddenSeries||(c.stackKey=c.type+q(c.options.stack,""))})};C.prototype.buildStacks=function(){var a=this.series,c=q(this.options.reversedStacks,!0),e=a.length,h;if(!this.isXAxis){this.usePercentage=
!1;for(h=e;h--;)a[c?h:e-h-1].setStackedPoints();for(h=0;h<e;h++)a[h].modifyStacks()}};C.prototype.renderStackTotals=function(){var a=this.chart,c=a.renderer,e=this.stacks,h=this.stackTotalGroup;h||(this.stackTotalGroup=h=c.g("stack-labels").attr({visibility:"visible",zIndex:6}).add());h.translate(a.plotLeft,a.plotTop);y(e,function(a){y(a,function(a){a.render(h)})})};C.prototype.resetStacks=function(){var a=this,c=a.stacks;a.isXAxis||y(c,function(c){y(c,function(f,e){f.touched<a.stacksTouched?(f.destroy(),
delete c[e]):(f.total=null,f.cumulative=null)})})};C.prototype.cleanStacks=function(){var a;this.isXAxis||(this.oldStacks&&(a=this.stacks=this.oldStacks),y(a,function(a){y(a,function(a){a.cumulative=a.total})}))};x.prototype.setStackedPoints=function(){if(this.options.stacking&&(!0===this.visible||!1===this.chart.options.chart.ignoreHiddenSeries)){var f=this.processedXData,c=this.processedYData,e=[],h=c.length,l=this.options,d=l.threshold,b=q(l.startFromThreshold&&d,0),v=l.stack,l=l.stacking,p=this.stackKey,
u="-"+p,t=this.negStacks,y=this.yAxis,A=y.stacks,H=y.oldStacks,m,D,B,x,G,g,w;y.stacksTouched+=1;for(G=0;G<h;G++)g=f[G],w=c[G],m=this.getStackIndicator(m,g,this.index),x=m.key,B=(D=t&&w<(b?0:d))?u:p,A[B]||(A[B]={}),A[B][g]||(H[B]&&H[B][g]?(A[B][g]=H[B][g],A[B][g].total=null):A[B][g]=new a.StackItem(y,y.options.stackLabels,D,g,v)),B=A[B][g],null!==w?(B.points[x]=B.points[this.index]=[q(B.cumulative,b)],n(B.cumulative)||(B.base=x),B.touched=y.stacksTouched,0<m.index&&!1===this.singleStacks&&(B.points[x][0]=
B.points[this.index+","+g+",0"][0])):B.points[x]=B.points[this.index]=null,"percent"===l?(D=D?p:u,t&&A[D]&&A[D][g]?(D=A[D][g],B.total=D.total=Math.max(D.total,B.total)+Math.abs(w)||0):B.total=F(B.total+(Math.abs(w)||0))):B.total=F(B.total+(w||0)),B.cumulative=q(B.cumulative,b)+(w||0),null!==w&&(B.points[x].push(B.cumulative),e[G]=B.cumulative);"percent"===l&&(y.usePercentage=!0);this.stackedYData=e;y.oldStacks={}}};x.prototype.modifyStacks=function(){var a=this,c=a.stackKey,h=a.yAxis.stacks,r=a.processedXData,
l,d=a.options.stacking;a[d+"Stacker"]&&e([c,"-"+c],function(b){for(var c=r.length,f,e;c--;)if(f=r[c],l=a.getStackIndicator(l,f,a.index,b),e=(f=h[b]&&h[b][f])&&f.points[l.key])a[d+"Stacker"](e,f,c)})};x.prototype.percentStacker=function(a,c,e){c=c.total?100/c.total:0;a[0]=F(a[0]*c);a[1]=F(a[1]*c);this.stackedYData[e]=a[1]};x.prototype.getStackIndicator=function(a,c,e,h){!n(a)||a.x!==c||h&&a.key!==h?a={x:c,index:0,key:h}:a.index++;a.key=[e,c,a.index].join();return a}})(K);(function(a){var C=a.addEvent,
E=a.animate,F=a.Axis,n=a.createElement,h=a.css,e=a.defined,u=a.each,y=a.erase,q=a.extend,x=a.fireEvent,f=a.inArray,c=a.isNumber,k=a.isObject,r=a.isArray,l=a.merge,d=a.objectEach,b=a.pick,v=a.Point,p=a.Series,I=a.seriesTypes,t=a.setAnimation,L=a.splat;q(a.Chart.prototype,{addSeries:function(a,c,d){var f,e=this;a&&(c=b(c,!0),x(e,"addSeries",{options:a},function(){f=e.initSeries(a);e.isDirtyLegend=!0;e.linkSeries();x(e,"afterAddSeries");c&&e.redraw(d)}));return f},addAxis:function(a,c,d,f){var e=c?"xAxis":
"yAxis",h=this.options;a=l(a,{index:this[e].length,isX:c});c=new F(this,a);h[e]=L(h[e]||{});h[e].push(a);b(d,!0)&&this.redraw(f);return c},showLoading:function(a){var b=this,c=b.options,d=b.loadingDiv,f=c.loading,e=function(){d&&h(d,{left:b.plotLeft+"px",top:b.plotTop+"px",width:b.plotWidth+"px",height:b.plotHeight+"px"})};d||(b.loadingDiv=d=n("div",{className:"highcharts-loading highcharts-loading-hidden"},null,b.container),b.loadingSpan=n("span",{className:"highcharts-loading-inner"},null,d),C(b,
"redraw",e));d.className="highcharts-loading";b.loadingSpan.innerHTML=a||c.lang.loading;h(d,q(f.style,{zIndex:10}));h(b.loadingSpan,f.labelStyle);b.loadingShown||(h(d,{opacity:0,display:""}),E(d,{opacity:f.style.opacity||.5},{duration:f.showDuration||0}));b.loadingShown=!0;e()},hideLoading:function(){var a=this.options,b=this.loadingDiv;b&&(b.className="highcharts-loading highcharts-loading-hidden",E(b,{opacity:0},{duration:a.loading.hideDuration||100,complete:function(){h(b,{display:"none"})}}));
this.loadingShown=!1},propsRequireDirtyBox:"backgroundColor borderColor borderWidth margin marginTop marginRight marginBottom marginLeft spacing spacingTop spacingRight spacingBottom spacingLeft borderRadius plotBackgroundColor plotBackgroundImage plotBorderColor plotBorderWidth plotShadow shadow".split(" "),propsRequireUpdateSeries:"chart.inverted chart.polar chart.ignoreHiddenSeries chart.type colors plotOptions time tooltip".split(" "),update:function(a,h,m,k){var t=this,p={credits:"addCredits",
title:"setTitle",subtitle:"setSubtitle"},r=a.chart,g,n,A=[];x(t,"update",{options:a});if(r){l(!0,t.options.chart,r);"className"in r&&t.setClassName(r.className);"reflow"in r&&t.setReflow(r.reflow);if("inverted"in r||"polar"in r||"type"in r)t.propFromSeries(),g=!0;"alignTicks"in r&&(g=!0);d(r,function(a,b){-1!==f("chart."+b,t.propsRequireUpdateSeries)&&(n=!0);-1!==f(b,t.propsRequireDirtyBox)&&(t.isDirtyBox=!0)});"style"in r&&t.renderer.setStyle(r.style)}a.colors&&(this.options.colors=a.colors);a.plotOptions&&
l(!0,this.options.plotOptions,a.plotOptions);d(a,function(a,b){if(t[b]&&"function"===typeof t[b].update)t[b].update(a,!1);else if("function"===typeof t[p[b]])t[p[b]](a);"chart"!==b&&-1!==f(b,t.propsRequireUpdateSeries)&&(n=!0)});u("xAxis yAxis zAxis series colorAxis pane".split(" "),function(b){var c;a[b]&&("series"===b&&(c=[],u(t[b],function(a,b){a.options.isInternal||c.push(b)})),u(L(a[b]),function(a,d){(d=e(a.id)&&t.get(a.id)||t[b][c?c[d]:d])&&d.coll===b&&(d.update(a,!1),m&&(d.touched=!0));if(!d&&
m)if("series"===b)t.addSeries(a,!1).touched=!0;else if("xAxis"===b||"yAxis"===b)t.addAxis(a,"xAxis"===b,!1).touched=!0}),m&&u(t[b],function(a){a.touched||a.options.isInternal?delete a.touched:A.push(a)}))});u(A,function(a){a.remove(!1)});g&&u(t.axes,function(a){a.update({},!1)});n&&u(t.series,function(a){a.update({},!1)});a.loading&&l(!0,t.options.loading,a.loading);g=r&&r.width;r=r&&r.height;c(g)&&g!==t.chartWidth||c(r)&&r!==t.chartHeight?t.setSize(g,r,k):b(h,!0)&&t.redraw(k);x(t,"afterUpdate",{options:a})},
setSubtitle:function(a){this.setTitle(void 0,a)}});q(v.prototype,{update:function(a,c,d,f){function e(){h.applyOptions(a);null===h.y&&g&&(h.graphic=g.destroy());k(a,!0)&&(g&&g.element&&a&&a.marker&&void 0!==a.marker.symbol&&(h.graphic=g.destroy()),a&&a.dataLabels&&h.dataLabel&&(h.dataLabel=h.dataLabel.destroy()),h.connector&&(h.connector=h.connector.destroy()));l=h.index;m.updateParallelArrays(h,l);p.data[l]=k(p.data[l],!0)||k(a,!0)?h.options:b(a,p.data[l]);m.isDirty=m.isDirtyData=!0;!m.fixedBox&&
m.hasCartesianSeries&&(t.isDirtyBox=!0);"point"===p.legendType&&(t.isDirtyLegend=!0);c&&t.redraw(d)}var h=this,m=h.series,g=h.graphic,l,t=m.chart,p=m.options;c=b(c,!0);!1===f?e():h.firePointEvent("update",{options:a},e)},remove:function(a,b){this.series.removePoint(f(this,this.series.data),a,b)}});q(p.prototype,{addPoint:function(a,c,d,f){var e=this.options,h=this.data,m=this.chart,g=this.xAxis,g=g&&g.hasNames&&g.names,l=e.data,k,t,p=this.xData,r,n;c=b(c,!0);k={series:this};this.pointClass.prototype.applyOptions.apply(k,
[a]);n=k.x;r=p.length;if(this.requireSorting&&n<p[r-1])for(t=!0;r&&p[r-1]>n;)r--;this.updateParallelArrays(k,"splice",r,0,0);this.updateParallelArrays(k,r);g&&k.name&&(g[n]=k.name);l.splice(r,0,a);t&&(this.data.splice(r,0,null),this.processData());"point"===e.legendType&&this.generatePoints();d&&(h[0]&&h[0].remove?h[0].remove(!1):(h.shift(),this.updateParallelArrays(k,"shift"),l.shift()));this.isDirtyData=this.isDirty=!0;c&&m.redraw(f)},removePoint:function(a,c,d){var f=this,e=f.data,h=e[a],m=f.points,
g=f.chart,l=function(){m&&m.length===e.length&&m.splice(a,1);e.splice(a,1);f.options.data.splice(a,1);f.updateParallelArrays(h||{series:f},"splice",a,1);h&&h.destroy();f.isDirty=!0;f.isDirtyData=!0;c&&g.redraw()};t(d,g);c=b(c,!0);h?h.firePointEvent("remove",null,l):l()},remove:function(a,c,d){function f(){e.destroy();h.isDirtyLegend=h.isDirtyBox=!0;h.linkSeries();b(a,!0)&&h.redraw(c)}var e=this,h=e.chart;!1!==d?x(e,"remove",null,f):f()},update:function(c,d){var e=this,h=e.chart,k=e.userOptions,t=
e.oldType||e.type,p=c.type||k.type||h.options.chart.type,g=I[t].prototype,r,n=["group","markerGroup","dataLabelsGroup"],v=["navigatorSeries","baseSeries"],A=e.finishedAnimating&&{animation:!1},y=["data","name","turboThreshold"],H=a.keys(c),z=0<H.length;u(H,function(a){-1===f(a,y)&&(z=!1)});if(z)c.data&&this.setData(c.data,!1),c.name&&this.setName(c.name,!1);else{v=n.concat(v);u(v,function(a){v[a]=e[a];delete e[a]});c=l(k,A,{index:e.index,pointStart:b(k.pointStart,e.xData[0])},{data:e.options.data},
c);e.remove(!1,null,!1);for(r in g)e[r]=void 0;I[p||t]?q(e,I[p||t].prototype):a.error(17,!0);u(v,function(a){e[a]=v[a]});e.init(h,c);c.zIndex!==k.zIndex&&u(n,function(a){e[a]&&e[a].attr({zIndex:c.zIndex})});e.oldType=t;h.linkSeries()}x(this,"afterUpdate");b(d,!0)&&h.redraw(!1)},setName:function(a){this.name=this.options.name=this.userOptions.name=a;this.chart.isDirtyLegend=!0}});q(F.prototype,{update:function(a,c){var f=this.chart,e=a&&a.events||{};a=l(this.userOptions,a);f.options[this.coll].indexOf&&
(f.options[this.coll][f.options[this.coll].indexOf(this.userOptions)]=a);d(f.options[this.coll].events,function(a,b){"undefined"===typeof e[b]&&(e[b]=void 0)});this.destroy(!0);this.init(f,q(a,{events:e}));f.isDirtyBox=!0;b(c,!0)&&f.redraw()},remove:function(a){for(var c=this.chart,d=this.coll,f=this.series,e=f.length;e--;)f[e]&&f[e].remove(!1);y(c.axes,this);y(c[d],this);r(c.options[d])?c.options[d].splice(this.options.index,1):delete c.options[d];u(c[d],function(a,b){a.options.index=a.userOptions.index=
b});this.destroy();c.isDirtyBox=!0;b(a,!0)&&c.redraw()},setTitle:function(a,b){this.update({title:a},b)},setCategories:function(a,b){this.update({categories:a},b)}})})(K);(function(a){var C=a.color,E=a.each,F=a.map,n=a.pick,h=a.Series,e=a.seriesType;e("area","line",{softThreshold:!1,threshold:0},{singleStacks:!1,getStackPoints:function(e){var h=[],q=[],u=this.xAxis,f=this.yAxis,c=f.stacks[this.stackKey],k={},r=this.index,l=f.series,d=l.length,b,v=n(f.options.reversedStacks,!0)?1:-1,p;e=e||this.points;
if(this.options.stacking){for(p=0;p<e.length;p++)e[p].leftNull=e[p].rightNull=null,k[e[p].x]=e[p];a.objectEach(c,function(a,b){null!==a.total&&q.push(b)});q.sort(function(a,b){return a-b});b=F(l,function(){return this.visible});E(q,function(a,e){var l=0,t,n;if(k[a]&&!k[a].isNull)h.push(k[a]),E([-1,1],function(f){var h=1===f?"rightNull":"leftNull",l=0,m=c[q[e+f]];if(m)for(p=r;0<=p&&p<d;)t=m.points[p],t||(p===r?k[a][h]=!0:b[p]&&(n=c[a].points[p])&&(l-=n[1]-n[0])),p+=v;k[a][1===f?"rightCliff":"leftCliff"]=
l});else{for(p=r;0<=p&&p<d;){if(t=c[a].points[p]){l=t[1];break}p+=v}l=f.translate(l,0,1,0,1);h.push({isNull:!0,plotX:u.translate(a,0,0,0,1),x:a,plotY:l,yBottom:l})}})}return h},getGraphPath:function(a){var e=h.prototype.getGraphPath,q=this.options,u=q.stacking,f=this.yAxis,c,k,r=[],l=[],d=this.index,b,v=f.stacks[this.stackKey],p=q.threshold,I=f.getThreshold(q.threshold),t,q=q.connectNulls||"percent"===u,L=function(c,e,h){var m=a[c];c=u&&v[m.x].points[d];var k=m[h+"Null"]||0;h=m[h+"Cliff"]||0;var t,
n,m=!0;h||k?(t=(k?c[0]:c[1])+h,n=c[0]+h,m=!!k):!u&&a[e]&&a[e].isNull&&(t=n=p);void 0!==t&&(l.push({plotX:b,plotY:null===t?I:f.getThreshold(t),isNull:m,isCliff:!0}),r.push({plotX:b,plotY:null===n?I:f.getThreshold(n),doCurve:!1}))};a=a||this.points;u&&(a=this.getStackPoints(a));for(c=0;c<a.length;c++)if(k=a[c].isNull,b=n(a[c].rectPlotX,a[c].plotX),t=n(a[c].yBottom,I),!k||q)q||L(c,c-1,"left"),k&&!u&&q||(l.push(a[c]),r.push({x:c,plotX:b,plotY:t})),q||L(c,c+1,"right");c=e.call(this,l,!0,!0);r.reversed=
!0;k=e.call(this,r,!0,!0);k.length&&(k[0]="L");k=c.concat(k);e=e.call(this,l,!1,q);k.xMap=c.xMap;this.areaPath=k;return e},drawGraph:function(){this.areaPath=[];h.prototype.drawGraph.apply(this);var a=this,e=this.areaPath,q=this.options,x=[["area","highcharts-area",this.color,q.fillColor]];E(this.zones,function(f,c){x.push(["zone-area-"+c,"highcharts-area highcharts-zone-area-"+c+" "+f.className,f.color||a.color,f.fillColor||q.fillColor])});E(x,function(f){var c=f[0],h=a[c];h?(h.endX=a.preventGraphAnimation?
null:e.xMap,h.animate({d:e})):(h=a[c]=a.chart.renderer.path(e).addClass(f[1]).attr({fill:n(f[3],C(f[2]).setOpacity(n(q.fillOpacity,.75)).get()),zIndex:0}).add(a.group),h.isArea=!0);h.startX=e.xMap;h.shiftUnit=q.step?2:1})},drawLegendSymbol:a.LegendSymbolMixin.drawRectangle})})(K);(function(a){var C=a.pick;a=a.seriesType;a("spline","line",{},{getPointSpline:function(a,F,n){var h=F.plotX,e=F.plotY,u=a[n-1];n=a[n+1];var y,q,x,f;if(u&&!u.isNull&&!1!==u.doCurve&&!F.isCliff&&n&&!n.isNull&&!1!==n.doCurve&&
!F.isCliff){a=u.plotY;x=n.plotX;n=n.plotY;var c=0;y=(1.5*h+u.plotX)/2.5;q=(1.5*e+a)/2.5;x=(1.5*h+x)/2.5;f=(1.5*e+n)/2.5;x!==y&&(c=(f-q)*(x-h)/(x-y)+e-f);q+=c;f+=c;q>a&&q>e?(q=Math.max(a,e),f=2*e-q):q<a&&q<e&&(q=Math.min(a,e),f=2*e-q);f>n&&f>e?(f=Math.max(n,e),q=2*e-f):f<n&&f<e&&(f=Math.min(n,e),q=2*e-f);F.rightContX=x;F.rightContY=f}F=["C",C(u.rightContX,u.plotX),C(u.rightContY,u.plotY),C(y,h),C(q,e),h,e];u.rightContX=u.rightContY=null;return F}})})(K);(function(a){var C=a.seriesTypes.area.prototype,
E=a.seriesType;E("areaspline","spline",a.defaultPlotOptions.area,{getStackPoints:C.getStackPoints,getGraphPath:C.getGraphPath,drawGraph:C.drawGraph,drawLegendSymbol:a.LegendSymbolMixin.drawRectangle})})(K);(function(a){var C=a.animObject,E=a.color,F=a.each,n=a.extend,h=a.isNumber,e=a.merge,u=a.pick,y=a.Series,q=a.seriesType,x=a.svg;q("column","line",{borderRadius:0,crisp:!0,groupPadding:.2,marker:null,pointPadding:.1,minPointLength:0,cropThreshold:50,pointRange:null,states:{hover:{halo:!1,brightness:.1},
select:{color:"#cccccc",borderColor:"#000000"}},dataLabels:{align:null,verticalAlign:null,y:null},softThreshold:!1,startFromThreshold:!0,stickyTracking:!1,tooltip:{distance:6},threshold:0,borderColor:"#ffffff"},{cropShoulder:0,directTouch:!0,trackerGroups:["group","dataLabelsGroup"],negStacks:!0,init:function(){y.prototype.init.apply(this,arguments);var a=this,c=a.chart;c.hasRendered&&F(c.series,function(c){c.type===a.type&&(c.isDirty=!0)})},getColumnMetrics:function(){var a=this,c=a.options,e=a.xAxis,
h=a.yAxis,l=e.options.reversedStacks,l=e.reversed&&!l||!e.reversed&&l,d,b={},n=0;!1===c.grouping?n=1:F(a.chart.series,function(c){var f=c.options,e=c.yAxis,l;c.type!==a.type||!c.visible&&a.chart.options.chart.ignoreHiddenSeries||h.len!==e.len||h.pos!==e.pos||(f.stacking?(d=c.stackKey,void 0===b[d]&&(b[d]=n++),l=b[d]):!1!==f.grouping&&(l=n++),c.columnIndex=l)});var p=Math.min(Math.abs(e.transA)*(e.ordinalSlope||c.pointRange||e.closestPointRange||e.tickInterval||1),e.len),q=p*c.groupPadding,t=(p-2*
q)/(n||1),c=Math.min(c.maxPointWidth||e.len,u(c.pointWidth,t*(1-2*c.pointPadding)));a.columnMetrics={width:c,offset:(t-c)/2+(q+((a.columnIndex||0)+(l?1:0))*t-p/2)*(l?-1:1)};return a.columnMetrics},crispCol:function(a,c,e,h){var f=this.chart,d=this.borderWidth,b=-(d%2?.5:0),d=d%2?.5:1;f.inverted&&f.renderer.isVML&&(d+=1);this.options.crisp&&(e=Math.round(a+e)+b,a=Math.round(a)+b,e-=a);h=Math.round(c+h)+d;b=.5>=Math.abs(c)&&.5<h;c=Math.round(c)+d;h-=c;b&&h&&(--c,h+=1);return{x:a,y:c,width:e,height:h}},
translate:function(){var a=this,c=a.chart,e=a.options,h=a.dense=2>a.closestPointRange*a.xAxis.transA,h=a.borderWidth=u(e.borderWidth,h?0:1),l=a.yAxis,d=e.threshold,b=a.translatedThreshold=l.getThreshold(d),n=u(e.minPointLength,5),p=a.getColumnMetrics(),q=p.width,t=a.barW=Math.max(q,1+2*h),x=a.pointXOffset=p.offset;c.inverted&&(b-=.5);e.pointPadding&&(t=Math.ceil(t));y.prototype.translate.apply(a);F(a.points,function(f){var e=u(f.yBottom,b),h=999+Math.abs(e),h=Math.min(Math.max(-h,f.plotY),l.len+h),
k=f.plotX+x,p=t,r=Math.min(h,e),v,g=Math.max(h,e)-r;n&&Math.abs(g)<n&&(g=n,v=!l.reversed&&!f.negative||l.reversed&&f.negative,f.y===d&&a.dataMax<=d&&l.min<d&&(v=!v),r=Math.abs(r-b)>n?e-n:b-(v?n:0));f.barX=k;f.pointWidth=q;f.tooltipPos=c.inverted?[l.len+l.pos-c.plotLeft-h,a.xAxis.len-k-p/2,g]:[k+p/2,h+l.pos-c.plotTop,g];f.shapeType="rect";f.shapeArgs=a.crispCol.apply(a,f.isNull?[k,b,p,0]:[k,r,p,g])})},getSymbol:a.noop,drawLegendSymbol:a.LegendSymbolMixin.drawRectangle,drawGraph:function(){this.group[this.dense?
"addClass":"removeClass"]("highcharts-dense-data")},pointAttribs:function(a,c){var f=this.options,h,l=this.pointAttrToOptions||{};h=l.stroke||"borderColor";var d=l["stroke-width"]||"borderWidth",b=a&&a.color||this.color,n=a&&a[h]||f[h]||this.color||b,p=a&&a[d]||f[d]||this[d]||0,l=f.dashStyle;a&&this.zones.length&&(b=a.getZone(),b=a.options.color||b&&b.color||this.color);c&&(a=e(f.states[c],a.options.states&&a.options.states[c]||{}),c=a.brightness,b=a.color||void 0!==c&&E(b).brighten(a.brightness).get()||
b,n=a[h]||n,p=a[d]||p,l=a.dashStyle||l);h={fill:b,stroke:n,"stroke-width":p};l&&(h.dashstyle=l);return h},drawPoints:function(){var a=this,c=this.chart,k=a.options,r=c.renderer,l=k.animationLimit||250,d;F(a.points,function(b){var f=b.graphic,p=f&&c.pointCount<l?"animate":"attr";if(h(b.plotY)&&null!==b.y){d=b.shapeArgs;if(f)f[p](e(d));else b.graphic=f=r[b.shapeType](d).add(b.group||a.group);k.borderRadius&&f.attr({r:k.borderRadius});f[p](a.pointAttribs(b,b.selected&&"select")).shadow(k.shadow,null,
k.stacking&&!k.borderRadius);f.addClass(b.getClassName(),!0)}else f&&(b.graphic=f.destroy())})},animate:function(a){var c=this,f=this.yAxis,e=c.options,h=this.chart.inverted,d={},b=h?"translateX":"translateY",q;x&&(a?(d.scaleY=.001,a=Math.min(f.pos+f.len,Math.max(f.pos,f.toPixels(e.threshold))),h?d.translateX=a-f.len:d.translateY=a,c.group.attr(d)):(q=c.group.attr(b),c.group.animate({scaleY:1},n(C(c.options.animation),{step:function(a,e){d[b]=q+e.pos*(f.pos-q);c.group.attr(d)}})),c.animate=null))},
remove:function(){var a=this,c=a.chart;c.hasRendered&&F(c.series,function(c){c.type===a.type&&(c.isDirty=!0)});y.prototype.remove.apply(a,arguments)}})})(K);(function(a){a=a.seriesType;a("bar","column",null,{inverted:!0})})(K);(function(a){var C=a.Series;a=a.seriesType;a("scatter","line",{lineWidth:0,findNearestPointBy:"xy",marker:{enabled:!0},tooltip:{headerFormat:'\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e \x3cspan style\x3d"font-size: 0.85em"\x3e {series.name}\x3c/span\x3e\x3cbr/\x3e',
pointFormat:"x: \x3cb\x3e{point.x}\x3c/b\x3e\x3cbr/\x3ey: \x3cb\x3e{point.y}\x3c/b\x3e\x3cbr/\x3e"}},{sorted:!1,requireSorting:!1,noSharedTooltip:!0,trackerGroups:["group","markerGroup","dataLabelsGroup"],takeOrdinalPosition:!1,drawGraph:function(){this.options.lineWidth&&C.prototype.drawGraph.call(this)}})})(K);(function(a){var C=a.deg2rad,E=a.isNumber,F=a.pick,n=a.relativeLength;a.CenteredSeriesMixin={getCenter:function(){var a=this.options,e=this.chart,u=2*(a.slicedOffset||0),y=e.plotWidth-2*u,
e=e.plotHeight-2*u,q=a.center,q=[F(q[0],"50%"),F(q[1],"50%"),a.size||"100%",a.innerSize||0],x=Math.min(y,e),f,c;for(f=0;4>f;++f)c=q[f],a=2>f||2===f&&/%$/.test(c),q[f]=n(c,[y,e,x,q[2]][f])+(a?u:0);q[3]>q[2]&&(q[3]=q[2]);return q},getStartAndEndRadians:function(a,e){a=E(a)?a:0;e=E(e)&&e>a&&360>e-a?e:a+360;return{start:C*(a+-90),end:C*(e+-90)}}}})(K);(function(a){var C=a.addEvent,E=a.CenteredSeriesMixin,F=a.defined,n=a.each,h=a.extend,e=E.getStartAndEndRadians,u=a.inArray,y=a.noop,q=a.pick,x=a.Point,
f=a.Series,c=a.seriesType,k=a.setAnimation;c("pie","line",{center:[null,null],clip:!1,colorByPoint:!0,dataLabels:{allowOverlap:!0,distance:30,enabled:!0,formatter:function(){return this.point.isNull?void 0:this.point.name},x:0},ignoreHiddenPoint:!0,legendType:"point",marker:null,size:null,showInLegend:!1,slicedOffset:10,stickyTracking:!1,tooltip:{followPointer:!0},borderColor:"#ffffff",borderWidth:1,states:{hover:{brightness:.1}}},{isCartesian:!1,requireSorting:!1,directTouch:!0,noSharedTooltip:!0,
trackerGroups:["group","dataLabelsGroup"],axisTypes:[],pointAttribs:a.seriesTypes.column.prototype.pointAttribs,animate:function(a){var c=this,d=c.points,b=c.startAngleRad;a||(n(d,function(a){var d=a.graphic,f=a.shapeArgs;d&&(d.attr({r:a.startR||c.center[3]/2,start:b,end:b}),d.animate({r:f.r,start:f.start,end:f.end},c.options.animation))}),c.animate=null)},updateTotals:function(){var a,c=0,d=this.points,b=d.length,f,e=this.options.ignoreHiddenPoint;for(a=0;a<b;a++)f=d[a],c+=e&&!f.visible?0:f.isNull?
0:f.y;this.total=c;for(a=0;a<b;a++)f=d[a],f.percentage=0<c&&(f.visible||!e)?f.y/c*100:0,f.total=c},generatePoints:function(){f.prototype.generatePoints.call(this);this.updateTotals()},translate:function(a){this.generatePoints();var c=0,d=this.options,b=d.slicedOffset,f=b+(d.borderWidth||0),h,k,t,n=e(d.startAngle,d.endAngle),r=this.startAngleRad=n.start,n=(this.endAngleRad=n.end)-r,u=this.points,m,x=d.dataLabels.distance,d=d.ignoreHiddenPoint,y,C=u.length,G;a||(this.center=a=this.getCenter());this.getX=
function(b,c,d){t=Math.asin(Math.min((b-a[1])/(a[2]/2+d.labelDistance),1));return a[0]+(c?-1:1)*Math.cos(t)*(a[2]/2+d.labelDistance)};for(y=0;y<C;y++){G=u[y];G.labelDistance=q(G.options.dataLabels&&G.options.dataLabels.distance,x);this.maxLabelDistance=Math.max(this.maxLabelDistance||0,G.labelDistance);h=r+c*n;if(!d||G.visible)c+=G.percentage/100;k=r+c*n;G.shapeType="arc";G.shapeArgs={x:a[0],y:a[1],r:a[2]/2,innerR:a[3]/2,start:Math.round(1E3*h)/1E3,end:Math.round(1E3*k)/1E3};t=(k+h)/2;t>1.5*Math.PI?
t-=2*Math.PI:t<-Math.PI/2&&(t+=2*Math.PI);G.slicedTranslation={translateX:Math.round(Math.cos(t)*b),translateY:Math.round(Math.sin(t)*b)};k=Math.cos(t)*a[2]/2;m=Math.sin(t)*a[2]/2;G.tooltipPos=[a[0]+.7*k,a[1]+.7*m];G.half=t<-Math.PI/2||t>Math.PI/2?1:0;G.angle=t;h=Math.min(f,G.labelDistance/5);G.labelPos=[a[0]+k+Math.cos(t)*G.labelDistance,a[1]+m+Math.sin(t)*G.labelDistance,a[0]+k+Math.cos(t)*h,a[1]+m+Math.sin(t)*h,a[0]+k,a[1]+m,0>G.labelDistance?"center":G.half?"right":"left",t]}},drawGraph:null,
drawPoints:function(){var a=this,c=a.chart.renderer,d,b,f,e,k=a.options.shadow;k&&!a.shadowGroup&&(a.shadowGroup=c.g("shadow").add(a.group));n(a.points,function(l){b=l.graphic;if(l.isNull)b&&(l.graphic=b.destroy());else{e=l.shapeArgs;d=l.getTranslate();var t=l.shadowGroup;k&&!t&&(t=l.shadowGroup=c.g("shadow").add(a.shadowGroup));t&&t.attr(d);f=a.pointAttribs(l,l.selected&&"select");b?b.setRadialReference(a.center).attr(f).animate(h(e,d)):(l.graphic=b=c[l.shapeType](e).setRadialReference(a.center).attr(d).add(a.group),
b.attr(f).attr({"stroke-linejoin":"round"}).shadow(k,t));b.attr({visibility:l.visible?"inherit":"hidden"});b.addClass(l.getClassName())}})},searchPoint:y,sortByAngle:function(a,c){a.sort(function(a,b){return void 0!==a.angle&&(b.angle-a.angle)*c})},drawLegendSymbol:a.LegendSymbolMixin.drawRectangle,getCenter:E.getCenter,getSymbol:y},{init:function(){x.prototype.init.apply(this,arguments);var a=this,c;a.name=q(a.name,"Slice");c=function(c){a.slice("select"===c.type)};C(a,"select",c);C(a,"unselect",
c);return a},isValid:function(){return a.isNumber(this.y,!0)&&0<=this.y},setVisible:function(a,c){var d=this,b=d.series,f=b.chart,e=b.options.ignoreHiddenPoint;c=q(c,e);a!==d.visible&&(d.visible=d.options.visible=a=void 0===a?!d.visible:a,b.options.data[u(d,b.data)]=d.options,n(["graphic","dataLabel","connector","shadowGroup"],function(b){if(d[b])d[b][a?"show":"hide"](!0)}),d.legendItem&&f.legend.colorizeItem(d,a),a||"hover"!==d.state||d.setState(""),e&&(b.isDirty=!0),c&&f.redraw())},slice:function(a,
c,d){var b=this.series;k(d,b.chart);q(c,!0);this.sliced=this.options.sliced=F(a)?a:!this.sliced;b.options.data[u(this,b.data)]=this.options;this.graphic.animate(this.getTranslate());this.shadowGroup&&this.shadowGroup.animate(this.getTranslate())},getTranslate:function(){return this.sliced?this.slicedTranslation:{translateX:0,translateY:0}},haloPath:function(a){var c=this.shapeArgs;return this.sliced||!this.visible?[]:this.series.chart.renderer.symbols.arc(c.x,c.y,c.r+a,c.r+a,{innerR:this.shapeArgs.r-
1,start:c.start,end:c.end})}})})(K);(function(a){var C=a.addEvent,E=a.arrayMax,F=a.defined,n=a.each,h=a.extend,e=a.format,u=a.map,y=a.merge,q=a.noop,x=a.pick,f=a.relativeLength,c=a.Series,k=a.seriesTypes,r=a.some,l=a.stableSort;a.distribute=function(c,b,f){function d(a,b){return a.target-b.target}var e,h=!0,k=c,q=[],v;v=0;var m=k.reducedLen||b;for(e=c.length;e--;)v+=c[e].size;if(v>m){l(c,function(a,b){return(b.rank||0)-(a.rank||0)});for(v=e=0;v<=m;)v+=c[e].size,e++;q=c.splice(e-1,c.length)}l(c,d);
for(c=u(c,function(a){return{size:a.size,targets:[a.target],align:x(a.align,.5)}});h;){for(e=c.length;e--;)h=c[e],v=(Math.min.apply(0,h.targets)+Math.max.apply(0,h.targets))/2,h.pos=Math.min(Math.max(0,v-h.size*h.align),b-h.size);e=c.length;for(h=!1;e--;)0<e&&c[e-1].pos+c[e-1].size>c[e].pos&&(c[e-1].size+=c[e].size,c[e-1].targets=c[e-1].targets.concat(c[e].targets),c[e-1].align=.5,c[e-1].pos+c[e-1].size>b&&(c[e-1].pos=b-c[e-1].size),c.splice(e,1),h=!0)}k.push.apply(k,q);e=0;r(c,function(c){var d=
0;if(r(c.targets,function(){k[e].pos=c.pos+d;if(Math.abs(k[e].pos-k[e].target)>f)return n(k.slice(0,e+1),function(a){delete a.pos}),k.reducedLen=(k.reducedLen||b)-.1*b,k.reducedLen>.1*b&&a.distribute(k,b,f),!0;d+=k[e].size;e++}))return!0});l(k,d)};c.prototype.drawDataLabels=function(){function c(a,b){var c=b.filter;return c?(b=c.operator,a=a[c.property],c=c.value,"\x3e"===b&&a>c||"\x3c"===b&&a<c||"\x3e\x3d"===b&&a>=c||"\x3c\x3d"===b&&a<=c||"\x3d\x3d"===b&&a==c||"\x3d\x3d\x3d"===b&&a===c?!0:!1):!0}
var b=this,f=b.chart,h=b.options,l=h.dataLabels,k=b.points,r,q,u=b.hasRendered||0,m,D,B=x(l.defer,!!h.animation),E=f.renderer;if(l.enabled||b._hasPointLabels)b.dlProcessOptions&&b.dlProcessOptions(l),D=b.plotGroup("dataLabelsGroup","data-labels",B&&!u?"hidden":"visible",l.zIndex||6),B&&(D.attr({opacity:+u}),u||C(b,"afterAnimate",function(){b.visible&&D.show(!0);D[h.animation?"animate":"attr"]({opacity:1},{duration:200})})),q=l,n(k,function(d){var g,k=d.dataLabel,t,p,n=d.connector,u=!k,v;r=d.dlOptions||
d.options&&d.options.dataLabels;(g=x(r&&r.enabled,q.enabled)&&!d.isNull)&&(g=!0===c(d,r||l));g&&(l=y(q,r),t=d.getLabelConfig(),v=l[d.formatPrefix+"Format"]||l.format,m=F(v)?e(v,t,f.time):(l[d.formatPrefix+"Formatter"]||l.formatter).call(t,l),v=l.style,t=l.rotation,v.color=x(l.color,v.color,b.color,"#000000"),"contrast"===v.color&&(d.contrastColor=E.getContrast(d.color||b.color),v.color=l.inside||0>x(d.labelDistance,l.distance)||h.stacking?d.contrastColor:"#000000"),h.cursor&&(v.cursor=h.cursor),p=
{fill:l.backgroundColor,stroke:l.borderColor,"stroke-width":l.borderWidth,r:l.borderRadius||0,rotation:t,padding:l.padding,zIndex:1},a.objectEach(p,function(a,b){void 0===a&&delete p[b]}));!k||g&&F(m)?g&&F(m)&&(k?p.text=m:(k=d.dataLabel=t?E.text(m,0,-9999).addClass("highcharts-data-label"):E.label(m,0,-9999,l.shape,null,null,l.useHTML,null,"data-label"),k.addClass(" highcharts-data-label-color-"+d.colorIndex+" "+(l.className||"")+(l.useHTML?" highcharts-tracker":""))),k.attr(p),k.css(v).shadow(l.shadow),
k.added||k.add(D),b.alignDataLabel(d,k,l,null,u)):(d.dataLabel=k=k.destroy(),n&&(d.connector=n.destroy()))});a.fireEvent(this,"afterDrawDataLabels")};c.prototype.alignDataLabel=function(a,b,c,e,f){var d=this.chart,l=d.inverted,k=x(a.dlBox&&a.dlBox.centerX,a.plotX,-9999),n=x(a.plotY,-9999),m=b.getBBox(),p,r=c.rotation,q=c.align,u=this.visible&&(a.series.forceDL||d.isInsidePlot(k,Math.round(n),l)||e&&d.isInsidePlot(k,l?e.x+1:e.y+e.height-1,l)),g="justify"===x(c.overflow,"justify");if(u&&(p=c.style.fontSize,
p=d.renderer.fontMetrics(p,b).b,e=h({x:l?this.yAxis.len-n:k,y:Math.round(l?this.xAxis.len-k:n),width:0,height:0},e),h(c,{width:m.width,height:m.height}),r?(g=!1,k=d.renderer.rotCorr(p,r),k={x:e.x+c.x+e.width/2+k.x,y:e.y+c.y+{top:0,middle:.5,bottom:1}[c.verticalAlign]*e.height},b[f?"attr":"animate"](k).attr({align:q}),n=(r+720)%360,n=180<n&&360>n,"left"===q?k.y-=n?m.height:0:"center"===q?(k.x-=m.width/2,k.y-=m.height/2):"right"===q&&(k.x-=m.width,k.y-=n?0:m.height),b.placed=!0,b.alignAttr=k):(b.align(c,
null,e),k=b.alignAttr),g?a.isLabelJustified=this.justifyDataLabel(b,c,k,m,e,f):x(c.crop,!0)&&(u=d.isInsidePlot(k.x,k.y)&&d.isInsidePlot(k.x+m.width,k.y+m.height)),c.shape&&!r))b[f?"attr":"animate"]({anchorX:l?d.plotWidth-a.plotY:a.plotX,anchorY:l?d.plotHeight-a.plotX:a.plotY});u||(b.attr({y:-9999}),b.placed=!1)};c.prototype.justifyDataLabel=function(a,b,c,e,f,h){var d=this.chart,l=b.align,k=b.verticalAlign,m,t,n=a.box?0:a.padding||0;m=c.x+n;0>m&&("right"===l?b.align="left":b.x=-m,t=!0);m=c.x+e.width-
n;m>d.plotWidth&&("left"===l?b.align="right":b.x=d.plotWidth-m,t=!0);m=c.y+n;0>m&&("bottom"===k?b.verticalAlign="top":b.y=-m,t=!0);m=c.y+e.height-n;m>d.plotHeight&&("top"===k?b.verticalAlign="bottom":b.y=d.plotHeight-m,t=!0);t&&(a.placed=!h,a.align(b,null,f));return t};k.pie&&(k.pie.prototype.drawDataLabels=function(){var d=this,b=d.data,e,f=d.chart,h=d.options.dataLabels,l=x(h.connectorPadding,10),k=x(h.connectorWidth,1),r=f.plotWidth,q=f.plotHeight,m=Math.round(f.chartWidth/3),u,y=d.center,C=y[2]/
2,G=y[1],g,w,K,Q,J=[[],[]],O,N,z,R,S=[0,0,0,0];d.visible&&(h.enabled||d._hasPointLabels)&&(n(b,function(a){a.dataLabel&&a.visible&&a.dataLabel.shortened&&(a.dataLabel.attr({width:"auto"}).css({width:"auto",textOverflow:"clip"}),a.dataLabel.shortened=!1)}),c.prototype.drawDataLabels.apply(d),n(b,function(a){a.dataLabel&&(a.visible?(J[a.half].push(a),a.dataLabel._pos=null,!F(h.style.width)&&!F(a.options.dataLabels&&a.options.dataLabels.style&&a.options.dataLabels.style.width)&&a.dataLabel.getBBox().width>
m&&(a.dataLabel.css({width:.7*m}),a.dataLabel.shortened=!0)):a.dataLabel=a.dataLabel.destroy())}),n(J,function(b,c){var k,m,t=b.length,p=[],u;if(t)for(d.sortByAngle(b,c-.5),0<d.maxLabelDistance&&(k=Math.max(0,G-C-d.maxLabelDistance),m=Math.min(G+C+d.maxLabelDistance,f.plotHeight),n(b,function(a){0<a.labelDistance&&a.dataLabel&&(a.top=Math.max(0,G-C-a.labelDistance),a.bottom=Math.min(G+C+a.labelDistance,f.plotHeight),u=a.dataLabel.getBBox().height||21,a.distributeBox={target:a.labelPos[1]-a.top+u/
2,size:u,rank:a.y},p.push(a.distributeBox))}),k=m+u-k,a.distribute(p,k,k/5)),R=0;R<t;R++)e=b[R],K=e.labelPos,g=e.dataLabel,z=!1===e.visible?"hidden":"inherit",N=k=K[1],p&&F(e.distributeBox)&&(void 0===e.distributeBox.pos?z="hidden":(Q=e.distributeBox.size,N=e.top+e.distributeBox.pos)),delete e.positionIndex,O=h.justify?y[0]+(c?-1:1)*(C+e.labelDistance):d.getX(N<e.top+2||N>e.bottom-2?k:N,c,e),g._attr={visibility:z,align:K[6]},g._pos={x:O+h.x+({left:l,right:-l}[K[6]]||0),y:N+h.y-10},K.x=O,K.y=N,x(h.crop,
!0)&&(w=g.getBBox().width,k=null,O-w<l&&1===c?(k=Math.round(w-O+l),S[3]=Math.max(k,S[3])):O+w>r-l&&0===c&&(k=Math.round(O+w-r+l),S[1]=Math.max(k,S[1])),0>N-Q/2?S[0]=Math.max(Math.round(-N+Q/2),S[0]):N+Q/2>q&&(S[2]=Math.max(Math.round(N+Q/2-q),S[2])),g.sideOverflow=k)}),0===E(S)||this.verifyDataLabelOverflow(S))&&(this.placeDataLabels(),k&&n(this.points,function(a){var b;u=a.connector;if((g=a.dataLabel)&&g._pos&&a.visible&&0<a.labelDistance){z=g._attr.visibility;if(b=!u)a.connector=u=f.renderer.path().addClass("highcharts-data-label-connector  highcharts-color-"+
a.colorIndex+(a.className?" "+a.className:"")).add(d.dataLabelsGroup),u.attr({"stroke-width":k,stroke:h.connectorColor||a.color||"#666666"});u[b?"attr":"animate"]({d:d.connectorPath(a.labelPos)});u.attr("visibility",z)}else u&&(a.connector=u.destroy())}))},k.pie.prototype.connectorPath=function(a){var b=a.x,c=a.y;return x(this.options.dataLabels.softConnector,!0)?["M",b+("left"===a[6]?5:-5),c,"C",b,c,2*a[2]-a[4],2*a[3]-a[5],a[2],a[3],"L",a[4],a[5]]:["M",b+("left"===a[6]?5:-5),c,"L",a[2],a[3],"L",
a[4],a[5]]},k.pie.prototype.placeDataLabels=function(){n(this.points,function(a){var b=a.dataLabel;b&&a.visible&&((a=b._pos)?(b.sideOverflow&&(b._attr.width=b.getBBox().width-b.sideOverflow,b.css({width:b._attr.width+"px",textOverflow:this.options.dataLabels.style.textOverflow||"ellipsis"}),b.shortened=!0),b.attr(b._attr),b[b.moved?"animate":"attr"](a),b.moved=!0):b&&b.attr({y:-9999}))},this)},k.pie.prototype.alignDataLabel=q,k.pie.prototype.verifyDataLabelOverflow=function(a){var b=this.center,c=
this.options,d=c.center,e=c.minSize||80,h,l=null!==c.size;l||(null!==d[0]?h=Math.max(b[2]-Math.max(a[1],a[3]),e):(h=Math.max(b[2]-a[1]-a[3],e),b[0]+=(a[3]-a[1])/2),null!==d[1]?h=Math.max(Math.min(h,b[2]-Math.max(a[0],a[2])),e):(h=Math.max(Math.min(h,b[2]-a[0]-a[2]),e),b[1]+=(a[0]-a[2])/2),h<b[2]?(b[2]=h,b[3]=Math.min(f(c.innerSize||0,h),h),this.translate(b),this.drawDataLabels&&this.drawDataLabels()):l=!0);return l});k.column&&(k.column.prototype.alignDataLabel=function(a,b,e,f,h){var d=this.chart.inverted,
l=a.series,k=a.dlBox||a.shapeArgs,n=x(a.below,a.plotY>x(this.translatedThreshold,l.yAxis.len)),m=x(e.inside,!!this.options.stacking);k&&(f=y(k),0>f.y&&(f.height+=f.y,f.y=0),k=f.y+f.height-l.yAxis.len,0<k&&(f.height-=k),d&&(f={x:l.yAxis.len-f.y-f.height,y:l.xAxis.len-f.x-f.width,width:f.height,height:f.width}),m||(d?(f.x+=n?0:f.width,f.width=0):(f.y+=n?f.height:0,f.height=0)));e.align=x(e.align,!d||m?"center":n?"right":"left");e.verticalAlign=x(e.verticalAlign,d||m?"middle":n?"top":"bottom");c.prototype.alignDataLabel.call(this,
a,b,e,f,h);a.isLabelJustified&&a.contrastColor&&a.dataLabel.css({color:a.contrastColor})})})(K);(function(a){var C=a.Chart,E=a.each,F=a.objectEach,n=a.pick;a=a.addEvent;a(C,"render",function(){var a=[];E(this.labelCollectors||[],function(e){a=a.concat(e())});E(this.yAxis||[],function(e){e.options.stackLabels&&!e.options.stackLabels.allowOverlap&&F(e.stacks,function(e){F(e,function(e){a.push(e.label)})})});E(this.series||[],function(e){var h=e.options.dataLabels,y=e.dataLabelCollections||["dataLabel"];
(h.enabled||e._hasPointLabels)&&!h.allowOverlap&&e.visible&&E(y,function(h){E(e.points,function(e){e[h]&&(e[h].labelrank=n(e.labelrank,e.shapeArgs&&e.shapeArgs.height),a.push(e[h]))})})});this.hideOverlappingLabels(a)});C.prototype.hideOverlappingLabels=function(a){var e=a.length,h,n,q,x,f,c,k=function(a,c,d,b,e,f,h,k){return!(e>a+d||e+h<a||f>c+b||f+k<c)};q=function(a){var c,d,b,e=2*(a.box?0:a.padding||0);if(a&&(!a.alignAttr||a.placed))return c=a.alignAttr||{x:a.attr("x"),y:a.attr("y")},d=a.parentGroup,
a.width||(b=a.getBBox(),a.width=b.width,a.height=b.height),{x:c.x+(d.translateX||0),y:c.y+(d.translateY||0),width:a.width-e,height:a.height-e}};for(n=0;n<e;n++)if(h=a[n])h.oldOpacity=h.opacity,h.newOpacity=1,h.absoluteBox=q(h);a.sort(function(a,c){return(c.labelrank||0)-(a.labelrank||0)});for(n=0;n<e;n++)for(c=(q=a[n])&&q.absoluteBox,h=n+1;h<e;++h)if(f=(x=a[h])&&x.absoluteBox,c&&f&&q!==x&&0!==q.newOpacity&&0!==x.newOpacity&&(f=k(c.x,c.y,c.width,c.height,f.x,f.y,f.width,f.height)))(q.labelrank<x.labelrank?
q:x).newOpacity=0;E(a,function(a){var c,d;a&&(d=a.newOpacity,a.oldOpacity!==d&&(a.alignAttr&&a.placed?(d?a.show(!0):c=function(){a.hide()},a.alignAttr.opacity=d,a[a.isOld?"animate":"attr"](a.alignAttr,null,c)):a.attr({opacity:d})),a.isOld=!0)})}})(K);(function(a){var C=a.addEvent,E=a.Chart,F=a.createElement,n=a.css,h=a.defaultOptions,e=a.defaultPlotOptions,u=a.each,y=a.extend,q=a.fireEvent,x=a.hasTouch,f=a.inArray,c=a.isObject,k=a.Legend,r=a.merge,l=a.pick,d=a.Point,b=a.Series,v=a.seriesTypes,p=a.svg,
I;I=a.TrackerMixin={drawTrackerPoint:function(){var a=this,b=a.chart.pointer,c=function(a){var c=b.getPointFromEvent(a);void 0!==c&&(b.isDirectTouch=!0,c.onMouseOver(a))};u(a.points,function(a){a.graphic&&(a.graphic.element.point=a);a.dataLabel&&(a.dataLabel.div?a.dataLabel.div.point=a:a.dataLabel.element.point=a)});a._hasTracking||(u(a.trackerGroups,function(d){if(a[d]){a[d].addClass("highcharts-tracker").on("mouseover",c).on("mouseout",function(a){b.onTrackerMouseOut(a)});if(x)a[d].on("touchstart",
c);a.options.cursor&&a[d].css(n).css({cursor:a.options.cursor})}}),a._hasTracking=!0);q(this,"afterDrawTracker")},drawTrackerGraph:function(){var a=this,b=a.options,c=b.trackByArea,d=[].concat(c?a.areaPath:a.graphPath),e=d.length,f=a.chart,h=f.pointer,l=f.renderer,k=f.options.tooltip.snap,g=a.tracker,n,r=function(){if(f.hoverSeries!==a)a.onMouseOver()},v="rgba(192,192,192,"+(p?.0001:.002)+")";if(e&&!c)for(n=e+1;n--;)"M"===d[n]&&d.splice(n+1,0,d[n+1]-k,d[n+2],"L"),(n&&"M"===d[n]||n===e)&&d.splice(n,
0,"L",d[n-2]+k,d[n-1]);g?g.attr({d:d}):a.graph&&(a.tracker=l.path(d).attr({"stroke-linejoin":"round",visibility:a.visible?"visible":"hidden",stroke:v,fill:c?v:"none","stroke-width":a.graph.strokeWidth()+(c?0:2*k),zIndex:2}).add(a.group),u([a.tracker,a.markerGroup],function(a){a.addClass("highcharts-tracker").on("mouseover",r).on("mouseout",function(a){h.onTrackerMouseOut(a)});b.cursor&&a.css({cursor:b.cursor});if(x)a.on("touchstart",r)}));q(this,"afterDrawTracker")}};v.column&&(v.column.prototype.drawTracker=
I.drawTrackerPoint);v.pie&&(v.pie.prototype.drawTracker=I.drawTrackerPoint);v.scatter&&(v.scatter.prototype.drawTracker=I.drawTrackerPoint);y(k.prototype,{setItemEvents:function(a,b,c){var e=this,f=e.chart.renderer.boxWrapper,h="highcharts-legend-"+(a instanceof d?"point":"series")+"-active";(c?b:a.legendGroup).on("mouseover",function(){a.setState("hover");f.addClass(h);b.css(e.options.itemHoverStyle)}).on("mouseout",function(){b.css(r(a.visible?e.itemStyle:e.itemHiddenStyle));f.removeClass(h);a.setState()}).on("click",
function(b){var c=function(){a.setVisible&&a.setVisible()};f.removeClass(h);b={browserEvent:b};a.firePointEvent?a.firePointEvent("legendItemClick",b,c):q(a,"legendItemClick",b,c)})},createCheckboxForItem:function(a){a.checkbox=F("input",{type:"checkbox",checked:a.selected,defaultChecked:a.selected},this.options.itemCheckboxStyle,this.chart.container);C(a.checkbox,"click",function(b){q(a.series||a,"checkboxClick",{checked:b.target.checked,item:a},function(){a.select()})})}});h.legend.itemStyle.cursor=
"pointer";y(E.prototype,{showResetZoom:function(){function a(){b.zoomOut()}var b=this,c=h.lang,d=b.options.chart.resetZoomButton,e=d.theme,f=e.states,k="chart"===d.relativeTo?null:"plotBox";q(this,"beforeShowResetZoom",null,function(){b.resetZoomButton=b.renderer.button(c.resetZoom,null,null,a,e,f&&f.hover).attr({align:d.position.align,title:c.resetZoomTitle}).addClass("highcharts-reset-zoom").add().align(d.position,!1,k)})},zoomOut:function(){q(this,"selection",{resetSelection:!0},this.zoom)},zoom:function(a){var b,
d=this.pointer,e=!1,f;!a||a.resetSelection?(u(this.axes,function(a){b=a.zoom()}),d.initiated=!1):u(a.xAxis.concat(a.yAxis),function(a){var c=a.axis;d[c.isXAxis?"zoomX":"zoomY"]&&(b=c.zoom(a.min,a.max),c.displayBtn&&(e=!0))});f=this.resetZoomButton;e&&!f?this.showResetZoom():!e&&c(f)&&(this.resetZoomButton=f.destroy());b&&this.redraw(l(this.options.chart.animation,a&&a.animation,100>this.pointCount))},pan:function(a,b){var c=this,d=c.hoverPoints,e;d&&u(d,function(a){a.setState()});u("xy"===b?[1,0]:
[1],function(b){b=c[b?"xAxis":"yAxis"][0];var d=b.horiz,f=a[d?"chartX":"chartY"],d=d?"mouseDownX":"mouseDownY",h=c[d],g=(b.pointRange||0)/2,k=b.reversed&&!c.inverted||!b.reversed&&c.inverted?-1:1,l=b.getExtremes(),m=b.toValue(h-f,!0)+g*k,k=b.toValue(h+b.len-f,!0)-g*k,n=k<m,h=n?k:m,m=n?m:k,k=Math.min(l.dataMin,g?l.min:b.toValue(b.toPixels(l.min)-b.minPixelPadding)),g=Math.max(l.dataMax,g?l.max:b.toValue(b.toPixels(l.max)+b.minPixelPadding)),n=k-h;0<n&&(m+=n,h=k);n=m-g;0<n&&(m=g,h-=n);b.series.length&&
h!==l.min&&m!==l.max&&(b.setExtremes(h,m,!1,!1,{trigger:"pan"}),e=!0);c[d]=f});e&&c.redraw(!1);n(c.container,{cursor:"move"})}});y(d.prototype,{select:function(a,b){var c=this,d=c.series,e=d.chart;a=l(a,!c.selected);c.firePointEvent(a?"select":"unselect",{accumulate:b},function(){c.selected=c.options.selected=a;d.options.data[f(c,d.data)]=c.options;c.setState(a&&"select");b||u(e.getSelectedPoints(),function(a){a.selected&&a!==c&&(a.selected=a.options.selected=!1,d.options.data[f(a,d.data)]=a.options,
a.setState(""),a.firePointEvent("unselect"))})})},onMouseOver:function(a){var b=this.series.chart,c=b.pointer;a=a?c.normalize(a):c.getChartCoordinatesFromPoint(this,b.inverted);c.runPointActions(a,this)},onMouseOut:function(){var a=this.series.chart;this.firePointEvent("mouseOut");u(a.hoverPoints||[],function(a){a.setState()});a.hoverPoints=a.hoverPoint=null},importEvents:function(){if(!this.hasImportedEvents){var b=this,c=r(b.series.options.point,b.options).events;b.events=c;a.objectEach(c,function(a,
c){C(b,c,a)});this.hasImportedEvents=!0}},setState:function(a,b){var c=Math.floor(this.plotX),d=this.plotY,f=this.series,h=f.options.states[a||"normal"]||{},k=e[f.type].marker&&f.options.marker,n=k&&!1===k.enabled,p=k&&k.states&&k.states[a||"normal"]||{},g=!1===p.enabled,t=f.stateMarkerGraphic,r=this.marker||{},u=f.chart,v=f.halo,x,C=k&&f.markerAttribs;a=a||"";if(!(a===this.state&&!b||this.selected&&"select"!==a||!1===h.enabled||a&&(g||n&&!1===p.enabled)||a&&r.states&&r.states[a]&&!1===r.states[a].enabled)){C&&
(x=f.markerAttribs(this,a));if(this.graphic)this.state&&this.graphic.removeClass("highcharts-point-"+this.state),a&&this.graphic.addClass("highcharts-point-"+a),this.graphic.animate(f.pointAttribs(this,a),l(u.options.chart.animation,h.animation)),x&&this.graphic.animate(x,l(u.options.chart.animation,p.animation,k.animation)),t&&t.hide();else{if(a&&p){k=r.symbol||f.symbol;t&&t.currentSymbol!==k&&(t=t.destroy());if(t)t[b?"animate":"attr"]({x:x.x,y:x.y});else k&&(f.stateMarkerGraphic=t=u.renderer.symbol(k,
x.x,x.y,x.width,x.height).add(f.markerGroup),t.currentSymbol=k);t&&t.attr(f.pointAttribs(this,a))}t&&(t[a&&u.isInsidePlot(c,d,u.inverted)?"show":"hide"](),t.element.point=this)}(c=h.halo)&&c.size?(v||(f.halo=v=u.renderer.path().add((this.graphic||t).parentGroup)),v.show()[b?"animate":"attr"]({d:this.haloPath(c.size)}),v.attr({"class":"highcharts-halo highcharts-color-"+l(this.colorIndex,f.colorIndex)+(this.className?" "+this.className:""),zIndex:-1}),v.point=this,v.attr(y({fill:this.color||f.color,
"fill-opacity":c.opacity},c.attributes))):v&&v.point&&v.point.haloPath&&v.animate({d:v.point.haloPath(0)},null,v.hide);this.state=a;q(this,"afterSetState")}},haloPath:function(a){return this.series.chart.renderer.symbols.circle(Math.floor(this.plotX)-a,this.plotY-a,2*a,2*a)}});y(b.prototype,{onMouseOver:function(){var a=this.chart,b=a.hoverSeries;if(b&&b!==this)b.onMouseOut();this.options.events.mouseOver&&q(this,"mouseOver");this.setState("hover");a.hoverSeries=this},onMouseOut:function(){var a=
this.options,b=this.chart,c=b.tooltip,d=b.hoverPoint;b.hoverSeries=null;if(d)d.onMouseOut();this&&a.events.mouseOut&&q(this,"mouseOut");!c||this.stickyTracking||c.shared&&!this.noSharedTooltip||c.hide();this.setState()},setState:function(a){var b=this,c=b.options,d=b.graph,e=c.states,f=c.lineWidth,c=0;a=a||"";if(b.state!==a&&(u([b.group,b.markerGroup,b.dataLabelsGroup],function(c){c&&(b.state&&c.removeClass("highcharts-series-"+b.state),a&&c.addClass("highcharts-series-"+a))}),b.state=a,!e[a]||!1!==
e[a].enabled)&&(a&&(f=e[a].lineWidth||f+(e[a].lineWidthPlus||0)),d&&!d.dashstyle))for(f={"stroke-width":f},d.animate(f,l(e[a||"normal"]&&e[a||"normal"].animation,b.chart.options.chart.animation));b["zone-graph-"+c];)b["zone-graph-"+c].attr(f),c+=1},setVisible:function(a,b){var c=this,d=c.chart,e=c.legendItem,f,h=d.options.chart.ignoreHiddenSeries,k=c.visible;f=(c.visible=a=c.options.visible=c.userOptions.visible=void 0===a?!k:a)?"show":"hide";u(["group","dataLabelsGroup","markerGroup","tracker","tt"],
function(a){if(c[a])c[a][f]()});if(d.hoverSeries===c||(d.hoverPoint&&d.hoverPoint.series)===c)c.onMouseOut();e&&d.legend.colorizeItem(c,a);c.isDirty=!0;c.options.stacking&&u(d.series,function(a){a.options.stacking&&a.visible&&(a.isDirty=!0)});u(c.linkedSeries,function(b){b.setVisible(a,!1)});h&&(d.isDirtyBox=!0);q(c,f);!1!==b&&d.redraw()},show:function(){this.setVisible(!0)},hide:function(){this.setVisible(!1)},select:function(a){this.selected=a=void 0===a?!this.selected:a;this.checkbox&&(this.checkbox.checked=
a);q(this,a?"select":"unselect")},drawTracker:I.drawTrackerGraph})})(K);(function(a){var C=a.Chart,E=a.each,F=a.inArray,n=a.isArray,h=a.isObject,e=a.pick,u=a.splat;C.prototype.setResponsive=function(e){var h=this.options.responsive,n=[],f=this.currentResponsive;h&&h.rules&&E(h.rules,function(c){void 0===c._id&&(c._id=a.uniqueKey());this.matchResponsiveRule(c,n,e)},this);var c=a.merge.apply(0,a.map(n,function(c){return a.find(h.rules,function(a){return a._id===c}).chartOptions})),n=n.toString()||void 0;
n!==(f&&f.ruleIds)&&(f&&this.update(f.undoOptions,e),n?(this.currentResponsive={ruleIds:n,mergedOptions:c,undoOptions:this.currentOptions(c)},this.update(c,e)):this.currentResponsive=void 0)};C.prototype.matchResponsiveRule=function(a,h){var n=a.condition;(n.callback||function(){return this.chartWidth<=e(n.maxWidth,Number.MAX_VALUE)&&this.chartHeight<=e(n.maxHeight,Number.MAX_VALUE)&&this.chartWidth>=e(n.minWidth,0)&&this.chartHeight>=e(n.minHeight,0)}).call(this)&&h.push(a._id)};C.prototype.currentOptions=
function(e){function q(e,c,k,r){var f;a.objectEach(e,function(a,b){if(!r&&-1<F(b,["series","xAxis","yAxis"]))for(a=u(a),k[b]=[],f=0;f<a.length;f++)c[b][f]&&(k[b][f]={},q(a[f],c[b][f],k[b][f],r+1));else h(a)?(k[b]=n(a)?[]:{},q(a,c[b]||{},k[b],r+1)):k[b]=c[b]||null})}var x={};q(e,this.options,x,0);return x}})(K);return K});


/***/ }),

/***/ "./node_modules/highcharts/modules/exporting.js":
/*!******************************************************!*\
  !*** ./node_modules/highcharts/modules/exporting.js ***!
  \******************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

/*
 Highcharts JS v6.1.1 (2018-06-27)
 Exporting module

 (c) 2010-2017 Torstein Honsi

 License: www.highcharts.com/license
*/
(function(h){"object"===typeof module&&module.exports?module.exports=h:h(Highcharts)})(function(h){(function(f){var h=f.defaultOptions,z=f.doc,A=f.Chart,q=f.addEvent,I=f.removeEvent,C=f.fireEvent,r=f.createElement,D=f.discardElement,u=f.css,p=f.merge,B=f.pick,k=f.each,F=f.objectEach,t=f.extend,J=f.isTouchDevice,E=f.win,G=E.navigator.userAgent,K=f.Renderer.prototype.symbols;/Edge\/|Trident\/|MSIE /.test(G);/firefox/i.test(G);t(h.lang,{printChart:"Print chart",downloadPNG:"Download PNG image",downloadJPEG:"Download JPEG image",
downloadPDF:"Download PDF document",downloadSVG:"Download SVG vector image",contextButtonTitle:"Chart context menu"});h.navigation={buttonOptions:{theme:{},symbolSize:14,symbolX:12.5,symbolY:10.5,align:"right",buttonSpacing:3,height:22,verticalAlign:"top",width:24}};p(!0,h.navigation,{menuStyle:{border:"1px solid #999999",background:"#ffffff",padding:"5px 0"},menuItemStyle:{padding:"0.5em 1em",background:"none",color:"#333333",fontSize:J?"14px":"11px",transition:"background 250ms, color 250ms"},menuItemHoverStyle:{background:"#335cad",
color:"#ffffff"},buttonOptions:{symbolFill:"#666666",symbolStroke:"#666666",symbolStrokeWidth:3,theme:{fill:"#ffffff",stroke:"none",padding:5}}});h.exporting={type:"image/png",url:"https://export.highcharts.com/",printMaxWidth:780,scale:2,buttons:{contextButton:{className:"highcharts-contextbutton",menuClassName:"highcharts-contextmenu",symbol:"menu",_titleKey:"contextButtonTitle",menuItems:"printChart separator downloadPNG downloadJPEG downloadPDF downloadSVG".split(" ")}},menuItemDefinitions:{printChart:{textKey:"printChart",
onclick:function(){this.print()}},separator:{separator:!0},downloadPNG:{textKey:"downloadPNG",onclick:function(){this.exportChart()}},downloadJPEG:{textKey:"downloadJPEG",onclick:function(){this.exportChart({type:"image/jpeg"})}},downloadPDF:{textKey:"downloadPDF",onclick:function(){this.exportChart({type:"application/pdf"})}},downloadSVG:{textKey:"downloadSVG",onclick:function(){this.exportChart({type:"image/svg+xml"})}}}};f.post=function(a,b,e){var c=r("form",p({method:"post",action:a,enctype:"multipart/form-data"},
e),{display:"none"},z.body);F(b,function(a,b){r("input",{type:"hidden",name:b,value:a},null,c)});c.submit();D(c)};t(A.prototype,{sanitizeSVG:function(a,b){if(b&&b.exporting&&b.exporting.allowHTML){var e=a.match(/<\/svg>(.*?$)/);e&&e[1]&&(e='\x3cforeignObject x\x3d"0" y\x3d"0" width\x3d"'+b.chart.width+'" height\x3d"'+b.chart.height+'"\x3e\x3cbody xmlns\x3d"http://www.w3.org/1999/xhtml"\x3e'+e[1]+"\x3c/body\x3e\x3c/foreignObject\x3e",a=a.replace("\x3c/svg\x3e",e+"\x3c/svg\x3e"))}a=a.replace(/zIndex="[^"]+"/g,
"").replace(/isShadow="[^"]+"/g,"").replace(/symbolName="[^"]+"/g,"").replace(/jQuery[0-9]+="[^"]+"/g,"").replace(/url\(("|&quot;)(\S+)("|&quot;)\)/g,"url($2)").replace(/url\([^#]+#/g,"url(#").replace(/<svg /,'\x3csvg xmlns:xlink\x3d"http://www.w3.org/1999/xlink" ').replace(/ (|NS[0-9]+\:)href=/g," xlink:href\x3d").replace(/\n/," ").replace(/<\/svg>.*?$/,"\x3c/svg\x3e").replace(/(fill|stroke)="rgba\(([ 0-9]+,[ 0-9]+,[ 0-9]+),([ 0-9\.]+)\)"/g,'$1\x3d"rgb($2)" $1-opacity\x3d"$3"').replace(/&nbsp;/g,
"\u00a0").replace(/&shy;/g,"\u00ad");this.ieSanitizeSVG&&(a=this.ieSanitizeSVG(a));return a},getChartHTML:function(){return this.container.innerHTML},getSVG:function(a){var b,e,c,v,m,g=p(this.options,a);e=r("div",null,{position:"absolute",top:"-9999em",width:this.chartWidth+"px",height:this.chartHeight+"px"},z.body);c=this.renderTo.style.width;m=this.renderTo.style.height;c=g.exporting.sourceWidth||g.chart.width||/px$/.test(c)&&parseInt(c,10)||600;m=g.exporting.sourceHeight||g.chart.height||/px$/.test(m)&&
parseInt(m,10)||400;t(g.chart,{animation:!1,renderTo:e,forExport:!0,renderer:"SVGRenderer",width:c,height:m});g.exporting.enabled=!1;delete g.data;g.series=[];k(this.series,function(a){v=p(a.userOptions,{animation:!1,enableMouseTracking:!1,showCheckbox:!1,visible:a.visible});v.isInternal||g.series.push(v)});k(this.axes,function(a){a.userOptions.internalKey||(a.userOptions.internalKey=f.uniqueKey())});b=new f.Chart(g,this.callback);a&&k(["xAxis","yAxis","series"],function(c){var d={};a[c]&&(d[c]=a[c],
b.update(d))});k(this.axes,function(a){var c=f.find(b.axes,function(b){return b.options.internalKey===a.userOptions.internalKey}),d=a.getExtremes(),e=d.userMin,d=d.userMax;c&&(void 0!==e&&e!==c.min||void 0!==d&&d!==c.max)&&c.setExtremes(e,d,!0,!1)});c=b.getChartHTML();C(this,"getSVG",{chartCopy:b});c=this.sanitizeSVG(c,g);g=null;b.destroy();D(e);return c},getSVGForExport:function(a,b){var e=this.options.exporting;return this.getSVG(p({chart:{borderRadius:0}},e.chartOptions,b,{exporting:{sourceWidth:a&&
a.sourceWidth||e.sourceWidth,sourceHeight:a&&a.sourceHeight||e.sourceHeight}}))},exportChart:function(a,b){b=this.getSVGForExport(a,b);a=p(this.options.exporting,a);f.post(a.url,{filename:a.filename||"chart",type:a.type,width:a.width||0,scale:a.scale,svg:b},a.formAttributes)},print:function(){var a=this,b=a.container,e=[],c=b.parentNode,f=z.body,m=f.childNodes,g=a.options.exporting.printMaxWidth,d,n;if(!a.isPrinting){a.isPrinting=!0;a.pointer.reset(null,0);C(a,"beforePrint");if(n=g&&a.chartWidth>
g)d=[a.options.chart.width,void 0,!1],a.setSize(g,void 0,!1);k(m,function(a,b){1===a.nodeType&&(e[b]=a.style.display,a.style.display="none")});f.appendChild(b);E.focus();E.print();setTimeout(function(){c.appendChild(b);k(m,function(a,b){1===a.nodeType&&(a.style.display=e[b])});a.isPrinting=!1;n&&a.setSize.apply(a,d);C(a,"afterPrint")},1E3)}},contextMenu:function(a,b,e,c,v,m,g){var d=this,n=d.options.navigation,h=d.chartWidth,H=d.chartHeight,p="cache-"+a,l=d[p],w=Math.max(v,m),x,y;l||(d[p]=l=r("div",
{className:a},{position:"absolute",zIndex:1E3,padding:w+"px",pointerEvents:"auto"},d.fixedDiv||d.container),x=r("div",{className:"highcharts-menu"},null,l),u(x,t({MozBoxShadow:"3px 3px 10px #888",WebkitBoxShadow:"3px 3px 10px #888",boxShadow:"3px 3px 10px #888"},n.menuStyle)),y=function(){u(l,{display:"none"});g&&g.setState(0);d.openMenu=!1},d.exportEvents.push(q(l,"mouseleave",function(){l.hideTimer=setTimeout(y,500)}),q(l,"mouseenter",function(){f.clearTimeout(l.hideTimer)}),q(z,"mouseup",function(b){d.pointer.inClass(b.target,
a)||y()}),q(l,"click",function(){d.openMenu&&y()})),k(b,function(a){"string"===typeof a&&(a=d.options.exporting.menuItemDefinitions[a]);if(f.isObject(a,!0)){var b;a.separator?b=r("hr",null,null,x):(b=r("div",{className:"highcharts-menu-item",onclick:function(b){b&&b.stopPropagation();y();a.onclick&&a.onclick.apply(d,arguments)},innerHTML:a.text||d.options.lang[a.textKey]},null,x),b.onmouseover=function(){u(this,n.menuItemHoverStyle)},b.onmouseout=function(){u(this,n.menuItemStyle)},u(b,t({cursor:"pointer"},
n.menuItemStyle)));d.exportDivElements.push(b)}}),d.exportDivElements.push(x,l),d.exportMenuWidth=l.offsetWidth,d.exportMenuHeight=l.offsetHeight);b={display:"block"};e+d.exportMenuWidth>h?b.right=h-e-v-w+"px":b.left=e-w+"px";c+m+d.exportMenuHeight>H&&"top"!==g.alignOptions.verticalAlign?b.bottom=H-c-w+"px":b.top=c+m-w+"px";u(l,b);d.openMenu=!0},addButton:function(a){var b=this,e=b.renderer,c=p(b.options.navigation.buttonOptions,a),f=c.onclick,m=c.menuItems,g,d,n=c.symbolSize||12;b.btnCount||(b.btnCount=
0);b.exportDivElements||(b.exportDivElements=[],b.exportSVGElements=[]);if(!1!==c.enabled){var h=c.theme,k=h.states,q=k&&k.hover,k=k&&k.select,l;delete h.states;f?l=function(a){a.stopPropagation();f.call(b,a)}:m&&(l=function(){b.contextMenu(d.menuClassName,m,d.translateX,d.translateY,d.width,d.height,d);d.setState(2)});c.text&&c.symbol?h.paddingLeft=B(h.paddingLeft,25):c.text||t(h,{width:c.width,height:c.height,padding:0});d=e.button(c.text,0,0,l,h,q,k).addClass(a.className).attr({"stroke-linecap":"round",
title:B(b.options.lang[c._titleKey],"")});d.menuClassName=a.menuClassName||"highcharts-menu-"+b.btnCount++;c.symbol&&(g=e.symbol(c.symbol,c.symbolX-n/2,c.symbolY-n/2,n,n,{width:n,height:n}).addClass("highcharts-button-symbol").attr({zIndex:1}).add(d),g.attr({stroke:c.symbolStroke,fill:c.symbolFill,"stroke-width":c.symbolStrokeWidth||1}));d.add(b.exportingGroup).align(t(c,{width:d.width,x:B(c.x,b.buttonOffset)}),!0,"spacingBox");b.buttonOffset+=(d.width+c.buttonSpacing)*("right"===c.align?-1:1);b.exportSVGElements.push(d,
g)}},destroyExport:function(a){var b=a?a.target:this;a=b.exportSVGElements;var e=b.exportDivElements,c=b.exportEvents,h;a&&(k(a,function(a,c){a&&(a.onclick=a.ontouchstart=null,h="cache-"+a.menuClassName,b[h]&&delete b[h],b.exportSVGElements[c]=a.destroy())}),a.length=0);b.exportingGroup&&(b.exportingGroup.destroy(),delete b.exportingGroup);e&&(k(e,function(a,c){f.clearTimeout(a.hideTimer);I(a,"mouseleave");b.exportDivElements[c]=a.onmouseout=a.onmouseover=a.ontouchstart=a.onclick=null;D(a)}),e.length=
0);c&&(k(c,function(a){a()}),c.length=0)}});K.menu=function(a,b,e,c){return["M",a,b+2.5,"L",a+e,b+2.5,"M",a,b+c/2+.5,"L",a+e,b+c/2+.5,"M",a,b+c-1.5,"L",a+e,b+c-1.5]};A.prototype.renderExporting=function(){var a=this,b=a.options.exporting,e=b.buttons,c=a.isDirtyExporting||!a.exportSVGElements;a.buttonOffset=0;a.isDirtyExporting&&a.destroyExport();c&&!1!==b.enabled&&(a.exportEvents=[],a.exportingGroup=a.exportingGroup||a.renderer.g("exporting-group").attr({zIndex:3}).add(),F(e,function(b){a.addButton(b)}),
a.isDirtyExporting=!1);q(a,"destroy",a.destroyExport)};q(A,"init",function(){var a=this;k(["exporting","navigation"],function(b){a[b]={update:function(e,c){a.isDirtyExporting=!0;p(!0,a.options[b],e);B(c,!0)&&a.redraw()}}})});A.prototype.callbacks.push(function(a){a.renderExporting();q(a,"redraw",a.renderExporting)})})(h)});


/***/ }),

/***/ "./node_modules/sweetalert2/dist/sweetalert2.all.js":
/*!**********************************************************!*\
  !*** ./node_modules/sweetalert2/dist/sweetalert2.all.js ***!
  \**********************************************************/
/*! dynamic exports provided */
/*! exports used: default */
/***/ (function(module, exports, __webpack_require__) {

/*!
* sweetalert2 v7.26.11
* Released under the MIT License.
*/
(function (global, factory) {
	 true ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(global.Sweetalert2 = factory());
}(this, (function () { 'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
  return typeof obj;
} : function (obj) {
  return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
};











var classCallCheck = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

var createClass = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();







var _extends = Object.assign || function (target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i];

    for (var key in source) {
      if (Object.prototype.hasOwnProperty.call(source, key)) {
        target[key] = source[key];
      }
    }
  }

  return target;
};

var get = function get(object, property, receiver) {
  if (object === null) object = Function.prototype;
  var desc = Object.getOwnPropertyDescriptor(object, property);

  if (desc === undefined) {
    var parent = Object.getPrototypeOf(object);

    if (parent === null) {
      return undefined;
    } else {
      return get(parent, property, receiver);
    }
  } else if ("value" in desc) {
    return desc.value;
  } else {
    var getter = desc.get;

    if (getter === undefined) {
      return undefined;
    }

    return getter.call(receiver);
  }
};

var inherits = function (subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      enumerable: false,
      writable: true,
      configurable: true
    }
  });
  if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
};











var possibleConstructorReturn = function (self, call) {
  if (!self) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return call && (typeof call === "object" || typeof call === "function") ? call : self;
};

var consolePrefix = 'SweetAlert2:';

/**
 * Filter the unique values into a new array
 * @param arr
 */
var uniqueArray = function uniqueArray(arr) {
  var result = [];
  for (var i = 0; i < arr.length; i++) {
    if (result.indexOf(arr[i]) === -1) {
      result.push(arr[i]);
    }
  }
  return result;
};

/**
 * Convert NodeList to Array
 * @param nodeList
 */
var toArray$1 = function toArray$$1(nodeList) {
  return Array.prototype.slice.call(nodeList);
};

/**
* Converts `inputOptions` into an array of `[value, label]`s
* @param inputOptions
*/
var formatInputOptions = function formatInputOptions(inputOptions) {
  var result = [];
  if (typeof Map !== 'undefined' && inputOptions instanceof Map) {
    inputOptions.forEach(function (value, key) {
      result.push([key, value]);
    });
  } else {
    Object.keys(inputOptions).forEach(function (key) {
      result.push([key, inputOptions[key]]);
    });
  }
  return result;
};

/**
 * Standardise console warnings
 * @param message
 */
var warn = function warn(message) {
  console.warn(consolePrefix + ' ' + message);
};

/**
 * Standardise console errors
 * @param message
 */
var error = function error(message) {
  console.error(consolePrefix + ' ' + message);
};

/**
 * Private global state for `warnOnce`
 * @type {Array}
 * @private
 */
var previousWarnOnceMessages = [];

/**
 * Show a console warning, but only if it hasn't already been shown
 * @param message
 */
var warnOnce = function warnOnce(message) {
  if (!(previousWarnOnceMessages.indexOf(message) !== -1)) {
    previousWarnOnceMessages.push(message);
    warn(message);
  }
};

/**
 * If `arg` is a function, call it (with no arguments or context) and return the result.
 * Otherwise, just pass the value through
 * @param arg
 */
var callIfFunction = function callIfFunction(arg) {
  return typeof arg === 'function' ? arg() : arg;
};

var isThenable = function isThenable(arg) {
  return (typeof arg === 'undefined' ? 'undefined' : _typeof(arg)) === 'object' && typeof arg.then === 'function';
};

var DismissReason = Object.freeze({
  cancel: 'cancel',
  backdrop: 'overlay',
  close: 'close',
  esc: 'esc',
  timer: 'timer'
});

var version = "7.26.11";

var argsToParams = function argsToParams(args) {
  var params = {};
  switch (_typeof(args[0])) {
    case 'string':
      ['title', 'html', 'type'].forEach(function (name, index) {
        switch (_typeof(args[index])) {
          case 'string':
            params[name] = args[index];
            break;
          case 'undefined':
            break;
          default:
            error('Unexpected type of ' + name + '! Expected "string", got ' + _typeof(args[index]));
        }
      });
      break;

    case 'object':
      _extends(params, args[0]);
      break;

    default:
      error('Unexpected type of argument! Expected "string" or "object", got "' + _typeof(args[0]) + '"');
  }
  return params;
};

/**
 * Adapt a legacy inputValidator for use with expectRejections=false
 */
var adaptInputValidator = function adaptInputValidator(legacyValidator) {
  return function adaptedInputValidator(inputValue, extraParams) {
    return legacyValidator.call(this, inputValue, extraParams).then(function () {
      return undefined;
    }, function (validationError) {
      return validationError;
    });
  };
};

var swalPrefix = 'swal2-';

var prefix = function prefix(items) {
  var result = {};
  for (var i in items) {
    result[items[i]] = swalPrefix + items[i];
  }
  return result;
};

var swalClasses = prefix(['container', 'shown', 'height-auto', 'iosfix', 'popup', 'modal', 'no-backdrop', 'toast', 'toast-shown', 'toast-column', 'fade', 'show', 'hide', 'noanimation', 'close', 'title', 'header', 'content', 'actions', 'confirm', 'cancel', 'footer', 'icon', 'icon-text', 'image', 'input', 'file', 'range', 'select', 'radio', 'checkbox', 'label', 'textarea', 'inputerror', 'validationerror', 'progresssteps', 'activeprogressstep', 'progresscircle', 'progressline', 'loading', 'styled', 'top', 'top-start', 'top-end', 'top-left', 'top-right', 'center', 'center-start', 'center-end', 'center-left', 'center-right', 'bottom', 'bottom-start', 'bottom-end', 'bottom-left', 'bottom-right', 'grow-row', 'grow-column', 'grow-fullscreen']);

var iconTypes = prefix(['success', 'warning', 'info', 'question', 'error']);

// Remember state in cases where opening and handling a modal will fiddle with it.
var states = {
  previousBodyPadding: null
};

var hasClass = function hasClass(elem, className) {
  return elem.classList.contains(className);
};

var focusInput = function focusInput(input) {
  input.focus();

  // place cursor at end of text in text input
  if (input.type !== 'file') {
    // http://stackoverflow.com/a/2345915/1331425
    var val = input.value;
    input.value = '';
    input.value = val;
  }
};

var addOrRemoveClass = function addOrRemoveClass(target, classList, add) {
  if (!target || !classList) {
    return;
  }
  if (typeof classList === 'string') {
    classList = classList.split(/\s+/).filter(Boolean);
  }
  classList.forEach(function (className) {
    if (target.forEach) {
      target.forEach(function (elem) {
        add ? elem.classList.add(className) : elem.classList.remove(className);
      });
    } else {
      add ? target.classList.add(className) : target.classList.remove(className);
    }
  });
};

var addClass = function addClass(target, classList) {
  addOrRemoveClass(target, classList, true);
};

var removeClass = function removeClass(target, classList) {
  addOrRemoveClass(target, classList, false);
};

var getChildByClass = function getChildByClass(elem, className) {
  for (var i = 0; i < elem.childNodes.length; i++) {
    if (hasClass(elem.childNodes[i], className)) {
      return elem.childNodes[i];
    }
  }
};

var show = function show(elem) {
  elem.style.opacity = '';
  elem.style.display = elem.id === swalClasses.content ? 'block' : 'flex';
};

var hide = function hide(elem) {
  elem.style.opacity = '';
  elem.style.display = 'none';
};

// borrowed from jquery $(elem).is(':visible') implementation
var isVisible = function isVisible(elem) {
  return elem && (elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length);
};

var removeStyleProperty = function removeStyleProperty(elem, property) {
  if (elem.style.removeProperty) {
    elem.style.removeProperty(property);
  } else {
    elem.style.removeAttribute(property);
  }
};

var getContainer = function getContainer() {
  return document.body.querySelector('.' + swalClasses.container);
};

var elementByClass = function elementByClass(className) {
  var container = getContainer();
  return container ? container.querySelector('.' + className) : null;
};

var getPopup = function getPopup() {
  return elementByClass(swalClasses.popup);
};

var getIcons = function getIcons() {
  var popup = getPopup();
  return toArray$1(popup.querySelectorAll('.' + swalClasses.icon));
};

var getTitle = function getTitle() {
  return elementByClass(swalClasses.title);
};

var getContent = function getContent() {
  return elementByClass(swalClasses.content);
};

var getImage = function getImage() {
  return elementByClass(swalClasses.image);
};

var getProgressSteps = function getProgressSteps() {
  return elementByClass(swalClasses.progresssteps);
};

var getValidationError = function getValidationError() {
  return elementByClass(swalClasses.validationerror);
};

var getConfirmButton = function getConfirmButton() {
  return elementByClass(swalClasses.confirm);
};

var getCancelButton = function getCancelButton() {
  return elementByClass(swalClasses.cancel);
};

/* @deprecated */
/* istanbul ignore next */
var getButtonsWrapper = function getButtonsWrapper() {
  warnOnce('swal.getButtonsWrapper() is deprecated and will be removed in the next major release, use swal.getActions() instead');
  return elementByClass(swalClasses.actions);
};

var getActions = function getActions() {
  return elementByClass(swalClasses.actions);
};

var getFooter = function getFooter() {
  return elementByClass(swalClasses.footer);
};

var getCloseButton = function getCloseButton() {
  return elementByClass(swalClasses.close);
};

var getFocusableElements = function getFocusableElements() {
  var focusableElementsWithTabindex = toArray$1(getPopup().querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])'))
  // sort according to tabindex
  .sort(function (a, b) {
    a = parseInt(a.getAttribute('tabindex'));
    b = parseInt(b.getAttribute('tabindex'));
    if (a > b) {
      return 1;
    } else if (a < b) {
      return -1;
    }
    return 0;
  });

  // https://github.com/jkup/focusable/blob/master/index.js
  var otherFocusableElements = toArray$1(getPopup().querySelectorAll('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable], audio[controls], video[controls]')).filter(function (el) {
    return el.getAttribute('tabindex') !== '-1';
  });

  return uniqueArray(focusableElementsWithTabindex.concat(otherFocusableElements)).filter(function (el) {
    return isVisible(el);
  });
};

var isModal = function isModal() {
  return !isToast() && !document.body.classList.contains(swalClasses['no-backdrop']);
};

var isToast = function isToast() {
  return document.body.classList.contains(swalClasses['toast-shown']);
};

var isLoading = function isLoading() {
  return getPopup().hasAttribute('data-loading');
};

// Detect Node env
var isNodeEnv = function isNodeEnv() {
  return typeof window === 'undefined' || typeof document === 'undefined';
};

var sweetHTML = ('\n <div aria-labelledby="' + swalClasses.title + '" aria-describedby="' + swalClasses.content + '" class="' + swalClasses.popup + '" tabindex="-1">\n   <div class="' + swalClasses.header + '">\n     <ul class="' + swalClasses.progresssteps + '"></ul>\n     <div class="' + swalClasses.icon + ' ' + iconTypes.error + '">\n       <span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span>\n     </div>\n     <div class="' + swalClasses.icon + ' ' + iconTypes.question + '">\n       <span class="' + swalClasses['icon-text'] + '">?</span>\n      </div>\n     <div class="' + swalClasses.icon + ' ' + iconTypes.warning + '">\n       <span class="' + swalClasses['icon-text'] + '">!</span>\n      </div>\n     <div class="' + swalClasses.icon + ' ' + iconTypes.info + '">\n       <span class="' + swalClasses['icon-text'] + '">i</span>\n      </div>\n     <div class="' + swalClasses.icon + ' ' + iconTypes.success + '">\n       <div class="swal2-success-circular-line-left"></div>\n       <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>\n       <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>\n       <div class="swal2-success-circular-line-right"></div>\n     </div>\n     <img class="' + swalClasses.image + '" />\n     <h2 class="' + swalClasses.title + '" id="' + swalClasses.title + '"></h2>\n     <button type="button" class="' + swalClasses.close + '">\xD7</button>\n   </div>\n   <div class="' + swalClasses.content + '">\n     <div id="' + swalClasses.content + '"></div>\n     <input class="' + swalClasses.input + '" />\n     <input type="file" class="' + swalClasses.file + '" />\n     <div class="' + swalClasses.range + '">\n       <input type="range" />\n       <output></output>\n     </div>\n     <select class="' + swalClasses.select + '"></select>\n     <div class="' + swalClasses.radio + '"></div>\n     <label for="' + swalClasses.checkbox + '" class="' + swalClasses.checkbox + '">\n       <input type="checkbox" />\n       <span class="' + swalClasses.label + '"></span>\n     </label>\n     <textarea class="' + swalClasses.textarea + '"></textarea>\n     <div class="' + swalClasses.validationerror + '" id="' + swalClasses.validationerror + '"></div>\n   </div>\n   <div class="' + swalClasses.actions + '">\n     <button type="button" class="' + swalClasses.confirm + '">OK</button>\n     <button type="button" class="' + swalClasses.cancel + '">Cancel</button>\n   </div>\n   <div class="' + swalClasses.footer + '">\n   </div>\n </div>\n').replace(/(^|\n)\s*/g, '');

/*
 * Add modal + backdrop to DOM
 */
var init = function init(params) {
  // Clean up the old popup if it exists
  var c = getContainer();
  if (c) {
    c.parentNode.removeChild(c);
    removeClass([document.documentElement, document.body], [swalClasses['no-backdrop'], swalClasses['toast-shown'], swalClasses['has-column']]);
  }

  if (isNodeEnv()) {
    error('SweetAlert2 requires document to initialize');
    return;
  }

  var container = document.createElement('div');
  container.className = swalClasses.container;
  container.innerHTML = sweetHTML;

  var targetElement = typeof params.target === 'string' ? document.querySelector(params.target) : params.target;
  targetElement.appendChild(container);

  var popup = getPopup();
  var content = getContent();
  var input = getChildByClass(content, swalClasses.input);
  var file = getChildByClass(content, swalClasses.file);
  var range = content.querySelector('.' + swalClasses.range + ' input');
  var rangeOutput = content.querySelector('.' + swalClasses.range + ' output');
  var select = getChildByClass(content, swalClasses.select);
  var checkbox = content.querySelector('.' + swalClasses.checkbox + ' input');
  var textarea = getChildByClass(content, swalClasses.textarea);

  // a11y
  popup.setAttribute('role', params.toast ? 'alert' : 'dialog');
  popup.setAttribute('aria-live', params.toast ? 'polite' : 'assertive');
  if (!params.toast) {
    popup.setAttribute('aria-modal', 'true');
  }

  var oldInputVal = void 0; // IE11 workaround, see #1109 for details
  var resetValidationError = function resetValidationError(e) {
    if (Swal.isVisible() && oldInputVal !== e.target.value) {
      Swal.resetValidationError();
    }
    oldInputVal = e.target.value;
  };

  input.oninput = resetValidationError;
  file.onchange = resetValidationError;
  select.onchange = resetValidationError;
  checkbox.onchange = resetValidationError;
  textarea.oninput = resetValidationError;

  range.oninput = function (e) {
    resetValidationError(e);
    rangeOutput.value = range.value;
  };

  range.onchange = function (e) {
    resetValidationError(e);
    range.nextSibling.value = range.value;
  };

  return popup;
};

var parseHtmlToContainer = function parseHtmlToContainer(param, target) {
  if (!param) {
    return hide(target);
  }

  if ((typeof param === 'undefined' ? 'undefined' : _typeof(param)) === 'object') {
    target.innerHTML = '';
    if (0 in param) {
      for (var i = 0; i in param; i++) {
        target.appendChild(param[i].cloneNode(true));
      }
    } else {
      target.appendChild(param.cloneNode(true));
    }
  } else if (param) {
    target.innerHTML = param;
  }
  show(target);
};

var animationEndEvent = function () {
  // Prevent run in Node env
  if (isNodeEnv()) {
    return false;
  }

  var testEl = document.createElement('div');
  var transEndEventNames = {
    'WebkitAnimation': 'webkitAnimationEnd',
    'OAnimation': 'oAnimationEnd oanimationend',
    'animation': 'animationend'
  };
  for (var i in transEndEventNames) {
    if (transEndEventNames.hasOwnProperty(i) && typeof testEl.style[i] !== 'undefined') {
      return transEndEventNames[i];
    }
  }

  return false;
}();

// Measure width of scrollbar
// https://github.com/twbs/bootstrap/blob/master/js/modal.js#L279-L286
var measureScrollbar = function measureScrollbar() {
  var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
  if (supportsTouch) {
    return 0;
  }
  var scrollDiv = document.createElement('div');
  scrollDiv.style.width = '50px';
  scrollDiv.style.height = '50px';
  scrollDiv.style.overflow = 'scroll';
  document.body.appendChild(scrollDiv);
  var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
  document.body.removeChild(scrollDiv);
  return scrollbarWidth;
};

var renderActions = function renderActions(params) {
  var actions = getActions();
  var confirmButton = getConfirmButton();
  var cancelButton = getCancelButton();

  // Actions (buttons) wrapper
  if (!params.showConfirmButton && !params.showCancelButton) {
    hide(actions);
  } else {
    show(actions);
  }

  // Cancel button
  if (params.showCancelButton) {
    cancelButton.style.display = 'inline-block';
  } else {
    hide(cancelButton);
  }

  // Confirm button
  if (params.showConfirmButton) {
    removeStyleProperty(confirmButton, 'display');
  } else {
    hide(confirmButton);
  }

  // Edit text on confirm and cancel buttons
  confirmButton.innerHTML = params.confirmButtonText;
  cancelButton.innerHTML = params.cancelButtonText;

  // ARIA labels for confirm and cancel buttons
  confirmButton.setAttribute('aria-label', params.confirmButtonAriaLabel);
  cancelButton.setAttribute('aria-label', params.cancelButtonAriaLabel);

  // Add buttons custom classes
  confirmButton.className = swalClasses.confirm;
  addClass(confirmButton, params.confirmButtonClass);
  cancelButton.className = swalClasses.cancel;
  addClass(cancelButton, params.cancelButtonClass);

  // Buttons styling
  if (params.buttonsStyling) {
    addClass([confirmButton, cancelButton], swalClasses.styled);

    // Buttons background colors
    if (params.confirmButtonColor) {
      confirmButton.style.backgroundColor = params.confirmButtonColor;
    }
    if (params.cancelButtonColor) {
      cancelButton.style.backgroundColor = params.cancelButtonColor;
    }

    // Loading state
    var confirmButtonBackgroundColor = window.getComputedStyle(confirmButton).getPropertyValue('background-color');
    confirmButton.style.borderLeftColor = confirmButtonBackgroundColor;
    confirmButton.style.borderRightColor = confirmButtonBackgroundColor;
  } else {
    removeClass([confirmButton, cancelButton], swalClasses.styled);

    confirmButton.style.backgroundColor = confirmButton.style.borderLeftColor = confirmButton.style.borderRightColor = '';
    cancelButton.style.backgroundColor = cancelButton.style.borderLeftColor = cancelButton.style.borderRightColor = '';
  }
};

var renderContent = function renderContent(params) {
  var content = getContent().querySelector('#' + swalClasses.content);

  // Content as HTML
  if (params.html) {
    parseHtmlToContainer(params.html, content);

    // Content as plain text
  } else if (params.text) {
    content.textContent = params.text;
    show(content);
  } else {
    hide(content);
  }
};

var renderIcon = function renderIcon(params) {
  var icons = getIcons();
  for (var i = 0; i < icons.length; i++) {
    hide(icons[i]);
  }
  if (params.type) {
    if (Object.keys(iconTypes).indexOf(params.type) !== -1) {
      var icon = Swal.getPopup().querySelector('.' + swalClasses.icon + '.' + iconTypes[params.type]);
      show(icon);

      // Animate icon
      if (params.animation) {
        addClass(icon, 'swal2-animate-' + params.type + '-icon');
      }
    } else {
      error('Unknown type! Expected "success", "error", "warning", "info" or "question", got "' + params.type + '"');
    }
  }
};

var renderImage = function renderImage(params) {
  var image = getImage();

  if (params.imageUrl) {
    image.setAttribute('src', params.imageUrl);
    image.setAttribute('alt', params.imageAlt);
    show(image);

    if (params.imageWidth) {
      image.setAttribute('width', params.imageWidth);
    } else {
      image.removeAttribute('width');
    }

    if (params.imageHeight) {
      image.setAttribute('height', params.imageHeight);
    } else {
      image.removeAttribute('height');
    }

    image.className = swalClasses.image;
    if (params.imageClass) {
      addClass(image, params.imageClass);
    }
  } else {
    hide(image);
  }
};

var renderProgressSteps = function renderProgressSteps(params) {
  var progressStepsContainer = getProgressSteps();
  var currentProgressStep = parseInt(params.currentProgressStep === null ? Swal.getQueueStep() : params.currentProgressStep, 10);
  if (params.progressSteps && params.progressSteps.length) {
    show(progressStepsContainer);
    progressStepsContainer.innerHTML = '';
    if (currentProgressStep >= params.progressSteps.length) {
      warn('Invalid currentProgressStep parameter, it should be less than progressSteps.length ' + '(currentProgressStep like JS arrays starts from 0)');
    }
    params.progressSteps.forEach(function (step, index) {
      var circle = document.createElement('li');
      addClass(circle, swalClasses.progresscircle);
      circle.innerHTML = step;
      if (index === currentProgressStep) {
        addClass(circle, swalClasses.activeprogressstep);
      }
      progressStepsContainer.appendChild(circle);
      if (index !== params.progressSteps.length - 1) {
        var line = document.createElement('li');
        addClass(line, swalClasses.progressline);
        if (params.progressStepsDistance) {
          line.style.width = params.progressStepsDistance;
        }
        progressStepsContainer.appendChild(line);
      }
    });
  } else {
    hide(progressStepsContainer);
  }
};

var renderTitle = function renderTitle(params) {
  var title = getTitle();

  if (params.titleText) {
    title.innerText = params.titleText;
  } else if (params.title) {
    if (typeof params.title === 'string') {
      params.title = params.title.split('\n').join('<br />');
    }
    parseHtmlToContainer(params.title, title);
  }
};

var fixScrollbar = function fixScrollbar() {
  // for queues, do not do this more than once
  if (states.previousBodyPadding !== null) {
    return;
  }
  // if the body has overflow
  if (document.body.scrollHeight > window.innerHeight) {
    // add padding so the content doesn't shift after removal of scrollbar
    states.previousBodyPadding = parseInt(window.getComputedStyle(document.body).getPropertyValue('padding-right'));
    document.body.style.paddingRight = states.previousBodyPadding + measureScrollbar() + 'px';
  }
};

var undoScrollbar = function undoScrollbar() {
  if (states.previousBodyPadding !== null) {
    document.body.style.paddingRight = states.previousBodyPadding;
    states.previousBodyPadding = null;
  }
};

// Fix iOS scrolling http://stackoverflow.com/q/39626302/1331425

/* istanbul ignore next */
var iOSfix = function iOSfix() {
  var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  if (iOS && !hasClass(document.body, swalClasses.iosfix)) {
    var offset = document.body.scrollTop;
    document.body.style.top = offset * -1 + 'px';
    addClass(document.body, swalClasses.iosfix);
  }
};

/* istanbul ignore next */
var undoIOSfix = function undoIOSfix() {
  if (hasClass(document.body, swalClasses.iosfix)) {
    var offset = parseInt(document.body.style.top, 10);
    removeClass(document.body, swalClasses.iosfix);
    document.body.style.top = '';
    document.body.scrollTop = offset * -1;
  }
};

// From https://developer.paciellogroup.com/blog/2018/06/the-current-state-of-modal-dialog-accessibility/
// Adding aria-hidden="true" to elements outside of the active modal dialog ensures that
// elements not within the active modal dialog will not be surfaced if a user opens a screen
// reader’s list of elements (headings, form controls, landmarks, etc.) in the document.

var setAriaHidden = function setAriaHidden() {
  var bodyChildren = toArray$1(document.body.children);
  bodyChildren.forEach(function (el) {
    if (el === getContainer() || el.contains(getContainer())) {
      return;
    }

    if (el.hasAttribute('aria-hidden')) {
      el.setAttribute('data-previous-aria-hidden', el.getAttribute('aria-hidden'));
    }
    el.setAttribute('aria-hidden', 'true');
  });
};

var unsetAriaHidden = function unsetAriaHidden() {
  var bodyChildren = toArray$1(document.body.children);
  bodyChildren.forEach(function (el) {
    if (el.hasAttribute('data-previous-aria-hidden')) {
      el.setAttribute('aria-hidden', el.getAttribute('data-previous-aria-hidden'));
      el.removeAttribute('data-previous-aria-hidden');
    } else {
      el.removeAttribute('aria-hidden');
    }
  });
};

var RESTORE_FOCUS_TIMEOUT = 100;

var globalState = {};

// Restore previous active (focused) element
var restoreActiveElement = function restoreActiveElement() {
  var x = window.scrollX;
  var y = window.scrollY;
  globalState.restoreFocusTimeout = setTimeout(function () {
    if (globalState.previousActiveElement && globalState.previousActiveElement.focus) {
      globalState.previousActiveElement.focus();
      globalState.previousActiveElement = null;
    } else if (document.body) {
      document.body.focus();
    }
  }, RESTORE_FOCUS_TIMEOUT); // issues/900
  if (typeof x !== 'undefined' && typeof y !== 'undefined') {
    // IE doesn't have scrollX/scrollY support
    window.scrollTo(x, y);
  }
};

/*
 * Global function to close sweetAlert
 */
var close = function close(onClose, onAfterClose) {
  var container = getContainer();
  var popup = getPopup();
  if (!popup) {
    return;
  }

  if (onClose !== null && typeof onClose === 'function') {
    onClose(popup);
  }

  removeClass(popup, swalClasses.show);
  addClass(popup, swalClasses.hide);

  var removePopupAndResetState = function removePopupAndResetState() {
    if (!isToast()) {
      restoreActiveElement();
      globalState.keydownTarget.removeEventListener('keydown', globalState.keydownHandler, { capture: globalState.keydownListenerCapture });
      globalState.keydownHandlerAdded = false;
    }

    if (container.parentNode) {
      container.parentNode.removeChild(container);
    }
    removeClass([document.documentElement, document.body], [swalClasses.shown, swalClasses['height-auto'], swalClasses['no-backdrop'], swalClasses['toast-shown'], swalClasses['toast-column']]);

    if (isModal()) {
      undoScrollbar();
      undoIOSfix();
      unsetAriaHidden();
    }

    if (onAfterClose !== null && typeof onAfterClose === 'function') {
      setTimeout(function () {
        onAfterClose();
      });
    }
  };

  // If animation is supported, animate
  if (animationEndEvent && !hasClass(popup, swalClasses.noanimation)) {
    popup.addEventListener(animationEndEvent, function swalCloseEventFinished() {
      popup.removeEventListener(animationEndEvent, swalCloseEventFinished);
      if (hasClass(popup, swalClasses.hide)) {
        removePopupAndResetState();
      }
    });
  } else {
    // Otherwise, remove immediately
    removePopupAndResetState();
  }
};

/*
 * Global function to determine if swal2 popup is shown
 */
var isVisible$1 = function isVisible() {
  return !!getPopup();
};

/*
 * Global function to click 'Confirm' button
 */
var clickConfirm = function clickConfirm() {
  return getConfirmButton().click();
};

/*
 * Global function to click 'Cancel' button
 */
var clickCancel = function clickCancel() {
  return getCancelButton().click();
};

function fire() {
  var Swal = this;

  for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
    args[_key] = arguments[_key];
  }

  return new (Function.prototype.bind.apply(Swal, [null].concat(args)))();
}

/**
 * Extends a Swal class making it able to be instantiated without the `new` keyword (and thus without `Swal.fire`)
 * @param ParentSwal
 * @returns {NoNewKeywordSwal}
 */
function withNoNewKeyword(ParentSwal) {
  var NoNewKeywordSwal = function NoNewKeywordSwal() {
    for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    if (!(this instanceof NoNewKeywordSwal)) {
      return new (Function.prototype.bind.apply(NoNewKeywordSwal, [null].concat(args)))();
    }
    Object.getPrototypeOf(NoNewKeywordSwal).apply(this, args);
  };
  NoNewKeywordSwal.prototype = _extends(Object.create(ParentSwal.prototype), { constructor: NoNewKeywordSwal });

  if (typeof Object.setPrototypeOf === 'function') {
    Object.setPrototypeOf(NoNewKeywordSwal, ParentSwal);
  } else {
    // Android 4.4
    // eslint-disable-next-line
    NoNewKeywordSwal.__proto__ = ParentSwal;
  }
  return NoNewKeywordSwal;
}

var defaultParams = {
  title: '',
  titleText: '',
  text: '',
  html: '',
  footer: '',
  type: null,
  toast: false,
  customClass: '',
  target: 'body',
  backdrop: true,
  animation: true,
  heightAuto: true,
  allowOutsideClick: true,
  allowEscapeKey: true,
  allowEnterKey: true,
  stopKeydownPropagation: true,
  keydownListenerCapture: false,
  showConfirmButton: true,
  showCancelButton: false,
  preConfirm: null,
  confirmButtonText: 'OK',
  confirmButtonAriaLabel: '',
  confirmButtonColor: null,
  confirmButtonClass: null,
  cancelButtonText: 'Cancel',
  cancelButtonAriaLabel: '',
  cancelButtonColor: null,
  cancelButtonClass: null,
  buttonsStyling: true,
  reverseButtons: false,
  focusConfirm: true,
  focusCancel: false,
  showCloseButton: false,
  closeButtonAriaLabel: 'Close this dialog',
  showLoaderOnConfirm: false,
  imageUrl: null,
  imageWidth: null,
  imageHeight: null,
  imageAlt: '',
  imageClass: null,
  timer: null,
  width: null,
  padding: null,
  background: null,
  input: null,
  inputPlaceholder: '',
  inputValue: '',
  inputOptions: {},
  inputAutoTrim: true,
  inputClass: null,
  inputAttributes: {},
  inputValidator: null,
  grow: false,
  position: 'center',
  progressSteps: [],
  currentProgressStep: null,
  progressStepsDistance: null,
  onBeforeOpen: null,
  onAfterClose: null,
  onOpen: null,
  onClose: null,
  useRejections: false,
  expectRejections: false
};

var deprecatedParams = ['useRejections', 'expectRejections'];

/**
 * Is valid parameter
 * @param {String} paramName
 */
var isValidParameter = function isValidParameter(paramName) {
  return defaultParams.hasOwnProperty(paramName) || paramName === 'extraParams';
};

/**
 * Is valid parameter for toasts
 * @param {String} paramName
 */
var isValidToastParameter = function isValidToastParameter(paramName) {
  var incompatibleParams = ['allowOutsideClick', 'allowEnterKey', 'backdrop', 'focusConfirm', 'focusCancel', 'heightAuto', 'keydownListenerCapture'];
  return incompatibleParams.indexOf(paramName) === -1;
};

/**
 * Is deprecated parameter
 * @param {String} paramName
 */
var isDeprecatedParameter = function isDeprecatedParameter(paramName) {
  return deprecatedParams.indexOf(paramName) !== -1;
};

/**
 * Show relevant warnings for given params
 *
 * @param params
 */
var showWarningsForParams = function showWarningsForParams(params) {
  for (var param in params) {
    if (!isValidParameter(param)) {
      warn('Unknown parameter "' + param + '"');
    }
    if (params.toast && !isValidToastParameter(param)) {
      warn('The parameter "' + param + '" is incompatible with toasts');
    }
    if (isDeprecatedParameter(param)) {
      warnOnce('The parameter "' + param + '" is deprecated and will be removed in the next major release.');
    }
  }
};

var deprecationWarning = '"setDefaults" & "resetDefaults" methods are deprecated in favor of "mixin" method and will be removed in the next major release. For new projects, use "mixin". For past projects already using "setDefaults", support will be provided through an additional package.';
var defaults$1 = {};

function withGlobalDefaults(ParentSwal) {
  var SwalWithGlobalDefaults = function (_ParentSwal) {
    inherits(SwalWithGlobalDefaults, _ParentSwal);

    function SwalWithGlobalDefaults() {
      classCallCheck(this, SwalWithGlobalDefaults);
      return possibleConstructorReturn(this, (SwalWithGlobalDefaults.__proto__ || Object.getPrototypeOf(SwalWithGlobalDefaults)).apply(this, arguments));
    }

    createClass(SwalWithGlobalDefaults, [{
      key: '_main',
      value: function _main(params) {
        return get(SwalWithGlobalDefaults.prototype.__proto__ || Object.getPrototypeOf(SwalWithGlobalDefaults.prototype), '_main', this).call(this, _extends({}, defaults$1, params));
      }
    }], [{
      key: 'setDefaults',
      value: function setDefaults(params) {
        warnOnce(deprecationWarning);
        if (!params || (typeof params === 'undefined' ? 'undefined' : _typeof(params)) !== 'object') {
          throw new TypeError('SweetAlert2: The argument for setDefaults() is required and has to be a object');
        }
        showWarningsForParams(params);
        // assign valid params from `params` to `defaults`
        Object.keys(params).forEach(function (param) {
          if (ParentSwal.isValidParameter(param)) {
            defaults$1[param] = params[param];
          }
        });
      }
    }, {
      key: 'resetDefaults',
      value: function resetDefaults() {
        warnOnce(deprecationWarning);
        defaults$1 = {};
      }
    }]);
    return SwalWithGlobalDefaults;
  }(ParentSwal);

  // Set default params if `window._swalDefaults` is an object


  if (typeof window !== 'undefined' && _typeof(window._swalDefaults) === 'object') {
    SwalWithGlobalDefaults.setDefaults(window._swalDefaults);
  }

  return SwalWithGlobalDefaults;
}

/**
 * Returns an extended version of `Swal` containing `params` as defaults.
 * Useful for reusing Swal configuration.
 *
 * For example:
 *
 * Before:
 * const textPromptOptions = { input: 'text', showCancelButton: true }
 * const {value: firstName} = await Swal({ ...textPromptOptions, title: 'What is your first name?' })
 * const {value: lastName} = await Swal({ ...textPromptOptions, title: 'What is your last name?' })
 *
 * After:
 * const TextPrompt = Swal.mixin({ input: 'text', showCancelButton: true })
 * const {value: firstName} = await TextPrompt('What is your first name?')
 * const {value: lastName} = await TextPrompt('What is your last name?')
 *
 * @param mixinParams
 */
function mixin(mixinParams) {
  return withNoNewKeyword(function (_ref) {
    inherits(MixinSwal, _ref);

    function MixinSwal() {
      classCallCheck(this, MixinSwal);
      return possibleConstructorReturn(this, (MixinSwal.__proto__ || Object.getPrototypeOf(MixinSwal)).apply(this, arguments));
    }

    createClass(MixinSwal, [{
      key: '_main',
      value: function _main(params) {
        return get(MixinSwal.prototype.__proto__ || Object.getPrototypeOf(MixinSwal.prototype), '_main', this).call(this, _extends({}, mixinParams, params));
      }
    }]);
    return MixinSwal;
  }(this));
}

// private global state for the queue feature
var currentSteps = [];

/*
 * Global function for chaining sweetAlert popups
 */
var queue = function queue(steps) {
  var swal = this;
  currentSteps = steps;
  var resetQueue = function resetQueue() {
    currentSteps = [];
    document.body.removeAttribute('data-swal2-queue-step');
  };
  var queueResult = [];
  return new Promise(function (resolve) {
    (function step(i, callback) {
      if (i < currentSteps.length) {
        document.body.setAttribute('data-swal2-queue-step', i);

        swal(currentSteps[i]).then(function (result) {
          if (typeof result.value !== 'undefined') {
            queueResult.push(result.value);
            step(i + 1, callback);
          } else {
            resetQueue();
            resolve({ dismiss: result.dismiss });
          }
        });
      } else {
        resetQueue();
        resolve({ value: queueResult });
      }
    })(0);
  });
};

/*
 * Global function for getting the index of current popup in queue
 */
var getQueueStep = function getQueueStep() {
  return document.body.getAttribute('data-swal2-queue-step');
};

/*
 * Global function for inserting a popup to the queue
 */
var insertQueueStep = function insertQueueStep(step, index) {
  if (index && index < currentSteps.length) {
    return currentSteps.splice(index, 0, step);
  }
  return currentSteps.push(step);
};

/*
 * Global function for deleting a popup from the queue
 */
var deleteQueueStep = function deleteQueueStep(index) {
  if (typeof currentSteps[index] !== 'undefined') {
    currentSteps.splice(index, 1);
  }
};

/**
 * Show spinner instead of Confirm button and disable Cancel button
 */
var showLoading = function showLoading() {
  var popup = getPopup();
  if (!popup) {
    Swal('');
  }
  popup = getPopup();
  var actions = getActions();
  var confirmButton = getConfirmButton();
  var cancelButton = getCancelButton();

  show(actions);
  show(confirmButton);
  addClass([popup, actions], swalClasses.loading);
  confirmButton.disabled = true;
  cancelButton.disabled = true;

  popup.setAttribute('data-loading', true);
  popup.setAttribute('aria-busy', true);
  popup.focus();
};

/**
 * If `timer` parameter is set, returns number os milliseconds of timer remained.
 * Otherwise, returns null.
 */
var getTimerLeft = function getTimerLeft() {
  return globalState.timeout && globalState.timeout.getTimerLeft();
};



var staticMethods = Object.freeze({
	isValidParameter: isValidParameter,
	isDeprecatedParameter: isDeprecatedParameter,
	argsToParams: argsToParams,
	adaptInputValidator: adaptInputValidator,
	close: close,
	closePopup: close,
	closeModal: close,
	closeToast: close,
	isVisible: isVisible$1,
	clickConfirm: clickConfirm,
	clickCancel: clickCancel,
	getContainer: getContainer,
	getPopup: getPopup,
	getTitle: getTitle,
	getContent: getContent,
	getImage: getImage,
	getIcons: getIcons,
	getCloseButton: getCloseButton,
	getButtonsWrapper: getButtonsWrapper,
	getActions: getActions,
	getConfirmButton: getConfirmButton,
	getCancelButton: getCancelButton,
	getFooter: getFooter,
	getFocusableElements: getFocusableElements,
	isLoading: isLoading,
	fire: fire,
	mixin: mixin,
	queue: queue,
	getQueueStep: getQueueStep,
	insertQueueStep: insertQueueStep,
	deleteQueueStep: deleteQueueStep,
	showLoading: showLoading,
	enableLoading: showLoading,
	getTimerLeft: getTimerLeft
});

// https://github.com/Riim/symbol-polyfill/blob/master/index.js

/* istanbul ignore next */
var _Symbol = typeof Symbol === 'function' ? Symbol : function () {
  var idCounter = 0;
  function _Symbol(key) {
    return '__' + key + '_' + Math.floor(Math.random() * 1e9) + '_' + ++idCounter + '__';
  }
  _Symbol.iterator = _Symbol('Symbol.iterator');
  return _Symbol;
}();

// WeakMap polyfill, needed for Android 4.4
// Related issue: https://github.com/sweetalert2/sweetalert2/issues/1071
// http://webreflection.blogspot.fi/2015/04/a-weakmap-polyfill-in-20-lines-of-code.html

/* istanbul ignore next */
var WeakMap$1 = typeof WeakMap === 'function' ? WeakMap : function (s, dP, hOP) {
  function WeakMap() {
    dP(this, s, { value: _Symbol('WeakMap') });
  }
  WeakMap.prototype = {
    'delete': function del(o) {
      delete o[this[s]];
    },
    get: function get(o) {
      return o[this[s]];
    },
    has: function has(o) {
      return hOP.call(o, this[s]);
    },
    set: function set(o, v) {
      dP(o, this[s], { configurable: true, value: v });
    }
  };
  return WeakMap;
}(_Symbol('WeakMap'), Object.defineProperty, {}.hasOwnProperty);

/**
 * This module containts `WeakMap`s for each effectively-"private  property" that a `swal` has.
 * For example, to set the private property "foo" of `this` to "bar", you can `privateProps.foo.set(this, 'bar')`
 * This is the approach that Babel will probably take to implement private methods/fields
 *   https://github.com/tc39/proposal-private-methods
 *   https://github.com/babel/babel/pull/7555
 * Once we have the changes from that PR in Babel, and our core class fits reasonable in *one module*
 *   then we can use that language feature.
 */

var privateProps = {
  promise: new WeakMap$1(),
  innerParams: new WeakMap$1(),
  domCache: new WeakMap$1()
};

/**
 * Enables buttons and hide loader.
 */
function hideLoading() {
  var innerParams = privateProps.innerParams.get(this);
  var domCache = privateProps.domCache.get(this);
  if (!innerParams.showConfirmButton) {
    hide(domCache.confirmButton);
    if (!innerParams.showCancelButton) {
      hide(domCache.actions);
    }
  }
  removeClass([domCache.popup, domCache.actions], swalClasses.loading);
  domCache.popup.removeAttribute('aria-busy');
  domCache.popup.removeAttribute('data-loading');
  domCache.confirmButton.disabled = false;
  domCache.cancelButton.disabled = false;
}

// Get input element by specified type or, if type isn't specified, by params.input
function getInput(inputType) {
  var innerParams = privateProps.innerParams.get(this);
  var domCache = privateProps.domCache.get(this);
  inputType = inputType || innerParams.input;
  if (!inputType) {
    return null;
  }
  switch (inputType) {
    case 'select':
    case 'textarea':
    case 'file':
      return getChildByClass(domCache.content, swalClasses[inputType]);
    case 'checkbox':
      return domCache.popup.querySelector('.' + swalClasses.checkbox + ' input');
    case 'radio':
      return domCache.popup.querySelector('.' + swalClasses.radio + ' input:checked') || domCache.popup.querySelector('.' + swalClasses.radio + ' input:first-child');
    case 'range':
      return domCache.popup.querySelector('.' + swalClasses.range + ' input');
    default:
      return getChildByClass(domCache.content, swalClasses.input);
  }
}

function enableButtons() {
  var domCache = privateProps.domCache.get(this);
  domCache.confirmButton.disabled = false;
  domCache.cancelButton.disabled = false;
}

function disableButtons() {
  var domCache = privateProps.domCache.get(this);
  domCache.confirmButton.disabled = true;
  domCache.cancelButton.disabled = true;
}

function enableConfirmButton() {
  var domCache = privateProps.domCache.get(this);
  domCache.confirmButton.disabled = false;
}

function disableConfirmButton() {
  var domCache = privateProps.domCache.get(this);
  domCache.confirmButton.disabled = true;
}

function enableInput() {
  var input = this.getInput();
  if (!input) {
    return false;
  }
  if (input.type === 'radio') {
    var radiosContainer = input.parentNode.parentNode;
    var radios = radiosContainer.querySelectorAll('input');
    for (var i = 0; i < radios.length; i++) {
      radios[i].disabled = false;
    }
  } else {
    input.disabled = false;
  }
}

function disableInput() {
  var input = this.getInput();
  if (!input) {
    return false;
  }
  if (input && input.type === 'radio') {
    var radiosContainer = input.parentNode.parentNode;
    var radios = radiosContainer.querySelectorAll('input');
    for (var i = 0; i < radios.length; i++) {
      radios[i].disabled = true;
    }
  } else {
    input.disabled = true;
  }
}

// Show block with validation error
function showValidationError(error) {
  var domCache = privateProps.domCache.get(this);
  domCache.validationError.innerHTML = error;
  var popupComputedStyle = window.getComputedStyle(domCache.popup);
  domCache.validationError.style.marginLeft = '-' + popupComputedStyle.getPropertyValue('padding-left');
  domCache.validationError.style.marginRight = '-' + popupComputedStyle.getPropertyValue('padding-right');
  show(domCache.validationError);

  var input = this.getInput();
  if (input) {
    input.setAttribute('aria-invalid', true);
    input.setAttribute('aria-describedBy', swalClasses.validationerror);
    focusInput(input);
    addClass(input, swalClasses.inputerror);
  }
}

// Hide block with validation error
function resetValidationError() {
  var domCache = privateProps.domCache.get(this);
  if (domCache.validationError) {
    hide(domCache.validationError);
  }

  var input = this.getInput();
  if (input) {
    input.removeAttribute('aria-invalid');
    input.removeAttribute('aria-describedBy');
    removeClass(input, swalClasses.inputerror);
  }
}

function getProgressSteps$1() {
  var innerParams = privateProps.innerParams.get(this);
  return innerParams.progressSteps;
}

function setProgressSteps(progressSteps) {
  var innerParams = privateProps.innerParams.get(this);
  var updatedParams = _extends({}, innerParams, { progressSteps: progressSteps });
  privateProps.innerParams.set(this, updatedParams);
  renderProgressSteps(updatedParams);
}

function showProgressSteps() {
  var domCache = privateProps.domCache.get(this);
  show(domCache.progressSteps);
}

function hideProgressSteps() {
  var domCache = privateProps.domCache.get(this);
  hide(domCache.progressSteps);
}

var Timer = function Timer(callback, delay) {
  classCallCheck(this, Timer);

  var id = void 0,
      started = void 0,
      running = void 0;
  var remaining = delay;
  this.start = function () {
    running = true;
    started = new Date();
    id = setTimeout(callback, remaining);
  };
  this.stop = function () {
    running = false;
    clearTimeout(id);
    remaining -= new Date() - started;
  };
  this.getTimerLeft = function () {
    if (running) {
      this.stop();
      this.start();
    }
    return remaining;
  };
  this.start();
};

var defaultInputValidators = {
  email: function email(string, extraParams) {
    return (/^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,24}$/.test(string) ? Promise.resolve() : Promise.reject(extraParams && extraParams.validationMessage ? extraParams.validationMessage : 'Invalid email address')
    );
  },
  url: function url(string, extraParams) {
    // taken from https://stackoverflow.com/a/3809435/1331425
    return (/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/.test(string) ? Promise.resolve() : Promise.reject(extraParams && extraParams.validationMessage ? extraParams.validationMessage : 'Invalid URL')
    );
  }
};

/**
 * Set type, text and actions on popup
 *
 * @param params
 * @returns {boolean}
 */
function setParameters(params) {
  // Use default `inputValidator` for supported input types if not provided
  if (!params.inputValidator) {
    Object.keys(defaultInputValidators).forEach(function (key) {
      if (params.input === key) {
        params.inputValidator = params.expectRejections ? defaultInputValidators[key] : Swal.adaptInputValidator(defaultInputValidators[key]);
      }
    });
  }

  // Determine if the custom target element is valid
  if (!params.target || typeof params.target === 'string' && !document.querySelector(params.target) || typeof params.target !== 'string' && !params.target.appendChild) {
    warn('Target parameter is not valid, defaulting to "body"');
    params.target = 'body';
  }

  var popup = void 0;
  var oldPopup = getPopup();
  var targetElement = typeof params.target === 'string' ? document.querySelector(params.target) : params.target;
  // If the model target has changed, refresh the popup
  if (oldPopup && targetElement && oldPopup.parentNode !== targetElement.parentNode) {
    popup = init(params);
  } else {
    popup = oldPopup || init(params);
  }

  // Set popup width
  if (params.width) {
    popup.style.width = typeof params.width === 'number' ? params.width + 'px' : params.width;
  }

  // Set popup padding
  if (params.padding) {
    popup.style.padding = typeof params.padding === 'number' ? params.padding + 'px' : params.padding;
  }

  // Set popup background
  if (params.background) {
    popup.style.background = params.background;
  }
  var popupBackgroundColor = window.getComputedStyle(popup).getPropertyValue('background-color');
  var successIconParts = popup.querySelectorAll('[class^=swal2-success-circular-line], .swal2-success-fix');
  for (var i = 0; i < successIconParts.length; i++) {
    successIconParts[i].style.backgroundColor = popupBackgroundColor;
  }

  var container = getContainer();
  var closeButton = getCloseButton();
  var footer = getFooter();

  // Title
  renderTitle(params);

  // Content
  renderContent(params);

  // Backdrop
  if (typeof params.backdrop === 'string') {
    getContainer().style.background = params.backdrop;
  } else if (!params.backdrop) {
    addClass([document.documentElement, document.body], swalClasses['no-backdrop']);
  }
  if (!params.backdrop && params.allowOutsideClick) {
    warn('"allowOutsideClick" parameter requires `backdrop` parameter to be set to `true`');
  }

  // Position
  if (params.position in swalClasses) {
    addClass(container, swalClasses[params.position]);
  } else {
    warn('The "position" parameter is not valid, defaulting to "center"');
    addClass(container, swalClasses.center);
  }

  // Grow
  if (params.grow && typeof params.grow === 'string') {
    var growClass = 'grow-' + params.grow;
    if (growClass in swalClasses) {
      addClass(container, swalClasses[growClass]);
    }
  }

  // Animation
  if (typeof params.animation === 'function') {
    params.animation = params.animation.call();
  }

  // Close button
  if (params.showCloseButton) {
    closeButton.setAttribute('aria-label', params.closeButtonAriaLabel);
    show(closeButton);
  } else {
    hide(closeButton);
  }

  // Default Class
  popup.className = swalClasses.popup;
  if (params.toast) {
    addClass([document.documentElement, document.body], swalClasses['toast-shown']);
    addClass(popup, swalClasses.toast);
  } else {
    addClass(popup, swalClasses.modal);
  }

  // Custom Class
  if (params.customClass) {
    addClass(popup, params.customClass);
  }

  // Progress steps
  renderProgressSteps(params);

  // Icon
  renderIcon(params);

  // Image
  renderImage(params);

  // Actions (buttons)
  renderActions(params);

  // Footer
  parseHtmlToContainer(params.footer, footer);

  // CSS animation
  if (params.animation === true) {
    removeClass(popup, swalClasses.noanimation);
  } else {
    addClass(popup, swalClasses.noanimation);
  }

  // showLoaderOnConfirm && preConfirm
  if (params.showLoaderOnConfirm && !params.preConfirm) {
    warn('showLoaderOnConfirm is set to true, but preConfirm is not defined.\n' + 'showLoaderOnConfirm should be used together with preConfirm, see usage example:\n' + 'https://sweetalert2.github.io/#ajax-request');
  }
}

/**
 * Open popup, add necessary classes and styles, fix scrollbar
 *
 * @param {Array} params
 */
var openPopup = function openPopup(params) {
  var container = getContainer();
  var popup = getPopup();

  if (params.onBeforeOpen !== null && typeof params.onBeforeOpen === 'function') {
    params.onBeforeOpen(popup);
  }

  if (params.animation) {
    addClass(popup, swalClasses.show);
    addClass(container, swalClasses.fade);
    removeClass(popup, swalClasses.hide);
  } else {
    removeClass(popup, swalClasses.fade);
  }
  show(popup);

  // scrolling is 'hidden' until animation is done, after that 'auto'
  container.style.overflowY = 'hidden';
  if (animationEndEvent && !hasClass(popup, swalClasses.noanimation)) {
    popup.addEventListener(animationEndEvent, function swalCloseEventFinished() {
      popup.removeEventListener(animationEndEvent, swalCloseEventFinished);
      container.style.overflowY = 'auto';
    });
  } else {
    container.style.overflowY = 'auto';
  }

  addClass([document.documentElement, document.body, container], swalClasses.shown);
  if (params.heightAuto && params.backdrop && !params.toast) {
    addClass([document.documentElement, document.body], swalClasses['height-auto']);
  }

  if (isModal()) {
    fixScrollbar();
    iOSfix();
    setAriaHidden();
  }
  if (!isToast() && !globalState.previousActiveElement) {
    globalState.previousActiveElement = document.activeElement;
  }
  if (params.onOpen !== null && typeof params.onOpen === 'function') {
    setTimeout(function () {
      params.onOpen(popup);
    });
  }
};

function _main(userParams) {
  var _this = this;

  showWarningsForParams(userParams);

  var innerParams = _extends({}, defaultParams, userParams);
  setParameters(innerParams);
  Object.freeze(innerParams);
  privateProps.innerParams.set(this, innerParams);

  // clear the previous timer
  if (globalState.timeout) {
    globalState.timeout.stop();
    delete globalState.timeout;
  }

  // clear the restore focus timeout
  clearTimeout(globalState.restoreFocusTimeout);

  var domCache = {
    popup: getPopup(),
    container: getContainer(),
    content: getContent(),
    actions: getActions(),
    confirmButton: getConfirmButton(),
    cancelButton: getCancelButton(),
    closeButton: getCloseButton(),
    validationError: getValidationError(),
    progressSteps: getProgressSteps()
  };
  privateProps.domCache.set(this, domCache);

  var constructor = this.constructor;

  return new Promise(function (resolve, reject) {
    // functions to handle all resolving/rejecting/settling
    var succeedWith = function succeedWith(value) {
      constructor.closePopup(innerParams.onClose, innerParams.onAfterClose); // TODO: make closePopup an *instance* method
      if (innerParams.useRejections) {
        resolve(value);
      } else {
        resolve({ value: value });
      }
    };
    var dismissWith = function dismissWith(dismiss) {
      constructor.closePopup(innerParams.onClose, innerParams.onAfterClose);
      if (innerParams.useRejections) {
        reject(dismiss);
      } else {
        resolve({ dismiss: dismiss });
      }
    };
    var errorWith = function errorWith(error$$1) {
      constructor.closePopup(innerParams.onClose, innerParams.onAfterClose);
      reject(error$$1);
    };

    // Close on timer
    if (innerParams.timer) {
      globalState.timeout = new Timer(function () {
        dismissWith('timer');
        delete globalState.timeout;
      }, innerParams.timer);
    }

    // Get the value of the popup input
    var getInputValue = function getInputValue() {
      var input = _this.getInput();
      if (!input) {
        return null;
      }
      switch (innerParams.input) {
        case 'checkbox':
          return input.checked ? 1 : 0;
        case 'radio':
          return input.checked ? input.value : null;
        case 'file':
          return input.files.length ? input.files[0] : null;
        default:
          return innerParams.inputAutoTrim ? input.value.trim() : input.value;
      }
    };

    // input autofocus
    if (innerParams.input) {
      setTimeout(function () {
        var input = _this.getInput();
        if (input) {
          focusInput(input);
        }
      }, 0);
    }

    var confirm = function confirm(value) {
      if (innerParams.showLoaderOnConfirm) {
        constructor.showLoading(); // TODO: make showLoading an *instance* method
      }

      if (innerParams.preConfirm) {
        _this.resetValidationError();
        var preConfirmPromise = Promise.resolve().then(function () {
          return innerParams.preConfirm(value, innerParams.extraParams);
        });
        if (innerParams.expectRejections) {
          preConfirmPromise.then(function (preConfirmValue) {
            return succeedWith(preConfirmValue || value);
          }, function (validationError) {
            _this.hideLoading();
            if (validationError) {
              _this.showValidationError(validationError);
            }
          });
        } else {
          preConfirmPromise.then(function (preConfirmValue) {
            if (isVisible(domCache.validationError) || preConfirmValue === false) {
              _this.hideLoading();
            } else {
              succeedWith(preConfirmValue || value);
            }
          }, function (error$$1) {
            return errorWith(error$$1);
          });
        }
      } else {
        succeedWith(value);
      }
    };

    // Mouse interactions
    var onButtonEvent = function onButtonEvent(e) {
      var target = e.target;
      var confirmButton = domCache.confirmButton,
          cancelButton = domCache.cancelButton;

      var targetedConfirm = confirmButton && (confirmButton === target || confirmButton.contains(target));
      var targetedCancel = cancelButton && (cancelButton === target || cancelButton.contains(target));

      switch (e.type) {
        case 'click':
          // Clicked 'confirm'
          if (targetedConfirm && constructor.isVisible()) {
            _this.disableButtons();
            if (innerParams.input) {
              var inputValue = getInputValue();

              if (innerParams.inputValidator) {
                _this.disableInput();
                var validationPromise = Promise.resolve().then(function () {
                  return innerParams.inputValidator(inputValue, innerParams.extraParams);
                });
                if (innerParams.expectRejections) {
                  validationPromise.then(function () {
                    _this.enableButtons();
                    _this.enableInput();
                    confirm(inputValue);
                  }, function (validationError) {
                    _this.enableButtons();
                    _this.enableInput();
                    if (validationError) {
                      _this.showValidationError(validationError);
                    }
                  });
                } else {
                  validationPromise.then(function (validationError) {
                    _this.enableButtons();
                    _this.enableInput();
                    if (validationError) {
                      _this.showValidationError(validationError);
                    } else {
                      confirm(inputValue);
                    }
                  }, function (error$$1) {
                    return errorWith(error$$1);
                  });
                }
              } else {
                confirm(inputValue);
              }
            } else {
              confirm(true);
            }

            // Clicked 'cancel'
          } else if (targetedCancel && constructor.isVisible()) {
            _this.disableButtons();
            dismissWith(constructor.DismissReason.cancel);
          }
          break;
        default:
      }
    };

    var buttons = domCache.popup.querySelectorAll('button');
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].onclick = onButtonEvent;
      buttons[i].onmouseover = onButtonEvent;
      buttons[i].onmouseout = onButtonEvent;
      buttons[i].onmousedown = onButtonEvent;
    }

    // Closing popup by close button
    domCache.closeButton.onclick = function () {
      dismissWith(constructor.DismissReason.close);
    };

    if (innerParams.toast) {
      // Closing popup by internal click
      domCache.popup.onclick = function () {
        if (innerParams.showConfirmButton || innerParams.showCancelButton || innerParams.showCloseButton || innerParams.input) {
          return;
        }
        dismissWith(constructor.DismissReason.close);
      };
    } else {
      var ignoreOutsideClick = false;

      // Ignore click events that had mousedown on the popup but mouseup on the container
      // This can happen when the user drags a slider
      domCache.popup.onmousedown = function () {
        domCache.container.onmouseup = function (e) {
          domCache.container.onmouseup = undefined;
          // We only check if the mouseup target is the container because usually it doesn't
          // have any other direct children aside of the popup
          if (e.target === domCache.container) {
            ignoreOutsideClick = true;
          }
        };
      };

      // Ignore click events that had mousedown on the container but mouseup on the popup
      domCache.container.onmousedown = function () {
        domCache.popup.onmouseup = function (e) {
          domCache.popup.onmouseup = undefined;
          // We also need to check if the mouseup target is a child of the popup
          if (e.target === domCache.popup || domCache.popup.contains(e.target)) {
            ignoreOutsideClick = true;
          }
        };
      };

      domCache.container.onclick = function (e) {
        if (ignoreOutsideClick) {
          ignoreOutsideClick = false;
          return;
        }
        if (e.target !== domCache.container) {
          return;
        }
        if (callIfFunction(innerParams.allowOutsideClick)) {
          dismissWith(constructor.DismissReason.backdrop);
        }
      };
    }

    // Reverse buttons (Confirm on the right side)
    if (innerParams.reverseButtons) {
      domCache.confirmButton.parentNode.insertBefore(domCache.cancelButton, domCache.confirmButton);
    } else {
      domCache.confirmButton.parentNode.insertBefore(domCache.confirmButton, domCache.cancelButton);
    }

    // Focus handling
    var setFocus = function setFocus(index, increment) {
      var focusableElements = getFocusableElements(innerParams.focusCancel);
      // search for visible elements and select the next possible match
      for (var _i = 0; _i < focusableElements.length; _i++) {
        index = index + increment;

        // rollover to first item
        if (index === focusableElements.length) {
          index = 0;

          // go to last item
        } else if (index === -1) {
          index = focusableElements.length - 1;
        }

        return focusableElements[index].focus();
      }
      // no visible focusable elements, focus the popup
      domCache.popup.focus();
    };

    var keydownHandler = function keydownHandler(e, innerParams) {
      if (innerParams.stopKeydownPropagation) {
        e.stopPropagation();
      }

      var arrowKeys = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Left', 'Right', 'Up', 'Down' // IE11
      ];

      if (e.key === 'Enter' && !e.isComposing) {
        if (e.target && _this.getInput() && e.target.outerHTML === _this.getInput().outerHTML) {
          if (['textarea', 'file'].indexOf(innerParams.input) !== -1) {
            return; // do not submit
          }

          constructor.clickConfirm();
          e.preventDefault();
        }

        // TAB
      } else if (e.key === 'Tab') {
        var targetElement = e.target;

        var focusableElements = getFocusableElements(innerParams.focusCancel);
        var btnIndex = -1;
        for (var _i2 = 0; _i2 < focusableElements.length; _i2++) {
          if (targetElement === focusableElements[_i2]) {
            btnIndex = _i2;
            break;
          }
        }

        if (!e.shiftKey) {
          // Cycle to the next button
          setFocus(btnIndex, 1);
        } else {
          // Cycle to the prev button
          setFocus(btnIndex, -1);
        }
        e.stopPropagation();
        e.preventDefault();

        // ARROWS - switch focus between buttons
      } else if (arrowKeys.indexOf(e.key) !== -1) {
        // focus Cancel button if Confirm button is currently focused
        if (document.activeElement === domCache.confirmButton && isVisible(domCache.cancelButton)) {
          domCache.cancelButton.focus();
          // and vice versa
        } else if (document.activeElement === domCache.cancelButton && isVisible(domCache.confirmButton)) {
          domCache.confirmButton.focus();
        }

        // ESC
      } else if ((e.key === 'Escape' || e.key === 'Esc') && callIfFunction(innerParams.allowEscapeKey) === true) {
        dismissWith(constructor.DismissReason.esc);
      }
    };

    if (globalState.keydownHandlerAdded) {
      globalState.keydownTarget.removeEventListener('keydown', globalState.keydownHandler, { capture: globalState.keydownListenerCapture });
      globalState.keydownHandlerAdded = false;
    }

    if (!innerParams.toast) {
      globalState.keydownHandler = function (e) {
        return keydownHandler(e, innerParams);
      };
      globalState.keydownTarget = innerParams.keydownListenerCapture ? window : domCache.popup;
      globalState.keydownListenerCapture = innerParams.keydownListenerCapture;
      globalState.keydownTarget.addEventListener('keydown', globalState.keydownHandler, { capture: globalState.keydownListenerCapture });
      globalState.keydownHandlerAdded = true;
    }

    _this.enableButtons();
    _this.hideLoading();
    _this.resetValidationError();

    if (innerParams.toast && (innerParams.input || innerParams.footer || innerParams.showCloseButton)) {
      addClass(document.body, swalClasses['toast-column']);
    } else {
      removeClass(document.body, swalClasses['toast-column']);
    }

    // inputs
    var inputTypes = ['input', 'file', 'range', 'select', 'radio', 'checkbox', 'textarea'];
    var input = void 0;
    for (var _i3 = 0; _i3 < inputTypes.length; _i3++) {
      var inputClass = swalClasses[inputTypes[_i3]];
      var inputContainer = getChildByClass(domCache.content, inputClass);
      input = _this.getInput(inputTypes[_i3]);

      // set attributes
      if (input) {
        for (var j in input.attributes) {
          if (input.attributes.hasOwnProperty(j)) {
            var attrName = input.attributes[j].name;
            if (attrName !== 'type' && attrName !== 'value') {
              input.removeAttribute(attrName);
            }
          }
        }
        for (var attr in innerParams.inputAttributes) {
          input.setAttribute(attr, innerParams.inputAttributes[attr]);
        }
      }

      // set class
      inputContainer.className = inputClass;
      if (innerParams.inputClass) {
        addClass(inputContainer, innerParams.inputClass);
      }

      hide(inputContainer);
    }

    var populateInputOptions = void 0;
    switch (innerParams.input) {
      case 'text':
      case 'email':
      case 'password':
      case 'number':
      case 'tel':
      case 'url':
        {
          input = getChildByClass(domCache.content, swalClasses.input);
          input.value = innerParams.inputValue;
          input.placeholder = innerParams.inputPlaceholder;
          input.type = innerParams.input;
          show(input);
          break;
        }
      case 'file':
        {
          input = getChildByClass(domCache.content, swalClasses.file);
          input.placeholder = innerParams.inputPlaceholder;
          input.type = innerParams.input;
          show(input);
          break;
        }
      case 'range':
        {
          var range = getChildByClass(domCache.content, swalClasses.range);
          var rangeInput = range.querySelector('input');
          var rangeOutput = range.querySelector('output');
          rangeInput.value = innerParams.inputValue;
          rangeInput.type = innerParams.input;
          rangeOutput.value = innerParams.inputValue;
          show(range);
          break;
        }
      case 'select':
        {
          var select = getChildByClass(domCache.content, swalClasses.select);
          select.innerHTML = '';
          if (innerParams.inputPlaceholder) {
            var placeholder = document.createElement('option');
            placeholder.innerHTML = innerParams.inputPlaceholder;
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.selected = true;
            select.appendChild(placeholder);
          }
          populateInputOptions = function populateInputOptions(inputOptions) {
            inputOptions.forEach(function (inputOption) {
              var optionValue = inputOption[0];
              var optionLabel = inputOption[1];
              var option = document.createElement('option');
              option.value = optionValue;
              option.innerHTML = optionLabel;
              if (innerParams.inputValue.toString() === optionValue.toString()) {
                option.selected = true;
              }
              select.appendChild(option);
            });
            show(select);
            select.focus();
          };
          break;
        }
      case 'radio':
        {
          var radio = getChildByClass(domCache.content, swalClasses.radio);
          radio.innerHTML = '';
          populateInputOptions = function populateInputOptions(inputOptions) {
            inputOptions.forEach(function (inputOption) {
              var radioValue = inputOption[0];
              var radioLabel = inputOption[1];
              var radioInput = document.createElement('input');
              var radioLabelElement = document.createElement('label');
              radioInput.type = 'radio';
              radioInput.name = swalClasses.radio;
              radioInput.value = radioValue;
              if (innerParams.inputValue.toString() === radioValue.toString()) {
                radioInput.checked = true;
              }
              var label = document.createElement('span');
              label.innerHTML = radioLabel;
              label.className = swalClasses.label;
              radioLabelElement.appendChild(radioInput);
              radioLabelElement.appendChild(label);
              radio.appendChild(radioLabelElement);
            });
            show(radio);
            var radios = radio.querySelectorAll('input');
            if (radios.length) {
              radios[0].focus();
            }
          };
          break;
        }
      case 'checkbox':
        {
          var checkbox = getChildByClass(domCache.content, swalClasses.checkbox);
          var checkboxInput = _this.getInput('checkbox');
          checkboxInput.type = 'checkbox';
          checkboxInput.value = 1;
          checkboxInput.id = swalClasses.checkbox;
          checkboxInput.checked = Boolean(innerParams.inputValue);
          var label = checkbox.querySelector('span');
          label.innerHTML = innerParams.inputPlaceholder;
          show(checkbox);
          break;
        }
      case 'textarea':
        {
          var textarea = getChildByClass(domCache.content, swalClasses.textarea);
          textarea.value = innerParams.inputValue;
          textarea.placeholder = innerParams.inputPlaceholder;
          show(textarea);
          break;
        }
      case null:
        {
          break;
        }
      default:
        error('Unexpected type of input! Expected "text", "email", "password", "number", "tel", "select", "radio", "checkbox", "textarea", "file" or "url", got "' + innerParams.input + '"');
        break;
    }

    if (innerParams.input === 'select' || innerParams.input === 'radio') {
      var processInputOptions = function processInputOptions(inputOptions) {
        return populateInputOptions(formatInputOptions(inputOptions));
      };
      if (isThenable(innerParams.inputOptions)) {
        constructor.showLoading();
        innerParams.inputOptions.then(function (inputOptions) {
          _this.hideLoading();
          processInputOptions(inputOptions);
        });
      } else if (_typeof(innerParams.inputOptions) === 'object') {
        processInputOptions(innerParams.inputOptions);
      } else {
        error('Unexpected type of inputOptions! Expected object, Map or Promise, got ' + _typeof(innerParams.inputOptions));
      }
    } else if (['text', 'email', 'number', 'tel', 'textarea'].indexOf(innerParams.input) !== -1 && isThenable(innerParams.inputValue)) {
      constructor.showLoading();
      hide(input);
      innerParams.inputValue.then(function (inputValue) {
        input.value = innerParams.input === 'number' ? parseFloat(inputValue) || 0 : inputValue + '';
        show(input);
        input.focus();
        _this.hideLoading();
      }).catch(function (err) {
        error('Error in inputValue promise: ' + err);
        input.value = '';
        show(input);
        input.focus();
        _this.hideLoading();
      });
    }

    openPopup(innerParams);

    if (!innerParams.toast) {
      if (!callIfFunction(innerParams.allowEnterKey)) {
        if (document.activeElement) {
          document.activeElement.blur();
        }
      } else if (innerParams.focusCancel && isVisible(domCache.cancelButton)) {
        domCache.cancelButton.focus();
      } else if (innerParams.focusConfirm && isVisible(domCache.confirmButton)) {
        domCache.confirmButton.focus();
      } else {
        setFocus(-1, 1);
      }
    }

    // fix scroll
    domCache.container.scrollTop = 0;
  });
}



var instanceMethods = Object.freeze({
	hideLoading: hideLoading,
	disableLoading: hideLoading,
	getInput: getInput,
	enableButtons: enableButtons,
	disableButtons: disableButtons,
	enableConfirmButton: enableConfirmButton,
	disableConfirmButton: disableConfirmButton,
	enableInput: enableInput,
	disableInput: disableInput,
	showValidationError: showValidationError,
	resetValidationError: resetValidationError,
	getProgressSteps: getProgressSteps$1,
	setProgressSteps: setProgressSteps,
	showProgressSteps: showProgressSteps,
	hideProgressSteps: hideProgressSteps,
	_main: _main
});

var currentInstance = void 0;

// SweetAlert constructor
function SweetAlert() {
  // Prevent run in Node env
  if (typeof window === 'undefined') {
    return;
  }

  // Check for the existence of Promise
  if (typeof Promise === 'undefined') {
    error('This package requires a Promise library, please include a shim to enable it in this browser (See: https://github.com/sweetalert2/sweetalert2/wiki/Migration-from-SweetAlert-to-SweetAlert2#1-ie-support)');
  }

  for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
    args[_key] = arguments[_key];
  }

  if (typeof args[0] === 'undefined') {
    error('At least 1 argument is expected!');
    return false;
  }

  currentInstance = this;

  var outerParams = Object.freeze(this.constructor.argsToParams(args));

  Object.defineProperties(this, {
    params: {
      value: outerParams,
      writable: false,
      enumerable: true
    }
  });

  var promise = this._main(this.params);
  privateProps.promise.set(this, promise);
}

// `catch` cannot be the name of a module export, so we define our thenable methods here instead
SweetAlert.prototype.then = function (onFulfilled, onRejected) {
  var promise = privateProps.promise.get(this);
  return promise.then(onFulfilled, onRejected);
};
SweetAlert.prototype.catch = function (onRejected) {
  var promise = privateProps.promise.get(this);
  return promise.catch(onRejected);
};
SweetAlert.prototype.finally = function (onFinally) {
  var promise = privateProps.promise.get(this);
  return promise.finally(onFinally);
};

// Assign instance methods from src/instanceMethods/*.js to prototype
_extends(SweetAlert.prototype, instanceMethods);

// Assign static methods from src/staticMethods/*.js to constructor
_extends(SweetAlert, staticMethods);

// Proxy to instance methods to constructor, for now, for backwards compatibility
Object.keys(instanceMethods).forEach(function (key) {
  SweetAlert[key] = function () {
    if (currentInstance) {
      var _currentInstance;

      return (_currentInstance = currentInstance)[key].apply(_currentInstance, arguments);
    }
  };
});

SweetAlert.DismissReason = DismissReason;

SweetAlert.noop = function () {};

SweetAlert.version = version;

var Swal = withNoNewKeyword(withGlobalDefaults(SweetAlert));
Swal.default = Swal;

return Swal;

})));
if (typeof window !== 'undefined' && window.Sweetalert2){  window.swal = window.sweetAlert = window.Swal = window.SweetAlert = window.Sweetalert2}

"undefined"!=typeof document&&function(e,t){var n=e.createElement("style");if(e.getElementsByTagName("head")[0].appendChild(n),n.styleSheet)n.styleSheet.disabled||(n.styleSheet.cssText=t);else try{n.innerHTML=t}catch(e){n.innerText=t}}(document,"@-webkit-keyframes swal2-show {\n" +
"  0% {\n" +
"    -webkit-transform: scale(0.7);\n" +
"            transform: scale(0.7); }\n" +
"  45% {\n" +
"    -webkit-transform: scale(1.05);\n" +
"            transform: scale(1.05); }\n" +
"  80% {\n" +
"    -webkit-transform: scale(0.95);\n" +
"            transform: scale(0.95); }\n" +
"  100% {\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1); } }\n" +
"\n" +
"@keyframes swal2-show {\n" +
"  0% {\n" +
"    -webkit-transform: scale(0.7);\n" +
"            transform: scale(0.7); }\n" +
"  45% {\n" +
"    -webkit-transform: scale(1.05);\n" +
"            transform: scale(1.05); }\n" +
"  80% {\n" +
"    -webkit-transform: scale(0.95);\n" +
"            transform: scale(0.95); }\n" +
"  100% {\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1); } }\n" +
"\n" +
"@-webkit-keyframes swal2-hide {\n" +
"  0% {\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1);\n" +
"    opacity: 1; }\n" +
"  100% {\n" +
"    -webkit-transform: scale(0.5);\n" +
"            transform: scale(0.5);\n" +
"    opacity: 0; } }\n" +
"\n" +
"@keyframes swal2-hide {\n" +
"  0% {\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1);\n" +
"    opacity: 1; }\n" +
"  100% {\n" +
"    -webkit-transform: scale(0.5);\n" +
"            transform: scale(0.5);\n" +
"    opacity: 0; } }\n" +
"\n" +
"@-webkit-keyframes swal2-animate-success-line-tip {\n" +
"  0% {\n" +
"    top: 1.1875em;\n" +
"    left: .0625em;\n" +
"    width: 0; }\n" +
"  54% {\n" +
"    top: 1.0625em;\n" +
"    left: .125em;\n" +
"    width: 0; }\n" +
"  70% {\n" +
"    top: 2.1875em;\n" +
"    left: -.375em;\n" +
"    width: 3.125em; }\n" +
"  84% {\n" +
"    top: 3em;\n" +
"    left: 1.3125em;\n" +
"    width: 1.0625em; }\n" +
"  100% {\n" +
"    top: 2.8125em;\n" +
"    left: .875em;\n" +
"    width: 1.5625em; } }\n" +
"\n" +
"@keyframes swal2-animate-success-line-tip {\n" +
"  0% {\n" +
"    top: 1.1875em;\n" +
"    left: .0625em;\n" +
"    width: 0; }\n" +
"  54% {\n" +
"    top: 1.0625em;\n" +
"    left: .125em;\n" +
"    width: 0; }\n" +
"  70% {\n" +
"    top: 2.1875em;\n" +
"    left: -.375em;\n" +
"    width: 3.125em; }\n" +
"  84% {\n" +
"    top: 3em;\n" +
"    left: 1.3125em;\n" +
"    width: 1.0625em; }\n" +
"  100% {\n" +
"    top: 2.8125em;\n" +
"    left: .875em;\n" +
"    width: 1.5625em; } }\n" +
"\n" +
"@-webkit-keyframes swal2-animate-success-line-long {\n" +
"  0% {\n" +
"    top: 3.375em;\n" +
"    right: 2.875em;\n" +
"    width: 0; }\n" +
"  65% {\n" +
"    top: 3.375em;\n" +
"    right: 2.875em;\n" +
"    width: 0; }\n" +
"  84% {\n" +
"    top: 2.1875em;\n" +
"    right: 0;\n" +
"    width: 3.4375em; }\n" +
"  100% {\n" +
"    top: 2.375em;\n" +
"    right: .5em;\n" +
"    width: 2.9375em; } }\n" +
"\n" +
"@keyframes swal2-animate-success-line-long {\n" +
"  0% {\n" +
"    top: 3.375em;\n" +
"    right: 2.875em;\n" +
"    width: 0; }\n" +
"  65% {\n" +
"    top: 3.375em;\n" +
"    right: 2.875em;\n" +
"    width: 0; }\n" +
"  84% {\n" +
"    top: 2.1875em;\n" +
"    right: 0;\n" +
"    width: 3.4375em; }\n" +
"  100% {\n" +
"    top: 2.375em;\n" +
"    right: .5em;\n" +
"    width: 2.9375em; } }\n" +
"\n" +
"@-webkit-keyframes swal2-rotate-success-circular-line {\n" +
"  0% {\n" +
"    -webkit-transform: rotate(-45deg);\n" +
"            transform: rotate(-45deg); }\n" +
"  5% {\n" +
"    -webkit-transform: rotate(-45deg);\n" +
"            transform: rotate(-45deg); }\n" +
"  12% {\n" +
"    -webkit-transform: rotate(-405deg);\n" +
"            transform: rotate(-405deg); }\n" +
"  100% {\n" +
"    -webkit-transform: rotate(-405deg);\n" +
"            transform: rotate(-405deg); } }\n" +
"\n" +
"@keyframes swal2-rotate-success-circular-line {\n" +
"  0% {\n" +
"    -webkit-transform: rotate(-45deg);\n" +
"            transform: rotate(-45deg); }\n" +
"  5% {\n" +
"    -webkit-transform: rotate(-45deg);\n" +
"            transform: rotate(-45deg); }\n" +
"  12% {\n" +
"    -webkit-transform: rotate(-405deg);\n" +
"            transform: rotate(-405deg); }\n" +
"  100% {\n" +
"    -webkit-transform: rotate(-405deg);\n" +
"            transform: rotate(-405deg); } }\n" +
"\n" +
"@-webkit-keyframes swal2-animate-error-x-mark {\n" +
"  0% {\n" +
"    margin-top: 1.625em;\n" +
"    -webkit-transform: scale(0.4);\n" +
"            transform: scale(0.4);\n" +
"    opacity: 0; }\n" +
"  50% {\n" +
"    margin-top: 1.625em;\n" +
"    -webkit-transform: scale(0.4);\n" +
"            transform: scale(0.4);\n" +
"    opacity: 0; }\n" +
"  80% {\n" +
"    margin-top: -.375em;\n" +
"    -webkit-transform: scale(1.15);\n" +
"            transform: scale(1.15); }\n" +
"  100% {\n" +
"    margin-top: 0;\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1);\n" +
"    opacity: 1; } }\n" +
"\n" +
"@keyframes swal2-animate-error-x-mark {\n" +
"  0% {\n" +
"    margin-top: 1.625em;\n" +
"    -webkit-transform: scale(0.4);\n" +
"            transform: scale(0.4);\n" +
"    opacity: 0; }\n" +
"  50% {\n" +
"    margin-top: 1.625em;\n" +
"    -webkit-transform: scale(0.4);\n" +
"            transform: scale(0.4);\n" +
"    opacity: 0; }\n" +
"  80% {\n" +
"    margin-top: -.375em;\n" +
"    -webkit-transform: scale(1.15);\n" +
"            transform: scale(1.15); }\n" +
"  100% {\n" +
"    margin-top: 0;\n" +
"    -webkit-transform: scale(1);\n" +
"            transform: scale(1);\n" +
"    opacity: 1; } }\n" +
"\n" +
"@-webkit-keyframes swal2-animate-error-icon {\n" +
"  0% {\n" +
"    -webkit-transform: rotateX(100deg);\n" +
"            transform: rotateX(100deg);\n" +
"    opacity: 0; }\n" +
"  100% {\n" +
"    -webkit-transform: rotateX(0deg);\n" +
"            transform: rotateX(0deg);\n" +
"    opacity: 1; } }\n" +
"\n" +
"@keyframes swal2-animate-error-icon {\n" +
"  0% {\n" +
"    -webkit-transform: rotateX(100deg);\n" +
"            transform: rotateX(100deg);\n" +
"    opacity: 0; }\n" +
"  100% {\n" +
"    -webkit-transform: rotateX(0deg);\n" +
"            transform: rotateX(0deg);\n" +
"    opacity: 1; } }\n" +
"\n" +
"body.swal2-toast-shown .swal2-container {\n" +
"  position: fixed;\n" +
"  background-color: transparent; }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-shown {\n" +
"    background-color: transparent; }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-top {\n" +
"    top: 0;\n" +
"    right: auto;\n" +
"    bottom: auto;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translateX(-50%);\n" +
"            transform: translateX(-50%); }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-top-end, body.swal2-toast-shown .swal2-container.swal2-top-right {\n" +
"    top: 0;\n" +
"    right: 0;\n" +
"    bottom: auto;\n" +
"    left: auto; }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-top-start, body.swal2-toast-shown .swal2-container.swal2-top-left {\n" +
"    top: 0;\n" +
"    right: auto;\n" +
"    bottom: auto;\n" +
"    left: 0; }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-center-start, body.swal2-toast-shown .swal2-container.swal2-center-left {\n" +
"    top: 50%;\n" +
"    right: auto;\n" +
"    bottom: auto;\n" +
"    left: 0;\n" +
"    -webkit-transform: translateY(-50%);\n" +
"            transform: translateY(-50%); }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-center {\n" +
"    top: 50%;\n" +
"    right: auto;\n" +
"    bottom: auto;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translate(-50%, -50%);\n" +
"            transform: translate(-50%, -50%); }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-center-end, body.swal2-toast-shown .swal2-container.swal2-center-right {\n" +
"    top: 50%;\n" +
"    right: 0;\n" +
"    bottom: auto;\n" +
"    left: auto;\n" +
"    -webkit-transform: translateY(-50%);\n" +
"            transform: translateY(-50%); }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-bottom-start, body.swal2-toast-shown .swal2-container.swal2-bottom-left {\n" +
"    top: auto;\n" +
"    right: auto;\n" +
"    bottom: 0;\n" +
"    left: 0; }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-bottom {\n" +
"    top: auto;\n" +
"    right: auto;\n" +
"    bottom: 0;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translateX(-50%);\n" +
"            transform: translateX(-50%); }\n" +
"  body.swal2-toast-shown .swal2-container.swal2-bottom-end, body.swal2-toast-shown .swal2-container.swal2-bottom-right {\n" +
"    top: auto;\n" +
"    right: 0;\n" +
"    bottom: 0;\n" +
"    left: auto; }\n" +
"\n" +
"body.swal2-toast-column .swal2-toast {\n" +
"  flex-direction: column;\n" +
"  align-items: stretch; }\n" +
"  body.swal2-toast-column .swal2-toast .swal2-actions {\n" +
"    flex: 1;\n" +
"    align-self: stretch;\n" +
"    height: 2.2em;\n" +
"    margin-top: .3125em; }\n" +
"  body.swal2-toast-column .swal2-toast .swal2-loading {\n" +
"    justify-content: center; }\n" +
"  body.swal2-toast-column .swal2-toast .swal2-input {\n" +
"    height: 2em;\n" +
"    margin: .3125em auto;\n" +
"    font-size: 1em; }\n" +
"  body.swal2-toast-column .swal2-toast .swal2-validationerror {\n" +
"    font-size: 1em; }\n" +
"\n" +
".swal2-popup.swal2-toast {\n" +
"  flex-direction: row;\n" +
"  align-items: center;\n" +
"  width: auto;\n" +
"  padding: 0.625em;\n" +
"  box-shadow: 0 0 0.625em #d9d9d9;\n" +
"  overflow-y: hidden; }\n" +
"  .swal2-popup.swal2-toast .swal2-header {\n" +
"    flex-direction: row; }\n" +
"  .swal2-popup.swal2-toast .swal2-title {\n" +
"    flex-grow: 1;\n" +
"    justify-content: flex-start;\n" +
"    margin: 0 .6em;\n" +
"    font-size: 1em; }\n" +
"  .swal2-popup.swal2-toast .swal2-footer {\n" +
"    margin: 0.5em 0 0;\n" +
"    padding: 0.5em 0 0;\n" +
"    font-size: 0.8em; }\n" +
"  .swal2-popup.swal2-toast .swal2-close {\n" +
"    position: initial;\n" +
"    width: 0.8em;\n" +
"    height: 0.8em;\n" +
"    line-height: 0.8; }\n" +
"  .swal2-popup.swal2-toast .swal2-content {\n" +
"    justify-content: flex-start;\n" +
"    font-size: 1em; }\n" +
"  .swal2-popup.swal2-toast .swal2-icon {\n" +
"    width: 2em;\n" +
"    min-width: 2em;\n" +
"    height: 2em;\n" +
"    margin: 0; }\n" +
"    .swal2-popup.swal2-toast .swal2-icon-text {\n" +
"      font-size: 2em;\n" +
"      font-weight: bold;\n" +
"      line-height: 1em; }\n" +
"    .swal2-popup.swal2-toast .swal2-icon.swal2-success .swal2-success-ring {\n" +
"      width: 2em;\n" +
"      height: 2em; }\n" +
"    .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {\n" +
"      top: .875em;\n" +
"      width: 1.375em; }\n" +
"      .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^='swal2-x-mark-line'][class$='left'] {\n" +
"        left: .3125em; }\n" +
"      .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^='swal2-x-mark-line'][class$='right'] {\n" +
"        right: .3125em; }\n" +
"  .swal2-popup.swal2-toast .swal2-actions {\n" +
"    height: auto;\n" +
"    margin: 0 .3125em; }\n" +
"  .swal2-popup.swal2-toast .swal2-styled {\n" +
"    margin: 0 .3125em;\n" +
"    padding: .3125em .625em;\n" +
"    font-size: 1em; }\n" +
"    .swal2-popup.swal2-toast .swal2-styled:focus {\n" +
"      box-shadow: 0 0 0 0.0625em #fff, 0 0 0 0.125em rgba(50, 100, 150, 0.4); }\n" +
"  .swal2-popup.swal2-toast .swal2-success {\n" +
"    border-color: #a5dc86; }\n" +
"    .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-circular-line'] {\n" +
"      position: absolute;\n" +
"      width: 2em;\n" +
"      height: 2.8125em;\n" +
"      -webkit-transform: rotate(45deg);\n" +
"              transform: rotate(45deg);\n" +
"      border-radius: 50%; }\n" +
"      .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-circular-line'][class$='left'] {\n" +
"        top: -.25em;\n" +
"        left: -.9375em;\n" +
"        -webkit-transform: rotate(-45deg);\n" +
"                transform: rotate(-45deg);\n" +
"        -webkit-transform-origin: 2em 2em;\n" +
"                transform-origin: 2em 2em;\n" +
"        border-radius: 4em 0 0 4em; }\n" +
"      .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-circular-line'][class$='right'] {\n" +
"        top: -.25em;\n" +
"        left: .9375em;\n" +
"        -webkit-transform-origin: 0 2em;\n" +
"                transform-origin: 0 2em;\n" +
"        border-radius: 0 4em 4em 0; }\n" +
"    .swal2-popup.swal2-toast .swal2-success .swal2-success-ring {\n" +
"      width: 2em;\n" +
"      height: 2em; }\n" +
"    .swal2-popup.swal2-toast .swal2-success .swal2-success-fix {\n" +
"      top: 0;\n" +
"      left: .4375em;\n" +
"      width: .4375em;\n" +
"      height: 2.6875em; }\n" +
"    .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-line'] {\n" +
"      height: .3125em; }\n" +
"      .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-line'][class$='tip'] {\n" +
"        top: 1.125em;\n" +
"        left: .1875em;\n" +
"        width: .75em; }\n" +
"      .swal2-popup.swal2-toast .swal2-success [class^='swal2-success-line'][class$='long'] {\n" +
"        top: .9375em;\n" +
"        right: .1875em;\n" +
"        width: 1.375em; }\n" +
"  .swal2-popup.swal2-toast.swal2-show {\n" +
"    -webkit-animation: showSweetToast .5s;\n" +
"            animation: showSweetToast .5s; }\n" +
"  .swal2-popup.swal2-toast.swal2-hide {\n" +
"    -webkit-animation: hideSweetToast .2s forwards;\n" +
"            animation: hideSweetToast .2s forwards; }\n" +
"  .swal2-popup.swal2-toast .swal2-animate-success-icon .swal2-success-line-tip {\n" +
"    -webkit-animation: animate-toast-success-tip .75s;\n" +
"            animation: animate-toast-success-tip .75s; }\n" +
"  .swal2-popup.swal2-toast .swal2-animate-success-icon .swal2-success-line-long {\n" +
"    -webkit-animation: animate-toast-success-long .75s;\n" +
"            animation: animate-toast-success-long .75s; }\n" +
"\n" +
"@-webkit-keyframes showSweetToast {\n" +
"  0% {\n" +
"    -webkit-transform: translateY(-0.625em) rotateZ(2deg);\n" +
"            transform: translateY(-0.625em) rotateZ(2deg);\n" +
"    opacity: 0; }\n" +
"  33% {\n" +
"    -webkit-transform: translateY(0) rotateZ(-2deg);\n" +
"            transform: translateY(0) rotateZ(-2deg);\n" +
"    opacity: .5; }\n" +
"  66% {\n" +
"    -webkit-transform: translateY(0.3125em) rotateZ(2deg);\n" +
"            transform: translateY(0.3125em) rotateZ(2deg);\n" +
"    opacity: .7; }\n" +
"  100% {\n" +
"    -webkit-transform: translateY(0) rotateZ(0);\n" +
"            transform: translateY(0) rotateZ(0);\n" +
"    opacity: 1; } }\n" +
"\n" +
"@keyframes showSweetToast {\n" +
"  0% {\n" +
"    -webkit-transform: translateY(-0.625em) rotateZ(2deg);\n" +
"            transform: translateY(-0.625em) rotateZ(2deg);\n" +
"    opacity: 0; }\n" +
"  33% {\n" +
"    -webkit-transform: translateY(0) rotateZ(-2deg);\n" +
"            transform: translateY(0) rotateZ(-2deg);\n" +
"    opacity: .5; }\n" +
"  66% {\n" +
"    -webkit-transform: translateY(0.3125em) rotateZ(2deg);\n" +
"            transform: translateY(0.3125em) rotateZ(2deg);\n" +
"    opacity: .7; }\n" +
"  100% {\n" +
"    -webkit-transform: translateY(0) rotateZ(0);\n" +
"            transform: translateY(0) rotateZ(0);\n" +
"    opacity: 1; } }\n" +
"\n" +
"@-webkit-keyframes hideSweetToast {\n" +
"  0% {\n" +
"    opacity: 1; }\n" +
"  33% {\n" +
"    opacity: .5; }\n" +
"  100% {\n" +
"    -webkit-transform: rotateZ(1deg);\n" +
"            transform: rotateZ(1deg);\n" +
"    opacity: 0; } }\n" +
"\n" +
"@keyframes hideSweetToast {\n" +
"  0% {\n" +
"    opacity: 1; }\n" +
"  33% {\n" +
"    opacity: .5; }\n" +
"  100% {\n" +
"    -webkit-transform: rotateZ(1deg);\n" +
"            transform: rotateZ(1deg);\n" +
"    opacity: 0; } }\n" +
"\n" +
"@-webkit-keyframes animate-toast-success-tip {\n" +
"  0% {\n" +
"    top: .5625em;\n" +
"    left: .0625em;\n" +
"    width: 0; }\n" +
"  54% {\n" +
"    top: .125em;\n" +
"    left: .125em;\n" +
"    width: 0; }\n" +
"  70% {\n" +
"    top: .625em;\n" +
"    left: -.25em;\n" +
"    width: 1.625em; }\n" +
"  84% {\n" +
"    top: 1.0625em;\n" +
"    left: .75em;\n" +
"    width: .5em; }\n" +
"  100% {\n" +
"    top: 1.125em;\n" +
"    left: .1875em;\n" +
"    width: .75em; } }\n" +
"\n" +
"@keyframes animate-toast-success-tip {\n" +
"  0% {\n" +
"    top: .5625em;\n" +
"    left: .0625em;\n" +
"    width: 0; }\n" +
"  54% {\n" +
"    top: .125em;\n" +
"    left: .125em;\n" +
"    width: 0; }\n" +
"  70% {\n" +
"    top: .625em;\n" +
"    left: -.25em;\n" +
"    width: 1.625em; }\n" +
"  84% {\n" +
"    top: 1.0625em;\n" +
"    left: .75em;\n" +
"    width: .5em; }\n" +
"  100% {\n" +
"    top: 1.125em;\n" +
"    left: .1875em;\n" +
"    width: .75em; } }\n" +
"\n" +
"@-webkit-keyframes animate-toast-success-long {\n" +
"  0% {\n" +
"    top: 1.625em;\n" +
"    right: 1.375em;\n" +
"    width: 0; }\n" +
"  65% {\n" +
"    top: 1.25em;\n" +
"    right: .9375em;\n" +
"    width: 0; }\n" +
"  84% {\n" +
"    top: .9375em;\n" +
"    right: 0;\n" +
"    width: 1.125em; }\n" +
"  100% {\n" +
"    top: .9375em;\n" +
"    right: .1875em;\n" +
"    width: 1.375em; } }\n" +
"\n" +
"@keyframes animate-toast-success-long {\n" +
"  0% {\n" +
"    top: 1.625em;\n" +
"    right: 1.375em;\n" +
"    width: 0; }\n" +
"  65% {\n" +
"    top: 1.25em;\n" +
"    right: .9375em;\n" +
"    width: 0; }\n" +
"  84% {\n" +
"    top: .9375em;\n" +
"    right: 0;\n" +
"    width: 1.125em; }\n" +
"  100% {\n" +
"    top: .9375em;\n" +
"    right: .1875em;\n" +
"    width: 1.375em; } }\n" +
"\n" +
"body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {\n" +
"  overflow-y: hidden; }\n" +
"\n" +
"body.swal2-height-auto {\n" +
"  height: auto !important; }\n" +
"\n" +
"body.swal2-no-backdrop .swal2-shown {\n" +
"  top: auto;\n" +
"  right: auto;\n" +
"  bottom: auto;\n" +
"  left: auto;\n" +
"  background-color: transparent; }\n" +
"  body.swal2-no-backdrop .swal2-shown > .swal2-modal {\n" +
"    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-top {\n" +
"    top: 0;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translateX(-50%);\n" +
"            transform: translateX(-50%); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-top-start, body.swal2-no-backdrop .swal2-shown.swal2-top-left {\n" +
"    top: 0;\n" +
"    left: 0; }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-top-end, body.swal2-no-backdrop .swal2-shown.swal2-top-right {\n" +
"    top: 0;\n" +
"    right: 0; }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-center {\n" +
"    top: 50%;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translate(-50%, -50%);\n" +
"            transform: translate(-50%, -50%); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-center-start, body.swal2-no-backdrop .swal2-shown.swal2-center-left {\n" +
"    top: 50%;\n" +
"    left: 0;\n" +
"    -webkit-transform: translateY(-50%);\n" +
"            transform: translateY(-50%); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-center-end, body.swal2-no-backdrop .swal2-shown.swal2-center-right {\n" +
"    top: 50%;\n" +
"    right: 0;\n" +
"    -webkit-transform: translateY(-50%);\n" +
"            transform: translateY(-50%); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-bottom {\n" +
"    bottom: 0;\n" +
"    left: 50%;\n" +
"    -webkit-transform: translateX(-50%);\n" +
"            transform: translateX(-50%); }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-bottom-start, body.swal2-no-backdrop .swal2-shown.swal2-bottom-left {\n" +
"    bottom: 0;\n" +
"    left: 0; }\n" +
"  body.swal2-no-backdrop .swal2-shown.swal2-bottom-end, body.swal2-no-backdrop .swal2-shown.swal2-bottom-right {\n" +
"    right: 0;\n" +
"    bottom: 0; }\n" +
"\n" +
".swal2-container {\n" +
"  display: flex;\n" +
"  position: fixed;\n" +
"  top: 0;\n" +
"  right: 0;\n" +
"  bottom: 0;\n" +
"  left: 0;\n" +
"  flex-direction: row;\n" +
"  align-items: center;\n" +
"  justify-content: center;\n" +
"  padding: 10px;\n" +
"  background-color: transparent;\n" +
"  z-index: 1060;\n" +
"  overflow-x: hidden;\n" +
"  -webkit-overflow-scrolling: touch; }\n" +
"  .swal2-container.swal2-top {\n" +
"    align-items: flex-start; }\n" +
"  .swal2-container.swal2-top-start, .swal2-container.swal2-top-left {\n" +
"    align-items: flex-start;\n" +
"    justify-content: flex-start; }\n" +
"  .swal2-container.swal2-top-end, .swal2-container.swal2-top-right {\n" +
"    align-items: flex-start;\n" +
"    justify-content: flex-end; }\n" +
"  .swal2-container.swal2-center {\n" +
"    align-items: center; }\n" +
"  .swal2-container.swal2-center-start, .swal2-container.swal2-center-left {\n" +
"    align-items: center;\n" +
"    justify-content: flex-start; }\n" +
"  .swal2-container.swal2-center-end, .swal2-container.swal2-center-right {\n" +
"    align-items: center;\n" +
"    justify-content: flex-end; }\n" +
"  .swal2-container.swal2-bottom {\n" +
"    align-items: flex-end; }\n" +
"  .swal2-container.swal2-bottom-start, .swal2-container.swal2-bottom-left {\n" +
"    align-items: flex-end;\n" +
"    justify-content: flex-start; }\n" +
"  .swal2-container.swal2-bottom-end, .swal2-container.swal2-bottom-right {\n" +
"    align-items: flex-end;\n" +
"    justify-content: flex-end; }\n" +
"  .swal2-container.swal2-grow-fullscreen > .swal2-modal {\n" +
"    display: flex !important;\n" +
"    flex: 1;\n" +
"    align-self: stretch;\n" +
"    justify-content: center; }\n" +
"  .swal2-container.swal2-grow-row > .swal2-modal {\n" +
"    display: flex !important;\n" +
"    flex: 1;\n" +
"    align-content: center;\n" +
"    justify-content: center; }\n" +
"  .swal2-container.swal2-grow-column {\n" +
"    flex: 1;\n" +
"    flex-direction: column; }\n" +
"    .swal2-container.swal2-grow-column.swal2-top, .swal2-container.swal2-grow-column.swal2-center, .swal2-container.swal2-grow-column.swal2-bottom {\n" +
"      align-items: center; }\n" +
"    .swal2-container.swal2-grow-column.swal2-top-start, .swal2-container.swal2-grow-column.swal2-center-start, .swal2-container.swal2-grow-column.swal2-bottom-start, .swal2-container.swal2-grow-column.swal2-top-left, .swal2-container.swal2-grow-column.swal2-center-left, .swal2-container.swal2-grow-column.swal2-bottom-left {\n" +
"      align-items: flex-start; }\n" +
"    .swal2-container.swal2-grow-column.swal2-top-end, .swal2-container.swal2-grow-column.swal2-center-end, .swal2-container.swal2-grow-column.swal2-bottom-end, .swal2-container.swal2-grow-column.swal2-top-right, .swal2-container.swal2-grow-column.swal2-center-right, .swal2-container.swal2-grow-column.swal2-bottom-right {\n" +
"      align-items: flex-end; }\n" +
"    .swal2-container.swal2-grow-column > .swal2-modal {\n" +
"      display: flex !important;\n" +
"      flex: 1;\n" +
"      align-content: center;\n" +
"      justify-content: center; }\n" +
"  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen) > .swal2-modal {\n" +
"    margin: auto; }\n" +
"  @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {\n" +
"    .swal2-container .swal2-modal {\n" +
"      margin: 0 !important; } }\n" +
"  .swal2-container.swal2-fade {\n" +
"    transition: background-color .1s; }\n" +
"  .swal2-container.swal2-shown {\n" +
"    background-color: rgba(0, 0, 0, 0.4); }\n" +
"\n" +
".swal2-popup {\n" +
"  display: none;\n" +
"  position: relative;\n" +
"  flex-direction: column;\n" +
"  justify-content: center;\n" +
"  width: 32em;\n" +
"  max-width: 100%;\n" +
"  padding: 1.25em;\n" +
"  border-radius: 0.3125em;\n" +
"  background: #fff;\n" +
"  font-family: inherit;\n" +
"  font-size: 1rem;\n" +
"  box-sizing: border-box; }\n" +
"  .swal2-popup:focus {\n" +
"    outline: none; }\n" +
"  .swal2-popup.swal2-loading {\n" +
"    overflow-y: hidden; }\n" +
"  .swal2-popup .swal2-header {\n" +
"    display: flex;\n" +
"    flex-direction: column;\n" +
"    align-items: center; }\n" +
"  .swal2-popup .swal2-title {\n" +
"    display: block;\n" +
"    position: relative;\n" +
"    max-width: 100%;\n" +
"    margin: 0 0 0.4em;\n" +
"    padding: 0;\n" +
"    color: #595959;\n" +
"    font-size: 1.875em;\n" +
"    font-weight: 600;\n" +
"    text-align: center;\n" +
"    text-transform: none;\n" +
"    word-wrap: break-word; }\n" +
"  .swal2-popup .swal2-actions {\n" +
"    align-items: center;\n" +
"    justify-content: center;\n" +
"    margin: 1.25em auto 0;\n" +
"    z-index: 1; }\n" +
"    .swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled[disabled] {\n" +
"      opacity: .4; }\n" +
"    .swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled:hover {\n" +
"      background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)); }\n" +
"    .swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled:active {\n" +
"      background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)); }\n" +
"    .swal2-popup .swal2-actions.swal2-loading .swal2-styled.swal2-confirm {\n" +
"      width: 2.5em;\n" +
"      height: 2.5em;\n" +
"      margin: .46875em;\n" +
"      padding: 0;\n" +
"      border: .25em solid transparent;\n" +
"      border-radius: 100%;\n" +
"      border-color: transparent;\n" +
"      background-color: transparent !important;\n" +
"      color: transparent;\n" +
"      cursor: default;\n" +
"      box-sizing: border-box;\n" +
"      -webkit-animation: swal2-rotate-loading 1.5s linear 0s infinite normal;\n" +
"              animation: swal2-rotate-loading 1.5s linear 0s infinite normal;\n" +
"      -webkit-user-select: none;\n" +
"         -moz-user-select: none;\n" +
"          -ms-user-select: none;\n" +
"              user-select: none; }\n" +
"    .swal2-popup .swal2-actions.swal2-loading .swal2-styled.swal2-cancel {\n" +
"      margin-right: 30px;\n" +
"      margin-left: 30px; }\n" +
"    .swal2-popup .swal2-actions.swal2-loading :not(.swal2-styled).swal2-confirm::after {\n" +
"      display: inline-block;\n" +
"      width: 15px;\n" +
"      height: 15px;\n" +
"      margin-left: 5px;\n" +
"      border: 3px solid #999999;\n" +
"      border-radius: 50%;\n" +
"      border-right-color: transparent;\n" +
"      box-shadow: 1px 1px 1px #fff;\n" +
"      content: '';\n" +
"      -webkit-animation: swal2-rotate-loading 1.5s linear 0s infinite normal;\n" +
"              animation: swal2-rotate-loading 1.5s linear 0s infinite normal; }\n" +
"  .swal2-popup .swal2-styled {\n" +
"    margin: 0 .3125em;\n" +
"    padding: .625em 2em;\n" +
"    font-weight: 500;\n" +
"    box-shadow: none; }\n" +
"    .swal2-popup .swal2-styled:not([disabled]) {\n" +
"      cursor: pointer; }\n" +
"    .swal2-popup .swal2-styled.swal2-confirm {\n" +
"      border: 0;\n" +
"      border-radius: 0.25em;\n" +
"      background: initial;\n" +
"      background-color: #3085d6;\n" +
"      color: #fff;\n" +
"      font-size: 1.0625em; }\n" +
"    .swal2-popup .swal2-styled.swal2-cancel {\n" +
"      border: 0;\n" +
"      border-radius: 0.25em;\n" +
"      background: initial;\n" +
"      background-color: #aaa;\n" +
"      color: #fff;\n" +
"      font-size: 1.0625em; }\n" +
"    .swal2-popup .swal2-styled:focus {\n" +
"      outline: none;\n" +
"      box-shadow: 0 0 0 2px #fff, 0 0 0 4px rgba(50, 100, 150, 0.4); }\n" +
"    .swal2-popup .swal2-styled::-moz-focus-inner {\n" +
"      border: 0; }\n" +
"  .swal2-popup .swal2-footer {\n" +
"    justify-content: center;\n" +
"    margin: 1.25em 0 0;\n" +
"    padding: 1em 0 0;\n" +
"    border-top: 1px solid #eee;\n" +
"    color: #545454;\n" +
"    font-size: 1em; }\n" +
"  .swal2-popup .swal2-image {\n" +
"    max-width: 100%;\n" +
"    margin: 1.25em auto; }\n" +
"  .swal2-popup .swal2-close {\n" +
"    position: absolute;\n" +
"    top: 0;\n" +
"    right: 0;\n" +
"    justify-content: center;\n" +
"    width: 1.2em;\n" +
"    height: 1.2em;\n" +
"    padding: 0;\n" +
"    transition: color 0.1s ease-out;\n" +
"    border: none;\n" +
"    border-radius: 0;\n" +
"    background: transparent;\n" +
"    color: #cccccc;\n" +
"    font-family: serif;\n" +
"    font-size: 2.5em;\n" +
"    line-height: 1.2;\n" +
"    cursor: pointer;\n" +
"    overflow: hidden; }\n" +
"    .swal2-popup .swal2-close:hover {\n" +
"      -webkit-transform: none;\n" +
"              transform: none;\n" +
"      color: #f27474; }\n" +
"  .swal2-popup > .swal2-input,\n" +
"  .swal2-popup > .swal2-file,\n" +
"  .swal2-popup > .swal2-textarea,\n" +
"  .swal2-popup > .swal2-select,\n" +
"  .swal2-popup > .swal2-radio,\n" +
"  .swal2-popup > .swal2-checkbox {\n" +
"    display: none; }\n" +
"  .swal2-popup .swal2-content {\n" +
"    justify-content: center;\n" +
"    margin: 0;\n" +
"    padding: 0;\n" +
"    color: #545454;\n" +
"    font-size: 1.125em;\n" +
"    font-weight: 300;\n" +
"    line-height: normal;\n" +
"    z-index: 1;\n" +
"    word-wrap: break-word; }\n" +
"  .swal2-popup #swal2-content {\n" +
"    text-align: center; }\n" +
"  .swal2-popup .swal2-input,\n" +
"  .swal2-popup .swal2-file,\n" +
"  .swal2-popup .swal2-textarea,\n" +
"  .swal2-popup .swal2-select,\n" +
"  .swal2-popup .swal2-radio,\n" +
"  .swal2-popup .swal2-checkbox {\n" +
"    margin: 1em auto; }\n" +
"  .swal2-popup .swal2-input,\n" +
"  .swal2-popup .swal2-file,\n" +
"  .swal2-popup .swal2-textarea {\n" +
"    width: 100%;\n" +
"    transition: border-color .3s, box-shadow .3s;\n" +
"    border: 1px solid #d9d9d9;\n" +
"    border-radius: 0.1875em;\n" +
"    font-size: 1.125em;\n" +
"    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.06);\n" +
"    box-sizing: border-box; }\n" +
"    .swal2-popup .swal2-input.swal2-inputerror,\n" +
"    .swal2-popup .swal2-file.swal2-inputerror,\n" +
"    .swal2-popup .swal2-textarea.swal2-inputerror {\n" +
"      border-color: #f27474 !important;\n" +
"      box-shadow: 0 0 2px #f27474 !important; }\n" +
"    .swal2-popup .swal2-input:focus,\n" +
"    .swal2-popup .swal2-file:focus,\n" +
"    .swal2-popup .swal2-textarea:focus {\n" +
"      border: 1px solid #b4dbed;\n" +
"      outline: none;\n" +
"      box-shadow: 0 0 3px #c4e6f5; }\n" +
"    .swal2-popup .swal2-input::-webkit-input-placeholder,\n" +
"    .swal2-popup .swal2-file::-webkit-input-placeholder,\n" +
"    .swal2-popup .swal2-textarea::-webkit-input-placeholder {\n" +
"      color: #cccccc; }\n" +
"    .swal2-popup .swal2-input:-ms-input-placeholder,\n" +
"    .swal2-popup .swal2-file:-ms-input-placeholder,\n" +
"    .swal2-popup .swal2-textarea:-ms-input-placeholder {\n" +
"      color: #cccccc; }\n" +
"    .swal2-popup .swal2-input::-ms-input-placeholder,\n" +
"    .swal2-popup .swal2-file::-ms-input-placeholder,\n" +
"    .swal2-popup .swal2-textarea::-ms-input-placeholder {\n" +
"      color: #cccccc; }\n" +
"    .swal2-popup .swal2-input::placeholder,\n" +
"    .swal2-popup .swal2-file::placeholder,\n" +
"    .swal2-popup .swal2-textarea::placeholder {\n" +
"      color: #cccccc; }\n" +
"  .swal2-popup .swal2-range input {\n" +
"    width: 80%; }\n" +
"  .swal2-popup .swal2-range output {\n" +
"    width: 20%;\n" +
"    font-weight: 600;\n" +
"    text-align: center; }\n" +
"  .swal2-popup .swal2-range input,\n" +
"  .swal2-popup .swal2-range output {\n" +
"    height: 2.625em;\n" +
"    margin: 1em auto;\n" +
"    padding: 0;\n" +
"    font-size: 1.125em;\n" +
"    line-height: 2.625em; }\n" +
"  .swal2-popup .swal2-input {\n" +
"    height: 2.625em;\n" +
"    padding: 0.75em; }\n" +
"    .swal2-popup .swal2-input[type='number'] {\n" +
"      max-width: 10em; }\n" +
"  .swal2-popup .swal2-file {\n" +
"    font-size: 1.125em; }\n" +
"  .swal2-popup .swal2-textarea {\n" +
"    height: 6.75em;\n" +
"    padding: 0.75em; }\n" +
"  .swal2-popup .swal2-select {\n" +
"    min-width: 50%;\n" +
"    max-width: 100%;\n" +
"    padding: .375em .625em;\n" +
"    color: #545454;\n" +
"    font-size: 1.125em; }\n" +
"  .swal2-popup .swal2-radio,\n" +
"  .swal2-popup .swal2-checkbox {\n" +
"    align-items: center;\n" +
"    justify-content: center; }\n" +
"    .swal2-popup .swal2-radio label,\n" +
"    .swal2-popup .swal2-checkbox label {\n" +
"      margin: 0 .6em;\n" +
"      font-size: 1.125em; }\n" +
"    .swal2-popup .swal2-radio input,\n" +
"    .swal2-popup .swal2-checkbox input {\n" +
"      margin: 0 .4em; }\n" +
"  .swal2-popup .swal2-validationerror {\n" +
"    display: none;\n" +
"    align-items: center;\n" +
"    justify-content: center;\n" +
"    padding: 0.625em;\n" +
"    background: #f0f0f0;\n" +
"    color: #666666;\n" +
"    font-size: 1em;\n" +
"    font-weight: 300;\n" +
"    overflow: hidden; }\n" +
"    .swal2-popup .swal2-validationerror::before {\n" +
"      display: inline-block;\n" +
"      width: 1.5em;\n" +
"      min-width: 1.5em;\n" +
"      height: 1.5em;\n" +
"      margin: 0 .625em;\n" +
"      border-radius: 50%;\n" +
"      background-color: #f27474;\n" +
"      color: #fff;\n" +
"      font-weight: 600;\n" +
"      line-height: 1.5em;\n" +
"      text-align: center;\n" +
"      content: '!';\n" +
"      zoom: normal; }\n" +
"\n" +
"@supports (-ms-accelerator: true) {\n" +
"  .swal2-range input {\n" +
"    width: 100% !important; }\n" +
"  .swal2-range output {\n" +
"    display: none; } }\n" +
"\n" +
"@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {\n" +
"  .swal2-range input {\n" +
"    width: 100% !important; }\n" +
"  .swal2-range output {\n" +
"    display: none; } }\n" +
"\n" +
"@-moz-document url-prefix() {\n" +
"  .swal2-close:focus {\n" +
"    outline: 2px solid rgba(50, 100, 150, 0.4); } }\n" +
"\n" +
".swal2-icon {\n" +
"  position: relative;\n" +
"  justify-content: center;\n" +
"  width: 5em;\n" +
"  height: 5em;\n" +
"  margin: 1.25em auto 1.875em;\n" +
"  border: .25em solid transparent;\n" +
"  border-radius: 50%;\n" +
"  line-height: 5em;\n" +
"  cursor: default;\n" +
"  box-sizing: content-box;\n" +
"  -webkit-user-select: none;\n" +
"     -moz-user-select: none;\n" +
"      -ms-user-select: none;\n" +
"          user-select: none;\n" +
"  zoom: normal; }\n" +
"  .swal2-icon-text {\n" +
"    font-size: 3.75em; }\n" +
"  .swal2-icon.swal2-error {\n" +
"    border-color: #f27474; }\n" +
"    .swal2-icon.swal2-error .swal2-x-mark {\n" +
"      position: relative;\n" +
"      flex-grow: 1; }\n" +
"    .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {\n" +
"      display: block;\n" +
"      position: absolute;\n" +
"      top: 2.3125em;\n" +
"      width: 2.9375em;\n" +
"      height: .3125em;\n" +
"      border-radius: .125em;\n" +
"      background-color: #f27474; }\n" +
"      .swal2-icon.swal2-error [class^='swal2-x-mark-line'][class$='left'] {\n" +
"        left: 1.0625em;\n" +
"        -webkit-transform: rotate(45deg);\n" +
"                transform: rotate(45deg); }\n" +
"      .swal2-icon.swal2-error [class^='swal2-x-mark-line'][class$='right'] {\n" +
"        right: 1em;\n" +
"        -webkit-transform: rotate(-45deg);\n" +
"                transform: rotate(-45deg); }\n" +
"  .swal2-icon.swal2-warning {\n" +
"    border-color: #facea8;\n" +
"    color: #f8bb86; }\n" +
"  .swal2-icon.swal2-info {\n" +
"    border-color: #9de0f6;\n" +
"    color: #3fc3ee; }\n" +
"  .swal2-icon.swal2-question {\n" +
"    border-color: #c9dae1;\n" +
"    color: #87adbd; }\n" +
"  .swal2-icon.swal2-success {\n" +
"    border-color: #a5dc86; }\n" +
"    .swal2-icon.swal2-success [class^='swal2-success-circular-line'] {\n" +
"      position: absolute;\n" +
"      width: 3.75em;\n" +
"      height: 7.5em;\n" +
"      -webkit-transform: rotate(45deg);\n" +
"              transform: rotate(45deg);\n" +
"      border-radius: 50%; }\n" +
"      .swal2-icon.swal2-success [class^='swal2-success-circular-line'][class$='left'] {\n" +
"        top: -.4375em;\n" +
"        left: -2.0635em;\n" +
"        -webkit-transform: rotate(-45deg);\n" +
"                transform: rotate(-45deg);\n" +
"        -webkit-transform-origin: 3.75em 3.75em;\n" +
"                transform-origin: 3.75em 3.75em;\n" +
"        border-radius: 7.5em 0 0 7.5em; }\n" +
"      .swal2-icon.swal2-success [class^='swal2-success-circular-line'][class$='right'] {\n" +
"        top: -.6875em;\n" +
"        left: 1.875em;\n" +
"        -webkit-transform: rotate(-45deg);\n" +
"                transform: rotate(-45deg);\n" +
"        -webkit-transform-origin: 0 3.75em;\n" +
"                transform-origin: 0 3.75em;\n" +
"        border-radius: 0 7.5em 7.5em 0; }\n" +
"    .swal2-icon.swal2-success .swal2-success-ring {\n" +
"      position: absolute;\n" +
"      top: -.25em;\n" +
"      left: -.25em;\n" +
"      width: 100%;\n" +
"      height: 100%;\n" +
"      border: 0.25em solid rgba(165, 220, 134, 0.3);\n" +
"      border-radius: 50%;\n" +
"      z-index: 2;\n" +
"      box-sizing: content-box; }\n" +
"    .swal2-icon.swal2-success .swal2-success-fix {\n" +
"      position: absolute;\n" +
"      top: .5em;\n" +
"      left: 1.625em;\n" +
"      width: .4375em;\n" +
"      height: 5.625em;\n" +
"      -webkit-transform: rotate(-45deg);\n" +
"              transform: rotate(-45deg);\n" +
"      z-index: 1; }\n" +
"    .swal2-icon.swal2-success [class^='swal2-success-line'] {\n" +
"      display: block;\n" +
"      position: absolute;\n" +
"      height: .3125em;\n" +
"      border-radius: .125em;\n" +
"      background-color: #a5dc86;\n" +
"      z-index: 2; }\n" +
"      .swal2-icon.swal2-success [class^='swal2-success-line'][class$='tip'] {\n" +
"        top: 2.875em;\n" +
"        left: .875em;\n" +
"        width: 1.5625em;\n" +
"        -webkit-transform: rotate(45deg);\n" +
"                transform: rotate(45deg); }\n" +
"      .swal2-icon.swal2-success [class^='swal2-success-line'][class$='long'] {\n" +
"        top: 2.375em;\n" +
"        right: .5em;\n" +
"        width: 2.9375em;\n" +
"        -webkit-transform: rotate(-45deg);\n" +
"                transform: rotate(-45deg); }\n" +
"\n" +
".swal2-progresssteps {\n" +
"  align-items: center;\n" +
"  margin: 0 0 1.25em;\n" +
"  padding: 0;\n" +
"  font-weight: 600; }\n" +
"  .swal2-progresssteps li {\n" +
"    display: inline-block;\n" +
"    position: relative; }\n" +
"  .swal2-progresssteps .swal2-progresscircle {\n" +
"    width: 2em;\n" +
"    height: 2em;\n" +
"    border-radius: 2em;\n" +
"    background: #3085d6;\n" +
"    color: #fff;\n" +
"    line-height: 2em;\n" +
"    text-align: center;\n" +
"    z-index: 20; }\n" +
"    .swal2-progresssteps .swal2-progresscircle:first-child {\n" +
"      margin-left: 0; }\n" +
"    .swal2-progresssteps .swal2-progresscircle:last-child {\n" +
"      margin-right: 0; }\n" +
"    .swal2-progresssteps .swal2-progresscircle.swal2-activeprogressstep {\n" +
"      background: #3085d6; }\n" +
"      .swal2-progresssteps .swal2-progresscircle.swal2-activeprogressstep ~ .swal2-progresscircle {\n" +
"        background: #add8e6; }\n" +
"      .swal2-progresssteps .swal2-progresscircle.swal2-activeprogressstep ~ .swal2-progressline {\n" +
"        background: #add8e6; }\n" +
"  .swal2-progresssteps .swal2-progressline {\n" +
"    width: 2.5em;\n" +
"    height: .4em;\n" +
"    margin: 0 -1px;\n" +
"    background: #3085d6;\n" +
"    z-index: 10; }\n" +
"\n" +
"[class^='swal2'] {\n" +
"  -webkit-tap-highlight-color: transparent; }\n" +
"\n" +
".swal2-show {\n" +
"  -webkit-animation: swal2-show 0.3s;\n" +
"          animation: swal2-show 0.3s; }\n" +
"  .swal2-show.swal2-noanimation {\n" +
"    -webkit-animation: none;\n" +
"            animation: none; }\n" +
"\n" +
".swal2-hide {\n" +
"  -webkit-animation: swal2-hide 0.15s forwards;\n" +
"          animation: swal2-hide 0.15s forwards; }\n" +
"  .swal2-hide.swal2-noanimation {\n" +
"    -webkit-animation: none;\n" +
"            animation: none; }\n" +
"\n" +
"[dir='rtl'] .swal2-close {\n" +
"  right: auto;\n" +
"  left: 0; }\n" +
"\n" +
".swal2-animate-success-icon .swal2-success-line-tip {\n" +
"  -webkit-animation: swal2-animate-success-line-tip 0.75s;\n" +
"          animation: swal2-animate-success-line-tip 0.75s; }\n" +
"\n" +
".swal2-animate-success-icon .swal2-success-line-long {\n" +
"  -webkit-animation: swal2-animate-success-line-long 0.75s;\n" +
"          animation: swal2-animate-success-line-long 0.75s; }\n" +
"\n" +
".swal2-animate-success-icon .swal2-success-circular-line-right {\n" +
"  -webkit-animation: swal2-rotate-success-circular-line 4.25s ease-in;\n" +
"          animation: swal2-rotate-success-circular-line 4.25s ease-in; }\n" +
"\n" +
".swal2-animate-error-icon {\n" +
"  -webkit-animation: swal2-animate-error-icon 0.5s;\n" +
"          animation: swal2-animate-error-icon 0.5s; }\n" +
"  .swal2-animate-error-icon .swal2-x-mark {\n" +
"    -webkit-animation: swal2-animate-error-x-mark 0.5s;\n" +
"            animation: swal2-animate-error-x-mark 0.5s; }\n" +
"\n" +
"@-webkit-keyframes swal2-rotate-loading {\n" +
"  0% {\n" +
"    -webkit-transform: rotate(0deg);\n" +
"            transform: rotate(0deg); }\n" +
"  100% {\n" +
"    -webkit-transform: rotate(360deg);\n" +
"            transform: rotate(360deg); } }\n" +
"\n" +
"@keyframes swal2-rotate-loading {\n" +
"  0% {\n" +
"    -webkit-transform: rotate(0deg);\n" +
"            transform: rotate(0deg); }\n" +
"  100% {\n" +
"    -webkit-transform: rotate(360deg);\n" +
"            transform: rotate(360deg); } }");

/***/ }),

/***/ "./node_modules/underscore/underscore.js":
/*!***********************************************!*\
  !*** ./node_modules/underscore/underscore.js ***!
  \***********************************************/
/*! dynamic exports provided */
/*! exports used: default */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, module) {var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;//     Underscore.js 1.9.1
//     http://underscorejs.org
//     (c) 2009-2018 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.

(function() {

  // Baseline setup
  // --------------

  // Establish the root object, `window` (`self`) in the browser, `global`
  // on the server, or `this` in some virtual machines. We use `self`
  // instead of `window` for `WebWorker` support.
  var root = typeof self == 'object' && self.self === self && self ||
            typeof global == 'object' && global.global === global && global ||
            this ||
            {};

  // Save the previous value of the `_` variable.
  var previousUnderscore = root._;

  // Save bytes in the minified (but not gzipped) version:
  var ArrayProto = Array.prototype, ObjProto = Object.prototype;
  var SymbolProto = typeof Symbol !== 'undefined' ? Symbol.prototype : null;

  // Create quick reference variables for speed access to core prototypes.
  var push = ArrayProto.push,
      slice = ArrayProto.slice,
      toString = ObjProto.toString,
      hasOwnProperty = ObjProto.hasOwnProperty;

  // All **ECMAScript 5** native function implementations that we hope to use
  // are declared here.
  var nativeIsArray = Array.isArray,
      nativeKeys = Object.keys,
      nativeCreate = Object.create;

  // Naked function reference for surrogate-prototype-swapping.
  var Ctor = function(){};

  // Create a safe reference to the Underscore object for use below.
  var _ = function(obj) {
    if (obj instanceof _) return obj;
    if (!(this instanceof _)) return new _(obj);
    this._wrapped = obj;
  };

  // Export the Underscore object for **Node.js**, with
  // backwards-compatibility for their old module API. If we're in
  // the browser, add `_` as a global object.
  // (`nodeType` is checked to ensure that `module`
  // and `exports` are not HTML elements.)
  if (typeof exports != 'undefined' && !exports.nodeType) {
    if (typeof module != 'undefined' && !module.nodeType && module.exports) {
      exports = module.exports = _;
    }
    exports._ = _;
  } else {
    root._ = _;
  }

  // Current version.
  _.VERSION = '1.9.1';

  // Internal function that returns an efficient (for current engines) version
  // of the passed-in callback, to be repeatedly applied in other Underscore
  // functions.
  var optimizeCb = function(func, context, argCount) {
    if (context === void 0) return func;
    switch (argCount == null ? 3 : argCount) {
      case 1: return function(value) {
        return func.call(context, value);
      };
      // The 2-argument case is omitted because we’re not using it.
      case 3: return function(value, index, collection) {
        return func.call(context, value, index, collection);
      };
      case 4: return function(accumulator, value, index, collection) {
        return func.call(context, accumulator, value, index, collection);
      };
    }
    return function() {
      return func.apply(context, arguments);
    };
  };

  var builtinIteratee;

  // An internal function to generate callbacks that can be applied to each
  // element in a collection, returning the desired result — either `identity`,
  // an arbitrary callback, a property matcher, or a property accessor.
  var cb = function(value, context, argCount) {
    if (_.iteratee !== builtinIteratee) return _.iteratee(value, context);
    if (value == null) return _.identity;
    if (_.isFunction(value)) return optimizeCb(value, context, argCount);
    if (_.isObject(value) && !_.isArray(value)) return _.matcher(value);
    return _.property(value);
  };

  // External wrapper for our callback generator. Users may customize
  // `_.iteratee` if they want additional predicate/iteratee shorthand styles.
  // This abstraction hides the internal-only argCount argument.
  _.iteratee = builtinIteratee = function(value, context) {
    return cb(value, context, Infinity);
  };

  // Some functions take a variable number of arguments, or a few expected
  // arguments at the beginning and then a variable number of values to operate
  // on. This helper accumulates all remaining arguments past the function’s
  // argument length (or an explicit `startIndex`), into an array that becomes
  // the last argument. Similar to ES6’s "rest parameter".
  var restArguments = function(func, startIndex) {
    startIndex = startIndex == null ? func.length - 1 : +startIndex;
    return function() {
      var length = Math.max(arguments.length - startIndex, 0),
          rest = Array(length),
          index = 0;
      for (; index < length; index++) {
        rest[index] = arguments[index + startIndex];
      }
      switch (startIndex) {
        case 0: return func.call(this, rest);
        case 1: return func.call(this, arguments[0], rest);
        case 2: return func.call(this, arguments[0], arguments[1], rest);
      }
      var args = Array(startIndex + 1);
      for (index = 0; index < startIndex; index++) {
        args[index] = arguments[index];
      }
      args[startIndex] = rest;
      return func.apply(this, args);
    };
  };

  // An internal function for creating a new object that inherits from another.
  var baseCreate = function(prototype) {
    if (!_.isObject(prototype)) return {};
    if (nativeCreate) return nativeCreate(prototype);
    Ctor.prototype = prototype;
    var result = new Ctor;
    Ctor.prototype = null;
    return result;
  };

  var shallowProperty = function(key) {
    return function(obj) {
      return obj == null ? void 0 : obj[key];
    };
  };

  var has = function(obj, path) {
    return obj != null && hasOwnProperty.call(obj, path);
  }

  var deepGet = function(obj, path) {
    var length = path.length;
    for (var i = 0; i < length; i++) {
      if (obj == null) return void 0;
      obj = obj[path[i]];
    }
    return length ? obj : void 0;
  };

  // Helper for collection methods to determine whether a collection
  // should be iterated as an array or as an object.
  // Related: http://people.mozilla.org/~jorendorff/es6-draft.html#sec-tolength
  // Avoids a very nasty iOS 8 JIT bug on ARM-64. #2094
  var MAX_ARRAY_INDEX = Math.pow(2, 53) - 1;
  var getLength = shallowProperty('length');
  var isArrayLike = function(collection) {
    var length = getLength(collection);
    return typeof length == 'number' && length >= 0 && length <= MAX_ARRAY_INDEX;
  };

  // Collection Functions
  // --------------------

  // The cornerstone, an `each` implementation, aka `forEach`.
  // Handles raw objects in addition to array-likes. Treats all
  // sparse array-likes as if they were dense.
  _.each = _.forEach = function(obj, iteratee, context) {
    iteratee = optimizeCb(iteratee, context);
    var i, length;
    if (isArrayLike(obj)) {
      for (i = 0, length = obj.length; i < length; i++) {
        iteratee(obj[i], i, obj);
      }
    } else {
      var keys = _.keys(obj);
      for (i = 0, length = keys.length; i < length; i++) {
        iteratee(obj[keys[i]], keys[i], obj);
      }
    }
    return obj;
  };

  // Return the results of applying the iteratee to each element.
  _.map = _.collect = function(obj, iteratee, context) {
    iteratee = cb(iteratee, context);
    var keys = !isArrayLike(obj) && _.keys(obj),
        length = (keys || obj).length,
        results = Array(length);
    for (var index = 0; index < length; index++) {
      var currentKey = keys ? keys[index] : index;
      results[index] = iteratee(obj[currentKey], currentKey, obj);
    }
    return results;
  };

  // Create a reducing function iterating left or right.
  var createReduce = function(dir) {
    // Wrap code that reassigns argument variables in a separate function than
    // the one that accesses `arguments.length` to avoid a perf hit. (#1991)
    var reducer = function(obj, iteratee, memo, initial) {
      var keys = !isArrayLike(obj) && _.keys(obj),
          length = (keys || obj).length,
          index = dir > 0 ? 0 : length - 1;
      if (!initial) {
        memo = obj[keys ? keys[index] : index];
        index += dir;
      }
      for (; index >= 0 && index < length; index += dir) {
        var currentKey = keys ? keys[index] : index;
        memo = iteratee(memo, obj[currentKey], currentKey, obj);
      }
      return memo;
    };

    return function(obj, iteratee, memo, context) {
      var initial = arguments.length >= 3;
      return reducer(obj, optimizeCb(iteratee, context, 4), memo, initial);
    };
  };

  // **Reduce** builds up a single result from a list of values, aka `inject`,
  // or `foldl`.
  _.reduce = _.foldl = _.inject = createReduce(1);

  // The right-associative version of reduce, also known as `foldr`.
  _.reduceRight = _.foldr = createReduce(-1);

  // Return the first value which passes a truth test. Aliased as `detect`.
  _.find = _.detect = function(obj, predicate, context) {
    var keyFinder = isArrayLike(obj) ? _.findIndex : _.findKey;
    var key = keyFinder(obj, predicate, context);
    if (key !== void 0 && key !== -1) return obj[key];
  };

  // Return all the elements that pass a truth test.
  // Aliased as `select`.
  _.filter = _.select = function(obj, predicate, context) {
    var results = [];
    predicate = cb(predicate, context);
    _.each(obj, function(value, index, list) {
      if (predicate(value, index, list)) results.push(value);
    });
    return results;
  };

  // Return all the elements for which a truth test fails.
  _.reject = function(obj, predicate, context) {
    return _.filter(obj, _.negate(cb(predicate)), context);
  };

  // Determine whether all of the elements match a truth test.
  // Aliased as `all`.
  _.every = _.all = function(obj, predicate, context) {
    predicate = cb(predicate, context);
    var keys = !isArrayLike(obj) && _.keys(obj),
        length = (keys || obj).length;
    for (var index = 0; index < length; index++) {
      var currentKey = keys ? keys[index] : index;
      if (!predicate(obj[currentKey], currentKey, obj)) return false;
    }
    return true;
  };

  // Determine if at least one element in the object matches a truth test.
  // Aliased as `any`.
  _.some = _.any = function(obj, predicate, context) {
    predicate = cb(predicate, context);
    var keys = !isArrayLike(obj) && _.keys(obj),
        length = (keys || obj).length;
    for (var index = 0; index < length; index++) {
      var currentKey = keys ? keys[index] : index;
      if (predicate(obj[currentKey], currentKey, obj)) return true;
    }
    return false;
  };

  // Determine if the array or object contains a given item (using `===`).
  // Aliased as `includes` and `include`.
  _.contains = _.includes = _.include = function(obj, item, fromIndex, guard) {
    if (!isArrayLike(obj)) obj = _.values(obj);
    if (typeof fromIndex != 'number' || guard) fromIndex = 0;
    return _.indexOf(obj, item, fromIndex) >= 0;
  };

  // Invoke a method (with arguments) on every item in a collection.
  _.invoke = restArguments(function(obj, path, args) {
    var contextPath, func;
    if (_.isFunction(path)) {
      func = path;
    } else if (_.isArray(path)) {
      contextPath = path.slice(0, -1);
      path = path[path.length - 1];
    }
    return _.map(obj, function(context) {
      var method = func;
      if (!method) {
        if (contextPath && contextPath.length) {
          context = deepGet(context, contextPath);
        }
        if (context == null) return void 0;
        method = context[path];
      }
      return method == null ? method : method.apply(context, args);
    });
  });

  // Convenience version of a common use case of `map`: fetching a property.
  _.pluck = function(obj, key) {
    return _.map(obj, _.property(key));
  };

  // Convenience version of a common use case of `filter`: selecting only objects
  // containing specific `key:value` pairs.
  _.where = function(obj, attrs) {
    return _.filter(obj, _.matcher(attrs));
  };

  // Convenience version of a common use case of `find`: getting the first object
  // containing specific `key:value` pairs.
  _.findWhere = function(obj, attrs) {
    return _.find(obj, _.matcher(attrs));
  };

  // Return the maximum element (or element-based computation).
  _.max = function(obj, iteratee, context) {
    var result = -Infinity, lastComputed = -Infinity,
        value, computed;
    if (iteratee == null || typeof iteratee == 'number' && typeof obj[0] != 'object' && obj != null) {
      obj = isArrayLike(obj) ? obj : _.values(obj);
      for (var i = 0, length = obj.length; i < length; i++) {
        value = obj[i];
        if (value != null && value > result) {
          result = value;
        }
      }
    } else {
      iteratee = cb(iteratee, context);
      _.each(obj, function(v, index, list) {
        computed = iteratee(v, index, list);
        if (computed > lastComputed || computed === -Infinity && result === -Infinity) {
          result = v;
          lastComputed = computed;
        }
      });
    }
    return result;
  };

  // Return the minimum element (or element-based computation).
  _.min = function(obj, iteratee, context) {
    var result = Infinity, lastComputed = Infinity,
        value, computed;
    if (iteratee == null || typeof iteratee == 'number' && typeof obj[0] != 'object' && obj != null) {
      obj = isArrayLike(obj) ? obj : _.values(obj);
      for (var i = 0, length = obj.length; i < length; i++) {
        value = obj[i];
        if (value != null && value < result) {
          result = value;
        }
      }
    } else {
      iteratee = cb(iteratee, context);
      _.each(obj, function(v, index, list) {
        computed = iteratee(v, index, list);
        if (computed < lastComputed || computed === Infinity && result === Infinity) {
          result = v;
          lastComputed = computed;
        }
      });
    }
    return result;
  };

  // Shuffle a collection.
  _.shuffle = function(obj) {
    return _.sample(obj, Infinity);
  };

  // Sample **n** random values from a collection using the modern version of the
  // [Fisher-Yates shuffle](http://en.wikipedia.org/wiki/Fisher–Yates_shuffle).
  // If **n** is not specified, returns a single random element.
  // The internal `guard` argument allows it to work with `map`.
  _.sample = function(obj, n, guard) {
    if (n == null || guard) {
      if (!isArrayLike(obj)) obj = _.values(obj);
      return obj[_.random(obj.length - 1)];
    }
    var sample = isArrayLike(obj) ? _.clone(obj) : _.values(obj);
    var length = getLength(sample);
    n = Math.max(Math.min(n, length), 0);
    var last = length - 1;
    for (var index = 0; index < n; index++) {
      var rand = _.random(index, last);
      var temp = sample[index];
      sample[index] = sample[rand];
      sample[rand] = temp;
    }
    return sample.slice(0, n);
  };

  // Sort the object's values by a criterion produced by an iteratee.
  _.sortBy = function(obj, iteratee, context) {
    var index = 0;
    iteratee = cb(iteratee, context);
    return _.pluck(_.map(obj, function(value, key, list) {
      return {
        value: value,
        index: index++,
        criteria: iteratee(value, key, list)
      };
    }).sort(function(left, right) {
      var a = left.criteria;
      var b = right.criteria;
      if (a !== b) {
        if (a > b || a === void 0) return 1;
        if (a < b || b === void 0) return -1;
      }
      return left.index - right.index;
    }), 'value');
  };

  // An internal function used for aggregate "group by" operations.
  var group = function(behavior, partition) {
    return function(obj, iteratee, context) {
      var result = partition ? [[], []] : {};
      iteratee = cb(iteratee, context);
      _.each(obj, function(value, index) {
        var key = iteratee(value, index, obj);
        behavior(result, value, key);
      });
      return result;
    };
  };

  // Groups the object's values by a criterion. Pass either a string attribute
  // to group by, or a function that returns the criterion.
  _.groupBy = group(function(result, value, key) {
    if (has(result, key)) result[key].push(value); else result[key] = [value];
  });

  // Indexes the object's values by a criterion, similar to `groupBy`, but for
  // when you know that your index values will be unique.
  _.indexBy = group(function(result, value, key) {
    result[key] = value;
  });

  // Counts instances of an object that group by a certain criterion. Pass
  // either a string attribute to count by, or a function that returns the
  // criterion.
  _.countBy = group(function(result, value, key) {
    if (has(result, key)) result[key]++; else result[key] = 1;
  });

  var reStrSymbol = /[^\ud800-\udfff]|[\ud800-\udbff][\udc00-\udfff]|[\ud800-\udfff]/g;
  // Safely create a real, live array from anything iterable.
  _.toArray = function(obj) {
    if (!obj) return [];
    if (_.isArray(obj)) return slice.call(obj);
    if (_.isString(obj)) {
      // Keep surrogate pair characters together
      return obj.match(reStrSymbol);
    }
    if (isArrayLike(obj)) return _.map(obj, _.identity);
    return _.values(obj);
  };

  // Return the number of elements in an object.
  _.size = function(obj) {
    if (obj == null) return 0;
    return isArrayLike(obj) ? obj.length : _.keys(obj).length;
  };

  // Split a collection into two arrays: one whose elements all satisfy the given
  // predicate, and one whose elements all do not satisfy the predicate.
  _.partition = group(function(result, value, pass) {
    result[pass ? 0 : 1].push(value);
  }, true);

  // Array Functions
  // ---------------

  // Get the first element of an array. Passing **n** will return the first N
  // values in the array. Aliased as `head` and `take`. The **guard** check
  // allows it to work with `_.map`.
  _.first = _.head = _.take = function(array, n, guard) {
    if (array == null || array.length < 1) return n == null ? void 0 : [];
    if (n == null || guard) return array[0];
    return _.initial(array, array.length - n);
  };

  // Returns everything but the last entry of the array. Especially useful on
  // the arguments object. Passing **n** will return all the values in
  // the array, excluding the last N.
  _.initial = function(array, n, guard) {
    return slice.call(array, 0, Math.max(0, array.length - (n == null || guard ? 1 : n)));
  };

  // Get the last element of an array. Passing **n** will return the last N
  // values in the array.
  _.last = function(array, n, guard) {
    if (array == null || array.length < 1) return n == null ? void 0 : [];
    if (n == null || guard) return array[array.length - 1];
    return _.rest(array, Math.max(0, array.length - n));
  };

  // Returns everything but the first entry of the array. Aliased as `tail` and `drop`.
  // Especially useful on the arguments object. Passing an **n** will return
  // the rest N values in the array.
  _.rest = _.tail = _.drop = function(array, n, guard) {
    return slice.call(array, n == null || guard ? 1 : n);
  };

  // Trim out all falsy values from an array.
  _.compact = function(array) {
    return _.filter(array, Boolean);
  };

  // Internal implementation of a recursive `flatten` function.
  var flatten = function(input, shallow, strict, output) {
    output = output || [];
    var idx = output.length;
    for (var i = 0, length = getLength(input); i < length; i++) {
      var value = input[i];
      if (isArrayLike(value) && (_.isArray(value) || _.isArguments(value))) {
        // Flatten current level of array or arguments object.
        if (shallow) {
          var j = 0, len = value.length;
          while (j < len) output[idx++] = value[j++];
        } else {
          flatten(value, shallow, strict, output);
          idx = output.length;
        }
      } else if (!strict) {
        output[idx++] = value;
      }
    }
    return output;
  };

  // Flatten out an array, either recursively (by default), or just one level.
  _.flatten = function(array, shallow) {
    return flatten(array, shallow, false);
  };

  // Return a version of the array that does not contain the specified value(s).
  _.without = restArguments(function(array, otherArrays) {
    return _.difference(array, otherArrays);
  });

  // Produce a duplicate-free version of the array. If the array has already
  // been sorted, you have the option of using a faster algorithm.
  // The faster algorithm will not work with an iteratee if the iteratee
  // is not a one-to-one function, so providing an iteratee will disable
  // the faster algorithm.
  // Aliased as `unique`.
  _.uniq = _.unique = function(array, isSorted, iteratee, context) {
    if (!_.isBoolean(isSorted)) {
      context = iteratee;
      iteratee = isSorted;
      isSorted = false;
    }
    if (iteratee != null) iteratee = cb(iteratee, context);
    var result = [];
    var seen = [];
    for (var i = 0, length = getLength(array); i < length; i++) {
      var value = array[i],
          computed = iteratee ? iteratee(value, i, array) : value;
      if (isSorted && !iteratee) {
        if (!i || seen !== computed) result.push(value);
        seen = computed;
      } else if (iteratee) {
        if (!_.contains(seen, computed)) {
          seen.push(computed);
          result.push(value);
        }
      } else if (!_.contains(result, value)) {
        result.push(value);
      }
    }
    return result;
  };

  // Produce an array that contains the union: each distinct element from all of
  // the passed-in arrays.
  _.union = restArguments(function(arrays) {
    return _.uniq(flatten(arrays, true, true));
  });

  // Produce an array that contains every item shared between all the
  // passed-in arrays.
  _.intersection = function(array) {
    var result = [];
    var argsLength = arguments.length;
    for (var i = 0, length = getLength(array); i < length; i++) {
      var item = array[i];
      if (_.contains(result, item)) continue;
      var j;
      for (j = 1; j < argsLength; j++) {
        if (!_.contains(arguments[j], item)) break;
      }
      if (j === argsLength) result.push(item);
    }
    return result;
  };

  // Take the difference between one array and a number of other arrays.
  // Only the elements present in just the first array will remain.
  _.difference = restArguments(function(array, rest) {
    rest = flatten(rest, true, true);
    return _.filter(array, function(value){
      return !_.contains(rest, value);
    });
  });

  // Complement of _.zip. Unzip accepts an array of arrays and groups
  // each array's elements on shared indices.
  _.unzip = function(array) {
    var length = array && _.max(array, getLength).length || 0;
    var result = Array(length);

    for (var index = 0; index < length; index++) {
      result[index] = _.pluck(array, index);
    }
    return result;
  };

  // Zip together multiple lists into a single array -- elements that share
  // an index go together.
  _.zip = restArguments(_.unzip);

  // Converts lists into objects. Pass either a single array of `[key, value]`
  // pairs, or two parallel arrays of the same length -- one of keys, and one of
  // the corresponding values. Passing by pairs is the reverse of _.pairs.
  _.object = function(list, values) {
    var result = {};
    for (var i = 0, length = getLength(list); i < length; i++) {
      if (values) {
        result[list[i]] = values[i];
      } else {
        result[list[i][0]] = list[i][1];
      }
    }
    return result;
  };

  // Generator function to create the findIndex and findLastIndex functions.
  var createPredicateIndexFinder = function(dir) {
    return function(array, predicate, context) {
      predicate = cb(predicate, context);
      var length = getLength(array);
      var index = dir > 0 ? 0 : length - 1;
      for (; index >= 0 && index < length; index += dir) {
        if (predicate(array[index], index, array)) return index;
      }
      return -1;
    };
  };

  // Returns the first index on an array-like that passes a predicate test.
  _.findIndex = createPredicateIndexFinder(1);
  _.findLastIndex = createPredicateIndexFinder(-1);

  // Use a comparator function to figure out the smallest index at which
  // an object should be inserted so as to maintain order. Uses binary search.
  _.sortedIndex = function(array, obj, iteratee, context) {
    iteratee = cb(iteratee, context, 1);
    var value = iteratee(obj);
    var low = 0, high = getLength(array);
    while (low < high) {
      var mid = Math.floor((low + high) / 2);
      if (iteratee(array[mid]) < value) low = mid + 1; else high = mid;
    }
    return low;
  };

  // Generator function to create the indexOf and lastIndexOf functions.
  var createIndexFinder = function(dir, predicateFind, sortedIndex) {
    return function(array, item, idx) {
      var i = 0, length = getLength(array);
      if (typeof idx == 'number') {
        if (dir > 0) {
          i = idx >= 0 ? idx : Math.max(idx + length, i);
        } else {
          length = idx >= 0 ? Math.min(idx + 1, length) : idx + length + 1;
        }
      } else if (sortedIndex && idx && length) {
        idx = sortedIndex(array, item);
        return array[idx] === item ? idx : -1;
      }
      if (item !== item) {
        idx = predicateFind(slice.call(array, i, length), _.isNaN);
        return idx >= 0 ? idx + i : -1;
      }
      for (idx = dir > 0 ? i : length - 1; idx >= 0 && idx < length; idx += dir) {
        if (array[idx] === item) return idx;
      }
      return -1;
    };
  };

  // Return the position of the first occurrence of an item in an array,
  // or -1 if the item is not included in the array.
  // If the array is large and already in sort order, pass `true`
  // for **isSorted** to use binary search.
  _.indexOf = createIndexFinder(1, _.findIndex, _.sortedIndex);
  _.lastIndexOf = createIndexFinder(-1, _.findLastIndex);

  // Generate an integer Array containing an arithmetic progression. A port of
  // the native Python `range()` function. See
  // [the Python documentation](http://docs.python.org/library/functions.html#range).
  _.range = function(start, stop, step) {
    if (stop == null) {
      stop = start || 0;
      start = 0;
    }
    if (!step) {
      step = stop < start ? -1 : 1;
    }

    var length = Math.max(Math.ceil((stop - start) / step), 0);
    var range = Array(length);

    for (var idx = 0; idx < length; idx++, start += step) {
      range[idx] = start;
    }

    return range;
  };

  // Chunk a single array into multiple arrays, each containing `count` or fewer
  // items.
  _.chunk = function(array, count) {
    if (count == null || count < 1) return [];
    var result = [];
    var i = 0, length = array.length;
    while (i < length) {
      result.push(slice.call(array, i, i += count));
    }
    return result;
  };

  // Function (ahem) Functions
  // ------------------

  // Determines whether to execute a function as a constructor
  // or a normal function with the provided arguments.
  var executeBound = function(sourceFunc, boundFunc, context, callingContext, args) {
    if (!(callingContext instanceof boundFunc)) return sourceFunc.apply(context, args);
    var self = baseCreate(sourceFunc.prototype);
    var result = sourceFunc.apply(self, args);
    if (_.isObject(result)) return result;
    return self;
  };

  // Create a function bound to a given object (assigning `this`, and arguments,
  // optionally). Delegates to **ECMAScript 5**'s native `Function.bind` if
  // available.
  _.bind = restArguments(function(func, context, args) {
    if (!_.isFunction(func)) throw new TypeError('Bind must be called on a function');
    var bound = restArguments(function(callArgs) {
      return executeBound(func, bound, context, this, args.concat(callArgs));
    });
    return bound;
  });

  // Partially apply a function by creating a version that has had some of its
  // arguments pre-filled, without changing its dynamic `this` context. _ acts
  // as a placeholder by default, allowing any combination of arguments to be
  // pre-filled. Set `_.partial.placeholder` for a custom placeholder argument.
  _.partial = restArguments(function(func, boundArgs) {
    var placeholder = _.partial.placeholder;
    var bound = function() {
      var position = 0, length = boundArgs.length;
      var args = Array(length);
      for (var i = 0; i < length; i++) {
        args[i] = boundArgs[i] === placeholder ? arguments[position++] : boundArgs[i];
      }
      while (position < arguments.length) args.push(arguments[position++]);
      return executeBound(func, bound, this, this, args);
    };
    return bound;
  });

  _.partial.placeholder = _;

  // Bind a number of an object's methods to that object. Remaining arguments
  // are the method names to be bound. Useful for ensuring that all callbacks
  // defined on an object belong to it.
  _.bindAll = restArguments(function(obj, keys) {
    keys = flatten(keys, false, false);
    var index = keys.length;
    if (index < 1) throw new Error('bindAll must be passed function names');
    while (index--) {
      var key = keys[index];
      obj[key] = _.bind(obj[key], obj);
    }
  });

  // Memoize an expensive function by storing its results.
  _.memoize = function(func, hasher) {
    var memoize = function(key) {
      var cache = memoize.cache;
      var address = '' + (hasher ? hasher.apply(this, arguments) : key);
      if (!has(cache, address)) cache[address] = func.apply(this, arguments);
      return cache[address];
    };
    memoize.cache = {};
    return memoize;
  };

  // Delays a function for the given number of milliseconds, and then calls
  // it with the arguments supplied.
  _.delay = restArguments(function(func, wait, args) {
    return setTimeout(function() {
      return func.apply(null, args);
    }, wait);
  });

  // Defers a function, scheduling it to run after the current call stack has
  // cleared.
  _.defer = _.partial(_.delay, _, 1);

  // Returns a function, that, when invoked, will only be triggered at most once
  // during a given window of time. Normally, the throttled function will run
  // as much as it can, without ever going more than once per `wait` duration;
  // but if you'd like to disable the execution on the leading edge, pass
  // `{leading: false}`. To disable execution on the trailing edge, ditto.
  _.throttle = function(func, wait, options) {
    var timeout, context, args, result;
    var previous = 0;
    if (!options) options = {};

    var later = function() {
      previous = options.leading === false ? 0 : _.now();
      timeout = null;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    };

    var throttled = function() {
      var now = _.now();
      if (!previous && options.leading === false) previous = now;
      var remaining = wait - (now - previous);
      context = this;
      args = arguments;
      if (remaining <= 0 || remaining > wait) {
        if (timeout) {
          clearTimeout(timeout);
          timeout = null;
        }
        previous = now;
        result = func.apply(context, args);
        if (!timeout) context = args = null;
      } else if (!timeout && options.trailing !== false) {
        timeout = setTimeout(later, remaining);
      }
      return result;
    };

    throttled.cancel = function() {
      clearTimeout(timeout);
      previous = 0;
      timeout = context = args = null;
    };

    return throttled;
  };

  // Returns a function, that, as long as it continues to be invoked, will not
  // be triggered. The function will be called after it stops being called for
  // N milliseconds. If `immediate` is passed, trigger the function on the
  // leading edge, instead of the trailing.
  _.debounce = function(func, wait, immediate) {
    var timeout, result;

    var later = function(context, args) {
      timeout = null;
      if (args) result = func.apply(context, args);
    };

    var debounced = restArguments(function(args) {
      if (timeout) clearTimeout(timeout);
      if (immediate) {
        var callNow = !timeout;
        timeout = setTimeout(later, wait);
        if (callNow) result = func.apply(this, args);
      } else {
        timeout = _.delay(later, wait, this, args);
      }

      return result;
    });

    debounced.cancel = function() {
      clearTimeout(timeout);
      timeout = null;
    };

    return debounced;
  };

  // Returns the first function passed as an argument to the second,
  // allowing you to adjust arguments, run code before and after, and
  // conditionally execute the original function.
  _.wrap = function(func, wrapper) {
    return _.partial(wrapper, func);
  };

  // Returns a negated version of the passed-in predicate.
  _.negate = function(predicate) {
    return function() {
      return !predicate.apply(this, arguments);
    };
  };

  // Returns a function that is the composition of a list of functions, each
  // consuming the return value of the function that follows.
  _.compose = function() {
    var args = arguments;
    var start = args.length - 1;
    return function() {
      var i = start;
      var result = args[start].apply(this, arguments);
      while (i--) result = args[i].call(this, result);
      return result;
    };
  };

  // Returns a function that will only be executed on and after the Nth call.
  _.after = function(times, func) {
    return function() {
      if (--times < 1) {
        return func.apply(this, arguments);
      }
    };
  };

  // Returns a function that will only be executed up to (but not including) the Nth call.
  _.before = function(times, func) {
    var memo;
    return function() {
      if (--times > 0) {
        memo = func.apply(this, arguments);
      }
      if (times <= 1) func = null;
      return memo;
    };
  };

  // Returns a function that will be executed at most one time, no matter how
  // often you call it. Useful for lazy initialization.
  _.once = _.partial(_.before, 2);

  _.restArguments = restArguments;

  // Object Functions
  // ----------------

  // Keys in IE < 9 that won't be iterated by `for key in ...` and thus missed.
  var hasEnumBug = !{toString: null}.propertyIsEnumerable('toString');
  var nonEnumerableProps = ['valueOf', 'isPrototypeOf', 'toString',
    'propertyIsEnumerable', 'hasOwnProperty', 'toLocaleString'];

  var collectNonEnumProps = function(obj, keys) {
    var nonEnumIdx = nonEnumerableProps.length;
    var constructor = obj.constructor;
    var proto = _.isFunction(constructor) && constructor.prototype || ObjProto;

    // Constructor is a special case.
    var prop = 'constructor';
    if (has(obj, prop) && !_.contains(keys, prop)) keys.push(prop);

    while (nonEnumIdx--) {
      prop = nonEnumerableProps[nonEnumIdx];
      if (prop in obj && obj[prop] !== proto[prop] && !_.contains(keys, prop)) {
        keys.push(prop);
      }
    }
  };

  // Retrieve the names of an object's own properties.
  // Delegates to **ECMAScript 5**'s native `Object.keys`.
  _.keys = function(obj) {
    if (!_.isObject(obj)) return [];
    if (nativeKeys) return nativeKeys(obj);
    var keys = [];
    for (var key in obj) if (has(obj, key)) keys.push(key);
    // Ahem, IE < 9.
    if (hasEnumBug) collectNonEnumProps(obj, keys);
    return keys;
  };

  // Retrieve all the property names of an object.
  _.allKeys = function(obj) {
    if (!_.isObject(obj)) return [];
    var keys = [];
    for (var key in obj) keys.push(key);
    // Ahem, IE < 9.
    if (hasEnumBug) collectNonEnumProps(obj, keys);
    return keys;
  };

  // Retrieve the values of an object's properties.
  _.values = function(obj) {
    var keys = _.keys(obj);
    var length = keys.length;
    var values = Array(length);
    for (var i = 0; i < length; i++) {
      values[i] = obj[keys[i]];
    }
    return values;
  };

  // Returns the results of applying the iteratee to each element of the object.
  // In contrast to _.map it returns an object.
  _.mapObject = function(obj, iteratee, context) {
    iteratee = cb(iteratee, context);
    var keys = _.keys(obj),
        length = keys.length,
        results = {};
    for (var index = 0; index < length; index++) {
      var currentKey = keys[index];
      results[currentKey] = iteratee(obj[currentKey], currentKey, obj);
    }
    return results;
  };

  // Convert an object into a list of `[key, value]` pairs.
  // The opposite of _.object.
  _.pairs = function(obj) {
    var keys = _.keys(obj);
    var length = keys.length;
    var pairs = Array(length);
    for (var i = 0; i < length; i++) {
      pairs[i] = [keys[i], obj[keys[i]]];
    }
    return pairs;
  };

  // Invert the keys and values of an object. The values must be serializable.
  _.invert = function(obj) {
    var result = {};
    var keys = _.keys(obj);
    for (var i = 0, length = keys.length; i < length; i++) {
      result[obj[keys[i]]] = keys[i];
    }
    return result;
  };

  // Return a sorted list of the function names available on the object.
  // Aliased as `methods`.
  _.functions = _.methods = function(obj) {
    var names = [];
    for (var key in obj) {
      if (_.isFunction(obj[key])) names.push(key);
    }
    return names.sort();
  };

  // An internal function for creating assigner functions.
  var createAssigner = function(keysFunc, defaults) {
    return function(obj) {
      var length = arguments.length;
      if (defaults) obj = Object(obj);
      if (length < 2 || obj == null) return obj;
      for (var index = 1; index < length; index++) {
        var source = arguments[index],
            keys = keysFunc(source),
            l = keys.length;
        for (var i = 0; i < l; i++) {
          var key = keys[i];
          if (!defaults || obj[key] === void 0) obj[key] = source[key];
        }
      }
      return obj;
    };
  };

  // Extend a given object with all the properties in passed-in object(s).
  _.extend = createAssigner(_.allKeys);

  // Assigns a given object with all the own properties in the passed-in object(s).
  // (https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Object/assign)
  _.extendOwn = _.assign = createAssigner(_.keys);

  // Returns the first key on an object that passes a predicate test.
  _.findKey = function(obj, predicate, context) {
    predicate = cb(predicate, context);
    var keys = _.keys(obj), key;
    for (var i = 0, length = keys.length; i < length; i++) {
      key = keys[i];
      if (predicate(obj[key], key, obj)) return key;
    }
  };

  // Internal pick helper function to determine if `obj` has key `key`.
  var keyInObj = function(value, key, obj) {
    return key in obj;
  };

  // Return a copy of the object only containing the whitelisted properties.
  _.pick = restArguments(function(obj, keys) {
    var result = {}, iteratee = keys[0];
    if (obj == null) return result;
    if (_.isFunction(iteratee)) {
      if (keys.length > 1) iteratee = optimizeCb(iteratee, keys[1]);
      keys = _.allKeys(obj);
    } else {
      iteratee = keyInObj;
      keys = flatten(keys, false, false);
      obj = Object(obj);
    }
    for (var i = 0, length = keys.length; i < length; i++) {
      var key = keys[i];
      var value = obj[key];
      if (iteratee(value, key, obj)) result[key] = value;
    }
    return result;
  });

  // Return a copy of the object without the blacklisted properties.
  _.omit = restArguments(function(obj, keys) {
    var iteratee = keys[0], context;
    if (_.isFunction(iteratee)) {
      iteratee = _.negate(iteratee);
      if (keys.length > 1) context = keys[1];
    } else {
      keys = _.map(flatten(keys, false, false), String);
      iteratee = function(value, key) {
        return !_.contains(keys, key);
      };
    }
    return _.pick(obj, iteratee, context);
  });

  // Fill in a given object with default properties.
  _.defaults = createAssigner(_.allKeys, true);

  // Creates an object that inherits from the given prototype object.
  // If additional properties are provided then they will be added to the
  // created object.
  _.create = function(prototype, props) {
    var result = baseCreate(prototype);
    if (props) _.extendOwn(result, props);
    return result;
  };

  // Create a (shallow-cloned) duplicate of an object.
  _.clone = function(obj) {
    if (!_.isObject(obj)) return obj;
    return _.isArray(obj) ? obj.slice() : _.extend({}, obj);
  };

  // Invokes interceptor with the obj, and then returns obj.
  // The primary purpose of this method is to "tap into" a method chain, in
  // order to perform operations on intermediate results within the chain.
  _.tap = function(obj, interceptor) {
    interceptor(obj);
    return obj;
  };

  // Returns whether an object has a given set of `key:value` pairs.
  _.isMatch = function(object, attrs) {
    var keys = _.keys(attrs), length = keys.length;
    if (object == null) return !length;
    var obj = Object(object);
    for (var i = 0; i < length; i++) {
      var key = keys[i];
      if (attrs[key] !== obj[key] || !(key in obj)) return false;
    }
    return true;
  };


  // Internal recursive comparison function for `isEqual`.
  var eq, deepEq;
  eq = function(a, b, aStack, bStack) {
    // Identical objects are equal. `0 === -0`, but they aren't identical.
    // See the [Harmony `egal` proposal](http://wiki.ecmascript.org/doku.php?id=harmony:egal).
    if (a === b) return a !== 0 || 1 / a === 1 / b;
    // `null` or `undefined` only equal to itself (strict comparison).
    if (a == null || b == null) return false;
    // `NaN`s are equivalent, but non-reflexive.
    if (a !== a) return b !== b;
    // Exhaust primitive checks
    var type = typeof a;
    if (type !== 'function' && type !== 'object' && typeof b != 'object') return false;
    return deepEq(a, b, aStack, bStack);
  };

  // Internal recursive comparison function for `isEqual`.
  deepEq = function(a, b, aStack, bStack) {
    // Unwrap any wrapped objects.
    if (a instanceof _) a = a._wrapped;
    if (b instanceof _) b = b._wrapped;
    // Compare `[[Class]]` names.
    var className = toString.call(a);
    if (className !== toString.call(b)) return false;
    switch (className) {
      // Strings, numbers, regular expressions, dates, and booleans are compared by value.
      case '[object RegExp]':
      // RegExps are coerced to strings for comparison (Note: '' + /a/i === '/a/i')
      case '[object String]':
        // Primitives and their corresponding object wrappers are equivalent; thus, `"5"` is
        // equivalent to `new String("5")`.
        return '' + a === '' + b;
      case '[object Number]':
        // `NaN`s are equivalent, but non-reflexive.
        // Object(NaN) is equivalent to NaN.
        if (+a !== +a) return +b !== +b;
        // An `egal` comparison is performed for other numeric values.
        return +a === 0 ? 1 / +a === 1 / b : +a === +b;
      case '[object Date]':
      case '[object Boolean]':
        // Coerce dates and booleans to numeric primitive values. Dates are compared by their
        // millisecond representations. Note that invalid dates with millisecond representations
        // of `NaN` are not equivalent.
        return +a === +b;
      case '[object Symbol]':
        return SymbolProto.valueOf.call(a) === SymbolProto.valueOf.call(b);
    }

    var areArrays = className === '[object Array]';
    if (!areArrays) {
      if (typeof a != 'object' || typeof b != 'object') return false;

      // Objects with different constructors are not equivalent, but `Object`s or `Array`s
      // from different frames are.
      var aCtor = a.constructor, bCtor = b.constructor;
      if (aCtor !== bCtor && !(_.isFunction(aCtor) && aCtor instanceof aCtor &&
                               _.isFunction(bCtor) && bCtor instanceof bCtor)
                          && ('constructor' in a && 'constructor' in b)) {
        return false;
      }
    }
    // Assume equality for cyclic structures. The algorithm for detecting cyclic
    // structures is adapted from ES 5.1 section 15.12.3, abstract operation `JO`.

    // Initializing stack of traversed objects.
    // It's done here since we only need them for objects and arrays comparison.
    aStack = aStack || [];
    bStack = bStack || [];
    var length = aStack.length;
    while (length--) {
      // Linear search. Performance is inversely proportional to the number of
      // unique nested structures.
      if (aStack[length] === a) return bStack[length] === b;
    }

    // Add the first object to the stack of traversed objects.
    aStack.push(a);
    bStack.push(b);

    // Recursively compare objects and arrays.
    if (areArrays) {
      // Compare array lengths to determine if a deep comparison is necessary.
      length = a.length;
      if (length !== b.length) return false;
      // Deep compare the contents, ignoring non-numeric properties.
      while (length--) {
        if (!eq(a[length], b[length], aStack, bStack)) return false;
      }
    } else {
      // Deep compare objects.
      var keys = _.keys(a), key;
      length = keys.length;
      // Ensure that both objects contain the same number of properties before comparing deep equality.
      if (_.keys(b).length !== length) return false;
      while (length--) {
        // Deep compare each member
        key = keys[length];
        if (!(has(b, key) && eq(a[key], b[key], aStack, bStack))) return false;
      }
    }
    // Remove the first object from the stack of traversed objects.
    aStack.pop();
    bStack.pop();
    return true;
  };

  // Perform a deep comparison to check if two objects are equal.
  _.isEqual = function(a, b) {
    return eq(a, b);
  };

  // Is a given array, string, or object empty?
  // An "empty" object has no enumerable own-properties.
  _.isEmpty = function(obj) {
    if (obj == null) return true;
    if (isArrayLike(obj) && (_.isArray(obj) || _.isString(obj) || _.isArguments(obj))) return obj.length === 0;
    return _.keys(obj).length === 0;
  };

  // Is a given value a DOM element?
  _.isElement = function(obj) {
    return !!(obj && obj.nodeType === 1);
  };

  // Is a given value an array?
  // Delegates to ECMA5's native Array.isArray
  _.isArray = nativeIsArray || function(obj) {
    return toString.call(obj) === '[object Array]';
  };

  // Is a given variable an object?
  _.isObject = function(obj) {
    var type = typeof obj;
    return type === 'function' || type === 'object' && !!obj;
  };

  // Add some isType methods: isArguments, isFunction, isString, isNumber, isDate, isRegExp, isError, isMap, isWeakMap, isSet, isWeakSet.
  _.each(['Arguments', 'Function', 'String', 'Number', 'Date', 'RegExp', 'Error', 'Symbol', 'Map', 'WeakMap', 'Set', 'WeakSet'], function(name) {
    _['is' + name] = function(obj) {
      return toString.call(obj) === '[object ' + name + ']';
    };
  });

  // Define a fallback version of the method in browsers (ahem, IE < 9), where
  // there isn't any inspectable "Arguments" type.
  if (!_.isArguments(arguments)) {
    _.isArguments = function(obj) {
      return has(obj, 'callee');
    };
  }

  // Optimize `isFunction` if appropriate. Work around some typeof bugs in old v8,
  // IE 11 (#1621), Safari 8 (#1929), and PhantomJS (#2236).
  var nodelist = root.document && root.document.childNodes;
  if (typeof /./ != 'function' && typeof Int8Array != 'object' && typeof nodelist != 'function') {
    _.isFunction = function(obj) {
      return typeof obj == 'function' || false;
    };
  }

  // Is a given object a finite number?
  _.isFinite = function(obj) {
    return !_.isSymbol(obj) && isFinite(obj) && !isNaN(parseFloat(obj));
  };

  // Is the given value `NaN`?
  _.isNaN = function(obj) {
    return _.isNumber(obj) && isNaN(obj);
  };

  // Is a given value a boolean?
  _.isBoolean = function(obj) {
    return obj === true || obj === false || toString.call(obj) === '[object Boolean]';
  };

  // Is a given value equal to null?
  _.isNull = function(obj) {
    return obj === null;
  };

  // Is a given variable undefined?
  _.isUndefined = function(obj) {
    return obj === void 0;
  };

  // Shortcut function for checking if an object has a given property directly
  // on itself (in other words, not on a prototype).
  _.has = function(obj, path) {
    if (!_.isArray(path)) {
      return has(obj, path);
    }
    var length = path.length;
    for (var i = 0; i < length; i++) {
      var key = path[i];
      if (obj == null || !hasOwnProperty.call(obj, key)) {
        return false;
      }
      obj = obj[key];
    }
    return !!length;
  };

  // Utility Functions
  // -----------------

  // Run Underscore.js in *noConflict* mode, returning the `_` variable to its
  // previous owner. Returns a reference to the Underscore object.
  _.noConflict = function() {
    root._ = previousUnderscore;
    return this;
  };

  // Keep the identity function around for default iteratees.
  _.identity = function(value) {
    return value;
  };

  // Predicate-generating functions. Often useful outside of Underscore.
  _.constant = function(value) {
    return function() {
      return value;
    };
  };

  _.noop = function(){};

  // Creates a function that, when passed an object, will traverse that object’s
  // properties down the given `path`, specified as an array of keys or indexes.
  _.property = function(path) {
    if (!_.isArray(path)) {
      return shallowProperty(path);
    }
    return function(obj) {
      return deepGet(obj, path);
    };
  };

  // Generates a function for a given object that returns a given property.
  _.propertyOf = function(obj) {
    if (obj == null) {
      return function(){};
    }
    return function(path) {
      return !_.isArray(path) ? obj[path] : deepGet(obj, path);
    };
  };

  // Returns a predicate for checking whether an object has a given set of
  // `key:value` pairs.
  _.matcher = _.matches = function(attrs) {
    attrs = _.extendOwn({}, attrs);
    return function(obj) {
      return _.isMatch(obj, attrs);
    };
  };

  // Run a function **n** times.
  _.times = function(n, iteratee, context) {
    var accum = Array(Math.max(0, n));
    iteratee = optimizeCb(iteratee, context, 1);
    for (var i = 0; i < n; i++) accum[i] = iteratee(i);
    return accum;
  };

  // Return a random integer between min and max (inclusive).
  _.random = function(min, max) {
    if (max == null) {
      max = min;
      min = 0;
    }
    return min + Math.floor(Math.random() * (max - min + 1));
  };

  // A (possibly faster) way to get the current timestamp as an integer.
  _.now = Date.now || function() {
    return new Date().getTime();
  };

  // List of HTML entities for escaping.
  var escapeMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#x27;',
    '`': '&#x60;'
  };
  var unescapeMap = _.invert(escapeMap);

  // Functions for escaping and unescaping strings to/from HTML interpolation.
  var createEscaper = function(map) {
    var escaper = function(match) {
      return map[match];
    };
    // Regexes for identifying a key that needs to be escaped.
    var source = '(?:' + _.keys(map).join('|') + ')';
    var testRegexp = RegExp(source);
    var replaceRegexp = RegExp(source, 'g');
    return function(string) {
      string = string == null ? '' : '' + string;
      return testRegexp.test(string) ? string.replace(replaceRegexp, escaper) : string;
    };
  };
  _.escape = createEscaper(escapeMap);
  _.unescape = createEscaper(unescapeMap);

  // Traverses the children of `obj` along `path`. If a child is a function, it
  // is invoked with its parent as context. Returns the value of the final
  // child, or `fallback` if any child is undefined.
  _.result = function(obj, path, fallback) {
    if (!_.isArray(path)) path = [path];
    var length = path.length;
    if (!length) {
      return _.isFunction(fallback) ? fallback.call(obj) : fallback;
    }
    for (var i = 0; i < length; i++) {
      var prop = obj == null ? void 0 : obj[path[i]];
      if (prop === void 0) {
        prop = fallback;
        i = length; // Ensure we don't continue iterating.
      }
      obj = _.isFunction(prop) ? prop.call(obj) : prop;
    }
    return obj;
  };

  // Generate a unique integer id (unique within the entire client session).
  // Useful for temporary DOM ids.
  var idCounter = 0;
  _.uniqueId = function(prefix) {
    var id = ++idCounter + '';
    return prefix ? prefix + id : id;
  };

  // By default, Underscore uses ERB-style template delimiters, change the
  // following template settings to use alternative delimiters.
  _.templateSettings = {
    evaluate: /<%([\s\S]+?)%>/g,
    interpolate: /<%=([\s\S]+?)%>/g,
    escape: /<%-([\s\S]+?)%>/g
  };

  // When customizing `templateSettings`, if you don't want to define an
  // interpolation, evaluation or escaping regex, we need one that is
  // guaranteed not to match.
  var noMatch = /(.)^/;

  // Certain characters need to be escaped so that they can be put into a
  // string literal.
  var escapes = {
    "'": "'",
    '\\': '\\',
    '\r': 'r',
    '\n': 'n',
    '\u2028': 'u2028',
    '\u2029': 'u2029'
  };

  var escapeRegExp = /\\|'|\r|\n|\u2028|\u2029/g;

  var escapeChar = function(match) {
    return '\\' + escapes[match];
  };

  // JavaScript micro-templating, similar to John Resig's implementation.
  // Underscore templating handles arbitrary delimiters, preserves whitespace,
  // and correctly escapes quotes within interpolated code.
  // NB: `oldSettings` only exists for backwards compatibility.
  _.template = function(text, settings, oldSettings) {
    if (!settings && oldSettings) settings = oldSettings;
    settings = _.defaults({}, settings, _.templateSettings);

    // Combine delimiters into one regular expression via alternation.
    var matcher = RegExp([
      (settings.escape || noMatch).source,
      (settings.interpolate || noMatch).source,
      (settings.evaluate || noMatch).source
    ].join('|') + '|$', 'g');

    // Compile the template source, escaping string literals appropriately.
    var index = 0;
    var source = "__p+='";
    text.replace(matcher, function(match, escape, interpolate, evaluate, offset) {
      source += text.slice(index, offset).replace(escapeRegExp, escapeChar);
      index = offset + match.length;

      if (escape) {
        source += "'+\n((__t=(" + escape + "))==null?'':_.escape(__t))+\n'";
      } else if (interpolate) {
        source += "'+\n((__t=(" + interpolate + "))==null?'':__t)+\n'";
      } else if (evaluate) {
        source += "';\n" + evaluate + "\n__p+='";
      }

      // Adobe VMs need the match returned to produce the correct offset.
      return match;
    });
    source += "';\n";

    // If a variable is not specified, place data values in local scope.
    if (!settings.variable) source = 'with(obj||{}){\n' + source + '}\n';

    source = "var __t,__p='',__j=Array.prototype.join," +
      "print=function(){__p+=__j.call(arguments,'');};\n" +
      source + 'return __p;\n';

    var render;
    try {
      render = new Function(settings.variable || 'obj', '_', source);
    } catch (e) {
      e.source = source;
      throw e;
    }

    var template = function(data) {
      return render.call(this, data, _);
    };

    // Provide the compiled source as a convenience for precompilation.
    var argument = settings.variable || 'obj';
    template.source = 'function(' + argument + '){\n' + source + '}';

    return template;
  };

  // Add a "chain" function. Start chaining a wrapped Underscore object.
  _.chain = function(obj) {
    var instance = _(obj);
    instance._chain = true;
    return instance;
  };

  // OOP
  // ---------------
  // If Underscore is called as a function, it returns a wrapped object that
  // can be used OO-style. This wrapper holds altered versions of all the
  // underscore functions. Wrapped objects may be chained.

  // Helper function to continue chaining intermediate results.
  var chainResult = function(instance, obj) {
    return instance._chain ? _(obj).chain() : obj;
  };

  // Add your own custom functions to the Underscore object.
  _.mixin = function(obj) {
    _.each(_.functions(obj), function(name) {
      var func = _[name] = obj[name];
      _.prototype[name] = function() {
        var args = [this._wrapped];
        push.apply(args, arguments);
        return chainResult(this, func.apply(_, args));
      };
    });
    return _;
  };

  // Add all of the Underscore functions to the wrapper object.
  _.mixin(_);

  // Add all mutator Array functions to the wrapper.
  _.each(['pop', 'push', 'reverse', 'shift', 'sort', 'splice', 'unshift'], function(name) {
    var method = ArrayProto[name];
    _.prototype[name] = function() {
      var obj = this._wrapped;
      method.apply(obj, arguments);
      if ((name === 'shift' || name === 'splice') && obj.length === 0) delete obj[0];
      return chainResult(this, obj);
    };
  });

  // Add all accessor Array functions to the wrapper.
  _.each(['concat', 'join', 'slice'], function(name) {
    var method = ArrayProto[name];
    _.prototype[name] = function() {
      return chainResult(this, method.apply(this._wrapped, arguments));
    };
  });

  // Extracts the result from a wrapped and chained object.
  _.prototype.value = function() {
    return this._wrapped;
  };

  // Provide unwrapping proxy for some methods used in engine operations
  // such as arithmetic and JSON stringification.
  _.prototype.valueOf = _.prototype.toJSON = _.prototype.value;

  _.prototype.toString = function() {
    return String(this._wrapped);
  };

  // AMD registration happens at the end for compatibility with AMD loaders
  // that may not enforce next-turn semantics on modules. Even though general
  // practice for AMD registration is to be anonymous, underscore registers
  // as a named module because, like jQuery, it is a base library that is
  // popular enough to be bundled in a third party lib, but not be part of
  // an AMD load request. Those cases could generate an error when an
  // anonymous define() is called outside of a loader request.
  if (true) {
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function() {
      return _;
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  }
}());

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../webpack/buildin/module.js */ "./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ })

},["./assets/js/ccs_sm_filter.js"]);