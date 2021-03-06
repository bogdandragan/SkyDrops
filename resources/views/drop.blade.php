
@extends('master')

@section('title')
	@if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif
	| SkyDrops Beta
@endsection

@section('createFEButton')
	<a class="button" href="/upload">Create File Exchange</a>
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

	#dropTitle, #dropTitle2{
	margin-top:20px;
	display: block;
	color: #fff;
	font-size: 28px;
	}

	.validityDatepicker[readonly]{
		background-color: #fff;
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
<div class="subHeader">
	<div class="container wrap">
		<div class="subHeaderLeftBlock">
			@if(Auth::check())
				<i class="fa fa-2x fa-inbox titleIcon" data-hash="{{ $drop->hash }}"></i><span id="dropTitle" contenteditable="true" title="Click to rename drop">@if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif</span>
			@else
				<i class="fa fa-2x fa-inbox titleIcon"></i><span id="dropTitle2">@if($drop->title) {{ $drop->title }} @else {{ $drop->hash }} @endif</span>
			@endif
				<!-- <input type="button" class="button shareButton" value="Share" /> -->
			@if(Auth::check())
				<a style="width: 205px" id="copyDropLink" class="button downloadArchive" href="#"><i class="fa fa-link"></i> Get Link for File Exchange</a>
			@endif
				@if($drop->wasSaved)
				<a class="button downloadArchive" href="/d/{{ $drop->hash }}/downloadZip"><i class="fa fa-download"></i> Download Archive</a>
				@endif
			@if(Auth::check() && $drop->wasSaved)
				<a style="" id="copyArchiveLink" data-clipboard-text="" class="button downloadArchive" href="#"><i class="fa fa-link"></i> Copy Archive Link</a>
			@endif
			@if($drop->forUpload)
				<a style="" id="copyDropUploadLink" data-clipboard-text="" class="button downloadArchive" href="#"><i class="fa fa-link"></i> Get link for upload</a>
			@endif
		</div>
		<div class="box subHeaderRightBlock">
			<div class="boxHeader">
				File Exchange Information
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
								@if(Auth::check())
									<input type="text" class="form-control validityDatepicker" data-date-format="mm/dd/yyyy" value="{{date("d.m.Y", strtotime($drop->expires_at))}}" readonly>
									<input type="hidden" id="createdAt" value="{{$drop->dropscreated_at}}">
									<span id="updateValidityBtn" title="Click to change validity" class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
								@else
									<input type="text" class="form-control validityDatepicker" data-date-format="mm/dd/yyyy" value="{{date("d.m.Y", strtotime($drop->expires_at))}}" disabled>
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
									@if(!$file->isTmp)
									<tr>
										<td><span class="preview" data-index="{{$index}}" data-contenttype="{{ $file->content_type }}" data-hash="{{ $file->hash }}" href="#">{{ $file->name }}</span></td>
										<td>{{ App\File::formatFileSize($file->size) }}</td>
										<td>
											@if(substr($file->content_type, 0, 5) == "image")
											<!-- <a class="button preview downloadDrop">Preview</a> -->
											<!-- <span title="Preview" class="preview" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-eye"></i></span> -->
											@endif
												@if($drop->wasSaved)
													<a class="button downloadDrop" href="/f/{{ $file->hash }}/download"><i class="fa fa-download"></i> Download</a>
												@endif
												@if(Auth::check() && !$drop->wasDownloaded && $drop->wasSaved)
												<a id="removeFile" class="button removeDrop" href="" data-hash="{{ $file->hash }}"><i class="fa fa-times"></i> Remove</a>
												@endif
											<!--<a title="Download" href="/f/{{ $file->hash }}/download">
												<i class="fa fa-download"></i>
											</a> -->
										</td>
									</tr>
									@endif
								@endforeach

							</table>
						</div>
					</div>
					@if((Auth::check() && !$drop->wasDownloaded && $drop->wasSaved) || $drop->forUpload)
					<div class="mainBlock" style="float: none; width: auto;">
						<div class="title-block">
							<div class="fLeft">
								<h4>Add files to File Exchange</h4>
							</div>
							<div class="fRight">
								<input name="addFileButton" type="button" id="addFileButton" data-hash="{{ $drop->hash }}" class="button saveBtnDisabled" value="Save" disabled/>
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
					@endif
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
							<button type="button" id="shareButton" class="button"><i class="fa fa-share-alt"></i> Share</button>
						</div>
					</div>
					@endif
				</div>
				@if(Auth::check() && count($sharedWith) > 0)
				<div class="sideBlock" style="float: right; padding-left: 0;">
					<div class="box share">
						<div class="boxHeader">
							This drop was shared with:
					</div>
					<div class="boxContent">
						<ul style="margin-left: 10px; list-style: none;">
							@foreach ($sharedWith as $shared)
								<li>{{$shared->email}}</li>
							@endforeach
						</ul>
					</div>
				</div>
					@endif
	</div>
	<br clear="both" />
	<input id="isOwner" type="hidden" value="{{$owner}}">
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
			<div class="progressBar" style="width:0%;" data-dz-uploadprogress></div>
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
		$("#copyDropLink").attr('data-clipboard-text', location.protocol + '//' + location.host + location.pathname);
		var dropLink = new ZeroClipboard( document.getElementById("copyDropLink") );
		dropLink.on( "ready", function( readyEvent ) {
			dropLink.on( "aftercopy", function( event ) {
				swal("Link copied to clipboard.");
			} );
		});

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
				//src: "/img/fileicons/" + aContenttype[1] + ".png"
				src: "/img/no-preview.png"
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

	function removeNotAllowedCharacters(filename){
		var res = filename.replace(/,/g, '');
		res = res.replace(/;/g, '');
		res = res.replace(/\s/g, '');
		return res;
	}

	Dropzone.autoDiscover = false;
	var addDropzone;
	var totalSize;
	if ( $( ".addFileDropzone" ).length ) {
		addDropzone = new Dropzone(".addFileDropzone",
				{
					url: "/u/addFile",
					dictDefaultMessage: "Drop files here or click to upload.",
					parallelUploads: 10,
					maxFilesize: 16384,
					autoProcessQueue: true,
					thumbnailWidth: 178,
					thumbnailHeight: 200,
					uploadMultiple: true,
					previewTemplate: $('#preview-template').html(),
					renameFilename: function (filename) {
						return removeNotAllowedCharacters(filename)
					}
				});

		// Update the total progress bar
		addDropzone.on("totaluploadprogress", function (progress) {
			progress = parseInt(progress);
			$('.blackBlock .progressValue').text(progress + " %");
			$('.blackBlock .progressBar').css('width', progress + "%");
		});

		addDropzone.on("successmultiple", function (files, response) {
			//console.log(response);
			$('.blackBlock').hide();

			$("#totalSize").text(formatFileSize(response.drop.totalSize));
		});

		addDropzone.on("sending", function (file, xhr, formData) {
			formData.append("dropHash", $("#addFileButton").attr("data-hash"));
		});

		addDropzone.on("uploadprogress", function(file) {
			console.log(file);
			console.log(file.upload.progress + " " + file.name);
			$(".dz-filename span:contains("+file.name+")").parent().parent().children().last().css('width', file.upload.progress + "%");
		});

		addDropzone.on("addedfile", function (file) {
			//check if file was added
			if (this.files.length) {
				for (var i = 0; i < this.files.length - 1; i++){
					if(this.files[i].name === file.name && this.files[i].size === file.size && this.files[i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
					{
						this.removeFile(file);
					}
				}
			}
			totalSize += file.size;

			if(totalSize>10*1024*1024*1024){
				swal("Error!", "You cannot upload more than 10Gb", "error");
				this.removeFile(file);
				totalSize -= file.size;
			}

			$("#addFileButton").removeClass("saveBtnDisabled")
			$("#addFileButton").removeAttr('disabled');
		});

		addDropzone.on("removedfile", function (file) {
			var token = $('input[name=_token]').val();

			var filename = removeNotAllowedCharacters(file.name);

			if(typeof (Cookies.get(filename)) == 'undefined')
				return;

			var hash = Cookies.get(filename);
			console.log(hash);
			$.ajax({
				type:	'DELETE',
				url:	'/f/' + hash,
				data:	{ _token : token },
				success: function(data){
					totalSize -= file.size;
					if (!addDropzone.files.length) {
						$("#addFileButton").attr("disabled", true);
						$("#addFileButton").addClass("saveBtnDisabled");
						window.onbeforeunload = null;
					}
					Cookies.expire(filename);
				},
				error: function(data){
					swal("Error!", "An error occured while deleting the file.", "error");
				}
			});
		});

		addDropzone.on("error", function (error) {
			$('.blackBlock').hide();
			swal("Error!", "An error occured while creating new drop", "error");
		});

		$("#addFileButton").click(function(){
			var data = [];
			$.each(addDropzone.files, function (index, value) {
				var filename = removeNotAllowedCharacters(value.name);
				var hash = Cookies.get(filename);
				data.push(hash);
				Cookies.expire(filename);
			});

			var token = $('input[name=_token]').val();
			console.log("Data: "+data);
			$.ajax({
				type:	'POST',
				url:	'/u/addFile/save',
				data:	{ _token : token, files : data },
				success: function(data){
					var filesHtml = "";
					$.each(addDropzone.files, function (index, value) {
						var filename = removeNotAllowedCharacters(value.name);

						var row = "<tr><td><span class='preview' data-index='" + index + "' data-contenttype='" + value.content_type + "' data-hash='" + value.hash + "' href='#'>" + filename + "</span></td>" +
								"<td>" + formatFileSize(value.size) + "</td>" +
								"<td>" +
								"<a class='button downloadDrop' href='/f/" + value.hash + "/download'><i class='fa fa-download'></i> Download</a>";

						if($("#isOwner").val() == 1){
							row += "<a id='removeFile' class='button removeDrop' href='' data-hash='" + value.hash + "'><i class='fa fa-times'></i> Remove</a>";
						}
						row += "</td></tr>";
						filesHtml += row;
						window.onbeforeunload = null;
					});
					$('.files').data('key', $.parseJSON(JSON.stringify(addDropzone.files)));
					$(".files").append(filesHtml);
					console.log(addDropzone.files);
					addDropzone.files = [];
					console.log(addDropzone.files);
					$( ".dz-preview" ).remove();
					$("#addFileButton").attr("disabled", true);
					$("#addFileButton").addClass("saveBtnDisabled");
				},
				error: function(data){
					swal("Error!", "An error occured while saving file exchange.", "error");
				}
			});
		});

	}
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('input[name="_token"]').val()
			}
		});

	function formatFileSize(size){
		var units = [' B', ' KB', ' MB', ' GB', ' TB'];
		for (i = 0; size > 1024; i++) { size /= 1024; }
		return (Math.round(size * 100) / 100)+units[i];
	}

	function formatDate(oDate) {
		return ("0" + oDate.getDate()).slice(-2) + "." + ("0" + (oDate.getMonth() + 1)).slice(-2) + "." + oDate.getFullYear();
	}

	function updateDropvalidity(newDate, diffDays){
		var token = $('input[name=_token]').val();
		$.ajax({
			type:	'POST',
			url:	'/d/'+$("#addFileButton").attr("data-hash")+'/updateValidity',
			data:	{_token : token, newDate : newDate, diffDays : diffDays},
			success: function(data){
				swal("Updated!", "Drop validity date has been updated.", "success");
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status == 403){
					swal("Error!", jqXHR.statusText, "error");
				}
				else{
					swal("Error!", "An error occured while updating drop validity!", "error");
				}
			}
		});
	}

	var date = new Date();
	date.setDate(date.getDate() + 1);
	date = new Date(date);
	$('.validityDatepicker').datepicker({
		format: 'dd.mm.yyyy',
		startDate: date,
		endDate: '+90d'
	}).on('changeDate', function(e){
		$(this).datepicker('hide');
		var createdDate = $("#createdAt").val().split("-");
		var createdDate = new Date(createdDate[0], createdDate[1]-1, createdDate[2].substring(0,2))
		console.log(createdDate);
		var diffDays = Math.floor(($(".validityDatepicker").data('datepicker').dates[0] - createdDate) / (1000 * 60 * 60 * 24));
		console.log(diffDays);
		if(diffDays > 90){
			swal("Error!", "File exchange validity cannot be more than 90 days!", "error");
			return false;
		}

		var coins = 0;
		if(diffDays > 30 && diffDays <= 90){
			coins = 1
		}

		swal({
			title: "Are you sure?",
			text: "New date: "+ formatDate($(".validityDatepicker").data('datepicker').dates[0])+".\r\nThis operation will cost you "+coins+" coin(s)",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, continue!",
			cancelButtonText: "No, cancel pls!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm){
			if (isConfirm) {
				updateDropvalidity($(".validityDatepicker").data('datepicker').getFormattedDate('yyyy-mm-dd') + " 00:00:00", diffDays);
				var date = $(".validityDatepicker").data('datepicker').dates[0];
				date.setDate(date.getDate() + 1);
				$('.validityDatepicker').datepicker('setStartDate', date);
			} else {
				swal("Cancelled", "", "error");
			}
		});
	});
	var date = $(".validityDatepicker").data('datepicker').dates[0];
	date.setDate(date.getDate() + 1);

	$('.validityDatepicker').datepicker('setStartDate', date);

	$('#updateValidityBtn').on('click', function(){
		$('.validityDatepicker').datepicker('show');
	});

	$('.validityDatepicker').on('click', function(){
		//$('.validityDatepicker').datepicker('hide');
	});

	var oldDropTitle;
	$('#dropTitle').spellcheck = false;
	$('body').attr("spellcheck",false);
	$('#dropTitle').focus(function() {
		oldDropTitle = $(this).html();
	});

	$('#dropTitle').keypress(function(e) {
		if(e.which == 13) {
			console.log("enter");
			e.preventDefault();
		}
	});

	$('#dropTitle').focusout(function() {
		var str = $(this).html().trim();
		str = str.replace(/&nbsp;/g, ' ');
		str = str.replace(/<br>/g, '');
		str = str.trim();
		console.log(str);

		$(this).html(str);
		if($(this).html() == ""){
			$(this).html(oldDropTitle);
		}

		if(oldDropTitle != $(this).html()){
			var token = $('input[name=_token]').val();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('input[name="_token"]').val()
				}
			});

			$.ajax({
				type:	'POST',
				url:	'/d/'+$(".titleIcon").attr("data-hash")+'/updateTitle',
				data:	{_token : token, newTitle : $(this).html()},
				success: function(data){
					swal("Updated!", "Drop has been renamed.", "success");
				},
				error: function(jqXHR, textStatus, errorThrown){
					swal("Error!", "An error occured while renaming the drop!", "error");
				}
			});
		}
	});

	window.onbeforeunload = function (evt) {
		console.log("123465");
		if(addDropzone != undefined){
			if (addDropzone.files.length) {
				return "Are you sure you want to leave, cause there are some unsaved changes?";
			}
		}
	};

	window.addEventListener("pagehide", function () {
		console.log("659874");
		if(addDropzone == undefined){
			return false;
		}

		if (addDropzone.files.length) {
			var data = [];
			$.each(addDropzone.files, function (index, value) {
				var filename = removeNotAllowedCharacters(value.name);
				var hash = Cookies.get(filename);
				data.push(hash);
				Cookies.expire(filename);
			});

			var token = $('input[name=_token]').val();
			$.ajax({
				type:	'POST',
				url:	'/u/addFile/deleteTmp',
				data:	{ _token : token, files:data},
				async: false,
				success: function(data){

				},
				error: function(data){

				}
			});
		}
	});
