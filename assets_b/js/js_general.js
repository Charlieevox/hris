yii.confirm = function (message, ok, cancel) {
    bootbox.setDefaults("locale", 'id');
    bootbox.confirm(
        {
            message: message,
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}

function init() {
    $('select').each(function (e) {
        $(this).select2({
            theme: 'krajee',
            width: '100%'
        })
    });

    $('form').on('beforeSubmit', function () {
        $(this).find(':submit').prop('disabled', true);
    });

    //Handle WindowDialogBrowse TextBox to emulate button click on TextBox click.
    var input_selector = '.input-group:has(.input-group-btn):has(.WindowDialogBrowse) input';
    $(input_selector).css('cursor', 'pointer');
    $(document).on('keypress click', input_selector, function (e) {
        e.preventDefault();
        $(this).siblings('.input-group-btn').find('a.WindowDialogBrowse').trigger('click');
    });
    $('table tr:has(.WindowDialogSelect)').css('cursor', 'pointer');
}

$(document).on('pjax:complete', function () {
    init();
});
$(document).ready(function () {
    
    init();
    var input = $('form').find('input[type=text],textarea,select').filter(':enabled:visible:first');
    var tagName = input.prop("tagName");
    if (tagName == "SELECT") {
        $(input).next(".select2").find(".select2-selection").focus();
    } else {
        $(input).focus();
    }
	
    $('input[type=text]').val (function () {
    return this.value.toLocaleUpperCase();
	})
	

});

/////////////////////START HANDLE BROWSE BUTTON & DIALOG WINDOW\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

$(document).on('click', '.WindowDialogBrowse', function (e) {
    if ($(this).is('[disabled]')) {
        return false;
    }
    e.preventDefault();
	var filterInput = $(this).attr('data-filter-Input');
	var targetValueField = $(this).attr('data-target-value');
	var targetTextField = $(this).attr('data-target-text');
	var targetWidth = $(this).attr('data-target-width');
	var targetHeight = $(this).attr('data-target-height');
	var filter = $(filterInput).val();
	if (filter == undefined || filter == '' ) {
		filter = -1;
	}
	
	var browseUrl = $(this).attr('href') + "?filter=" + filter;

    if (targetWidth == undefined) {
        targetWidth = 800;
    }

    if (targetHeight == undefined) {
        targetHeight = 600;
    }
	
    $(this).focus();
    OpenPopupWindow(browseUrl, targetWidth, targetHeight, targetValueField, targetTextField);
});

$(document).on('click', '.WindowDialogSelect', function (e) {
    e.preventDefault();
    var returnValue = $(this).attr('data-return-value');
    var returnText = $(this).attr('data-return-text');
    ClosePopupWindow(returnValue, returnText);
});

$(document).on('click', 'tr:has(.WindowDialogSelect)', function (e) {
    e.preventDefault();
    var returnValue = $(this).find('.WindowDialogSelect').attr('data-return-value');
    var returnText = $(this).find('.WindowDialogSelect').attr('data-return-text');
    ClosePopupWindow(returnValue, returnText);
});

var popup;
function OpenPopupWindow(url, width, height, targetValueField, targetTextField) {
    popup = window.open(url, 'popUpWindow', "width=" + width + ",height=" + height + ",scrollbars=1,left=170,top=25");
    popup.focus();
    popup.valueField = targetValueField;
    popup.textField = targetTextField;
}

function ClosePopupWindow(value, text) {
    if (window.opener != null && !window.opener.closed) {
        var valueFieldID = window.valueField;
        var textFieldID = window.textField;
        window.opener.$(valueFieldID).val(value).trigger("change");

        text = JSON.parse(text);
        var i = 0;
        text.forEach(function (entry) {
            var id;
            if (i == 0) {
                id = textFieldID + ", " + textFieldID + "-" + i.toString();
            } else {
                id = textFieldID + "-" + i.toString();
            }
            window.opener.$(id).val(entry).trigger("change");
            i += 1;
        });
    }
    window.close();
}
/////UNTUK KEBUTUHAN SEMUA INPUT MENJADI UPPER/////
$(":input").attr("style", "text-transform: uppercase;");


$(':input').focus(function() {
        this.value = this.value.toLocaleUpperCase();
});

$(':input').focusout(function() {
        // Uppercase-ize contents
	this.value = this.value.toLocaleUpperCase();
});



/////////////////////END HANDLE BROWSE BUTTON & DIALOG WINDOW\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\     

