
var announcementManager;

$(document).ready(function () {
    $.datepicker.regional["cs"] = {
        closeText: 'Cerrar',
        prevText: '<',
        nextText: '>',
        currentText: 'Hoy',
        monthNames: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
        monthNamesShort: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
        dayNames: ['Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'],
        dayNamesShort: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So',],
        dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'],
        weekHeader: 'Sm',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: true,
        showMonthAfterYear: false,
        yearSuffix: '',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false
    };
    
    $.datepicker.setDefaults($.datepicker.regional["cs"]);
    announcementManager = new AnnouncementManager();
    CheckForAlertXML('body');
});

function DateToStr(date) {
    var d = date.getDate().toString();
    var m = (date.getMonth() + 1).toString();
    if (d.length == 1) {
        d = '0' + d;
    }
    if (m.length == 1) {
        m = '0' + m;
    }
    return d + '.' + m + '.' + date.getFullYear();
}

function StrToDate(str) {
    var arr = str.split(".");
    
    if (arr.length !== 3) {
        return false;
    }
    
    var day = parseInt(arr[0]);
    var month = parseInt(arr[1]);
    var year = parseInt(arr[2]);
    
    if (day === false || month === false || year === false) {
        return false;
    }
    
    var date = new Date(arr[2], arr[1] - 1, arr[0]);
    var dateCheck = DateToStr(date);
    var arrCheck = dateCheck.split(".");
    
    if (arr[0] !== arrCheck[0]
        || arr[1] !== arrCheck[1]
        || arr[2] !== arrCheck[2]
    ) {
        return false
    }
    return date;
}

function StrToFloat(a_sStr) {
    a_sStr = a_sStr.replace(',', '.');
    a_sStr = a_sStr.replace(' ', '');
    return parseFloat(a_sStr);
}

function SendAjaxRequest(data, isAsync, callback) {
    StartLoading();
    $.ajax({
        url: location.protocol + '//' + location.host + location.pathname,
        type: "POST",
        async: isAsync,
        data: "ajax=true&" + data,
        success: function (html) {
            CheckForAlertXML(html);
            callback(html);
            StopLoading();
        }
    });
}

function SubmitForm(type, form, ProcFnc) {
    SendAjaxRequest(
        "type=" + type +
        "&" + form.serialize(),
        true,
        ProcFnc
    );
}

function OnClickAjaxSubmit(event, type, button, ProcFnc) {
    event.preventDefault();
    
    var form = button.closest("form");
    var tempElement = $("<input type='hidden'/>");
    
    tempElement
        .attr("name", button.attr('name'))
        .val(button.val())
        .appendTo(form);
    
    SubmitForm(type, form, ProcFnc);
}

// je liche ?
function isOdd(num) {
    return num % 2;
}

var loadingTimeout;
var loadingCounter = 0;
function StartLoading() {
    
    if (loadingCounter == 0) {
        $('<div class="loading"><img src="images/ajax-loader.gif " /></div>').appendTo('.right > div > .cap');
    }
    loadingCounter++;
}

function StopLoading() {
    if (loadingCounter == 1) {
        $('.loading').remove();
    }
    loadingCounter--;
}

$.fn.changeVal = function (v) {
    return $(this).val(v).trigger("change");
};

function HighlightInvalidInput(name, mgs, searching) {
    if (searching == null) searching = $('body');
    var input = searching.find('input[name="' + name + '"]');
    input.attr('title', mgs);
    var prevColor = input.css('backgroundColor');
    
    input.animate({ 'backgroundColor': "rgb(255,150,150)", color: "white" }, 600, function () {
        input.focusin(function () {
            UnHighlightInput(input, prevColor);
        });
        input.change(function () {
            UnHighlightInput(input, prevColor);
        });
    });
}

function UnHighlightInput(input, prevColor) {
    input.animate({ 'backgroundColor': prevColor, color: "black" }, 600, function () {
        input.removeAttr('title');
    });
}

function CheckForAlertXML(code) {
    $(code).find('alert').each(function () {
        announcementManager.AddAnnouncement(
            $(this).find('color').text(),
            $(this).find('message').text(),
        );
    });
}

