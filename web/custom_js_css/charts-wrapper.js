/**
 * Created by Wazir Khan on 10/13/2017.
 */

globalOptions = {
    chart: {
        renderTo:'',
        type: '',
        zoomType: 'xy'
    },
    exporting: {
        sourceWidth: 800,
        sourceHeight:450,
        buttons: {
            contextButton: {
                menuItems: [ {
                    text: 'Print Chart',
                    onclick: function () {
                        this.print({
                        });
                    }

                }, {
                    text: 'Export as Image',
                    onclick: function () {
                        var title = this.title.textStr;
                        this.exportChart({
                            type: 'image/jpeg',
                            filename: title,

                        });
                    }
                }, {
                    text: 'Export as PDF',
                    onclick: function () {
                        var title = this.title.textStr;
                        this.exportChart({
                            type: 'application/pdf',
                            filename: title,

                        });
                    },

                }]
            }
        },
    },
    xAxis: {
        categories: [],
        labels: {
            style: {
                fontSize: '75%',
            }
        }
    },
    yAxis: {
        title: {
            text: 'Chart title'
        },
        labels: {
            style: {
                fontSize: '75%',
            }
        }
    },
    colors: ['#FF0000', '#C99900', '#FFFF00'],
    series: [
        {
            'data': 'test'
        }
    ],
};

// ================================================= Charts Wrappers Functions ================================

function threeAxisChart(renderTo, staticData, threeTitles, threeIndicators, threeColors, url, ajaxPrams) {
    var colors = typeof threeColors === 'undefined' ? ['#048aff', '#43AB0D', '#F00000'] : threeColors;
    var titles = typeof threeTitles === 'undefined' ? ['Title1', 'Title2', 'Title3'] : threeTitles;
    var indicators = typeof threeIndicators === 'undefined' ? ['Indicator1', 'Indicator1', 'Indicator1'] : threeIndicators;
    var vaccineUsage = {apiUrl:url,
        urlParams: ajaxPrams,
        renderTo: renderTo,
        chartData: staticData,
        legend: {
            layout: 'vertical',
            align: 'left',
            vAlign: 'top',
            x: 80,
            y: 55,
            color: '#FFFFFF'
        },
        axises: [
            {
                format:'',
                color: colors[1],
                opposite: true,
                title:titles[1]

            },
            {
                format:'',
                color: colors[0],
                opposite: false,
                title:titles[0],
                lineWidth: 1
            },
            {
                format:' %',
                color: colors[2],
                opposite: true,
                title:titles[2],
                lineWidth: 0
            },
        ],
        yAxises: [
            {
                color: colors[0],
                indicator:indicators[0],
                type: 'column',
                tooltip: '',
                yAxis: 1
            },
            {
                color: colors[1],
                yAxis: 0,
                indicator:indicators[1],
                type: 'spline',
                tooltip: ''

            },
            {
                color: colors[2],
                indicator:indicators[2],
                yAxis: 2,
                type: 'spline',
                tooltip: ' %'
            },


        ]
    };

    multiAxisColumnChart(vaccineUsage);
}
function colChart(renderTo, staticData, title, colors, ajaxPrams, url) {
    var colors = typeof colors === 'undefined' ? ['#FF0000', '#C99900', '#FFFF00'] : colors;
    var title = typeof title === 'undefined' ? 'No of children' : title;
    var settings = {apiUrl:url,
        urlParams: ajaxPrams,
        renderTo: renderTo,
        chartData: staticData,
        menu:[{chart:'line', title:'Line Chart'},
            {chart: 'column', title:'Back to Stack'}],
        stacking:'normal',
        yAxisTitle: title,
        colors: colors,

//                combination:[{type:'pie', method:'sum'}],
//                label:{title:'Total Vaccine Usage',
//                    position:{top:'18px', left:'170px'}}
    };
    columnChart(settings);
}

// ===================================================== End of Wrapper Functions ====================================

//chartType = {type: 'column', stacking: 'percent'}
/**
 * @param chartType
 * @param renderTo
 * @param data
 * @param titles
 * @param legend
 * @param colors
 * @param menu
 */
