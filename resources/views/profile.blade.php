@extends('master')

@section('title')
	Profile
@endsection

@section('createFEButton')
	<a class="button" href="/upload">Create Drop</a>
@endsection

@section('scripts')
	{!! HTML::script('/js/jquery.min.js') !!}
	{!! HTML::script('/js/bootstrap.min.js') !!}
	{!! HTML::script('/js/jquery-ui.min.js') !!}
	{!! HTML::script('/js/bootstrap-datepicker.js') !!}
	{!! HTML::script('/js/autosize.js') !!}
	{!! HTML::script('/js/dropzone.js') !!}
	{!! HTML::script('/js/selectize.js') !!}
	{!! HTML::script('/js/sweetalert.min.js') !!}
	{!! HTML::script('/js/chart.min.js') !!}
	{!! HTML::script('/js/jquery.overlay.min.js') !!}
	{!! HTML::script('/js/jquery.textcomplete.min.js') !!}
	{!! HTML::script('/js/skydrops.js') !!}
	{!! HTML::script('/js/chart.min.js') !!}
@endsection

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
<script>
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
@if(Auth::check())
	<script>
		var token = $('input[name=_token]').val();
		$.ajax({
			url: "/getAvailableCoins",
			type: 'POST',
			data:	{ _token : token },
			success: function(data){
				$("#coinsAmount").html(data[0]);
			},
			error: function(data){
				console.log(data);
			}
		});
	</script>
@endif
@endsection
