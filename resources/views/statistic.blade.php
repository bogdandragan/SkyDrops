@extends('master')

@section('title')
    Statustics Dashboard | SkyDrops Beta
@endsection

@section('createFEButton')
    <a class="button" href="/upload">Create Drop</a>
@endsection

@section('scripts')
    {!! HTML::script('/js/jquery.min.js') !!}
    {!! HTML::script('/js/bootstrap.min.js') !!}
    {!! HTML::script('/js/autosize.js') !!}
    {!! HTML::script('/js/dropzone.js') !!}
    {!! HTML::script('/js/selectize.js') !!}
    {!! HTML::script('/js/sweetalert.min.js') !!}
    {!! HTML::script('/js/chart.min.js') !!}
    {!! HTML::script('/js/jquery.overlay.min.js') !!}
    {!! HTML::script('/js/jquery.textcomplete.min.js') !!}
@endsection

@section('style')
    body{
        background: url({{ asset('/img/computer-767776_1920.jpg') }}) repeat-y center center;
    }
@endsection

@section('content')
    <div class="container noSubHeader wrap" ng-app="StatisticApp" ng-controller="chartsCtrl" data-ng-init="drawAllCharts()">
        <div class="box" style="padding: 20px;">
        <div class="row">
            <div class='col-md-5'>
                <div class="form-group">
                    <div class='input-group date' id='dateFrom'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-md-5'>
                <div class="form-group">
                    <div class='input-group date' id='dateTo'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-md-2 col-sm-12'>
                <button class="btn btn-primary" ng-click="drawAllCharts()" style="width :100%; margin-bottom: 10px;">Filter</button>
            </div>
        </div>
        <div class="row options" style="margin-bottom: 20px; margin-left: 0px;">
            <div class="radio-inline col-md-2">
                <label>
                    <input type="radio" name="optionsRadios1" id="lastWeek" ng-model="this.selectOption1" value="lastWeek" ng-change="setLastWeek()">
                    Last week
                </label>
            </div>
            <div class="radio-inline col-md-2">
                <label>
                    <input type="radio" name="optionsRadios1" id="lastMonth" ng-model="this.selectOption2" value="lastMonth" ng-change="setLastMonth()">
                    Last month
                </label>
            </div>
            <div class="radio-inline col-md-2">
                <label>
                    <input type="radio" name="optionsRadios1" id="lastYear" ng-model="this.selectOption3" value="lastYear" ng-change="setLastYear()">
                    Last year
                </label>
            </div>

        </div>
        <div class="row">
            <h5 class="col-md-12">From: <%this.startDate%> To: <%this.endDate%></h5>
        </div>
        <div class="text-center" ng-show="loading">
            <i class="fa fa-3x fa-spinner fa-spin"></i>
        </div>
        <div id="charts" ng-show="!loading">
            <div id="chart1"  style="margin-top: 20px;"></div>
            <div id="chart2" style="margin-top: 20px;"></div>
            <div id="chart3"></div>
            <div id="chart4"></div>
        </div>
        </div>
    </div>
    {!! Form::token() !!}

    {!! HTML::script('/js/angular.min.js') !!}
    <script src="/js/moment-with-locales.min.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {'packages':['line', 'corechart']});
        var date = new Date();
        var fromTimestamp = new Date(date.getFullYear(), date.getMonth(), date.getDate()-7, date.getHours(), date.getMinutes()).getTime();
        var toTimestamp = new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes()).getTime();
        $(function () {
            $('#dateFrom').datetimepicker({format: 'DD/MM/YYYY'});
            $('#dateTo').datetimepicker({
                useCurrent: false,
                format: 'DD/MM/YYYY'
            });

            $("#dateFrom").on("dp.change", function (e) {
                $('#dateTo').data("DateTimePicker").minDate(e.date);
                var moment = $('#dateFrom').data("DateTimePicker").date();
                fromTimestamp = moment.unix()*1000;
                console.log(fromTimestamp);
            });
            $("#dateTo").on("dp.change", function (e) {
                $('#dateFrom').data("DateTimePicker").maxDate(e.date);
                var moment = $('#dateTo').data("DateTimePicker").date();
                toTimestamp =  moment.unix()*1000;
                console.log(toTimestamp);
            });

            $('#dateFrom').data("DateTimePicker").defaultDate(new Date(date.getFullYear(), date.getMonth(), date.getDate()-7, date.getHours(), date.getMinutes()));
            $('#dateTo').data("DateTimePicker").defaultDate(new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes()));
        });
        var ngApplication = angular.module('StatisticApp', []);
        ngApplication.config(function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        });
        ngApplication.controller('chartsCtrl', function($scope, $http, $location){

            $scope.drawAllCharts = function(){
                this.selectOption1 = false;
                this.selectOption2 = false;
                this.selectOption3 = false;
                this.startDate = moment(fromTimestamp).format("DD-MM-YYYY");
                this.endDate = moment(toTimestamp).format("DD-MM-YYYY");

                $scope.loading = true;
                $scope.getDrops(function(drops){
                    console.log(drops);
                    google.charts.setOnLoadCallback(function(){drawDropsChart(drops)});
                });

                $scope.getUserAgents(function(userAgents){
                    console.log(userAgents);
                    google.charts.setOnLoadCallback(function(){drawUserAgentsPieChart(userAgents)});
                });
                /*$scope.getProtocols(function(protocols){
                    console.log(protocols);
                    var protocolsForChart = [];

                    for(var i = 0; i< protocols[1].length; i++){
                        var date = protocols[1][i].date.split("-");
                        var year = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getFullYear();
                        var month = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getMonth()+1;
                        var day = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getDate();
                        console.log(i);
                        var row = [new Date(year+"/"+month+"/"+day), protocols[1][i].count, protocols[2][i].count, protocols[3][i].count, protocols[4][i].count, protocols[5][i].count,
                            protocols[6][i].count, protocols[7][i].count, protocols[8][i].count, protocols[9][i].count, protocols[10][i].count, protocols[11][i].count, protocols[12][i].count, protocols[13][i].count];

                        protocolsForChart.push(row);
                    }

                    google.charts.setOnLoadCallback(function(){drawProtocolsChart(protocolsForChart)});
                });
                $scope.getProtocolsPie(function(protocols){
                    console.log(protocols);
                    google.charts.setOnLoadCallback(function(){drawProtocolsPieChart(protocols)});
                });
                $scope.getCountries(function(countries){
                    console.log(countries);
                    google.charts.setOnLoadCallback(function(){drawCountriesChart(countries)});
                    $(".load-bar").hide();
                });*/
                $scope.loading = false;
            }

            $scope.getDrops = function(callback){
                var url = '/admin/statistic/getDrops';
                var request = $http.get(url, {params: { from: moment(fromTimestamp).format("YYYY-MM-DD"), to: moment(toTimestamp).format("YYYY-MM-DD HH:MM:SS") }});

                request.success(function(data, status, headers, config) {
                    console.log(data);
                    var result = data;

                    var drops = [];

                    angular.forEach(result, function(item){
                        var date = item.created_at.split("-");
                        console.log(date);
                        var year = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getFullYear();
                        var month = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getMonth()+1;
                        var day = $scope.convertToLocalDate(new Date(date[0]+"/"+date[1]+"/"+date[2].substring(0,2))).getDate();

                        var row = [new Date(year+"/"+month+"/"+day), parseInt(item.count)];
                        console.log(row);
                        drops.push(row);
                    });
                    callback(drops);
                });
                request.error(function(data, status, headers, config) {
                    console.log("error:"+data.status);
                });
            };

            $scope.getUserAgents = function(callback){
                var url = '/admin/statistic/getUserAgent';
                var request = $http.get(url, {params: { from: moment(fromTimestamp).format("YYYY-MM-DD"), to: moment(toTimestamp).format("YYYY-MM-DD HH:MM:SS")}});

                request.success(function(data, status, headers, config) {
                    var result = data;

                    var userAgents = [['User Agent', 'Downloads']];

                    angular.forEach(result, function(item){
                        //console.log(item.value);
                        //var date = new Date($scope.convertToLocalDate(item.date).getFullYear(), $scope.convertToLocalDate(item.date).getMonth(), $scope.convertToLocalDate(item.date).getDate());
                        var row = [item.userAgent, parseInt(item.count)];
                        userAgents.push(row);
                    });
                    callback(userAgents);

                });
                request.error(function(data, status, headers, config) {
                    console.log("error:"+data.status);
                });
            };

            /*$scope.getProtocols = function(callback){

                var protocols = {protocolsIds :[1,2,3,4,5,6,7,8,9,10,11,12,13],
                    1:[], 2:[], 3:[], 4:[],  5:[], 6:[],  7:[], 8:[], 9:[], 10:[], 11:[], 12:[], 13:[]};

                var i = 0;
                angular.forEach(protocols.protocolsIds, function(item){
                    var url = '@routes.Activities.getProtocolsForChart()';
                    var request = $http.get(url, {params: { from: fromTimestamp, to: toTimestamp, p: item }});
                    request.success(function(data, status, headers, config) {
                        protocols[item] = data.result;
                        i++;
                        if(i==protocols.protocolsIds.length){
                            callback(protocols);
                        }
                    });
                    request.error(function(data, status, headers, config) {
                        console.log("error:"+data.status);
                    });
                });


            };

            $scope.getProtocolsPie = function(callback){

                var url = '@routes.Activities.getProtocolsForPieChart()';
                var request = $http.get(url, {params: { from: fromTimestamp, to: toTimestamp }});

                request.success(function(data, status, headers, config) {
                    var result = data.result;

                    var protocols = [['Protocol', 'Users']];

                    angular.forEach(result, function(item){
                        var row = [item.value, item.count];
                        protocols.push(row);
                    });
                    callback(protocols);

                });
                request.error(function(data, status, headers, config) {
                    console.log("error:"+data.status);
                });
            };*/

            function drawDropsChart(drops) {

                var data = new google.visualization.DataTable();

                data.addColumn('date', 'Day');
                data.addColumn('number', 'FE download');

                data.addRows(drops);

                var options = {
                    title: 'File exchange download by day',
                    hAxis: {
                        format: 'MMM dd, yyyy'
                    },
                    width: 1000,
                    height: 350
                };

                var dateFormatter = new google.visualization.DateFormat({formatType: 'short'});
                dateFormatter.format(data,0);

                var chart = new google.charts.Line(document.getElementById('chart1'));
                chart.draw(data, google.charts.Line.convertOptions(options));
            }

            function drawUserAgentsPieChart(userAgents) {

                var data = google.visualization.arrayToDataTable(userAgents);

                var options = {
                    title: 'File exchange download by user agent',
                    height: 350
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart2'));

                chart.draw(data, options);
            }

            /*function drawProtocolsPieChart(protocols) {

                var data = google.visualization.arrayToDataTable(protocols);

                var options = {
                    title: 'Users by protocol',
                    height: 350
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart3'));

                chart.draw(data, options);
            }

            function drawCountriesChart(countries) {

                var data = google.visualization.arrayToDataTable(countries);

                var options = {
                    title: 'Users by country',
                    height: 350
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart4'));

                chart.draw(data, options);
            }*/

            $scope.setLastWeek = function(){
                this.selectOption2 = false;
                this.selectOption3 = false;
                if(typeof($('#dateFrom').data("DateTimePicker")) == "undefined")
                    return;
                var fromDate = moment().subtract(1, 'w');
                var toDate = moment();
                $('#dateFrom').data("DateTimePicker").date(fromDate);
                $('#dateTo').data("DateTimePicker").date(toDate);
            };

            $scope.setLastMonth = function(){
                this.selectOption1 = false;
                this.selectOption3 = false;
                if(typeof($('#dateFrom').data("DateTimePicker")) == "undefined")
                    return;
                var fromDate = moment().subtract(1, 'M');
                var toDate = moment();

                $('#dateFrom').data("DateTimePicker").date(fromDate);
                $('#dateTo').data("DateTimePicker").date(toDate);
            };

            $scope.setLastYear = function(){
                this.selectOption1 = false;
                this.selectOption2 = false;
                if(typeof($('#dateFrom').data("DateTimePicker")) == "undefined")
                    return;
                var fromDate = moment().subtract(1, 'Y');
                var toDate = moment();

                $('#dateFrom').data("DateTimePicker").date(fromDate);
                $('#dateTo').data("DateTimePicker").date(toDate);
            };

            $scope.convertToLocalDate = function(timestamp){
                var localTime = new Date(timestamp);
                var hourOffset = localTime.getTimezoneOffset() / 60;
                localTime.setHours( localTime.getHours() - hourOffset );
                return localTime;
            };
        });

        $('.userBlock').click(function() {
            $('#defaultModal')
                    .addClass( $(this).data('direction') ).addClass('userModal');
            $('#defaultModal').modal('show');
            $('.modal-backdrop.in').css('opacity', ".5");
        });

    </script>
    @if(Auth::check())

    @endif
@endsection
