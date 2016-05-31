@extends('master')

@section('title')
	Groups
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
			<a href="/profile" class="nav">Drops</a>
			<a href="/profile/groups" class="nav active">Groups</a>
			<br clear="left" />
		</nav>
	</div>
</div>

<div class="container wrap">
				
				<!-- Content start -->
				<div class="sideBlock">
					<div class="box">
						<div class="boxHeader">
							Statistic
						</div>
						<div class="boxContent">
							Coming soon..
						</div>
					</div>
				</div>
				<div class="mainBlock">
					<div class="box">
						<div class="boxHeader">
							Groups
						</div>
						<div class="boxContent">
							@foreach($groups as $group)
								{{ $group->name }} <br>
							@endforeach
						</div>
					</div>
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
		console.log(token);

		$.ajax({
			type:	'DELETE',
			url:	'/d/' + $(this).attr('data-hash'),
			data:	{ _token : token },
			success: function(data){
				location.reload();
			}
		});

	});

</script>
@endsection

