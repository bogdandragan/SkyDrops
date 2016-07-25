<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<title>Welcome</title>

	<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/vegas.min.css') }}" rel="stylesheet">
	
	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,300,400,600' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700" rel="stylesheet" type="text/css">
	
	<!-- Icons -->
	<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style type="text/css">
	
		body{
			color: #fff;
			font-family: 'Open Sans', sans-serif;
		}
		
		.imgBG{
			position: absolute;
			width: 100%;
			height: 100%;
			background: #222;
			background-position: center center;
			background-repeat: no-repeat;
			background-size: cover;
		}
		
		.transBG{
			position: absolute;
			opacity: 0.5;
			background: #111;
			/* background: url('/img/background-image-overlay-full.png'); */
			width: 100%;
			height: 100%;
			z-index: 2;
		}
		
		.bodyBlock{
			text-align: center;
			padding-top: 15rem;
			position: relative;
			z-index: 5;
		}
		
		.header{
			padding-top: 3rem;
			position: relative;
			z-index: 6;
			text-align: left;
		}
		
		.header .logo .betaMark{
			color: #f05050;
			font-size: 1.2rem;
			padding-left: 0.3rem;
		}

		header a.logo {
			background-image: url('/img/logo.png');
		}
		
		.header a.logo {
			font-family: 'Open Sans', sans-serif;
			font-size: 2.4rem;
			text-align: center;
			margin-right: 15px;
			font-weight: 600;
			padding: 1.5rem;
		}
		
		.header a.logo:hover {
			text-decoration: none;
		}

		.home header {
			background: none;
   			box-shadow: none;
		}

		.home header a {
			color: #fefefe;
		}

		.home header a.logInOut {
			opacity: 0.8;
			border-radius: 5px;
			border: 2px solid #fefefe;
			padding: 10px 20px;
			margin-top: 10px;
		}
		
		.home header a.logInOut:hover { color: #fff }

		.home header a:hover {
			text-decoration: none;
			opacity: 1;
		}

		.main {
			margin-top: 20rem;
		}
		
		h1, h2{
			font-weight: 200;
		}
		
		h1{
			font-size: 8rem;
		}
		
		h2{
			font-family: 'Raleway';
			margin-top: -4rem;
			font-size: 4.6rem;
			font-weight: 200;
			margin-bottom: 100px;
			text-shadow: 0 2px 3px rgba(0,0,0,.2)
		}
		
		h3{
			font-weight: 400;
			font-size: 2.4rem;
			margin-bottom: 4rem;
		}
		
		h3 a{
			font-weight: 400;
		}
		
		p{
			margin-bottom: 1rem;
		}
		
		img{
			margin-top: 5rem;
			width: 28rem;
			height: 28rem;
		}
		
		a{
			text-decoration: none;
			color: #fff;
			font-weight: 700;
		}
		
		a:hover{
			text-decoration: underline;
		}
		
		.button{
			padding: 1.5rem 6rem;
		}
		
		.button:hover{
			text-decoration: none;
		}
		
		.beta{
			width: 100%;
			text-align: center;
			position: absolute;
			bottom: 2rem;
			color: #f05050 !important;
		}
		
		.br{
			margin: 1rem auto;
			display: block;
			width: 20rem;
			height: 0.2rem;
			border-top: 1px solid #f05050;
			border-bottom: 1px solid #f05050;
		}
		
		.button{
			display: block;
			margin: auto;
			width: 5rem;
		}

		.contactBox {
			background: rgba(0,0,0,0.5);
			border-radius: 5px;
			padding: 40px 60px;
			margin: 0 200px;
			text-align: left;
		}

		.header {
			margin-bottom: 200px;
		}

		.fleft {
			float: left;
		}

		.fleft p {
			max-width: 340px;
			font-size: 1.6rem;
			margin: 0;
		}

		.fleft strong {
			font-weight: 700;
		}

		.fright {
			float: right;
			padding-top: 5px;
		}

		.fright input {
			display: inline-block;
		}

		.fright input[type=text] {
			vertical-align:top;
		    padding: 1rem;
		    position: relative;
		    z-index: 1;
		    -webkit-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    box-sizing: border-box;
		    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.1);
		    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.1);
		    -webkit-border-radius: 3px;
		    -moz-border-radius: 3px;
		    border-radius: 3px;
		    border: 0;
		    font-size: 1.5rem;
		    color: #333;
		}

		.fright input[type=submit] {
			vertical-align:top;
			background: #2b957a;
			border: 0;
			padding: 0.8rem 1.5rem 0.9rem 1.5rem;
			border-radius: 3px;
			color: #fefefe;
			font-weight: 500;
			font-size: 1.5rem;
			font-family: 'Open Sans', sans-serif;
			margin-left: 5px;
		}

		.fright input[type=submit]:hover {
			cursor: pointer;
		    background-color: rgba(65,72,150,1);
		}

		.fright input[type=submit]:focus {outline:0;}
		
		@media screen and (max-width: 600px) {
			.bodyBlock{
				padding-top: 2rem;
			}
			
			img{
				width: 12rem !important;
				height: 12rem !important;
			}
			
			h2{
				margin-top: 0;
				font-size: 2rem !important;
			}
			
			.contactBox {
				margin: 0;
			}
			
			.main {
				margin-top: 80px;
			}
			
		}
	</style>
	<script type="text/javascript">
		document.getElementsByTagName('html')[0].style.fontSize = '62.5%';
	</script>
