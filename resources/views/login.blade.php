@extends('master')

@section('title')
	Log In
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
							<a style="display: block; margin-top: 1rem; font-weight: 200" href="auth/restore">Forgot password?</a>
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
