<title>@if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif</title>

@extends('master')

@section('content')
<div class="subHeader">
	<div class="wrap">
		<h2><i class="fa fa-inbox"></i> @if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif</h2>
		<!-- <input type="button" class="button shareButton" value="Share" /> -->
	</div>
</div>

<div class="container wrap dropBlock">
				
				<!-- Content start -->
				
				<div class="mainBlock">
					<div class="box">
						<div class="boxContent">
				
							<table class="files" cellpadding="0" cellspacing="0">
								@foreach ($files as $file)
									<tr>
										<td><span class="preview" data-contenttype="{{ $file->content_type }}" data-hash="{{ $file->hash }}" href="#">{{ $file->name }}</span></td>
										<td>{{ App\File::formatFileSize($file->size) }}</td>
										<td>
											@if(substr($file->content_type, 0, 5) == "image")
											<!-- <a class="button preview downloadDrop">Preview</a> -->
											<!-- <span title="Preview" class="preview" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-eye"></i></span> -->
											@endif
											<a class="button downloadDrop" href="/f/{{ $file->hash }}/download">Download</a>
											<!--<a title="Download" href="/f/{{ $file->hash }}/download">
												<i class="fa fa-download"></i>
											</a> -->
										</td>
									</tr>
								@endforeach
						
							</table>
						</div>
					</div>
				</div>
				<div class="sideBlock">
					<div class="box">
						<div class="boxHeader">
							Drop Information
						</div>
						<div class="boxContent">
							<table class="dropTable">
								<tr>
									<td>Creator</td>
									<td>
										<a href="/u/{{ $drop->user_id }}">{{ $drop->firstname }} {{ $drop->lastname }}</a>
									</td>
								</tr>
								<tr>
									<td>Created at</td>
									<td>
										<?php echo date("d.m.Y", strtotime($drop->dropscreated_at)); ?>
									</td>
								</tr>
								<!--
								<tr>
									<td>Tags</td>
									<td>
										@if(!empty($drop->tags))
										@foreach (explode(",", $drop->tags) as $tag)
										<span class="tag"><i class="fa fa-tag"></i>{{ $tag }}</span>
										@endforeach
										@else
										-
										@endif
									</td>
								</tr>
								-->
								<tr>
									<td>Validity</td>
									<td>
										<?php
											/*
											$datetime1 = new DateTime();
											$datetime2 = $drop->created_at;
											$interval = $datetime1->diff($datetime2);
											$elapsed = 7 - $interval->format('%a');
											$elapsed .= " days";
											*/
											$elapsed =  (isset($drop->expires_at)) ? "<span style='color: #e84c3d'>" . date("d.m.Y", strtotime($drop->expires_at)) . "</span>" : "<span class='infinite'></span>";

										?>
									{!! $elapsed !!}
									</td>
								</tr>
							</table>
						</div>
					</div>
					@if(Auth::check())
					<div class="box share">
						<div class="boxHeader">
							Share
						</div>
						<div class="boxContent">
							<p>Pick some contacts from the list or type valid email addresses.</p>
							<input type="text" id="input-contacts" />
							<textarea placeholder="Write a message" class="selectize-input"></textarea>
							<input type="button" id="shareButton" class="button" value="Share">
						</div>
					</div>
					@endif
				</div>

				<br clear="both" />
				<!-- Content end --> 
</div>
{!! Form::token() !!}
@if(Auth::check())
<input type="hidden" id="userID" value="{{ Auth::user()->id }}" />
@endif

	<!-- Absolute -->
<div class="modal fade black" id="filePreview" tabindex="-1" role="dialog">
  <div class="modal-dialog clearfix">
    <div class="modal-content">
      <div class="modal-header">
      	<div class="modal-header-left clearfix">
      		<span class="numPages"></span>
      		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      	</div>
        <h4 class="modal-title">Modal title</h4>
        <div class="modal-header-right">
        	<button class="btn btn-preview" id="copy-button" data-clipboard-text="" title="Click to copy me.">Copy Link</button>
	        <a href="" class="btn btn-preview">Download</a>
        </div>
      </div>
      <div class="modal-body">
      </div>
    </div><!-- /.modal-content -->

    <div class="modal-sidebar">
    	<div class="sidebar-header">
    		<span class="numComments"></span>
    	</div>
    	<div class="sidebar-content">
    		<div class="comment-block">

    		</div>
		@if(Auth::check())
    		<form action="">
			<!-- <input type="text" placeholder="Your name" /> -->
    			<textarea class="commentText mention" placeholder="Under construction" style="background-color: #fff !important;" ></textarea>
    			<input type="submit" class="button commentButton" value="Comment" />
    		</form>
		@endif
    	</div>
    </div>

  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="/js/zeroclipboard/ZeroClipboard.min.js"></script>

