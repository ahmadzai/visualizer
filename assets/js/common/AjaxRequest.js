import $ from 'jquery';
import Routing from './Routing';
import Chart from '../charts/Chart';
import Alerts from '../common/Alerts';

class AjaxRequest {

    constructor() {
        this.chart = new Chart();
    }
    partiallyUpdate(url, charts, params, loaderClass = null) {
        let self = this;
        if(loaderClass !== null)
            $('.'+loaderClass).show();

        let ajax = this.ajaxPromise(url, params);

        ajax.done(data => {
            for (let key in charts) {
                if(data.hasOwnProperty(key)) {
                    charts[key].data = data[key];
                    charts[key].renderTo = key;
                    self.chart.visualize(charts[key]);
                }
            }
            if(loaderClass !== null)
                $('.'+loaderClass).hide();
        }).fail(xhr => {
            Alerts.ajaxError(xhr);
        });

    }

    updateAll(url, charts, param1, param2) {

        $('.trend-loader, .info-loader, .loader').show();
        let self = this;
        let req1 = this.ajaxPromise(url, param2);
        let req2 = req1.then(function(data){
            self.populateDashboard({...charts}, data);
            $('.info-loader').hide();
            return self.ajaxPromise(url, param1);
        });

        req2.done(function(data) {
           self.populateDashboard({...charts}, data);
           $('.trend-loader, .loader').hide();
        });
    }

    populateDashboard (charts, data) {

        let self = this;
        $.each(charts, function (index, value) {
            if(data.hasOwnProperty(index)) {
                this.data = data[index];
                this.renderTo = index;
                self.chart.visualize(this);
            }
        });
    }

    ajaxPromise(url, params) {
        let req =$.ajax({
            url: Routing.generate(url),
            data: params,
            method: 'post'
        });
        return req;
    }


}

export default AjaxRequest;