<title>{{ $user->firstname }} {{ $user->lastname }}</title>

@extends('master')

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
@endsection