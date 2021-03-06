@extends('master')

@section('title')
	Upload | SKyDrop Beta
@endsection

@section('style')
	body{
		background: url({{ asset('/img/business-962316_1920.jpg') }}) repeat-y center center;
		background-position: 0% 10%;
	}


	.title-block {
	margin: 30px 0;
	}

	input[type=text] {
	background: none;
	border: 0;
	box-shadow: none;
	height: auto;
	outline: none;
	}

	input[name=inpTitle] {
	font-size: 3rem;
	}

	.input-group {
		width: 150px;
		margin: 5px 0px;
	}

	.form-control:focus {
	border: 0;
	outline: 0;
	box-shadow: none;
	}

	.ownDropzone {
	z-index: 8;
	}

	#date{
		border: 1px solid #ccc !important;
	}

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
	{!! HTML::script('/js/cookies.js') !!}
@endsection

@section('content')
<div class="container noSubHeader wrap">
	<div class="uploadBlock">
				<!-- Content start -->
				<div class="mainBlock" style="float: none; width: auto;">
					<div class="title-block" style="margin-bottom: 15px;">
						<div class="fLeft">
							<input name="inpTitle" class="form-control" type="text" placeholder="Name this File Exchange" title="Click to change" />
							<div class="input-group date">
								<input type="text" class="form-control" id="date" ><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						<div class="fRight">
							<input type="button" id="uploadButton" class="button" value="Save" disabled/>
						</div>
						<br clear="both" />
						<input id="emptyDrop" type="checkbox" name="emptyDrop"> Create upload request
					</div>
					<form action="{{ url('u/upload')}}" class="ownDropzone" id="my-awesome-dropzone" enctype="multipart/form-data" method="post">
						<div class="dz-message">
    Drop files here or click to upload.</div>
						{!! Form::token() !!}
						<input type="hidden" id="newHash" value="{{ $hash }}">
					</form>
					<div id="emptyDropOptions" style="display: none;">
					<label for="feSizeLimit">Select max. File Exchange size: </label>
					<select name="feSizeLimit" id="feSizeLimit" style="margin-bottom: 15px;">
						<option value="1">1 Gb</option>
						<option value="5">5 Gb</option>
						<option value="10">10 Gb</option>
					</select>
					</div>
				</div>
				<!-- Content end -->
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
			<p><b>You have {{ $coins }} coin(s)</b></p>
			<p><b>Total Cost: </b><span id="totalCost"></span><span> coin(s)</span></p>
			<p><b>Size: </b><span id="totalSize"></span><span  id="addSizeCoins"> +0</span><span> coin(s)</span></p>
			<p><b>File exchange validity: </b><span id="dropValidity"></span><span id="addValidityCoins"> +0</span><span> coin</span></p>
		@endif
	</div>
</div>

<!-- Absolute stuff -->
<div class="blackBlock">
	<div class="progressBar"></div>
	<div class="progressValue">0</div>

</div>

<!-- Hidden stuff -->
<div id="preview-template" style="display: none;">
<div class="dz-preview dz-file-preview">
  <i class="fa fa-trash" data-dz-remove></i>
  <div class="doc-preview">
    <img data-dz-thumbnail />
  </div>
  <div class="dz-details">
    <div class="dz-filename"><span data-dz-name></span></div>
    <div class="dz-size" data-dz-size></div>
	<div class="progressBar" style="width:0%;" data-dz-uploadprogress></div>
  </div>
  <!-- <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>

 <br clear="right" />
 <div class="dz-error-message"><span data-dz-errormessage></span></div> -->
</div>
</div>

<script>
	$(document).ready(function(){
		$('input[name=inpTitle]').focus();
		$("#uploadButton").addClass("saveBtnDisabled");
	});
	$('input[name=inpTitle]').val("");
	$( "#emptyDrop" ).prop( "checked", false );
	$('input[name=inpTitle]').focusout(function(){
		if($(this).val() == ""){
			$(this).val("New File Exchange");
			window.onbeforeunload = function(){
				return "Are you sure you want to leave, cause there are some unsaved changes?";
			}
		}
	});

	window.onbeforeunload = function (evt) {
		if ($('input[name=inpTitle]').val() != "") {
			return "Are you sure you want to leave, cause there are some unsaved changes?";
		}
	}
	var save = 0;
	$('#uploadButton').click(function(){
		save = 1;
	});

	window.addEventListener("pagehide", function () {

		if(save != 0){
			return;
		}

		var token = $('input[name=_token]').val();
		$.ajax({
			type:	'DELETE',
			url:	'/d/' + $("#newHash").val(),
			data:	{ _token : token },
			async: false,
			success: function(data){

			},
			error: function(data){

			}
		});
	});

	/*$(window).pagehide(function(){

	});*/
</script>
@if(Auth::check())
	<script>
		$(document).ready(function () {
		var token = $('input[name=_token]').val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('input[name="_token"]').val()
			}
		});


			/*$.ajax({
				url: "/getAvailableCoins",
				type: 'GET',
				success: function(data){
					$("#coinsAmount").html(data[0]);
				},
				error: function(data){
					console.log(data);
				}
			});*/
		});
	</script>
@endif
@endsection