// vcelku univerzalni
function RasiceConfirmForm(caption, text, CallBack, a_aButtons) {
    var HTML = '';
    HTML += '<div class="floatform">';
    HTML += '<div>';
    HTML += '<div class="caption">' + caption + '</div>';
    HTML += '<div class="text">' + text + '</div>';
    HTML += '<div class="buttonline">';
    if (a_aButtons) {
        for (var i = 0; i < a_aButtons.length; i++) {
            HTML +=
                '<button value="' + ((a_aButtons[i].submit === true) ? 'ok' : 'storno') + '">' +
                a_aButtons[i].message +
                '</button>';
        }
    } else {
        HTML += '<button value="ok">Ok</button>';
        HTML += '<button value="storno">Storno</button>';
    }
    HTML += '</div>';
    HTML += '</div>';
    HTML += '</div>';
    HTML += '</div>';
    
    $(HTML).appendTo('body');
    var obj = $('.floatform > div');
    CenterFloatForm(obj);
    obj.find('button[value="storno"]').focus();
    obj.on('click', 'button', function () {
        if ($(this).attr('value') === 'ok'
            && typeof CallBack === 'function'
        ) {
            CallBack();
        }
        obj.parent('.floatform').remove();
    });
    
    obj.keydown(function (e) {
        e.stopPropagation();
        if (e.keyCode === 39 && obj.find('button[value="ok"]').is(':focus')) { // right arrow
            obj.find('button[value="storno"]').focus();
        } else if (e.keyCode === 37 && obj.find('button[value="storno"]').is(':focus')) { // left arrow
            obj.find('button[value="ok"]').focus();
        } else if (e.keyCode === 13) {
            obj.find('button:focus').click();
        } else if (e.keyCode === 27) {
            obj.find('button[value="storno"]').click();
        }
    });
}

function CenterFloatForm(inForm) {
    var h = $(window).height() / 2 - inForm.outerHeight() / 2;
    if (h < 10) {
        h = 10;
    }
    var w = $(window).width() / 2 - inForm.outerWidth() / 2;
    if (w < 10) {
        w = 10;
    }
    inForm.css({
        marginLeft: w,
        marginTop: h
    });
}

function AnnouncementManager() {
    this.Announcements = [];
    this.ToRaiseCounter = 0;
    this.ClearingInterval;
    this.ClearingTimeout;
    
    this.AddAnnouncement = function (a_sColor, a_sText) {
        this.Announcements.push(new Announcement(a_sColor, a_sText));
        var elem = this.Announcements[this.Announcements.length - 1].element;
        elem.appendTo('body');
        this.ToRaiseCounter++;
        var me = this;
        var order = this.Announcements.length;
        setTimeout(
            function () {
                elem.animate(
                    { top: '-=' + (elem.outerHeight() * order) + 'px' },
                    150,
                    "swing",
                    me.startClear(),
                );
                me.ToRaiseCounter--;
            },
            me.ToRaiseCounter * 200
        );
    };
    
    this.startClear = function () {
        clearInterval(this.ClearingInterval);
        clearTimeout(this.ClearingTimeout);
        var me = this;
        this.ClearingTimeout = setTimeout(function () {
            me.ClearingInterval = setInterval(function () {
                for (var i = me.Announcements.length - 1; i >= 0; i--) {
                    var element = me.Announcements[i].element;
                    element.animate(
                        { top: '+=' + element.outerHeight() + 'px' },
                        250,
                        "swing",
                        function () {
                            if ($(this).offset().top >= $(window).outerHeight()) {
                                element.remove();
                                me.Announcements.shift();
                                if (me.Announcements.length == 0) {
                                    clearInterval(me.ClearingInterval);
                                }
                            }
                        },
                    );
                }
            }, 1500);
        }, 3000);
    };
}

function Announcement(a_sColor, a_sText, a_iIndex) {
    var color = "rgba(255,255,255,0.85)";
    var textColor = 'black';
    switch (a_sColor.toLowerCase()) {
        case "red":
            color = "rgba(255,100,100,0.85)";
            textColor = 'white';
            break;
        case "green":
            color = "rgba(100,255,100, 0.85)";
            break;
        case "white": break; // nic
        default:             // chyba
            // console.log("wrong paramter AnnouncementColor");
            break;
    }
    this.element = $('<div class="announcement">' + a_sText + '</div>');
    this.element.css({
        top: $(window).outerHeight() + 'px',
        width: $(window).outerWidth() + 'px',
        background: color,
        color: textColor
    });
    this.index = a_iIndex;
}
