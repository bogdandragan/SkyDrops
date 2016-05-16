<title>Not found</title>
@extends('master')

@section('content')

<div class="container wrap noSubHeader">
				
				<!-- Content start -->

<style>
	.container h2, .container h3, .container p {
		text-align: center;
	}

	.container h2 {
		margin-top: 10rem;
		margin-bottom: 0;
		font-size: 22rem;
		font-weight: 600;
	}

	.container h3 {
		font-size: 5rem;
		font-weight: 600;
		text-transform: uppercase;
		margin-top: 0;
		margin-bottom: 2rem;
	}

	.container p {
		width: 40rem;
		margin: auto;
	}

	.link {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 550px;
		height: 660px;
		background: url('/img/link-404.png');
		background-position: center;
		background-size: cover;
	}
</style>

						<h2>404</h2>
						<h3>Link not found</h3>
						<p>The page you are looking for was moved, removed, renamed or might never existed.</p>
						<div class="link"></div>
					  
				<!-- Content end --> 
					  
</div>
@endsection
