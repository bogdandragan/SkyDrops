<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<title>@yield('title')</title>

	<link href="{{ asset('/css/selectize.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sweetalert.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery.textcomplete.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

	<!-- Icons -->
	<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		@yield('style')
	</style>

	@yield('scripts')

</head>
<body>
	<header>
		<div class="wrap-full">
			<div class="headerLeft">
				<a href="/" class="logo">
					<span>SKyDrops</span><span class="betaMark">Beta</span>
				</a>
				<a class="button" href="/home">Home</a>

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
				@yield('createFEButton')
				@else
					<a class="button" href="/auth/register">Free Registration and Account</a>
					<a class="logInOut" href="/login"><i class="fa fa-sign-in"></i><span> Log In</span></a>
				@endif
				<br clear="both" />
			</div>
			<br clear="both" />
		</div>
	</header>
<!-- Scripts -->

	<script>

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
    		@if(Auth::user()->isAdmin)
    		<div class="text-center" style="margin-top: 10px;">
    			<a class="btn btn-primary" style="display: inline-block; width:49%;" href="/admin/dashboard">Manage users</a>
    			<a class="btn btn-primary" style="display: inline-block; width:49%;" href="/admin/statistic">FE statistics</a>
    		</div>
    		@endif
    	</div>

		<?php
			$isSKyUser = false;

			$domains = explode("@", Auth::user()->email);
			$domain = end($domains);

			$userGroup = \App\UserGroup::where('user_id', '=', Auth::user()->id)->first();

			if($userGroup || $domain == "skypro.ch"){
				$isSKyUser = true;
			}
		?>
		@if(!$isSKyUser)
    	<div class="sidebar-section">
    		<h2 style="font-weight: bold; margin-top: 0px">Your coins amount</h2>
			<span style="display: inline-block; color: #f7dc03"><i class="fa fa-3x fa-database"></i></span>
			<span style="display: inline-block"><h2 style="font-weight: bold; font-size: 36px; margin-top: 0px; margin-left: 10px;" id="coinsAmount">{{Auth::user()->coins}}</h2></span>
			<a class="btn btn-primary" style="width:100%;" href="/shop">Buy more coins</a>
			<a class="btn btn-primary" style="width:100%; margin-top: 10px;" href="/coinStatistics">My coins balance statistics</a>
    		<!--<canvas id="myChart" width="200" height="200" style="display: block; margin: 1.5rem auto 0 auto;"></canvas>-->
    	</div>
		@endif
    </div>

  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif
<script>
        $('#defaultModal .modal-content').click(function() {
            $('#defaultModal').modal('hide');
        });
</script>
</body>
</html>
