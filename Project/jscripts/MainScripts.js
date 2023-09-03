
$(document).ready(function () {
    
    $('body').on('keyup', 'form input[required]:visible', function () {
        var form = $(this).parents('form:eq(0)');
        TryEnableSubmit(form);
    });
    
    $('.focusroll').focusin(function () {
        var form = $(this).parents('form:eq(0)');
        form.find('input[required]:visible:eq(0)').focus();
    });
    
    $('body').find('form').each(function () {
        TryEnableSubmit($(this));
    });
    
    var textPrev = '';
    
    $('body').on('keyup', 'input.telnumber', function (e) {
        var text = $(this).val();
        var textFinal = '';
        var jumpTo = 0;
        var start = this.selectionStart;
        
        if (text.length <= textPrev.length) {
            textPrev = text;
            //this.setSelectionRange(start, start);
            return;
        }
        
        for (var i = 0; i < text.length; i++) {
            if (text[i] !== ' ') {
                textFinal += text[i];
            } else if (i <= start) {
                start--;
            }
        }
        
        text = textFinal;
        textFinal = '';
        
        if (text[0] === '+') {
            jumpTo = 4;
        } else {
            jumpTo = 3;
        }
        
        for (var i = 0; i < text.length; i++) {
            textFinal += text[i];
            jumpTo--;
            if (jumpTo === 0) {
                textFinal += ' ';
                jumpTo = 3;
                if (i < start) {
                    start++;
                }
            }
        }
        
        $(this).val(textFinal);
        this.setSelectionRange(start, start);
        textPrev = textFinal;
    });
    
    $('body').on('focusout', 'input.telnumber', function (e) {
        var text = $(this).val();
        $(this).val(text.trim());
    });
    
    $('body').on('click', '*[name="c_delete"]', function (e) {
        e.preventDefault();
        var self = $(this);
        var form = self.parents('form:eq(0)');
        RasiceConfirmForm(
            '<div class="warning"><img src="images/warning-iconELR.png"/><span>Upozornění</span></div>',
            'Opravdu si přejete vymazat záznam?', function () {
                var nextpk = 0;
                nextpk = parseInt($('.browser tbody tr').eq(parseInt($('.browser tbody tr.selected').index() + 1)).attr('pk'));
                form.append('<input type="hidden" name="nextpk" value="' + nextpk + '"/>');
                form.append('<input type="hidden" name="' + self.attr('name') + '" value="' + self.attr('value') + '"/>');
                form.submit();
            });
    });
    
    // obecne kliknuti nad prvky ve formuláři
    $('body').on('keydown', 'input:not(.datalist):not(.normalenter), select, textarea, button', function (e) {
        var form = $(this).parents('form:eq(0)');
        
        if (e.keyCode == 13) {
            if (!window.event.ctrlKey && $(this).attr('type') !== 'submit') {
                FocusNexInput($(this));
                return false;
            }
            form.find('*[name="c_submit"]').click();
        } else if (e.keyCode == 27) {
            $(this).blur();
        } else if (e.keyCode == 46 && window.event.ctrlKey) {
            form.find('*[name="c_delete"]').click();
        }
    });
    
    $('body').on('change', 'input[type="text"].uppercase', function () {
        $(this).val($(this).val().toUpperCase());
    });
    
    $('body').on('click', '.yearsummary .year .header', function () {
        $(this).parent('.year').find('.detail').slideToggle(250);
    });
    
    LoadYearSummary();
});

function FocusNexInput(input) {
    var focusable = $('body').find('input,a,select,button,textarea').filter(':visible');
    var next = focusable.eq(focusable.index(input) + 1);
    if (next.length) {
        next.focus();
    }
}

function TryEnableSubmit(form) {
    var enabled = true;
    
    form.find('input[required]:visible').each(function () {
        if ($(this).val().length === 0) {
            enabled = false;
        } else if ($(this).attr('name') === 'c_raal' && $(this).val().length < 3) {
            enabled = false;
        }
    });
    
    if (enabled) {
        EnableSubmit(form);
    } else {
        DisableSubmit(form);
    }
    
    return enabled;
}

function DisableSubmit(form) {
    form.find('*[type="submit"]').attr({ 'disabled': 'disabled' });
}

function EnableSubmit(form) {
    form.find('*[type="submit"]').removeAttr('disabled');
}

function LoadYearSummary() {
    SendAjaxRequest('type=getYearSummary', true, function (response) {
        var summary = $('.yearsummary > .scrolltable');
        var openYearNumbers = [];
        
        summary.find('.year').each(function () {
            if ($(this).find('.detail').is(":visible")) {
                openYearNumbers.push($(this).attr('yearnum'));
            }
        });
        
        summary.empty();
        
        $(response).find('year').each(function () {
            var html =
                '<div class="year" yearnum="' + $(this).attr('yearnum') + '">' +
                '<div class="header">' +
                '<table>' +
                '<td class="yearnum">' + $(this).attr('yearnum') + '</th>' +
                '<td class="count">' + $(this).attr('count') + '</th>' +
                '<td class="profit">' + $(this).attr('profit') + '</th>' +
                '</table>' +
                '</div>' +
                '<div class="detail">' +
                '<table>';
            
            var months = ['Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čvn', 'Čvc', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'];
            
            $(this).find('month').each(function () {
                html += '<tr>';
                html += '<td>' + months[$(this).attr('monthnum') - 1] + '</td>';
                html += '<td>' + $(this).attr('count') + '</td>';
                html += '<td class="profit">' + $(this).attr('profit') + '</td>';
                html += '</tr>';
            });
            
            html +=
                '</table>' +
                '</div>' +
                '</div>';
                
            var v_oObj = $(html);
            if (openYearNumbers.indexOf($(this).attr('yearnum')) == -1) {
                v_oObj.find('.detail').hide();
            }
            summary.append(v_oObj);
        });
    });
}
