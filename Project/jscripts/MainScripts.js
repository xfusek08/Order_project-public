$(document).ready(function(){
  $('body').on('keyup', 'form input[required]:visible', function(){
    var form = $(this).parents('form:eq(0)');
    TryEnableSubmit(form);
  });
  $('.focusroll').focusin(function(){
    var form = $(this).parents('form:eq(0)');
    form.find('input[required]:visible:eq(0)').focus();
  });
  $('body').find('form').each(function(){
    TryEnableSubmit($(this));
  });  
  var textpre = '';
  
  $('body').on('keyup', 'input.telnumber', function(e){
    var text = $(this).val();
    var textfinal = '';
    var tojmp = 0;  
    var start = this.selectionStart;
    
    if (text.length <= textpre.length)
    {
      textpre = text;
      //this.setSelectionRange(start, start);
      return;
    }
        
    for(var i = 0; i < text.length; i++)
    {
      if (text[i] !== ' ')
        textfinal += text[i];
      else
        if (i <= start)
          start--;
    }
    
    text = textfinal;
    textfinal = '';
    
    if (text[0] === '+')
      tojmp = 4;
    else 
      tojmp = 3;
    
    for(var i = 0; i < text.length; i++)
    {
      textfinal += text[i];
      tojmp--;
      if (tojmp === 0)
      {
        textfinal += ' ';
        
        if (i < start)
          start++;
          
        tojmp = 3;
      }
    }
    
    $(this).val(textfinal);
    this.setSelectionRange(start, start);
    textpre = textfinal;
  });  
  
  $('body').on('focusout', 'input.telnumber', function(e){
    var text = $(this).val();
    $(this).val(text.trim());
  });
  
  $('body').on('click', '*[name="c_delete"]', function(e){
    e.preventDefault();
    var self = $(this);
    var form = self.parents('form:eq(0)');      
    RasiceComfirmForm(
      '<div class="warning"><img src="images/warning-iconELR.png"/><span>Upozornění</span></div>',
      'Opravdu si přejete vymazat záznam?', function(){
      var nextpk = 0;
      nextpk = parseInt($('.browser tbody tr').eq(parseInt($('.browser tbody tr.selected').index() + 1)).attr('pk'));
      form.append('<input type="hidden" name="nextpk" value="' + nextpk + '"/>');
      form.append('<input type="hidden" name="' + self.attr('name') + '" value="' + self.attr('value') + '"/>');
      form.submit();
    });
  });
  
  // obecne kliknuti nad prvky ve formuláři
  $('body').on('keydown', 'input:not(.datalist):not(.normalenter), select, textarea, button', function(e) {      
    var form = $(this).parents('form:eq(0)');
    
    if (e.keyCode == 13) 
    {
      if (!window.event.ctrlKey && $(this).attr('type') !== 'submit')
      {
        FocusNexInput($(this));
        return false;
      }
      form.find('*[name="c_submit"]').click();
    }
    else if (e.keyCode == 27)
      $(this).blur();
    else if (e.keyCode == 46 && window.event.ctrlKey)
      form.find('*[name="c_delete"]').click();
  });
  
  $('body').on('change', 'input[type="text"].uppercase', function(){
    $(this).val($(this).val().toUpperCase());
  });
  $('body').on('click', '.yearsummary .year .header', function(){
    $(this).parent('.year').find('.detail').slideToggle(250);
  });
  LoadYearSummary();
});
function FocusNexInput(input)
{
  var 
    form = input.parents('form:eq(0)'), 
    focusable,
    next;

    focusable = $('body').find('input,a,select,button,textarea').filter(':visible');
    next = focusable.eq(focusable.index(input)+1);
    if (next.length)
      next.focus();
}
function TryEnableSubmit(form)
{
  var v_bEnabled = true;
  form.find('input[required]:visible').each(function(){
    if ($(this).val().length === 0)
      v_bEnabled = false;
    else if ($(this).attr('name') === 'c_raal' && $(this).val().length < 3)
      v_bEnabled = false;
  });

  if (v_bEnabled)
    EnableSubmit(form);
  else
    DisableSubmit(form);

  return v_bEnabled;
}
function DisableSubmit(form)
{
  form.find('*[type="submit"]').attr({'disabled': 'disabled'});
}
function EnableSubmit(form)
{
  form.find('*[type="submit"]').removeAttr('disabled');
}

function LoadYearSummary()
{
  SendAjaxRequest(
    'type=getYearSummary', 
    true, 
    function(response){  
      var summary = $('.yearsummary > .scrolltable');
      var openyearnums = [];
      summary.find('.year').each(function(){
        if ($(this).find('.detail').is(":visible"))
          openyearnums.push($(this).attr('yearnum'));
      });
      summary.empty();
      $(response).find('year').each(function(){        
        var html = 
          '<div class="year" yearnum="' + $(this).attr('yearnum') + '">' +
            '<div class="header">'+
              '<table>'+
                '<td class="yearnum">' + $(this).attr('yearnum') + '</th>'+
                '<td class="count">' + $(this).attr('count') + '</th>'+
                '<td class="profit">' + $(this).attr('profit') + '</th>'+
              '</table>'+
            '</div>'+
            '<div class="detail">'+
              '<table>';              
        var months = ['Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čvn', 'Čvc', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'];
        $(this).find('month').each(function(){        
          html += '<tr>';
          html += '<td>' + months[$(this).attr('monthnum') - 1] + '</td>';
          html += '<td>' + $(this).attr('count') + '</td>';
          html += '<td class="profit">' + $(this).attr('profit') + '</td>';
          html += '</tr>';
        });
        html += 
              '</table>'+
            '</div>'+
          '</div>';
        var v_oObj = $(html);
        if (openyearnums.indexOf($(this).attr('yearnum')) == -1)
          v_oObj.find('.detail').hide();
        summary.append(v_oObj);        
      });
    }
  );
}
