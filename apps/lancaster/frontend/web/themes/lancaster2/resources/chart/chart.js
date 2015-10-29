$(function () {
    chart.init();
});

var chart = {
    init: function() {
        $("#Bargraph").SimpleChart({
            ChartType: "Line",
            toolwidth: "50",
            toolheight: "25",
            axiscolor: "#E6E6E6",
            textcolor: "#6E6E6E",
            showlegends: true,
            data: [graphdata2, graphdata1],
            legendsize: "140",
            legendposition: 'bottom',
            xaxislabel: 'Hours',
            title: 'Money Chart',
            yaxislabel: 'Profit in $'
        });
        $("#sltchartype").on('change', function () {
            $("#Bargraph").SimpleChart('ChartType', $(this).val());
            $("#Bargraph").SimpleChart('reload', 'true');
        });
    },
}