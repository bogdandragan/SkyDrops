<title>Home</title>

@extends('master')

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
					<i class="fa fa-inbox"></i>@if($drop->title) {{ trim($drop->title) }} @else {{ substr(str_replace(",", ", ", $drop->dropFiles), 0, 40) }} @endif @if(strlen($drop->dropFiles) > 40)..  @endif
				</div>
				<div class="drop-content">
					<?php
						$imageContenttype = explode("/", explode(",", $drop->dropFilesContenttype)[0])[0];
						$imageHash = explode(",", $drop->dropFilesHash)[0]; 
					?>
					@if($imageContenttype == "image")
						<div class="imagePreview" style="background-image: url('/f/{{ $imageHash }}')"></div>
					@else
						<span class="noPreview">No Preview</span>
					@endif
				</div>
				<div class="drop-footer clearfix">
					<span title="{{ $html }}" class="numFiles">{{ sizeof(explode(",", $drop->dropFiles)) }} @if(sizeof(explode(",", $drop->dropFiles)) > 1) files @else file @endif </span>
					<span class="date">
						<?php $elapsed =  (isset($drop->expires_at)) ? date("d.m.Y", strtotime($drop->expires_at)) : "<span class='infinite'></span>"; ?>
						<i class="fa fa-calendar-check-o" style="font-weight: 600"></i> {!! $elapsed !!}
					</span>
					<input type="button" class="button removeDrop" data-hash="{{ $drop->hash }}" value="Remove" />
				</div>
			</a>
		@empty
			No Drops
		@endforelse
	</div>
	<style>
		.drop-block {
			margin-bottom: 10rem;
		}

		.drop {
			float: left;
			width: 38.5rem;
			border: .1rem solid #e1e1e1;
		    background: #fff;
		    margin-bottom: 1.5rem;
		    box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
		    color: #666;
		}

		.drop:not(:nth-child(3)) {
			margin-right: 1.5rem;
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
			display: none;
			width: auto;
			margin: 0;
			position: absolute;
		    background: #eb4d5c;
		    color: #fff;
		    padding: 5px 15px;
		    font-weight: 500;
		    font-size: 1.3rem;
		    border-radius: 3px;
		    right: 1.1rem;
		    top: 1.1rem;
		}
	</style>	
				  
	<!-- Content end --> 				  
</div>
{!! Form::token() !!}
@endsection
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
			text: "You will not be able to recover this drop!",
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
						swal("Deleted!", "Your drop has been deleted.", "success");
					}
				});
				$This.parent().parent().remove();
			} else {
				swal("Cancelled", "Your drop is safe :)", "error");
			}
		});
		
	});

	$(document).on('click', 'a.drop', function(event) {
	  if ($(event.target).closest('.removeDrop').length) {
	    event.preventDefault();
	  }
	});
</script>