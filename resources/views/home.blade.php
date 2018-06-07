@extends('layouts.app')

@section('content')
    <h5>Hello, {{\Auth::user()->name}}</h5>
    <div class="row tasks-info">
        <div class="col s3">
            <p>{{$data['hours']['month']}}</p>
            <span class="title">Spent Hours (month)</span>
        </div>
        <div class="col s3">
            <p>{{$data['hours']['all']}}</p>
            <span class="title">Spent Hours (all time)</span>
        </div>
        <div class="col s3">
            <p>{{$data['tasks']['month']}}</p>
            <span class="title">Tasks (month)</span>
        </div>
        <div class="col s3">
            <p>{{$data['tasks']['all']}}</p>
            <span class="title">Tasks (all time)</span>
        </div>
    </div>
    <div id="chartdiv" style="width: 100%;height: 350px;"></div>

    <div class="row">
        <form class="form-csv">
            <div class="input-field col s3">
                <input type="text" name="from" id="from" class="datepicker">
                <label for="from">C</label>
            </div>
            <div class="input-field col s3">
                <input type="text" name="to" id="to" class="datepicker">
                <label for="to">До</label>
            </div>
            <div class="input-field col s3">
                <button class="btn" type="button" id="import">Download CSV</button>
            </div>
        </form>
    </div>
    <iframe style="display: none;"></iframe>
@endsection

@section('script_custom')

    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script>
        var date = new Date('{{date('Y-m-d')}}');
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            maxDate: date
        });
        var chartData = generateChartData();

        $('#import').click(function(){
            $.ajax({
                url:'/getcsvdata',
                data:{from:$('#from').val(),to:$('#to').val()},
                type:'POST',
                success:function(response) {
                    response = JSON.parse(response);
                    var url ='/storage/app/'+response.file;
                    window.open(url);
                }
            });
        });



        function charttInit(chartData)
        {
            var chart = AmCharts.makeChart("chartdiv", {
                "type": "serial",
                "theme": "light",
                "marginRight": 80,
                "autoMarginOffset": 20,
                "marginTop": 7,
                "dataProvider": chartData,
                "valueAxes": [{
                    "axisAlpha": 0.2,
                    "dashLength": 1,
                    "position": "left"
                }],
                "mouseWheelZoomEnabled": true,
                "graphs": [{
                    "id": "g1",
                    "balloonText": "#[[title]]<br>[[value]]",
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "hideBulletsCount": 50,
                    "title": "[[dataProvider.title]]",
                    "valueField": "visits",

                    "useLineColorForBulletBorder": true,
                    "balloon":{
                        "drop":true
                    }
                }],
                "chartScrollbar": {
                    "autoGridCount": true,
                    "graph": "g1",
                    "scrollbarHeight": 40
                },
                "chartCursor": {
                    "limitToGraph":"g1"
                },
                "categoryField": "date",
                "categoryAxis": {
                    "parseDates": true,
                    "axisColor": "#DADADA",
                    "dashLength": 1,
                    "minorGridEnabled": true
                },
                "export": {
                    "enabled": true
                }
            });

            chart.addListener("rendered", zoomChart);
            zoomChart(chart,chartData);
        }


        // this method is called when chart is first inited as we listen for "rendered" event
        function zoomChart(chart,chartData) {
            // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
            chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
        }


        function generateChartData() {
            var chartData = [];
            $.ajax({
                url:'/chartdata',
                type:'POST',
                success:function(response){
                    response = JSON.parse(response);

                    for(var i in response){
                            var item = response[i];
                            chartData.push({
                                date:item.date,
                                visits:item.time,
                                title:item.task_id
                            })

                    }
                    console.log(chartData);
                    charttInit(chartData)
                }
            })
        }
    </script>
@endsection
