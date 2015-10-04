var Site = (function($, window, undefined) {
	var win = $(window),
			dataSetHeightSel = '[data-set-height]',
			dataSetHeight = 'set-height',
			centerModalSel = '.custom-modal',
			modalSel = '.modal',
			modalDialogSel = '.modal-dialog',
			youtubeModalSel = '#youtube-modal',
            mainImgSel = '.main-img',
			Events = {
				RESIZE: 'resize.setheight',
				SHOWN_MODAL: 'shown.bs.modal',
				HIDE_MODAL: 'hide.bs.modal'
			},
	init = function(){
		setHeight();
		win.off(Events.RESIZE).on(Events.RESIZE, function() {
			setHeight();
		}).trigger(Events.RESIZE);
		centerModal();
		$(youtubeModalSel).on(Events.HIDE_MODAL, function() {
			toggleVideo(this);
		});

	},
	setHeight = function() {
		var max = 0,
				childSel = $(dataSetHeightSel).data(dataSetHeight);
		$(dataSetHeightSel).find(childSel).css('height', 'auto').each(function() {
			h = $(this).outerHeight();
			max = Math.max(max, h);
		});
		$(dataSetHeightSel).find(childSel).css('height', max);
        $(dataSetHeightSel).find(mainImgSel).css('height', max);
	},
	centerModal = function() {
		$(centerModalSel).on(Events.SHOWN_MODAL, function() {
			var modalDialogEl = $(this).find(modalDialogSel),
					h = modalDialogEl.height(),
					w = modalDialogEl.width();
			modalDialogEl.css({
				'margin-top': - h/3,
				'margin-left': - w/2
			});
		}).on(Events.HIDE_MODAL, function() {

		});
	},
	toggleVideo = function(that) {
		var el = $(that);
		if(!el.find('iframe').length) {
			return;
		}
    el.find('iframe')[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
  }
	return {
		init: init
	}
})(jQuery, window);

jQuery(function() {
    Site.init();



});

jQuery(document).ready(function($) {
    var $loading = $('.loading').hide();
//    $(document)
//      .ajaxStart(function () {
//        $loading.show();
//      })
//      .ajaxStop(function () {
//        $loading.hide();
//      });
//
//    $('#register-modal').on('show.bs.modal', function (e) {
//        var $messageContainer = $(this).find('.messages');
//        var $form = $(this).find('#mc-embedded-subscribe-form');
//        $messageContainer.empty();
//        $form[0].reset();
//        $('.loading').hide();
//    });
//
//// bind submit handler to form
//    $('#mc-embedded-subscribe-form').on('submit', function(e) {
//        var $form = $(this);
//        var $submitButton = $form.find('button');
//        var $messageContainer = $form.find('.messages');
//        // prevent native submit
//        e.preventDefault();
//        // submit the form
//        $form.ajaxSubmit({
//            type: 'post',
//            dataType: 'json',
//            beforeSubmit: function() {
//                // disable submit button
//                $submitButton.attr('disabled','disabled');
//                // add spinner icon
//                $submitButton.find('i').removeClass().addClass('fa fa-circle-o-notch fa-spin');
//            },
//            success: function(response, status, xhr, form) {
//                if(response.status === 'ok') {
//                    // mail sent ok - display sent message
//                    for(var msg in response.messages) {
//                        showInputMessage(response.messages[msg], 'success', $messageContainer);
//                    }
//                    // clear the form
//                    form[0].reset();
//                }
//                else {
//                    for(var error in response.messages) {
//                        showInputMessage(response.messages[error], 'danger', $messageContainer);
//                    }
//                }
//                // make button active
//                $submitButton.removeAttr('disabled');
//            },
//            error: function(response) {
//                for(var error in response.messages) {
//                    showInputMessage(response.messages[error], 'warning', $messageContainer);
//                }
//                // make button active
//                $submitButton.removeAttr('disabled');
//            }
//        });
//        return false;
//    });

    function showInputMessage(message, status, messageContainer) {
        messageContainer.empty();
        messageContainer.append('<span class="element-top-10 text-' + status + '">' + message.message + '</span>');
    }


    // Read more button
    // Configure/customize these variables.
	
    var showChar = 200;  // How many characters are shown by default
    var ellipsestext = "...";
	
	if (document.location.pathname.match(/[^\/]+$/) == null) {
		var moretext = "Xem thêm";
        var lesstext = "Thu lại";
	} else {
		currentFileName = document.location.pathname.match(/[^\/]+$/)[0];
		if (currentFileName == 'eng-index.html') {
			var moretext = "Show more";
			var lesstext = "Show less";
		}
		
		if (currentFileName == 'vn-index.html') {
			var moretext = "Xem thêm";
			var lesstext = "Thu lại";
		}
	}

    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});