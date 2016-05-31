
@extends('master')

@section('title')
	@if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif
	| SkyDrops Beta
@endsection

@section('style')
	#filePreview .modal-body {
	padding: 0;
	height: 100%;
	overflow-y: auto;
	}

	#filePreview .modal-body img {
	max-width: 100%;
	max-height: max-content;
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
<div class="subHeader">
	<div class="container wrap">
		<div class="subHeaderLeftBlock">
			<h2><i class="fa fa-inbox"></i> @if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif</h2>
			<!-- <input type="button" class="button shareButton" value="Share" /> -->
			<a class="button downloadArchive" href="/d/{{ $drop->hash }}/downloadZip"><i class="fa fa-download"></i> Download Archive</a>
			<a style="width: 150px;" id="copyArchiveLink" data-clipboard-text="" class="button downloadArchive" href="#"><i class="fa fa-link"></i> Copy Archive Link</a>
			<a style="width: 150px;" id="copyDropUploadLink" data-clipboard-text="" class="button downloadArchive" href="#"><i class="fa fa-link"></i> Get link for upload</a>

		</div>
		<div class="box subHeaderRightBlock">
			<div class="boxHeader">
				Drop Information
			</div>
			<div class="boxContent">
				<table class="dropTable">
					<tr>
						<td>Total size</td>
						<td id="totalSize">
							{{App\File::formatFileSize($drop->totalSize)}}
						</td>
					</tr>
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
							<div>
								<input type="text" class="form-control validityDatepicker" data-date-format="mm/dd/yyyy" value="{{date("d.m.Y", strtotime($drop->expires_at))}}" disabled>
								@if(Auth::check())
								<span id="updateValidityBtn" title="Click to change validity" class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
								@endif
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>



</div>

<div class="container wrap dropBlock">

				<!-- Content start -->

				<div class="mainBlock">
					<div class="box">
						<div class="boxContent">
							<table class="files" cellpadding="0" cellspacing="0">
								@foreach ($files as $index => $file)
									<tr>
										<td><span class="preview" data-index="{{$index}}" data-contenttype="{{ $file->content_type }}" data-hash="{{ $file->hash }}" href="#">{{ $file->name }}</span></td>
										<td>{{ App\File::formatFileSize($file->size) }}</td>
										<td>
											@if(substr($file->content_type, 0, 5) == "image")
											<!-- <a class="button preview downloadDrop">Preview</a> -->
											<!-- <span title="Preview" class="preview" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-eye"></i></span> -->
											@endif
											<a class="button downloadDrop" href="/f/{{ $file->hash }}/download">Download</a>
											<a id="removeFile" class="button removeDrop" href="" data-hash="{{ $file->hash }}">Remove</a>
											<!--<a title="Download" href="/f/{{ $file->hash }}/download">
												<i class="fa fa-download"></i>
											</a> -->
										</td>
									</tr>
								@endforeach

							</table>
						</div>
					</div>

					<div class="mainBlock" style="float: none; width: auto;">
						<div class="title-block">
							<div class="fLeft">
								<h4>Add files to drop</h4>
							</div>
							<div class="fRight">
								<input name="addFileButton" type="button" id="addFileButton" data-hash="{{ $drop->hash }}" class="button" value="Upload" disabled/>
							</div>
							<br clear="both" />
							<br clear="both" />
						</div>
						<form action="{{ url('u/addFile')}}" class="addFileDropzone" id="my-awesome-dropzone" enctype="multipart/form-data" method="post">
							<div class="dz-message">
								Drop files here or click to upload.</div>
							{!! Form::token() !!}
						</form>


					</div>

				</div>
				<div class="sideBlock">
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
        	<button class="btn btn-preview" id="copy-button" data-clipboard-text="" title="Click to copy me.gfgfg">Copy Link</button>
	        <a href="" class="btn btn-preview">Download</a>
        </div>
      </div>
      <div class="modal-body">
		  <div class="content">

		  </div>
		  @if(count($files) > 1)
		  <div style="display: block; font-size: 30px; z-index: 100; bottom: 0px; right: 0px; width: 49%; float: left;">
			  <a class="glyphicon glyphicon-chevron-left previous" href="#" style="float: right; color: #fff;"></a>
		  </div>
		  <div style="display: block; font-size: 30px; z-index: 100; bottom: 0px; width: 49%; float: right;">
			  <a class="glyphicon glyphicon-chevron-right next" href="#" style="float: left; color: #fff;"></a>
		  </div>
		  @endif

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

