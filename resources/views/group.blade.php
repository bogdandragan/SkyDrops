<title>Groups</title>

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
						<div>
					</div>
				</div>
					  
				<!-- Content end --> 
					  
</div>
{!! Form::token() !!}
@endsection
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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