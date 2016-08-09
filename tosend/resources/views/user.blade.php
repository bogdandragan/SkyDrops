
@extends('master')

@section('title')
	{{ $user->firstname }} {{ $user->lastname }}
@endsection

@section('createFEButton')
	<a class="button" href="/upload">Create Drop</a>
@endsection

@section('content')

<div class="container noSubHeader wrap usrBlock">
				
	<!-- Content start -->

	<div class="usrImg">
	</div>
	<div class="usrDetails">
	{{ $user->firstname }} {{ $user->lastname }}
	</div>
		  
	<!-- Content end --> 
					  
</div>
{!! Form::token() !!}
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