<script src="/js/zeroclipboard/ZeroClipboard.min.js"></script>

<script>
	var oFile = {};
	var aMentions = [];
	var currentIndex = "";
	$(document).ready(function() {

		$("#copyArchiveLink").attr('data-clipboard-text', location.protocol + '//' + location.host + location.pathname + "/downloadZip");
		var archiveLink = new ZeroClipboard( document.getElementById("copyArchiveLink") );
		archiveLink.on( "ready", function( readyEvent ) {
			archiveLink.on( "aftercopy", function( event ) {
				swal("Link copied to clipboard.");
			} );
		});

		$("#copyDropUploadLink").attr('data-clipboard-text', location.protocol + '//' + location.host + location.pathname + "/sharedForUpload");
		var copyDropLink = new ZeroClipboard( document.getElementById("copyDropUploadLink") );
		copyDropLink.on( "ready", function( readyEvent ) {
			copyDropLink.on( "aftercopy", function( event ) {
				var token = $('input[name=_token]').val();
				$.ajax({
					type:	'POST',
					url:	'/d/'+$("#addFileButton").attr("data-hash")+'/shareForUpload',
					data:	{_token : token},
					success: function(data){
						swal("Link for upload copied to clipboard.");
					},
					error: function(data){
						swal("Error!", "An error occured while sharing the drop.", "error");
					}
				});


			});
		});

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
		currentIndex = $(this).attr('data-index');
	});

	$(document).on("click", ".next", function(){
		var nextIndex = parseInt(currentIndex)+1;
		var nextPreview = $(".preview[data-index='"+nextIndex+"']");
		if(nextPreview.length > 0){
			currentIndex = nextIndex;
			showPreview(nextPreview);
		}
	});

	$(document).on("click", ".previous", function(){
		var previousIndex = parseInt(currentIndex)-1;
		var previousPreview = $(".preview[data-index='"+previousIndex+"']");
		if(previousPreview.length > 0){
			currentIndex = previousIndex;
			showPreview(previousPreview);
		}
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
			$('#filePreview .modal-body .content').html($('<img/>', {
				src: "/f/" + oFile.hash
			}));
		} else if(aContenttype[0] == "application") {
			$('#filePreview .modal-body .content').html($('<img/>', {
				src: "/img/no-preview.png",
			}));

		} else if(aContenttype[0] == "video") {
			$('#filePreview .modal-body .content').html($('<video/>', {
				controls: true,
				style: "max-width: 100rem; width: 100%;",
				html: $('<source/>', {
					src: "/f/" + oFile.hash,
					type: oFile.content_type
				})
			}));
		} else {
			$('#filePreview .modal-body .content').html($('<img/>', {
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
		});

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


	$(document).on('click', '#removeFile', function(e){
		e.preventDefault();
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this file!",
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
					url:	'/f/' + $("#removeFile").attr('data-hash'),
					data:	{ _token : token },
					success: function(data){
						swal("Deleted!", "Your file has been deleted.", "success");
						$("#removeFile").parent().parent().remove();
					},
					error: function(data){
						swal("Error!", "An error occured while deleting the file.", "error");
					}
				});
			} else {
				swal("Cancelled", "Your file is safe :)", "error");
			}
		});

	});

	Dropzone.autoDiscover = false;


			var addDropzone = new Dropzone(".addFileDropzone",
					{
						url: "/u/addFile",
						dictDefaultMessage: "Drop files here or click to upload.",
						parallelUploads: 10,
						maxFilesize: 2047,
						autoProcessQueue: false,
						thumbnailWidth: 178,
						thumbnailHeight: 200,
						uploadMultiple: true,
						previewTemplate: $('#preview-template').html()
					});

			// Update the total progress bar
			addDropzone.on("totaluploadprogress", function(progress) {
				progress = parseInt(progress);
				$('.blackBlock .progressValue').text(progress + " %");
				$('.blackBlock .progressBar').css('width', progress + "%");
			});

			addDropzone.on("successmultiple", function(files, response) {
				//console.log(response);
				$('.blackBlock').hide();
				window.onbeforeunload = null;
				var filesHtml = "";
				$.each(response.files, function(index, value) {
					var row = "<tr><td><span class='preview' data-index='"+index+"' data-contenttype='"+value.content_type+"' data-hash='"+value.hash+"' href='#'>"+value.name+"</span></td>"+
							"<td>"+formatFileSize(value.size)+"</td>"+
							"<td>"+
							"<a class='button downloadDrop' href='/f/"+value.hash+"/download'>Download</a>"+
							"<a id='removeFile' class='button removeDrop' href='' data-hash='"+value.hash+"'>Remove</a>"+
							"</td>"+
							"</tr>";
					filesHtml+=row;
				});
				$('.files').data('key',$.parseJSON(JSON.stringify(response.files)));
				$(".files").html(filesHtml);
				$("#totalSize").text(formatFileSize(response.drop.totalSize));
				addDropzone.removeAllFiles(true);
			});

			addDropzone.on("sending", function(file, xhr, formData) {
				formData.append("dropHash", $("#addFileButton").attr("data-hash"));
			});

			addDropzone.on("addedfile", function(file) {
				$("#addFileButton").removeAttr('disabled');
			});

			addDropzone.on("removedfile", function(file) {
				if(!addDropzone.files.length){
					$("#addFileButton").attr("disabled", true);
				}
			});

			addDropzone.on("error", function(error) {
				$('.blackBlock').hide();
				swal("Error!", "An error occured while creating new drop", "error");
			});



		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('input[name="_token"]').val()
			}
		});

		$('#addFileButton').click(function(){
			addDropzone.processQueue();
			$('.blackBlock').show();

			window.onbeforeunload = function() {
				return "Your upload is not completed.";
			};

		});

	function formatFileSize(size){
		var units = [' B', ' KB', ' MB', ' GB', ' TB'];
		for (i = 0; size > 1024; i++) { size /= 1024; }
		return (Math.round(size * 100) / 100)+units[i];
	}

	function updateDropvalidity(newDate){
		var token = $('input[name=_token]').val();
		$.ajax({
			type:	'POST',
			url:	'/d/'+$("#addFileButton").attr("data-hash")+'/updateValidity',
			data:	{_token : token, newDate : newDate},
			success: function(data){
				swal("Updated!", "Drop validity date has been updated.", "success");
			},
			error: function(jqXHR, textStatus, errorThrown){
				swal("Error!", "An error occured while updating drop validity!", "error");
			}
		});
	}

	var date = new Date();
	date.setDate(date.getDate() + 1);
	date = new Date(date);
	$('.validityDatepicker').datepicker({
		format: 'dd.mm.yyyy',
		startDate: date
	}).on('changeDate', function(e){
		$(this).datepicker('hide');
		updateDropvalidity($(".validityDatepicker").data('datepicker').getFormattedDate('yyyy-mm-dd') + " 00:00:00");
	});

	$('#updateValidityBtn').on('click', function(){
		$('.validityDatepicker').datepicker('show');
	});

	$('.validityDatepicker').on('click', function(){
		$('.validityDatepicker').datepicker('hide');
	});

</script>


@endsection



