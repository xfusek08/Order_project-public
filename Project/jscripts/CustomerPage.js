
$(document).ready(function (){  
  $('.shadow').parent('div').scroll(function(){
    $(this).children('.shadow').css({top: $(this).scrollTop()});  
  });
  $('.custlist .conn').on('click', '.customer:not(.selected)', function(){
    location.href = '?cust=' + $(this).attr('pk');
  });
  $('body').on('click', '.browser tbody tr:not(.selected)', function(){
    ChangeCustAddr($(this).attr('pk'), true);
  });
  $('body').on('click', '.newcustomer:not(.selected)', function(){
    location.href='?ClearC';
  });
  $('body').on('click', '.newbt', function(){    
    location.href='?ClearA';
  });
  OnBrowseSelectCallBack = function (){
    $('.storagesconn input[name="orcadr_firma"]').focus();
    TryEnableSubmit($('.storagesconn form'));
  };
  OnBrowseRowChangeCallBack = function (index){
    ChangeCustAddr($('.browser tbody tr').eq(index).attr('pk'), true);
  };    
  
  $('input.colorinput').colorpicker({
    history: false,
    strings: 'Vyberte barvu,Standardní barvy,Další barvy,Zpět,dal3,dal4,dal5'
  });
});
function ChangeCustAddr(pk, ajax)
{
  var data = 'caddr=' + pk + '&brscroll=' + $('.browserconn').scrollTop();
  if (ajax)
  {
    SendAjaxRequest(
      'type=custaddrsel&' + data,
      true,
      function (response){
        SetCusrtAddrXML(response);
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
function SetCusrtAddrXML(xml)
{
  var form = $('.storagesconn form');
  
  form.find('input[name="orcadr_firma"]').val($(xml).find('orcadr_firma').text());
  form.find('input[name="orcadr_firma2"]').val($(xml).find('orcadr_firma2').text());

  form.find('input[name="orcadr_stat"]').val($(xml).find('orcadr_stat').text());
  form.find('input[name="orcadr_ulice"]').val($(xml).find('orcadr_ulice').text());
  form.find('input[name="orcadr_psc"]').val($(xml).find('orcadr_psc').text());
  form.find('input[name="orcadr_mesto"]').val($(xml).find('orcadr_mesto').text());
  form.find('input[name="orcadr_telnumber"]').val($(xml).find('orcadr_telnumber').text());
  form.find('input[name="orcadr_cas3"]').val($(xml).find('orcadr_cas3').text());
  form.find('input[name="orcadr_pozn1"]').val($(xml).find('orcadr_pozn1').text());
  form.find('input[name="orcadr_pozn2"]').val($(xml).find('orcadr_pozn2').text());

  $('.stattabl .naklnum').text($(xml).find('orcadr_naklnum').text());
  $('.stattabl .vyklnum').text($(xml).find('orcadr_vyklnum').text());
  $('.stattabl .totalnum').text(
    parseInt($(xml).find('orcadr_naklnum').text()) + 
    parseInt($(xml).find('orcadr_vyklnum').text())
  );
  
  SelectRow($(xml).find('pk').text());  
  TryEnableSubmit(form);
}