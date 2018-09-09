class ChartHelper {
    constructor() {
        this.chart = {
            renderTo: '',
            type: '',
            zoomType: 'xy'
        };

        this.lang = {
            printChart: 'Print chart',
            downloadPNG: 'Export PNG',
            downloadPDF: 'Export PDF',

        };

        this.exporting = {
            sourceWidth: 800,
            sourceHeight: 450,
            buttons: {
                contextButton: {
                    menuItems: [
                        'printChart',
                        'downloadPNG',
                        'downloadPDF'
                    ]
                }
            }
        };
        this.xAxis = {
            categories: [],
            labels: {
                style: {fontSize: '75%'}
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
    }
}

export default ChartHelper;