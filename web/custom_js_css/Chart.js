'use strict';
(function (window, $) {

    window.Charts = function () {
    };

    $.extend(window.Charts.prototype, {

        multiAxisChart: function () {
            var options = new ChartOptions();
            console.log(options);
        },
        /**
         * @param renderTo
         * @param data
         * @param chartType
         * @param combination [{type:'pie', method:'sum', color:[colorArray]}]
         * @param colors
         * @param titles
         * @param legend
         * @param menu
         * @param large
         * @param yAxisFormatter
         */
        columnBarChart: function (renderTo, data = [], chartType = {type:'column'},
                                  combination = [],
                                  colors = Highcharts.getOptions().colors,
                                  titles = {xTitle: null, yTitle: null},
                                  legend = {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                                  menu = [{chart:'percent', title:'Percent Stack'},
                                      {chart: 'column', title:'Column Chart'}],
                                  large = null,
                                  yAxisFormatter = ""
                                )
        {
            var settings = {
                renderTo, data, combination, chartType, colors, titles,
                legend, menu, large, yAxisFormatter
            };

            this._createChart(settings);

        },

        lineChart: function (renderTo, data = [], chartType = {type:'line'},
                             colors = Highcharts.getOptions().colors,
                             titles = {xTitle: null, yTitle: null},
                             legend = {enabled:true, position:{vAlign:'bottom', hAlign:'center'}},
                             menu = [{chart:'spline', title:'Spline Chart'},
                                 {chart: 'line', title:'Line Chart'}],)
        {
            var settings = {
                renderTo, data, chartType, colors, titles,
                legend, menu
            };

            this._createChart(settings);

        },
        pieDonutChart: function () {

        },
        areaChart: function () {

        },
        heatmapChart: function () {

        },

        //-------------------------------------------------- Private Functions ----------------------------------------
        _createChart: function(settings)  {
            // create the general options
            var options = new ChartOptions();
            // Common properties sets here
            options.chart.renderTo = settings.renderTo;
            options.chart.type = settings.chartType.type;
            options.colors = settings.colors;
            // Add Legend To Chart
            options['legend'] = {
                enabled: settings.legend.enabled,
                align: settings.legend.position.hAlign,
                verticalAlign: settings.legend.position.vAlign,
                itemStyle: {
                    fontWeight: 'normal',
                    fontSize: '100%'
                }
            };
            // Add Menu Items If It was set
            if(settings.menu!== null) {
                var menu = settings.menu;
                for(var i = 0; i < menu.length; i++) {
                    var menuItem = this._myMenuItems('', menu[i].chart, settings.renderTo, menu[i].title);
                    options.exporting.buttons.contextButton.menuItems.push(menuItem);
                }
            }
            // Add Label Toggle Menu
            var toggleMenu = this._dataLabelToggleMenu(settings.renderTo);
            options.exporting.buttons.contextButton.menuItems.push(toggleMenu);

            var data = JSON.parse(settings.data);
            options['title'] = {text: data.title, style: {fontSize:'100%'}};
            options['subtitle'] = {text: data.subTitle, verticalAlign:'bottom', style: {fontSize:'80%'}};
            // Set the data in the setting again
            settings.data = data;
            // call the separate functions for each type of chart
            var type = settings.chartType.type;
            switch (type) {
                case "bar":
                case "column":
                    options = this._createColumnChart(options, settings);
                    break;
                case "area":
                    options = this._createAreaChart(options, settings);
                    break;
                case "pie":
                case "donut":
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
            new Highcharts.chart(options);
        },
        //private function to create pie chart
        _createPieChart: function(options, settings) {
            // set the type of chart, static in this case
            var chart =  {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                renderTo: settings.renderTo,
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
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                            fontSize: '90%'
                        }
                    },
                    showInLegend: settings.legend,
                }
            }
            // if type was halfpie
            if(settings.type === 'halfpie') {
                plot.pie.startAngle = -90;
                plot.pie.endAngle = 90;
                plot.pie.center = ['50%', '75%'];
                plot.pie.dataLabels.distance = -30;
                plot.pie.dataLabels.format = '<b>{point.percentage:.1f}%</b>';
                options.chart.margin = -20;
                if(settings.legend) {
                    //options.chart.marginRight = 60;
                    options.legend = {
                        itemDistance: 8,
                        padding: 0,
                        margin:0,
                        // align: 'right',
                        // verticalAlign: 'top',
                        // layout: 'vertical',
                        // y: 50,
                        itemStyle: {
                            fontWeight: 'normal',
                            fontSize: '90%',

                        }
                    }
                }
            } else if(settings.type === 'donut') {
                plot.pie.innerSize = 100;
                plot.pie.depth = 45;

            }
            // if area was small
            if(settings.area === 'small') {
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

            if (settings.type === 'halfpie')
                series[0].innerSize = '50%';
            options.series = series;

            return options;

        },

        //private function to create multi-axises chart
        _createMultiAxisChart: function(options, settings) {

        },

        _createAreaChart: function(options, settings) {

        },

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */
        _createHeatMap: function(options, settings) {

            var dataObj = settings.data;
            var chart = {
                type: 'heatmap',
                marginBottom: 80,
                plotBorderWidth: 1,
                renderTo: settings.renderTo,
                marginTop: 80,
            };

            options.chart = chart;
            options.title.style.fontSize = '110%';

            var xAxis = (dataObj.xAxis === undefined || dataObj.xAxis === null) ? [] : dataObj.xAxis;
            options.xAxis = [
                {
                    categories: xAxis,

                    labels: {
                        style: {
                            fontSize:'80%'
                        },
                        //autoRotation: [-30],
                        autoRotation: false
                    }
                },
                {
                    linkedTo: 0,
                    opposite: true,
                    categories: xAxis,
                    labels: {
                        style: {
                            fontSize:'80%'
                        },
                        //autoRotation: [30],
                        autoRotation: false
                    }
                }
            ];

            options.yAxis = {
                categories: (dataObj.yAxis === undefined || dataObj.yAxis === null) ? [] : dataObj.yAxis,
                title: null,
                labels: {
                    style: {
                        fontSize:'80%'
                    }
                }
            };
            options.labels = {
                style: {
                    fontSize: '80%'
                }
            };

            options.colorAxis = {
                min: (dataObj.stops === undefined || dataObj.stops === null) ? 5: dataObj.stops.minValue,
                max: (dataObj.stops === undefined || dataObj.stops === null) ? 20: dataObj.stops.maxValue,
                tickInterval: 1,
                startOnTick: false,
                endOnTick: false,
                stops: [
                    [
                        0,
                        (dataObj.stops === undefined || dataObj.stops === null) ? "#43AB0D": dataObj.stops.minColor
                    ],
                    [
                        (dataObj.stops === undefined || dataObj.stops === null) ? 0.5:dataObj.stops.midStop,
                        (dataObj.stops === undefined || dataObj.stops === null) ? "#ffd927":dataObj.stops.midColor
                    ],
                    [
                        1,
                        (dataObj.stops === undefined || dataObj.stops === null) ? "#FF0000": dataObj.stops.maxColor
                    ]
                ]
            };

            options.legend.enabled = false;

            options.tooltip = {
                formatter: function () {
                    return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> <br>'+ tooltipTitle +' children <br><b>' +
                        this.point.value + '</b> in cluster <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
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
            var height = dataObj.yAxis === undefined ? '29px' : dataObj.yAxis.length*29+"px";
            var width = dataObj.xAxis === undefined ? '50px' : dataObj.xAxis.length*50+"px";
            $('#'+settings.renderTo).css("height", height);
            $('#'+settings.renderTo).css("min-width", width);

            return options;
        },

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */
        _createLineChart: function(options, settings) {

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

        },

        /**
         * @param options
         * @param settings
         * @returns {*}
         * @private
         */
        _createColumnChart: function(options, settings) {

            var chartType = settings.chartType.type;

            if(chartType === 'area' && settings.chartType.hasOwnProperty('stacking') &&
                settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: ' +
                                 '<b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
                    split: true
                }
            } else if(chartType === 'column' && settings.chartType.hasOwnProperty('stacking') &&
                settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: ' +
                                 '<b>{point.y} ({point.percentage:.1f}%)</b><br/>',
                }
            } else if(chartType === 'bar' && settings.chartType.hasOwnProperty('stacking') &&
                settings.chartType.stacking === 'percent') {
                options['tooltip'] = {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: ' +
                                 '<b>{point.y} ({point.percentage:.1f}%)</b><br/>',
                }
            }
            // set the plat option, empty, normal, percent
            var plot = {};
            plot[chartType] = {
                dataLabels: {
                    style: {
                        fontSize: '70%',
                    }
                }
            };
            if(settings.chartType.hasOwnProperty('stacking')) {
                plot[chartType] = {
                    stacking:settings.chartType.stacking,
                    dataLabels: {
                        style: {
                            fontSize: '70%',
                        }
                    }
                }
            }
            options['plotOptions'] = plot;
            // set the yAxis title
            options.yAxis.title.text = settings.titles.yTitle;
            // Label, not clear yet
            if(settings.hasOwnProperty('label')) {
                options['labels'] = {
                    items: [{
                        html: settings.label.title,
                        style: {
                            left: settings.label.position.left,
                            top: settings.label.position.top,
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                        }
                    }]
                }
            }
            // setting dynamic categories
            var data = settings.data;
            // checking for undefined categories and series
            var categories = data.categories === null || data.categories === undefined ? [] : data.categories;
            var series = data.series === null || data.series === undefined ? [] : data.series;
            options.xAxis.categories = categories;
            options.series = series;
            // change the formatter for yAxis if not empty
            if(settings.yAxisFormatter !== "")
                options.yAxis.labels = {format: '{value}'+settings.yAxisFormatter};

            // change the height of the renderTo (dynamic height)
            var height = categories.length === 0 ? 10: categories.length;
            if(settings.large !== null && settings.large === 'height')
                //console.log(dataObj.categories);
                $('#'+settings.renderTo).css("height", height*30+"px");

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

        },

        /**
         * @param sType
         * @param tType
         * @param renderTo
         * @param title
         * @returns {{text: *, onclick: onclick, separator: boolean}}
         * @private
         */
        _myMenuItems: function(sType, tType, renderTo, title) {
            var self = this;
            var menuItem = {
                text: title,
                onclick: function () {
                    self._changeChartType(sType, tType, renderTo);
                },
                separator: false
            }
            return menuItem;
        },

        /**
         * @param renderTo
         * @returns {{text: string, onclick: onclick, separator: boolean}}
         * @private
         */
        _dataLabelToggleMenu: function(renderTo) {
            var self = this;
            var menuItemLabel = {
                text: 'Toggle Labels',
                onclick: function () {
                    self._toggleLabels(renderTo);
                },
                separator: false
            };
            return menuItemLabel;
        },

        /**
         * @param sType
         * @param type
         * @param renderTo
         * @private
         */
        _changeChartType: function(sType, type, renderTo) {
            var chart = $('#'+renderTo).highcharts(),
                s = chart.series,
                sLen = s.length;
            var inverted = false;
            var polar = false;
            if(type === 'pie' || type === 'halfpie' || type === 'donut') {
                console.log(chart);
                //chart.userOptions
            } else if(type === 'percent' || type === 'normal') {
                for(var i =0; i < sLen; i++){

                    s[i].update({
                        type: 'column',
                        stacking: type,
                    }, false);
                }
            } else if(type === 'bar') {
                inverted = true;
            } else {
                for (var i = 0; i < sLen; i++) {

                    s[i].update({
                        type: type,
                        stacking:null
                    }, false);
                }
            }

            chart.update({
                chart: {
                    inverted: inverted,
                    polar: polar
                },
            });

            chart.redraw();
        },

        /**
         * @param renderTo
         * @private
         */
        _toggleLabels: function(renderTo) {
            var chart = $('#'+renderTo).highcharts(),
                s = chart.series,
                sLen = s.length;
            //console.log("hello something" + s[0].dataLabels.enabled);
            for(var i =0; i < sLen; i++){
                s[i].update({
                    dataLabels: {
                        enabled: !s[i].options.dataLabels.enabled
                    }
                }, false);
            }
            chart.redraw();

        },

        /**
         * @param sData
         * @param sOptions
         * @param sColors
         * @returns {*}
         * @private
         */
        _secondChart: function(sData, sOptions, sColors) {
            var data = sData;
            var mData = data.series;
            if(sOptions.type === 'pie' && sOptions.method === 'sum') {
                var tempData = [];
                for(var i = 0; i < mData.length; i++) {
                    var name = mData[i].name;
                        data = mData[i].data.reduce(function(prev, cur) {
                        return prev + cur;
                    });
                    var tmpObj = {name: name, y:data, color: sColors[i]};
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

            } else if(sOptions.type ==='spline') {
                var newData = [];
                var arrLength = mData[0].data.length;
                if(sOptions.method === 'average') {
                    //newData = null;
                    var avg = 0;
                    for (var i = 0; i < arrLength; i++) {
                        avg = 0;
                        for (var x = 0; x < mData.length; x++) {
                            var tmp = mData[x].data;
                            //console.log(tmp);
                            avg = avg + tmp[i];
                        }
                        newData.push(Math.round(avg / arrLength));
                    }
                } else if(sOptions.method === 'sum') {
                    //newData = null;
                    var sum = 0;
                    for (var i = 0; i < arrLength; i++) {
                        sum = 0;
                        for (var x = 0; x < mData.length; x++) {
                            var tmp = mData[x].data;
                            //console.log(tmp);
                            sum = sum + tmp[i];
                        }
                        newData.push(sum);
                    }
                }
                // return line chart
                return {
                    type: 'spline',
                    name: sOptions.method === 'sum'? 'Total': 'Average',
                    data: newData,
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: '#ffffff'
                    },
                    color: Highcharts.getOptions().colors[3]
                }
            }
        }

    });

    /**
     * @constructor
     * ChartOptions, general settings of the charts
     */
    var ChartOptions = function () {
        this.chart = {
            renderTo: '',
            type: '',
            zoomType: 'xy'
        };
        this.exporting = {
            sourceWidth: 800,
            sourceHeight: 450,
            buttons: {
                contextButton: {
                    menuItems: [
                        {
                            text:'Print Chart',
                            onClick: function () {
                                this.print();
                            }
                        },
                        {
                            text:'Export to PDF',
                            onClick: function () {
                                this.exportChart({
                                    type: 'application/pdf',
                                    filename: title
                                });
                            }
                        },
                        {
                            text: 'Export to Image',
                            onclick: function () {
                                var title = this.title.textStr;
                                this.exportChart({
                                    type: 'image/jpeg',
                                    filename: title

                                });
                            }
                        }
                    ]
                }
            }
        };
        this.xAxis = {
            categories: [],
            labels: {
                style: { fontSize: '75%'}
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

    $.extend(ChartOptions.prototype);

})(window, jQuery);
