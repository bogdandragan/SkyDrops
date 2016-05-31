var myDropzone = "";
var $select = "";
var $selectCon = "";
Dropzone.autoDiscover = false;

$(function() {

	if ( $( ".ownDropzone" ).length ) {
		var myDropzone = new Dropzone(".ownDropzone",
		{
			url: "/u/upload",
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
	myDropzone.on("totaluploadprogress", function(progress) {
		progress = parseInt(progress);
		$('.blackBlock .progressValue').text(progress + " %");
		$('.blackBlock .progressBar').css('width', progress + "%");
	});
	
	myDropzone.on("successmultiple", function(files, response) {
		//console.log(response);
		window.onbeforeunload = null;
		window.location = "/d/" + response;
	});
	
	myDropzone.on("sending", function(file, xhr, formData) {
		formData.append("title", $('input[name=inpTitle]').val());
		//formData.append("tags", JSON.stringify($select[0].selectize.items));
		//formData.append("contacts", JSON.stringify($selectCon[0].selectize.items));
		//formData.append("message", $('textarea').val());
		formData.append("tags", JSON.stringify([]));
		formData.append("contacts", JSON.stringify([]));
		formData.append("message", "");
		formData.append("expires_at", ($('#delDate').is(':checked')) ? "" : $('.input-group.date').data('datepicker').getFormattedDate('yyyy-mm-dd') + " 00:00:00");
	});

     myDropzone.on("addedfile", function(file) {
         $("#uploadButton").removeAttr('disabled');
     });

     myDropzone.on("removedfile", function(file) {
         if(!myDropzone.files.length){
             $("#uploadButton").attr("disabled", true);
         }
     });

     myDropzone.on("error", function(error) {
         $('.blackBlock').hide();
         swal("Error!", "An error occured while creating new drop", "error");
     });

	}

    $('#emptyDrop').change(function() {
        if($(this).is(":checked")) {
            $("#uploadButton").removeAttr('disabled');
            myDropzone.removeEventListeners();
        }
        else if(!myDropzone.files.length && $(this).not(":checked")){
            $("#uploadButton").attr("disabled", true);
            myDropzone.setupEventListeners();
        }
    });
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
	
	$('.inpSearch').keyup(function(){
		var sInput = this.value;
		$('table tr').hide().unhighlight();
		if(sInput){
			var aSearch = sInput.split(" ");
			$.each(aSearch, function(iIndex, sSearch){
				if(sSearch){
					$('table tr:contains("' + sSearch + '")').show().highlight(sSearch);
				}
			});
		} else $('table tr').show();
	});
	
	$select = $('input#input-tags').selectize({
		plugins: ['remove_button'],
		delimiter: ',',
		persist: false,
		placeholder: "Add tags",
		create: function(input) {
			return {
				value: input,
				text: input
			}
		}
	});

    var date = new Date();
    date.setDate(date.getDate() + 7);
    date = new Date(date);
    $('.input-group.date .form-control').val(formatDate(date));

	initDatepicker();
	initContacts();
	autosize($('textarea'));
	
	$('#uploadButton').click(function(){
        if($("#emptyDrop").is(":checked")) {
            createEmptyDrop();
        }
        else{
            myDropzone.processQueue();
            $('.blackBlock').show();
            window.onbeforeunload = function() {
                return "Your upload is not completed.";
            };
        }
	});
	
	$('#delDate').click(function(){
		if($(this).is(':checked')){
			$('.input-group.date').children().prop('disabled', true).val("").attr('placeholder', '');
		} else{
      var date = new Date();
      date.setDate(date.getDate() + 7);
      date = new Date(date);
			$('.input-group.date .form-control').prop('disabled', false).val(formatDate(date)).attr('placeholder', 'Select date');
		}
	});
	
	$('#shareButton').click(function(){
		$.post( window.location + "/share", { contacts: JSON.stringify($selectCon[0].selectize.items), message: $('textarea').val() }, function(jsonData){
			swal("Drop successfully shared.", "success");
			$('textarea.selectize-input').val("");
      $selectCon[0].selectize.clear();
		} );
	});

  $('.userBlock').click(function() {
    $('#defaultModal')
        .addClass( $(this).data('direction') ).addClass('userModal');
    $('#defaultModal').modal('show');
    $('.modal-backdrop.in').css('opacity', ".5");
  });

  $('#defaultModal .modal-content').click(function() {
    $('#defaultModal').modal('hide');
  });

  /*$( '.drop, .files tbody tr' )
  .on( "mouseenter", function() {
    $(this).find('.removeDrop, .downloadDrop, .filePreview').show();
  })
  .on( "mouseleave", function() {
    $(this).find('.removeDrop, .downloadDrop, .filePreview').hide();
  });*/
});


function initDatepicker(){
    $('.input-group.date').datepicker({
		format	: "dd.mm.yyyy",
		startDate: new Date()
	}).on('changeDate', function(e){
		$(this).datepicker('hide');
	});
}

function createEmptyDrop(){
    var formData = new FormData();
    formData.append("title", $('input[name=inpTitle]').val());
    formData.append("tags", JSON.stringify([]));
    formData.append("contacts", JSON.stringify([]));
    formData.append("message", "");
    formData.append("expires_at", ($('#delDate').is(':checked')) ? "" : $('.input-group.date').data('datepicker').getFormattedDate('yyyy-mm-dd') + " 00:00:00");

    $.ajax({
        url: "u/upload",
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data){
            window.location = "/d/" + data;
        },
        error: function(data){
            swal("Error!", "An error occured while creating new drop", "error");
        }
    });
}

function initContacts(){
	var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                  '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

  $.getJSON("/contacts", function(json){
    $selectCon = $('#input-contacts').selectize({
      plugins: ['remove_button'],
      placeholder: "Add contacts",
      persist: false,
      maxItems: null,
      valueField: 'email',
      labelField: 'name',
      searchField: ['name', 'email'],
      options: json,
      render: {
        item: function(item, escape) {
          return '<div class="item C">' +
            (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
            (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
          '</div>';
        },
        option: function(item, escape) {
          var label = item.name || item.email;
          var caption = item.name ? item.email : null;
          return '<div class="itemSel">' +
            '<span class="labelSel">' + escape(label) + '</span>' +
            (caption ? '<span class="captionSel">' + escape(caption) + '</span>' : '') +
          '</div>';
        }
      },
      createFilter: function(input) {
        var match, regex;

        // email@address.com
        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[0]);

        // name <email@address.com>
        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[2]);

        return false;
      },
      create: function(input) {
        if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
          return {email: input};
        }
        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
        if (match) {
          return {
            email : match[2],
            name  : $.trim(match[1])
          };
        }
        swal('Invalid email address.');
        return false;
      }
    });
  });
}

 Date.prototype.ddmmyyyy = function(exDay) {
	var dd  = (this.getDate() + exDay).toString();
	var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
	var yyyy = this.getFullYear().toString();
	return (dd[1]?dd:"0"+dd[0]) + "." + (mm[1]?mm:"0"+mm[0]) + "." + yyyy; // padding
};

