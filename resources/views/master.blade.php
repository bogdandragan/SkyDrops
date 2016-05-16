<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<title>SKyDrops v3</title>

	<link href="{{ asset('/css/selectize.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sweetalert.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery.textcomplete.css') }}" rel="stylesheet">
	
	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	
	<!-- Icons -->
	<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<header>
		<div class="wrap-full">
			<div class="headerLeft">
				<a href="/" class="logo">
					<span>SKyDrops</span><span class="betaMark">Beta</span>
				</a>
				
				<ul class="nav">
				<!--
					<li>
						<a href="/help"><i class="fa fa-question-circle"></i><span class="bigScreen"> Help</span></a>
					</li>
				-->
					<br clear="left" />
				</ul>
				<br clear="left" />
			</div>
			<div class="headerRight">
				<!--
				<div class="notificationBlock">
					<i class="fa fa-globe"></i>
					<span class="notifications">23</span>
				</div>
				-->
				@if(Auth::check())
				<a href="#" class="userBlock" data-direction='mRight'>
					<div class="userImage">
					</div>
					<div class="userDetails">
						<span class="userName">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
					</div>
					<br clear="left" />
				</a>
				<a class="button" href="/upload">Create Drop</a>
				@else
				<a class="logInOut" href="/login"><i class="fa fa-sign-in"></i><span> Log In</span></a>
				@endif
				<br clear="both" />
			</div>
			<br clear="both" />
		</div>
	</header>
<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	{!! HTML::script('/js/autosize.js') !!}
	{!! HTML::script('/js/dropzone.js') !!}
	{!! HTML::script('/js/selectize.js') !!}
	{!! HTML::script('/js/bootstrap-datepicker.js') !!}
	{!! HTML::script('/js/bootstrap.min.js') !!}
	{!! HTML::script('/js/sweetalert.min.js') !!}
	{!! HTML::script('/js/chart.min.js') !!}
	{!! HTML::script('/js/jquery.overlay.min.js') !!}
	{!! HTML::script('/js/jquery.textcomplete.min.js') !!}
	{!! HTML::script('/js/skydrops.js') !!}


	<script>

$(document).ready(function(){
	
	var data = [
    {
        value: 30,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Active Drops"
    },
    {
        value: 70,
        color: "#222",
        highlight: "#333",
        label: "Free Drops"
    }
];

var options = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke : false,

    //String - The colour of each segment stroke
    segmentStrokeColor : "#aaa",

    //Number - The width of each segment stroke
    segmentStrokeWidth : 0,

    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout : 60, // This is 0 for Pie charts

    //Number - Amount of animation steps
    animationSteps : 100,

    //String - Animation easing effect
    animationEasing : "easeOutBounce",

    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate : true,

    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale : false,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

};
	
	
	
	// For a pie chart
	var ctx = document.getElementById("myChart").getContext("2d");
	var myPieChart = new Chart(ctx).Pie(data,options);
	
});

</script>

	
	@yield('content')

@if(Auth::check())
	<!-- Absolute -->
<div class="modal fade black" id="defaultModal" tabindex="-1" role="dialog">
  <div class="modal-dialog clearfix">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body">
      </div>
    </div><!-- /.modal-content -->

    <div class="modal-sidebar">
    	<div class="sidebar-section">
    		<div class="userImage"></div>
    		<h3>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h3>
    		<h4>{{ Auth::user()->email }}</h4>
    		<a href="/logout" type="button" class="button">Logout</a>
    	</div>
    	<div class="sidebar-section">
    		<h2>Statistics</h2>
    		<canvas id="myChart" width="200" height="200" style="display: block; margin: 1.5rem auto 0 auto;"></canvas>
    	</div>
    </div>

  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif

</body>
</html>