function myChartWrapper(chartType, renderTo, data, titles, legend, colors, menu, large, yAxisFormatter) {
    var settings = {
        chartType: chartType,
        renderTo: renderTo,
        data: data,
        titles: (titles === undefined || titles === null)? {xTitle:null, yTitle:null}:titles,
        legend: (legend=== undefined || legend === null)? {enabled:true, position:{vAlign:'bottom', hAlign:'center'}}:legend,
        colors: (colors === undefined || colors === null) ? Highcharts.getOptions().colors : colors,
        menu: (menu === undefined || menu === null) ? null : menu,
        large: (large === undefined || large === null) ? null : large,
        yAxisFormatter: (yAxisFormatter === undefined || yAxisFormatter === null) ? '' : yAxisFormatter,
            /*
            [{chart:'line', title:'Line Chart'},
            {chart: 'column', title:'Column Chart'},
            {chart: 'normal', title:'Stack Chart'},
            {chart: 'bar', title:'Bar Chart'},
            {chart: 'area', title:'Area Chart'},
        {chart: 'percent', title:'Default'}], */
    }
    myChart(settings);
}
/*
 this function generates any chart with combination charts as well.
 */
function myChart(settings) {

    /*
     deep cloning the settings and global objects, otherwise it will messed up
     */
    var settings = jQuery.extend(true, {}, settings);
    var options = jQuery.extend(true, {}, globalOptions);

    //options.series = [];

    //console.log(options);
    // set the element where the chart should be rendered
    options.chart.renderTo = settings.renderTo;

    // set the type of chart, static in this case
    var chartType = settings.chartType.type;
    options.chart.type = chartType;

    if(chartType === 'area' && settings.chartType.hasOwnProperty('stacking') &&
        settings.chartType.stacking === 'percent') {
        options['tooltip'] = {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
            split: true
        }
    } else if(chartType === 'column' && settings.chartType.hasOwnProperty('stacking') &&
        settings.chartType.stacking === 'percent') {
        options['tooltip'] = {
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} ({point.percentage:.1f}%)</b><br/>',
        }
    } else if(chartType === 'bar' && settings.chartType.hasOwnProperty('stacking') &&
        settings.chartType.stacking === 'percent') {
        options['tooltip'] = {
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} ({point.percentage:.1f}%)</b><br/>',
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
    }
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

    if(settings.hasOwnProperty('titles'))
        options.yAxis.title.text = settings.titles.yTitle;

    // adding data label toggle menu option
    var labelMenu = dataLabelToggleMenu(settings.renderTo);

    options.exporting.buttons.contextButton.menuItems.forEach(function (item, index) {
        if(item.text === labelMenu.text) {
            options.exporting.buttons.contextButton.menuItems.pop();
        }
    });

    options.exporting.buttons.contextButton.menuItems.push(labelMenu);

    // check and set the menu options
    var menus = settings.menu;
    if(settings.hasOwnProperty('menu') && menus!== null) {
        for(var i = 0; i < menus.length; i++) {
            var menuItem = myMenuItems('', menus[i].chart, settings.renderTo, menus[i].title);
            options.exporting.buttons.contextButton.menuItems.push(menuItem);
            menuItem = null;
        }
    }

    // check for color property
    if(settings.hasOwnProperty('colors'))
        options.colors = settings.colors;

    //check for label
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

    // check if there was legned information
    /*
     legend: {
     align: (left, right, center)
     vAlign: (top, bottom, middle)
     x: (pixels in integer)
     y: (pixels in integer)
     floating: (true|false)

     }
     */
    if(settings.hasOwnProperty('legend')) {
        options['legend'] = {
            enabled: settings.legend.enabled,
            //align: settings.legend.position.hAlign,
            //verticalAlign: settings.legend.position.vAlign,
        }
    }

    if(settings.hasOwnProperty('legend') && settings.legend.enabled === true) {
        options['legend'] = {
            align: settings.legend.position.hAlign,
            verticalAlign: settings.legend.position.vAlign,
        }
    }

    // set the data of the chart to what assigned from TWIG
    //console.log(JSON.parse(settings.chartData));
    var dataObj = JSON.parse(settings.data);

    // =========================================================================
    // set the dynamic title
    options['title'] = {text: dataObj.title, marginBottom:5, style: {fontSize:'100%'}};
    options['subtitle'] = {text: dataObj.subTitle, style: {fontSize:'80%'}};
    // set the dynamic categories
    if(dataObj.categories === null || dataObj.categories === undefined) {
        options.xAxis.categories = [];
    } else {
        options.xAxis.categories = dataObj.categories;
    }

    // change the formatter for yAxis if not empty
    if(settings.yAxisFormatter !== "")
        options.yAxis.labels = {format: '{value}'+settings.yAxisFormatter}
    // set the data/series

    if(dataObj.series === null || dataObj.series === undefined) {
        options.series = [];
    } else {
        options.series = dataObj.series;
    }
    //console.log(dataObj["series"]);
    // check for the combined chart request
    if (settings.hasOwnProperty('combination')) {
        var secondCharts = settings.combination;
        for (var i = 0; i < secondCharts.length; i++) {
            var colors = options.colors;
            if (secondCharts.hasOwnProperty('colors')) {
                colors = secondCharts.colors;
            }
            var newSettings = secondChart(dataObj, secondCharts[i], colors);
            options.series.push(newSettings);
        }
    }

    var height = (dataObj.categories === undefined ) ? 10: dataObj.categories.length;

    if(settings.large !== null && settings.large === 'height') {
        //console.log(dataObj.categories);
        $('#'+settings.renderTo).css("height", height*30+"px");
    }
    // finally create chart
    var chart = new Highcharts.Chart(options);
    options = null;
}

