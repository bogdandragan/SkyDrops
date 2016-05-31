@extends('master')

@section('title')
	Upload
@endsection

@section('style')
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

	.input-group-addon {
	display: none;
	}

	.form-control:focus {
	border: 0;
	outline: 0;
	box-shadow: none;
	}

	.ownDropzone {
	z-index: 8;
	}

	.fLeft {
	width: calc(100% - 220px);
	}

	.fRight {
	width: 200px;
	margin-left: 20px;
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
@endsection

@section('content')
<div class="container noSubHeader wrap">
				<!-- Content start -->
				<div class="mainBlock" style="float: none; width: auto;">
					<div class="title-block">
						<div class="fLeft">
							<input name="inpTitle" class="form-control" type="text" placeholder="Name this Drop" title="Click to change" />
							<div class="input-group date">
							  <input type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						<div class="fRight">
							<input type="button" id="uploadButton" class="button" value="Upload" disabled/>
						</div>
						<br clear="both" />
						<input id="emptyDrop" type="checkbox" name="emptyDrop"> Create empty drop
					</div>
					<form action="{{ url('u/upload')}}" class="ownDropzone" id="my-awesome-dropzone" enctype="multipart/form-data" method="post">
						<div class="dz-message">
    Drop files here or click to upload.</div>
						{!! Form::token() !!}
					</form>
				</div>
				<!-- Content end -->
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
  </div>
  <!-- <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>

 <br clear="right" />
 <div class="dz-error-message"><span data-dz-errormessage></span></div> -->
</div>
</div>

<script>
	$(document).ready(function(){
		$('input[name=inpTitle]').focus();
	});
</script>

@endsection