<script>
	var oFile = {};
	var aMentions = [];
	$(document).ready(function() {



		//Textmention

		$.getJSON("/u", function(oData) {
			var aUsers = [];
			$.each(oData, function(sKey, oValue){
				aUsers.push({ id: oValue.id, email: oValue.email, fullname: oValue.firstname + " " + oValue.lastname + " " });
			});


			$('textarea.mention').textcomplete([
			    { // html
			        mentions: aUsers,
			        match: /\B@(\w*)$/,
			        search: function (term, callback) {
			            callback($.map(this.mentions, function (mention) {
						  var value = mention.fullname;
			                return value.indexOf(term) === 0 ? mention : null;
			            }));
			        },
			        index: 1,
				    template: function (mention) {
				        return mention.fullname
				   },
			        replace: function (mention) {
				       aMentions.push(mention.email);
			            return '@' + mention.fullname + " ";
			        }
			    }
				], { appendTo: 'body' }).overlay([
				    {
				        match: /\B@(?:\w+\s?){0,2}\w*/g,
				        css: {
				            'background-color': '#d8dfea'
				        }
				    }
				]);

		});




		$('.files').data('key',$.parseJSON('{!! $files !!}'));

		if(getQueryVariable('preview') == "true" && getQueryVariable('fileid')) {
			showPreview();
		};

		
	});

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

	$(document).on("click", ".preview", function(){
		showPreview($(this));
	});

	$(document).on("click", ".shareButton", function() {
		swal({ 
			title: "<small>Share by</small> Email",   
			text: $('.box.share').html(),  
			html: true
		});
	});

	$(document).on("click", ".commentButton", function(e) {
		e.preventDefault();
		$.post( "/fc", { link: $('.btn-preview').data('clipboard-text') , aEmails: JSON.stringify(aMentions), user_id: $('#userID').val(), file_id: oFile.id, text: $('textarea.commentText').val() }, function(jsonData){
			appendComment(jQuery.parseJSON(jsonData));
			$('.no-comments').remove();
			$('.commentText').val("");
			var a = parseInt($('.numComments').text().charAt(0).trim()) + 1;
			$('.numComments').text(a + " Comments");
		} );

	});

function showPreview($oThis) {

	$file = $oThis || $(document).find("[data-hash='" + getQueryVariable('fileid') + "']");
	oFile = {};
	var bFound = false;
		$.each($('.files').data('key'), function(sKey, oValue) {
			if(oValue.hash == $file.data('hash')) {
				oFile = oValue;
				iPos = sKey + 1;
				bFound = true;
			}
		});

		if(!bFound) return false;

		var aContenttype = oFile.content_type.split('/')
		if(aContenttype[0] == "image"){
			$('#filePreview .modal-body').html($('<img/>', {
				src: "/f/" + oFile.hash
			}));
		} else if(aContenttype[0] == "application") {
			$('#filePreview .modal-body').html($('<iframe/>', {
				src: "http://docs.google.com/gview?url=http://skydrops.skypro.ch/f/" + oFile.hash + "&embedded=true",
				frameborder: 0,
				width: "600px",
				height: "800px"
			}));

		} else if(aContenttype[0] == "video") {
			$('#filePreview .modal-body').html($('<video/>', {
				controls: true,
				style: "max-width: 100rem; width: 100%;",
				html: $('<source/>', {
					src: "/f/" + oFile.hash,
					type: oFile.content_type
				})
			}));
		} else {
			$('#filePreview .modal-body').html($('<img/>', {
				src: "/img/fileicons/" + aContenttype[1] + ".png"
			}));
		}

		$('#filePreview .modal-title').text(oFile.name);
		$('#filePreview .btn-preview').attr('href', "/f/" + oFile.hash + "/download");
		$('#filePreview .numPages').text((iPos + " of " + $('.files').data('key').length));

		//Show Comments
		$('.comment-block').text('');
		$.getJSON("/f/" + oFile.id + "/getComments", function(oData) {
			$('.numComments').text(oData.length + " Comments");
			if(oData.length > 0){
				$.each(oData, function(sKey, oValue) {
					appendComment(oValue);
				});
			} else {
				$('<p/>', {
					class: "no-comments",
					text: "No comments"
				}).prependTo('.comment-block');
			}

		});

		//Set Clipboard
		$('#filePreview #copy-button').attr('data-clipboard-text', location.protocol + '//' + location.host + location.pathname + "?preview=true&fileid=" + oFile.hash);
		var client = new ZeroClipboard( document.getElementById("copy-button") );

		client.on( "ready", function( readyEvent ) {

		  client.on( "aftercopy", function( event ) {
		    // `this` === `client`
		    // `event.target` === the element that was clicked
		    //event.target.style.display = "none";
		    //alert("Link copied to clipboard.");
		    swal("Link copied to clipboard.");
		  } );
		} );

		$('#filePreview').modal('show');
}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  } 
}

function appendComment(oValue) {
	$('<div/>', {
		class: "comment clearfix",
		html: [
			$('<div/>', {
				class: "comment-image"
			}),
			$('<div/>', {
				class: "comment-text",
				html: [
					$('<a/>', {
						href: "#",
						text: oValue.firstname + " " + oValue.lastname
					}),
					$('<p/>', {
						text: oValue.text
					}),
					$('<span/>', {
						class: "date",
						text: oValue.created_at
					})
				]
			})
		]
	}).prependTo('.comment-block');
}
</script>
<style>
	#filePreview .modal-body {
		padding: 0;
		height: 100%;
		overflow-y: auto; 
	}

	#filePreview .modal-body img {
		max-width: 100%;
		max-height: max-content;
	}
</style>
