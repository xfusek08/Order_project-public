
$(document).ready(function (){
  BrowserDataLoadAction = function (){
    $('.browser tbody tr').each(function(){
      var row = $(this);
      if (row.children('td').eq(7).text() != '')
        row.addClass('blocked');
    });
  };
  OnBrowseSelectCallBack = function (){
    $('.dbpageform input[name="ordlv_raal"]').focus();
    TryEnableSubmit($('.dbpageform > form'));
  };
  OnBrowseRowChangeCallBack = function (index){
    ChangeDopr($('.browser tbody tr').eq(index).attr('pk'), true);
  };    
  BrowserDataLoadAction();
  
  // eventy 
  $('body').on('click', '.browser tbody tr:not(.selected)', function(){
    ChangeDopr($(this).attr('pk'), true);
  });
  
  $('body').on('keyup', '.dbpageform input[name="ordlv_raal"]',
    function(){
      if ($(this).val().length == 3)
      {
        SendAjaxRequest(
          'type=raalins&' +
          'raal=' + $(this).val().toUpperCase(),
          true,
          function(response){
            if ($(response).find('or_deliverer').length)
              SetDelivererXML(response);
          }
        );
      }
      else if ($(this).val().length < 3)
        SelectRow('0');  
    }
  );  
  $('body').on('click', '.newbt', function(){    
    location.href='?Clear';
  });
});
function ChangeDopr(pk, ajax)
{
  var data = 'dopr=' + pk + '&brscroll=' + $('.browserconn').scrollTop();
  if (ajax)
  {
    SendAjaxRequest(
      'type=dorpsel&' + data,
      true,
      function (response){
        SetDelivererXML(response);
        window.history.replaceState({}, null, '?');
        
        SendAjaxRequest(
          'type=brscroll&'+
          'scroll=' + $('.browserconn').scrollTop(),
          true,
          function(response){
            // ok              
          }
        );
      }
    );
  }
  else
  {
    location.href = '?' + data;
  }
}
function SetDelivererXML(xml)
{
  $('.dbpageform input[name="ordlv_raal"]').val($(xml).find('ordlv_raal').text());
  $('.dbpageform input[name="ordlv_firma"]').val($(xml).find('ordlv_firma').text());
  $('.dbpageform input[name="ordlv_dic"]').val($(xml).find('ordlv_dic').text());
  $('.dbpageform input[name="ordlv_ic"]').val($(xml).find('ordlv_ic').text());
  $('.dbpageform input[name="ordlv_jmeno"]').val($(xml).find('ordlv_jmeno').text());
  $('.dbpageform input[name="ordlv_email"]').val($(xml).find('ordlv_email').text());
  $('.dbpageform input[name="ordlv_telnum"]').val($(xml).find('ordlv_telnum').text());
  $('.dbpageform input[name="ordlv_stat"]').val($(xml).find('ordlv_stat').text());
  $('.dbpageform input[name="ordlv_psc"]').val($(xml).find('ordlv_psc').text());
  $('.dbpageform input[name="ordlv_mesto"]').val($(xml).find('ordlv_mesto').text());
  $('.dbpageform input[name="ordlv_ulice"]').val($(xml).find('ordlv_ulice').text());
  $('.dbpageform input[name="ordlv_spz"]').val($(xml).find('ordlv_spz').text());
  $('.dbpageform input[name="ordlv_pozn"]').val($(xml).find('ordlv_pozn').text());
  $('.dbpageform input[name="ordlv_blokace"]').val($(xml).find('ordlv_blokace').text());
  $('.stattabl .objnum').text($(xml).find('ordlv_objnum').text());
  $('.stattabl .obratp').text($(xml).find('ordlv_obrat').text() + ' kč');
  $('.stattabl .zisk').text($(xml).find('ordlv_zisk').text() + ' kč');
  $('.stattabl .koeficient').text($(xml).find('ordlv_koeficient').text());

  SelectRow($(xml).find('pk').text());  
  TryEnableSubmit($('.dbpageform > form'));
}