</script>

@if(Auth::check())

<script>
	// For a pie chart

	/*var chartData = [
		{
			value: 0,
			color:"#F7464A",
			highlight: "#FF5A5E",
			label: "Used Drops"
		},
		{
			value: 0,
			color: "#222",
			highlight: "#333",
			label: "Free Drops"
		}
	];

	var options = {
		//Boolean - Whether we should show a stroke on each segment
		segmentShowStroke : false,

		//String - The colour of each segment stroke
		segmentStrokeColor : "#aaa",

		//Number - The width of each segment stroke
		segmentStrokeWidth : 0,

		//Number - The percentage of the chart that we cut out of the middle
		percentageInnerCutout : 60, // This is 0 for Pie charts

		//Number - Amount of animation steps
		animationSteps : 100,

		//String - Animation easing effect
		animationEasing : "easeOutBounce",

		//Boolean - Whether we animate the rotation of the Doughnut
		animateRotate : true,

		//Boolean - Whether we animate scaling the Doughnut from the centre
		animateScale : false,

	};*/

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
				/*chartData[0].value = parseInt(data[1]);
				 chartData[1].value = parseInt(data[0]);
				 var ctx = document.getElementById("myChart").getContext("2d");
				 var myPieChart = new Chart(ctx).Pie(chartData,options);*/
	/*		},
			error: function(data){
				console.log(data);
			}
		});*/
	});


	/*
	 1) @skypro.ch не будут покупать coins. Дава сделаем как проще:

	 - или всем дадим 1000000 coins

	 - или вообще не будем их учитывать

	 - или пусть счетчик не уменьшает количество coins для них

	 //2) Календарь для выбора даты срока жизни FE.

	// - по меньшей дате у нас ограниечение есть - это хорошо

	// но по большей нет, а у нас в задании не более 90 дней (если я не лшибаюсь).

	// Запретить пользователю выбирать дату более чем 90 дней от текущей.

	 3) Forgot password & Registration формы - остался старый дизайн (надо бы что-то покрасивее)

	 Может их модальными сделать или еще как но оставить на Landing Page (чтоб юзер от туда не уходил).

	 4) Contact Us form- по сле отправки давай покажем окошко с картинкой "Thank you". (можно взять с страницы комникатора "картинка стикера")

	 5) Если юзер не залогиненый то он не может попасть на Landing Page. Давай может сделаем логику чтоб мог.

	 Так же проблема с покупкой coins. Кнопки есть на Landing Page но юзер может увидеть эту страницу только когда он не залогиненый...

	 Опять же, если юзер не залогиненый и нажмет купить coins то ему нужно будет залогиниться.

	 Думаю надо как-то так:

	 - Залогиненый юзер может попасть на landing page

	 - если юзер залогиненый и на landing page то не показываем ему log in & registration. вместо этого лучше какуюто кнопку или картинку чтоб он мог попасть на свою страничку

	 6) Давайте добавим прокрутку (эффект) при нажатии на элементы главного меню. Так же выделим пункт меню на котором мы в текущий момент находимся.

	 7) Не надо дублировать меню на каждой странице landing page. В заголовке у нас уже есть меню.
	 */
</script>
@endif

@endsection



