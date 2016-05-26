@extends('master')

@section('title')
	Log In
@endsection

@section('content')
<div class="container wrap noSubHeader wrapLogin">
				
				<!-- Content start -->
				<div class="box">
					<div class="boxHeader">
						Login
					</div>
					<div class="boxContent">
						<form id="loginForm" action="/u/ldap" method="POST">
							<label for="username">Username</label>
							<input type="text" name="username" autofocus />
							<label for="password">Password</label>
							<input type="password" name="password" />
							<input type="submit" id="submitButton" class="button" value="Log In">
							<a style="display: block; margin-top: 1rem; font-weight: 200" href="http://reset.skypro.ch/">Forgot password?</a>
							{!! Form::token() !!}
						</form>
					</div>
				</div>
				
					  
				<!-- Content end --> 
					  
</div>
	<script>
		$("#loginForm").submit(function(e) {
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: "/u/ldap",
				data: $("#loginForm").serialize(),
				success: function(data) {
					window.location = "/";
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status == 401) {
						$(".alert-danger").remove();
						var errorHtml = "<div class='alert alert-danger' style='margin-top: 10px;'><ul style='list-style-type: none;'>"+
								"<li>"+jqXHR.statusText+"</li></ul></div>"
						$("#submitButton").after(errorHtml);
					}
				}
			});

		});
	</script>
@endsection
