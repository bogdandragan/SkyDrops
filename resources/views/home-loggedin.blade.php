@extends('master')

@section('title')
	Home | SkyDrops Beta
@endsection

@section('createFEButton')
<a class="button" href="/upload">Create File Exchange</a>
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

@section('style')
	body{
		background: url({{ asset('/img/macbook-925596_1920.jpg') }}) repeat-y center center;
	}

	.drop-block {
	margin-bottom: 10rem;
	}

	.drop {
	float: left;
	width: 38.5rem;
	border: .1rem solid #e1e1e1;
	background: #fff;
	margin-bottom: 1.5rem;
	margin-right: 1.5rem;
	box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
	color: #666;
	}

	.drop:active {
	text-decoration: none;
	}

	.drop:hover {
	color: #666;
	-moz-box-shadow: 1px 1px 5px #999; -webkit-box-shadow: 1px 1px 5px #999; box-shadow: 1px 1px 5px #999;
	}

	.drop .drop-header {
	padding: 1.5rem;
	border-bottom: 1px dotted #e1e1e1;
	font-weight: 700;
	}

	.drop .drop-content {
	text-align: center;
	background: #f3f3f3;
	height: 20rem;
	}

	.drop .drop-content img {
	height: 100%;
	max-width: 100%;
	max-height: 20rem;
	}

	.drop .drop-content .imagePreview {
	width: 100%;
	height: 100%;
	background-position: center;
	background-size: cover;
	}

	.drop .drop-content .noPreview {
	display: inline-block;
	margin-top: 9rem;
	color: #999;
	font-weight: 600;
	}

	.drop .drop-content img.fileIcon {
	width: 10rem;
	height: auto;
	margin-top: 5rem;
	}

	.drop .drop-footer {
	padding: 1.5rem;
	position: relative;
	}

	.drop .drop-footer .numFiles {
	float: left;
	}

	.drop .drop-footer .date {
	float: right;
	}

	.button.removeDrop {
	display: inline-block;
	width: auto;
	margin: 0;
	background: #eb4d5c;
	color: #fff;
	padding: 5px 15px;
	font-weight: 500;
	font-size: 1.3rem;
	border-radius: 3px;
	right: 1.1rem;
	top: 1.1rem;
	}

	.button:hover, .button:focus {
		background-color: #414896;
		border-color: #414896;
		color: #fff;
		text-decoration: none;
	}
@endsection

@section('content')

<div class="container noSubHeader wrap">
	<!-- Content start -->

	<div class="drop-block clearfix">

		@forelse ($drops as $drop)
			<?php $html = ""; ?>
			@foreach(explode(",", $drop->dropFiles) as $file)

			<?php
				$html .= "<span class='tt'>". $file ."</span>";
			?>

			@endforeach

			<a href="/d/{{ $drop->hash }}" class="drop">
				<div class="drop-header">
					<i class="fa fa-inbox"></i>
						@if($drop->title)
							{{ trim(substr($drop->title, 0, 28)) }}
							@if(strlen($drop->title) > 28)
								..
							@endif
						@else
							{{ substr(str_replace(",", ", ", $drop->dropFiles), 0, 28) }}
						@endif
						@if(!$drop->title && strlen($drop->dropFiles) > 28)
							..
						@endif
					<span style="float: right;">{{App\File::formatFileSize($drop->dropSize)}}</span>
				</div>
				<div class="drop-content">
					<?php
						$imageContenttype = "";
						$imageHash = "";
						foreach (explode(",", $drop->dropFilesContenttype) as $index=>$contentType){
							if(explode("/", $contentType)[0] == "image"){
								$imageContenttype = explode("/", $contentType)[0];
								$imageHash = explode(",", $drop->dropFilesHash)[$index];
							}
						}
					?>
					@if($imageContenttype == "image")
						<div class="imagePreview" style="background-image: url('/f/{{ $imageHash }}')"></div>
					@else
						<span class="noPreview">No Preview</span>
					@endif
				</div>
				<div class="drop-footer clearfix">
					<span title="{{ $html }}" class="numFiles">
						@if($drop->dropFiles == null)
							0
						@else
							{{ sizeof(explode(",", $drop->dropFiles)) }}
						@endif
						@if($drop->dropFiles == null || sizeof(explode(",", $drop->dropFiles)) > 1)
							files
						@else
							file
						@endif
					</span>
					<input style="float: right; margin-left: 5px; margin-top: -5px;" type="button" class="button removeDrop" data-hash="{{ $drop->hash }}" value="Remove" />
					<span class="date">
						<?php $elapsed =  (isset($drop->expires_at)) ? date("d.m.Y", strtotime($drop->expires_at)) : "<span class='infinite'></span>"; ?>
						<i class="fa fa-calendar-check-o" style="font-weight: 600"></i> {!! $elapsed !!}
					</span>
				</div>
			</a>
		@empty
			<div class="container" style="background-color: #f3f3f3; margin-top: 100px; width: 500px; border-radius: 10px;">
				<div class="row" style="text-align: center;">
					<p class="text-center" style="color: #414896; font-size: 16px;">File exchange list is empty</p>
					<a class="button text-center" href="/upload" style="display:inline-block; width: 200px">Create File Exchange</a>
				</div>
			</div>
		@endforelse
	</div>
	<!-- Content end -->
	{!! Form::token() !!}
</div>

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

	$(document).on('click', '.removeDrop', function(e){
		$This = $(this);
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this file exchange!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel pls!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm){
			if (isConfirm) {
				var token = $('input[name=_token]').val();
				$.ajax({
					type:	'DELETE',
					url:	'/d/' + $This.attr('data-hash'),
					data:	{ _token : token },
					success: function(data){
						$This.parent().parent().remove();
						swal("Deleted!", "Your file exchange has been deleted.", "success");
					},
					error: function(data){
						swal("Error!", "An error occured while deleting the file exchange.", "error");
					}
				});

			} else {
				swal("Cancelled", "Your file exchange is safe :)", "error");
			}
		});

	});

	$(document).on('click', 'a.drop', function(event) {
		if ($(event.target).closest('.removeDrop').length) {
			event.preventDefault();
		}
	});
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