/**
 * @param renderTo: string container name
 * @param data: json array
 * @param legend: boolean true|false
 * @param colors: array of color codes
 * @param menu: array of menu objects [{chart:'type', title:'Label'}]
 * @param type: string type of Pies (donut, halfpie, pie)
 */
function myPieChartWrapper(renderTo, data, legend, colors, menu, type, area) {
    var settings = {
        type: (type === undefined || type === null) ? 'pie':type,
        renderTo: renderTo,
        data: data,
        legend: (legend===undefined || legend === null)?false:legend,
        colors: (colors === undefined || colors === null) ? Highcharts.getOptions().colors : colors,
        menu: (menu === undefined || menu === null) ? null : menu,
        area: (area === undefined || area === null) ? 'large':'small'
    }
    myPieChart(settings);
}

function myPieChart(settings) {
    /*
     deep cloning the settings and global objects, otherwise it will messed up
     */
    var settings = jQuery.extend(true, {}, settings);
    var options = jQuery.extend(true, {}, globalOptions);

    // set the type of chart, static in this case
    var chartOptions =  {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        renderTo: settings.renderTo,
    };
    options.chart = chartOptions;

    var plot = {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: (settings.legend === true) ? false : true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    fontSize: '90%'
                }
            },
        }
    }

    if(settings.type === 'halfpie') {
        plot.pie.startAngle = -90;
        plot.pie.endAngle = 90;
        plot.pie.center = ['50%', '75%'];
        plot.pie.dataLabels.distance = -50;
        plot.pie.dataLabels.format = '<b>{point.name}</b>';
    } else if(settings.type === 'donut') {
        plot.pie.innerSize = 100;
        plot.pie.depth = 45;

    }

    if(settings.area === 'small') {
        plot.pie.dataLabels.distance = -20;
        plot.pie.dataLabels.format = '<b>{point.name}</b>';
        options.chart.margin = 0;
        options.chart.marginTop = 15;
    }
    if(settings.type === 'halfpie') {
        options.chart.margin = -20;

    }

    options['plotOptions'] = plot;

    // check and set the menu options
    var menus = settings.menu;
    if(settings.hasOwnProperty('menu') && menus !== null) {
        for(var i = 0; i < menus.length; i++) {
            var menuItem = myMenuItems('', menus[i].chart, settings.renderTo, menus[i].title);
            options.exporting.buttons.contextButton.menuItems.push(menuItem);
            menuItem = null;
        }
    }

    // adding data label toggle menu option
    var labelMenu = dataLabelToggleMenu(settings.renderTo);
    options.exporting.buttons.contextButton.menuItems.push(labelMenu);

    // check for color property
    if(settings.hasOwnProperty('colors'))
        options.colors = settings.colors;

    options['tooltip'] = {
        pointFormat: '{point.y}<b> ({point.percentage:.1f}%)</b>'
    };

    // set the data of the chart to what assigned from TWIG
    //console.log(JSON.parse(settings.chartData));
    var dataObj = JSON.parse(settings.data);

    // =========================================================================
    // set the dynamic title
    options['title'] = {text: dataObj.title, style: {fontSize:'100%'}};
    options['subtitle'] = {text: dataObj.subTitle, style: {fontSize:'80%'}};
    // set the data/series
    if(dataObj.series === null || dataObj.series === undefined) {
        options.series = [];
    } else {
        if (settings.type === 'halfpie')
            dataObj.series[0].innerSize = '50%';
        options.series = dataObj.series;
    }

    //console.log(dataObj["series"]);
    // finally create chart
    var chart = new Highcharts.Chart(options);
}