/*
 * jQuery Highlight plugin
 *
 * Based on highlight v3 by Johann Burkard
 * http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html
 *
 * Code a little bit refactored and cleaned (in my humble opinion).
 * Most important changes:
 *  - has an option to highlight only entire words (wordsOnly - false by default),
 *  - has an option to be case sensitive (caseSensitive - false by default)
 *  - highlight element tag and class names can be specified in options
 *
 * Usage:
 *   // wrap every occurrance of text 'lorem' in content
 *   // with <span class='highlight'> (default options)
 *   $('#content').highlight('lorem');
 *
 *   // search for and highlight more terms at once
 *   // so you can save some time on traversing DOM
 *   $('#content').highlight(['lorem', 'ipsum']);
 *   $('#content').highlight('lorem ipsum');
 *
 *   // search only for entire word 'lorem'
 *   $('#content').highlight('lorem', { wordsOnly: true });
 *
 *   // don't ignore case during search of term 'lorem'
 *   $('#content').highlight('lorem', { caseSensitive: true });
 *
 *   // wrap every occurrance of term 'ipsum' in content
 *   // with <em class='important'>
 *   $('#content').highlight('ipsum', { element: 'em', className: 'important' });
 *
 *   // remove default highlight
 *   $('#content').unhighlight();
 *
 *   // remove custom highlight
 *   $('#content').unhighlight({ element: 'em', className: 'important' });
 *
 *
 * Copyright (c) 2009 Bartek Szopka
 *
 * Licensed under MIT license.
 *
 */

jQuery.extend({
    highlight: function (node, re, nodeName, className) {
        if (node.nodeType === 3) {
            var match = node.data.match(re);
            if (match) {
                var highlight = document.createElement(nodeName || 'span');
                highlight.className = className || 'highlight';
                var wordNode = node.splitText(match.index);
                wordNode.splitText(match[0].length);
                var wordClone = wordNode.cloneNode(true);
                highlight.appendChild(wordClone);
                wordNode.parentNode.replaceChild(highlight, wordNode);
                return 1; //skip added node in parent
            }
        } else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
                !/(script|style)/i.test(node.tagName) && // ignore script and style nodes
                !(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
            for (var i = 0; i < node.childNodes.length; i++) {
                i += jQuery.highlight(node.childNodes[i], re, nodeName, className);
            }
        }
        return 0;
    }
});

jQuery.fn.unhighlight = function (options) {
    var settings = { className: 'highlight', element: 'span' };
    jQuery.extend(settings, options);

    return this.find(settings.element + "." + settings.className).each(function () {
        var parent = this.parentNode;
        parent.replaceChild(this.firstChild, this);
        parent.normalize();
    }).end();
};

jQuery.fn.highlight = function (words, options) {
    var settings = { className: 'highlight', element: 'span', caseSensitive: false, wordsOnly: false };
    jQuery.extend(settings, options);
    
    if (words.constructor === String) {
        words = [words];
    }
    words = jQuery.grep(words, function(word, i){
      return word != '';
    });
    words = jQuery.map(words, function(word, i) {
      return word.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    });
    if (words.length == 0) { return this; };

    var flag = settings.caseSensitive ? "" : "i";
    var pattern = "(" + words.join("|") + ")";
    if (settings.wordsOnly) {
        pattern = "\\b" + pattern + "\\b";
    }
    var re = new RegExp(pattern, flag);
    
    return this.each(function () {
        jQuery.highlight(this, re, settings.element, settings.className);
    });
};

$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});

function formatDate(oDate) {
  return ("0" + oDate.getDate()).slice(-2) + "." + ("0" + (oDate.getMonth() + 1)).slice(-2) + "." + oDate.getFullYear();
}
