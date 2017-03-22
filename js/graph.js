$(document).ready(function(){
    $.post('/info/get_graph_data', {url: window.location.href}, function(result){
        var res = JSON.parse(result);
        console.log(res);

        document.title = 'Results for ' + res[2].url

        Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Results for ' + res[2].url
        },
        xAxis: {
            categories: res[1]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of requests'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: res[0]
        });
    });
});