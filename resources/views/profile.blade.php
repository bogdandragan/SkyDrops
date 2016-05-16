<title>Profile</title>
@extends('master')

@section('content')
<div class="subHeader profile-header">
	<div class="wrap">
		<span class="avatar"></span><h2>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h2>
	</div>
</div>
<div class="navigation">
	<div class="wrap">
		<nav class="buttonWrap left">
			<a href="/profile" class="nav active">Drops</a>
			<a href="/profile/groups" class="nav">Groups</a>
			<br clear="left" />
		</nav>
	</div>
</div>

<div class="container wrap">
				
				<!-- Content start -->
				<div class="sideBlock">
					<div class="box">
						<div class="boxHeader">
							Statistics
						</div>
						<div class="boxContent">
							<p>Check out how much Drops you have left.</p>
							<canvas id="myChart" width="200" height="200"></canvas>
						</div>
					</div>
				</div>
				<div class="mainBlock">
					<div class="box">
						<div class="boxHeader">
							<div class="fLeft">
								My Drops
							</div>
							<div class="fRight noBold">
								<input class="form-control inpSearch" type="text" placeholder="Search" />
							</div>
							<br clear="both" />
						</div>
						<div class="boxContent">
						<table class="myDrops" cellspacing="0" cellpadding="0">
							
							@forelse ($drops as $drop)
								<tr>
									<td>
									<?php
											/*
											$datetime1 = new DateTime();
											$datetime2 = $drop->created_at;
											$interval = $datetime1->diff($datetime2);
											$elapsed = 7 - $interval->format('%a');
											$elapsed .= " days";
											*/
											$elapsed =  (isset($drop->expires_at)) ? date("d.m.Y", strtotime($drop->expires_at)) : "<span class='infinite'></span>";

										?>
									{!! $elapsed !!}										
									</td>
									<td>
										<?php $html = ""; ?>
										@foreach(explode(",", $drop->dropFiles) as $file)
										
										<?php
											
											$html .= "<span class='tt'>". $file ."</span>";
											
										?>
										
										@endforeach
									
									
									
										<a title="{{ $html }}" href="/d/{{ $drop->hash }}">@if($drop->title) {{ $drop->title }} @else {{ substr(str_replace(",", ", ", $drop->dropFiles), 0, 80) }} @endif @if(strlen($drop->dropFiles) > 80)..  @endif </a>
										<!--
										@if(!empty($drop->tags))
										<div style="margin-top: 5px">
											@foreach (explode(",", $drop->tags) as $tag)
											<span class="tag"><i class="fa fa-tag"></i>{{ $tag }}</span>
											@endforeach
										</div>
										@endif
										-->
									</td>
									<td>
										<a class="button removeDrop" data-hash="{{$drop->hash}}">Remove</a>
										<!-- <span title="Remove" class="removeDrop" data-hash="{{$drop->hash}}"><i class="fa fa-trash-o"></i></span> -->
									</td>
								</tr>
							@empty
								No Drops
							@endforelse
							
					
						</table>
						</div>
					</div>
				</div>
					  
				<!-- Content end --> 
					  
</div>
{!! Form::token() !!}
@endsection
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
{!! HTML::script('/js/chart.min.js') !!}
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
        color: "#eee",
        highlight: "#ddd",
        label: "Free Drops"
    }
];

var options = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke : true,

    //String - The colour of each segment stroke
    segmentStrokeColor : "#fff",

    //Number - The width of each segment stroke
    segmentStrokeWidth : 2,

    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout : 0, // This is 0 for Pie charts

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

	$( document ).tooltip({
		position: {
			my: "left bottom-0",
			at: "left top"
		},
		content: function(){
		  var element = $( this );
		  return element.attr('title')
		}
	});

	$(document).on('click', '.removeDrop', function(){
		
		var token = $('input[name=_token]').val();
		
		$.ajax({
			type:	'DELETE',
			url:	'/d/' + $(this).attr('data-hash'),
			data:	{ _token : token },
			success: function(data){
				//location.reload();
			}
		});
		$(this).parent().parent().remove();
		
	});

</script>