</head>
<body class="home">
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
				<a href="/profile" class="userBlock">
					<div class="userImage">
					</div>
					<div class="userDetails">
						<span class="userName">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
					</div>
					<br clear="left" />
				</a>
				<a class="logInOut" href="/logout"><i class="fa fa-sign-out"></i><span> Log Out</span></a>
				<a class="button" href="/upload">Create Drop</a>
				@else
					<a style="margin-right: 10px;" class="logInOut" href="/auth/register"><i></i><span> Sign Up</span></a>
					<a class="logInOut" href="/login"><i class="fa fa-sign-in"></i><span> Log In</span></a>
				@endif
				<br clear="both" />
			</div>
			<br clear="both" />
		</div>
	</header>

	<div class="imgBG">
		<div class="transBG"></div>
		<div class="bodyBlock wrap">
			<div class="main">
				<h2 style="margin-bottom: 20px;">A file exchange app for <strong>teams</strong><br>who like it <strong>simple</strong></h2>
				<!--<a style="margin-bottom: 20px; border: 2px solid #fefefe; border-radius: 5px; background-color: transparent; color: #fefefe;" class="btn btn-default" href="#">Create an empty drop</a>-->
				<div class="contactBox">
					<div class="fleft">
						<p>SKyDrops is a <strong>simple file exchange</strong> app made for you to
						simplify your daily work.</p>
					</div>
					<div class="fright">
						<form action="">
							<input type="text" id="contactText" placeholder="Email address" />
							<input type="submit" id="contactButton" value="Contact Us" />
						</form>
					</div>
					<br clear="both" />
				</div>
			</div>
		</div>
	</div>

	{!! Form::token() !!}

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	{!! HTML::script('/js/vegas.min.js') !!}

	<script>
		$(function() {

			$(".imgBG").vegas({
			    slides: [
			        { src: "/img/slide1.jpg" },
			        { src: "/img/slide2.jpg" },
			        { src: "/img/slide3.jpg" },
			        { src: "/img/slide4.jpg" }
			    ],
			    slide: 0,
			    preload: true,
			    delay: 10000,
			    transition: 'zoomOut'
			});

			$.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('input[name="_token"]').val()
		        }
		    });

		  $('form').on("submit", function(e){
		  	var $sMail = $('#contactText');
		  	if($sMail.val()){
		  		if(isEmail($sMail.val())){
				    $.post( "/exe/contact", { contacts: $sMail.val() }, function(jsonData){
				      $sMail.val("");
				      alert("Thank you for your interest!");
				    } );
			    } else {
			    	alert("Please enter a valid Email address.")
			    }
		    } else {
		    	alert("Please enter a Email address.")
		    }
		    e.preventDefault();
		  });

		});

		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
	</script>

</body>
</html>
