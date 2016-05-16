<title>Log In</title>

@extends('master')

@section('content')
<div class="container wrap noSubHeader wrapLogin">
				
				<!-- Content start -->
				<div class="box">
					<div class="boxHeader">
						Login
					</div>
					<div class="boxContent">
						<form action="/u/ldap" method="POST">
							<label for="username">Username</label>
							<input type="text" name="username" autofocus />
							<label for="password">Password</label>
							<input type="password" name="password" />
							<input type="submit" id="uploadButton" class="button" value="Log In">
							<a style="display: block; margin-top: 1rem; font-weight: 200" href="http://reset.skypro.ch/">Forgot password?</a>
							{!! Form::token() !!}
						</form>
					</div>
				</div>
				
					  
				<!-- Content end --> 
					  
</div>
@endsection