function myHeatMap(data, container, tooltipTitle) {
    var dataObj = JSON.parse(data);
    var options = {};
    //var options = jQuery.extend(true, {}, globalOptions);

        options.chart = {
            type: 'heatmap',
            marginTop: 100,
            marginBottom: 80,
            plotBorderWidth: 1,
            renderTo: container,
            marginTop: 80,
        };


        options.title = {
            text: dataObj.title === undefined? 'Heatmap': dataObj.title,
            style: {
                fontSize: '110%'
            }
        };

        options.xAxis = [
            {
                categories: dataObj.xAxis === undefined? [] : dataObj.xAxis,

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
                categories: dataObj.xAxis === undefined? [] : dataObj.xAxis,
                labels: {
                    style: {
                        fontSize:'80%'
                    },
                    //autoRotation: [30],
                    autoRotation: false
                }
            }
        ];

        //console.log("Y AXIS: ", dataObj.yAxis);
        // set the exporting options
        console.log(globalOptions.exporting);
        options.exporting = globalOptions.exporting;
        // label toggle option added
        var labelMenu = dataLabelToggleMenu(container);
        options.exporting.buttons.contextButton.menuItems.forEach(function (item, index) {
            if(item.text === labelMenu.text) {
                options.exporting.buttons.contextButton.menuItems.pop();
            }
        });

        options.exporting.buttons.contextButton.menuItems.push(labelMenu);

        options.yAxis = {
            categories: dataObj.yAxis === undefined? [] : dataObj.yAxis,
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
            min: dataObj.stops !== null ? dataObj.stops.minValue : 5,
            max: dataObj.stops !== null ? dataObj.stops.maxValue : 20,
            tickInterval: 1,
            startOnTick: false,
            endOnTick: false,
            stops: [
                [
                    0,
                    dataObj.stops !== null? dataObj.stops.minColor: "#43AB0D"
                ],
                [
                    dataObj.stops !== null? dataObj.stops.midStop: 0.5,
                    dataObj.stops !== null? dataObj.stops.midColor:"#ffd927"
                ],
                [
                    1,
                    dataObj.stops !== null? dataObj.stops.maxColor:"#FF0000"
                ]
            ]
        };

        options.legend = {
            enabled: false,
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        };

        options.tooltip = {
            formatter: function () {
                return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> <br>'+ tooltipTitle +' children <br><b>' +
                    this.point.value + '</b> in cluster <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
            }
        };
        //console.log(dataObj.data);
        options.series = [{

            name: 'Clusters Trends',
            borderWidth: 1,
            turboThreshold: 200,
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
    $('#'+container).css("height", height);
    $('#'+container).css("min-width", width);
    var chart = new Highcharts.Chart(options);

}

/*
 this function generates column chart with combination charts as well.
 */
function multiAxisColumnChart(settings) {
    /*
     deep cloning the settings and global objects, otherwise it will messed up
     */
    var settings = jQuery.extend(true, {}, settings);
    var options = jQuery.extend(true, {}, globalOptions);

    // set the element where the chart should be rendered
    options.chart.renderTo = settings.renderTo;

    options.chart['zoomType'] = 'xy';

    // check and set the menu options
    var menus = settings.menu;
    if(settings.hasOwnProperty('menu')) {
        for(var i = 0; i < menus.length; i++) {
            var menuItem = myMenuItems('', menus[i].chart, settings.renderTo, menus[i].title);
            options.exporting.buttons.contextButton.menuItems.push(menuItem);
            menuItem = null;
        }
    }

    // adding data label toggle menu option
    var labelMenu = dataLabelToggleMenu(settings.renderTo);
    options.exporting.buttons.contextButton.menuItems.push(labelMenu);


    // the tooltip are shared for now
    options['tooltip'] = {shared:true};

    // check for the legend settings
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
    if(settings.hasOwnProperty('legend')) {
        //console.log("we are here in the legend");
        options['legend'] = {
            layout:settings.legend.layout,
            align:settings.legend.align,
            verticalAlign:settings.legend.vAlign,
            x:settings.legend.x,
            y:settings.legend.y,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'

        }
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
    if(settings.hasOwnProperty('axises')) {
        var axises = settings.axises;
        var axis = [];
        for(var i = 0; i<axises.length; i++) {
            var tmpAxis = {
                labels: {
                    format: '{value}'+axises[i].format,
                    style: {color:axises[i].color}
                },
                title: {
                    text:axises[i].title,
                    style:{color:axises[i].color}
                },
                opposite:axises[i].opposite,
                gridLineWidth: axises[i].lineWidth,

            }
            axis.push(tmpAxis);
        }
        options['yAxis'] = axis;
    }

    // set the data of the chart to what assigned from TWIG
    var dataObj = JSON.parse(settings.chartData);
    // if the data wasn't sett in the loading, call the ajax then
    if(dataObj == null) {
        $.ajax({
            url: Routing.generate(settings.apiUrl, settings.urlParams),
            beforeSend: function () {
                // ajax loader path is in the data attribute of the main div
                var path = $("#main").data('ajax-loader');
                $('#' + settings.renderTo).html("<img src='" + path + "' class='ajax-loader'>");
            },
            success: function (data) {
                $('#' + settings.renderTo).html('');
                dataObj = JSON.parse(data);
            },
            cache: false
        });
    }

    //===========================================================================
    // Setting the options
    // set the dynamic title
    options['title'] = {text: dataObj.title, style: {fontSize:'100%'}};
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
        for (var i = 0; i < newSeries.length; i++) {

            for (var x = 0; x < dataSeries.length; x++) {
                if (dataSeries[x].name.toLowerCase() == newSeries[i].indicator.toLowerCase()) {
                    var tempSeries = {};
                    tempSeries['name'] = dataSeries[x].name;
                    tempSeries['type'] = newSeries[i].type;
                    tempSeries['data'] = dataSeries[x].data;
                    tempSeries['yAxis'] = newSeries[i].yAxis;
                    tempSeries['tooltip'] = {valueSuffix: newSeries[i].tooltip}
                    tempSeries['color'] = newSeries[i].color;
                    series.push(tempSeries);
                }
            }

        }
        options.series = series;
    }

    //console.log(options);
    var chart = new Highcharts.Chart(options);

    //=======================================================================


}

/*
 this function generates column chart with combination charts as well.
 */
function columnChart(settings) {
    /*
     deep cloning the settings and global objects, otherwise it will messed up
     */
    var settings = jQuery.extend(true, {}, settings);
    var options = jQuery.extend(true, {}, globalOptions);

    //options.series = [];

    //console.log(options);
    // set the element where the chart should be rendered
    options.chart.renderTo = settings.renderTo;

    // set the type of chart, static in this case
    options.chart.type = 'column';

    // set the plat option, empty, normal, percent
    options['plotOptions'] = {
        column:{
            stacking:settings.stacking,
            dataLabels: {
                style: {
                    fontSize: '70%',
                }
            }
        }
    };

    if(settings.hasOwnProperty('yAxisTitle'))
        options.yAxis.title.text = settings.yAxisTitle;

    // check and set the menu options
    var menus = settings.menu;
    if(settings.hasOwnProperty('menu')) {
        for(var i = 0; i < menus.length; i++) {
            var menuItem = myMenuItems('', menus[i].chart, settings.renderTo, menus[i].title);
            options.exporting.buttons.contextButton.menuItems.push(menuItem);
            menuItem = null;
        }
    }

    // adding data label toggle menu option
    var labelMenu = dataLabelToggleMenu(settings.renderTo);
    options.exporting.buttons.contextButton.menuItems.push(labelMenu);

    // check for color property
    if(settings.hasOwnProperty('colors'))
        options.colors = settings.colors;

    //check for label
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

    // check if there was legned information
    /*
     legend: {
     align: (left, right, center)
     vAlign: (top, bottom, middle)
     x: (pixels in integer)
     y: (pixels in integer)
     floating: (true|false)

     }
     */
    if(settings.hasOwnProperty('legend')) {
        options['legend'] = {
            align: settings.legend.align,
            verticalAlign: settings.legend.vAlign,
            x: settings.legend.x,
            y: settings.legend.y,
            floating:settings.legend.floating,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: (settings.legend.vAlign=='top')?1:0,
            shadow: false,
        }
    }

    // set the data of the chart to what assigned from TWIG
    //console.log(JSON.parse(settings.chartData));
    var dataObj = JSON.parse(settings.chartData);
    //console.log(dataObj);
    // if the data wasn't sett in the loading, call the ajax then
    if(dataObj == null) {
        //console.log("We are in the ajax block");
        $.ajax({
            url: Routing.generate(settings.apiUrl, settings.urlParams),
            beforeSend: function () {
                // ajax loader path is in the data attribute of the main div
                var path = $("#main").data('ajax-loader');
                $('#' + settings.renderTo).html("<img src='" + path + "' class='ajax-loader'>");
            },
            success: function (data) {
                $('#' + settings.renderTo).html('');
                dataObj = JSON.parse(data);
            },
            cache: false
        });
    }

    // =========================================================================
    // set the dynamic title
    options['title'] = {text: dataObj.title, style: {fontSize:'100%'}};
    // set the dynamic categories
    options.xAxis.categories = dataObj.categories;
    // set the data/series
    options.series = dataObj.series;
    //console.log(dataObj["series"]);
    // check for the combined chart request
    if (settings.hasOwnProperty('combination')) {
        var secondCharts = settings.combination;
        for (var i = 0; i < secondCharts.length; i++) {
            var colors = options.colors;
            if (secondCharts.hasOwnProperty('colors')) {
                colors = secondCharts.colors;
            }
            var newSettings = secondChart(dataObj, secondCharts[i], colors);
            options.series.push(newSettings);
        }
    }
    // finally create chart
    var chart = new Highcharts.Chart(options);
    // ==========================================================================


}

/*
 this function generates any chart with combination charts as well.
 */
function lineChart(settings) {
    /*
     deep cloning the settings and global objects, otherwise it will messed up
     */
    var settings = jQuery.extend(true, {}, settings);
    var options = jQuery.extend(true, {}, globalOptions);

    // set the element where the chart should be rendered
    options.chart.renderTo = settings.renderTo;

    // set the type of chart, static in this case
    options.chart.type = settings.type;

    // set the plot option, empty, normal, percent
    options['plotOptions'] = {column:{stacking:settings.stacking}};

    if(settings.hasOwnProperty('yAxisTitle'))
        options.yAxis.title.text = settings.yAxisTitle;

    // check and set the menu options
    var menus = settings.menu;
    if(settings.hasOwnProperty('menu')) {
        for(var i = 0; i < menus.length; i++) {
            var menuItem = myMenuItems('', menus[i].chart, settings.renderTo, menus[i].title);
            options.exporting.buttons.contextButton.menuItems.push(menuItem);
            menuItem = null;
        }
    }

    // adding data label toggle menu option
    var labelMenu = dataLabelToggleMenu(settings.renderTo);
    options.exporting.buttons.contextButton.menuItems.push(labelMenu);

    // check for color property
    if(settings.hasOwnProperty('colors'))
        options.colors = settings.colors;

    //check for label
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

    if(settings.hasOwnProperty('legend')) {
        //console.log("we are here in the legend");
        options['legend'] = {
            layout:settings.legend.layout,
            align:settings.legend.align,
            verticalAlign:settings.legend.vAlign,
            floating: settings.legend.floating,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'

        }
    }

    // set the data of the chart to what assigned from TWIG
    var dataObj = settings.data;
    // if the data wasn't sett in the loading, call the ajax then
    if(dataObj == null) {
        $.ajax({
            url: Routing.generate(settings.apiUrl, settings.urlParams),
            beforeSend: function () {
                // ajax loader path is in the data attribute of the main div
                var path = $("#main").data('ajax-loader');
                $('#' + settings.renderTo).html("<img src='" + path + "' class='ajax-loader'>");
            },
            success: function (data) {
                $('#' + settings.renderTo).html('');
                dataObj = JSON.parse(data);
            },
            cache: false
        });
    }

    // ===========================================================================
    // set the dynamic title
    options['title'] = {text: dataObj.title};
    // set the dynamic categories
    options.xAxis.categories = dataObj.categories;
    // set the data/series
    options.series = dataObj.series;
    console.log(options.series);
    // check for the combined chart request
    if (settings.hasOwnProperty('combination')) {
        var secondCharts = settings.combination;
        for (var i = 0; i < secondCharts.length; i++) {
            var colors = options.colors;
            if (secondCharts.hasOwnProperty('colors')) {
                colors = secondCharts.colors;
            }
            var newSettings = secondChart(data, secondCharts[i], colors);
            options.series.push(newSettings);
        }
    }
    // finally create chart
    var chart = new Highcharts.Chart(options);
    // ===========================================================================


}


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Helper Functions ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

/*
 this function creates menu item
 */
function myMenuItems(sType, tType, renderTo, title) {
    var menuItem = {
        text: title,
        onclick: function () {
            changeChartType(sType, tType, renderTo);
        },
        separator: false
    }
    return menuItem;
}

function dataLabelToggleMenu(renderTo) {
    //var menuItemLabel = [];
    var menuItemLabel = {
        text: 'Toggle Labels',
        onclick: function () {
            toggleLabels(renderTo);
        },
        separator: false
    };
    return menuItemLabel;
}

/*
 this function changes chart type, the sourceType is not used till now
 */
function changeChartType(sType, type, renderTo) {
    var chart = $('#'+renderTo).highcharts(),
        s = chart.series,
        sLen = s.length;
    var inverted = false;
    var polar = false;
    if(type == 'pie' || type == 'halfpie' || type == 'donut') {
        console.log(chart);
        //chart.userOptions
    } else if(type == 'percent' || type == 'normal') {
        for(var i =0; i < sLen; i++){

            s[i].update({
                type: 'column',
                stacking: type,
            }, false);
        }
    } else if(type == 'bar') {
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
    })

    chart.redraw();
}

/*
 this function toggles data labels
 */
function toggleLabels(renderTo) {
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

}

/*
 this function create the combined charts.
 */
function secondChart(sData, sOptions, sColors) {
    var data = sData;
    var mData = data.series;
    if(sOptions.type == 'pie' && sOptions.method == 'sum') {
        var tempData = [];
        for(var i = 0; i < mData.length; i++) {
            var name = mData[i].name;
            var data = mData[i].data.reduce(function(prev, cur) {
                return prev + cur;
            });
            var tmpObj = {name: name, y:data, color: sColors[i]};
            //tmpObj[dataName] = dataData;
            tempData.push(tmpObj);
        }
        var pieChart = {
            type: 'pie',
            name: 'Total',
            data: tempData,
            center: [90, 10],
            size: 100,
            showInLegend: false,
            dataLabels: {
                enabled: false
            }

        }

        return pieChart;
    } else if(sOptions.type =='spline') {
        var newData = [];
        var arrLength = mData[0].data.length;
        if(sOptions.method == 'average') {
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
        } else if(sOptions.method == 'sum') {
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
        var lineChart = {
            type: 'spline',
            name: sOptions.method == 'sum'? 'Total': 'Average',
            data: newData,
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: '#ffffff'
            },
            color: Highcharts.getOptions().colors[3]
        }

        return lineChart;
    }
}

