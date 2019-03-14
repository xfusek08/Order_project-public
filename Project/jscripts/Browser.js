var TimeoutBuffer;
var BrowserDataLoadAction;
var OnBrowseSelectCallBack;
var OnBrowseRowChangeCallBack;
var v_bAllLoaded = false

$(document).ready(function (){
  if ($('.browser').length > 0)
  {
    SetTimeoutArray();
    InitBrowser($('.browser'));    
    $(window).resize(function(){
      InitBrowser($('.browser'));    
    });
    CheckScreenLoaded();
    $('body').on('keyup', '.browser .headcol .filter input[name="textfilter"]', function(){
      var HeadcolIdent = $(this).closest('.headcol').attr('ident');
      SendFilterRequest(HeadcolIdent, 'text', 'text=' + $(this).val());
    });
    $('body').on('keyup', '.browser .headcol .filter input[name="numfilterval"]', function(){
      SendNumberFilterReq($(this));
    });
    $('body').on('change', '.browser .headcol .filter .operselect', function(){
      SendNumberFilterReq($(this).closest('.headcol').find('input[name="numfilterval"]'));
    });
    $('body').on('click', '.browser .headcol .filter .ordrbt', function(){
      SendOrderFilter($(this), window.event.ctrlKey);  
    });
    $('body').on('click', '.browser .headcol .filter .datefilter', function(){
      OpenDateFilter($(this));  
    });
    $('body').on('click', '.toolbar .clearfiletrs', function(){
      ClearFiltersRequest();  
    });
    
    $('body').keydown(function (e){
      if (!$('form *:focus').length && !$('headcol *:focus').length && $('.browser .selected').length && !$('.a4floatform').length)
      {
        var index = parseInt($('.browser .selected').index());
        if (e.keyCode == 38) // up arrow
        {
          e.preventDefault();
          index--;
          if (index >= 0)
          {
            if (typeof OnBrowseRowChangeCallBack === 'function')
              OnBrowseRowChangeCallBack(index);
          }
        }
        else if (e.keyCode == 40) // down arrow
        {
          e.preventDefault();
          index++;
          if ((index) < $('.browser table tr').length)
          {
            if (typeof OnBrowseRowChangeCallBack === 'function')
              OnBrowseRowChangeCallBack(index);
          }
        }      
        else if (e.keyCode == 13 || e.keyCode == 32) // Enter OR Space
        {
          e.preventDefault();
          if (typeof OnBrowseSelectCallBack === 'function')
            OnBrowseSelectCallBack();
        }      
      }
    });
  }
  AddSumarryToBrowser($('.browser'));
});

function InitBrowser(OrderBrowser)
{
  var nodata = OrderBrowser.find('.nodata').clone();
  OrderBrowser.find('.nodata').remove();
  var newtabhead = OrderBrowser.find('> div:eq(0)');
  var newtabbody = OrderBrowser.find('> div:eq(1)');
  if (newtabhead.length === 0 || newtabbody.length === 0)
  {
    var table = OrderBrowser.find('> table');
    newtabhead = $('<div><table></table></div>');
    newtabbody = $('<div><table></table></div>');
    newtabhead.find('table').append(table.find('thead').clone());
    newtabbody.find('table').append(table.find('tbody').clone());
    newtabhead.css({
      overflow: 'hidden'
    });
    newtabhead.insertBefore(table);
    newtabbody.css({
      height: OrderBrowser.outerHeight() - newtabhead.outerHeight(),
      overflow: 'auto'
    });
    newtabbody.insertBefore(table);
    table.remove();    
    newtabbody.scroll(function (){
      newtabhead.scrollLeft($(this).scrollLeft());
      if (newtabbody.scrollTop() >= (newtabbody.prop('scrollHeight') - newtabbody.height()))
        RequsetNextData(50, newtabbody.find('tr').size());            
    });
  }
  else
  {
    newtabhead.find('th').each(function (){
      $(this).css('maxWidth', '');
      $(this).css('minWidth', '');
    });
    newtabbody.find('tbody tr:eq(0) td').each(function (){
      $(this).css('maxWidth', '');
      $(this).css('minWidth', '');
    });
  }
  newtabhead.find('.spacing').remove();
  newtabhead.find('thead').clone().insertBefore(newtabbody.find('tbody'));    
  var cols = newtabbody.find('tbody tr:eq(0) td').size();
  var sizes = new Array();
  for (var i = 0; i < cols; i++)
    sizes.push(newtabbody.find('tbody tr:eq(0) td').eq(i).outerWidth() - 1);
  newtabbody.find('thead').remove();
  
  if (nodata.length > 0)
    nodata.appendTo(newtabbody);
  
  resizeBrowser(newtabhead, newtabbody, sizes);
  
  var width = OrderBrowser.outerWidth();
  newtabbody.css({maxWidth: width + 'px'});
  newtabhead.css({maxWidth: width + 'px'});
  
  newtabhead.find('tr:eq(0)').append('<th class="spacing" style="min-width: 15px; max-width: 15px"></th>');   
  
  if (OrderBrowser.attr('scrolltop'))
    newtabbody.scrollTop(OrderBrowser.attr('scrolltop'));
  if (OrderBrowser.attr('scrollleft'))
  {
    newtabbody.scrollLeft(OrderBrowser.attr('scrollleft'));
    newtabbody.scrollLeft(OrderBrowser.attr('scrollleft'));
  }
}
function resizeBrowser(newtabhead, newtabbody, sizes)
{
  newtabbody.height($('.browser').outerHeight() - newtabhead.outerHeight());
  for (var i = 0; i < sizes.length; i++)
  {
    var offset = 0;
    if (i == 0) offset = 10;
    newtabhead.find('th').eq(i).css({
      maxWidth: sizes[i] - offset,
      minWidth: sizes[i]
    });
    newtabbody.find('tr:first-child td').eq(i).css({
      maxWidth: sizes[i] - 10,
      minWidth: sizes[i] - 10
    });
  }   
}
function SelectRow(pk)
{
  $('.browser table tr').removeClass('selected');
  if (pk == '0') return;
  
  if ($('.browser table tr[pk=' + pk + ']').length > 0)
  {
    console.log('found .. ');
    $('.browser table tr[pk=' + pk + ']').addClass('selected');
    ScrollOnSelectedRow();  
  }
  else
  {
    RequsetNextData(0, $('.browser tbody tr').size(), function(){
      $('.browser table tr[pk=' + pk + ']').addClass('selected');
      ScrollOnSelectedRow();  
    });
  } 
}
function ScrollOnSelectedRow()
{
  var headheight = $('.browser > div:eq(0)').outerHeight();
  var scrollconn = $('.browser > div:eq(1)');
  var selectedrow = $('.browser > div:eq(1) tr.selected');
  var scrollTop = scrollconn.scrollTop();
  var bottomoffset = 0;
  if (scrollconn.prop('scrollWidth') > scrollconn.width())
    bottomoffset = 20;
  
  if (selectedrow.length != 0)
  {
    if (selectedrow.position().top + selectedrow.outerHeight() - headheight + bottomoffset> scrollconn.height())
      scrollconn.scrollTop(selectedrow.position().top - scrollconn.height() - headheight + bottomoffset + selectedrow.outerHeight() + scrollTop);
    else if (selectedrow.position().top - headheight + 1 < 0)
      scrollconn.scrollTop(scrollTop + selectedrow.position().top - headheight);
  }
}
function SetTimeoutArray()
{
  TimeoutBuffer = [];
  TimeoutBuffer.push({
    name: 'scrolltimeout',
    value: 'free'
  });
  $('.browser table thead .headcol').each(function(){
    var ident = $(this).attr('ident');
    if (ident)
    {
      TimeoutBuffer.push({
        name: ident,
        value: 'free'
      });
    }
  });
}
function SendNumberFilterReq(NumberInput)
{
  var HeadcolIdent = NumberInput.closest('.headcol').attr('ident');
  SendFilterRequest(
    HeadcolIdent, 
    'number', 
    'val=' + parseInt(NumberInput.val()) + 
    '&operindex=' + NumberInput.closest('.headcol').find('select.operselect').val()    
  ); 
}
function SendOrderFilter(OrderButton, isAdd)
{
  var HeadcolIdent = OrderButton.closest('.headcol').attr('ident');
  var ordval = '';
  var Addstr = '';
  if (isAdd) 
    Addstr = 'add';
  if (OrderButton.hasClass('down'))
    ordval = 'desc';        
  SendFilterRequest(HeadcolIdent, 'order' + Addstr, 'order=' + ordval, true);
}
function SendDateFilterReq(v_oDateFilter)
{
  var HeadcolIdent = v_oDateFilter.closest('.headcol').attr('ident');
  var v_sDateFrom = v_oDateFilter.attr('datefrom');
  var v_sDateto = v_oDateFilter.attr('dateto');
  SendFilterRequest(
    HeadcolIdent, 
    'date', 
    'datefrom=' + v_sDateFrom + 
    '&dateto=' + v_sDateto);
}
function SendFilterRequest(HeadcolIdent, DataType, valueparam, refresh, load = 50, skip = 0)
{
  //console.log('SendFilterRequest()');
  var Timeout = setTimeout(function (){
    SendAjaxRequest(
      'type=browser'+
      '&brtype=setheadfilter'+
      '&headindent=' + HeadcolIdent + 
      '&datatype=' + DataType +
      '&' + valueparam +
      '&load=' + load +
      '&skip=' + skip, 
      true, 
      function(response){
        if ($(response).attr('state') == 'ok')
        {
          v_bAllLoaded = false;
          var v_iScrollTop = $('.browser > div:eq(1)').scrollTop();
          var v_iScrollLeft = $('.browser > div:eq(1)').scrollLeft();
          if (refresh)
          {
            $('.browserconn').html($(response).find('data').html()); 
          }
          else
          {
            $('.browser > div:eq(1) table tbody').remove();
            $(response).find('data .browser table tbody').appendTo($('.browser > div:eq(1) table'));            
          }
          $('.browser').attr('scrolltop', v_iScrollTop);
          $('.browser').attr('scrollleft', v_iScrollLeft);
          InitBrowser($('.browser'));
          if (typeof BrowserDataLoadAction === 'function')
            BrowserDataLoadAction();
          CheckScreenLoaded();
          AddSumarryToBrowser($('.browser'));
        }
        else
        {
          g_AnouncementManager.AddAnouncement('red', 'Chyba odeslání filtru');
        }
      }
    );
    
    TimeoutBuffer[HeadcolIdent] = 'free';
  }, 300);
  
  if (TimeoutBuffer[HeadcolIdent] != 'free')
  { 
    clearTimeout(TimeoutBuffer[HeadcolIdent]);
  }
  TimeoutBuffer[HeadcolIdent] = Timeout;    
}
function RefreshBrowseData(load = 50, skip = 0)
{
  //console.log('RefreshBrowseData()');
  SendAjaxRequest(
    'type=browser'+
    '&brtype=reloaddata' +
    '&load=' + load +
    '&skip=' + skip,
    true, 
    function(response){
      if ($(response).attr('state') == 'ok')
      {
        v_bAllLoaded = false;
        var v_iScrollTop = $('.browser > div:eq(1)').scrollTop();
        var v_iScrollLeft = $('.browser > div:eq(1)').scrollLeft();
        $('.browser tbody .haveall').remove();
        $('.browser .nodata').remove();
        $('.browser table tbody').html($(response).find('data .browser table tbody').html());          
        $('.browser').attr('scrolltop', v_iScrollTop);
        $('.browser').attr('scrollleft', v_iScrollLeft);
        InitBrowser($('.browser'));
        $(response).find('data .browser .nodata').insertAfter($('.browser > div:eq(1)'));
        if (typeof BrowserDataLoadAction === 'function')
          BrowserDataLoadAction();
        CheckScreenLoaded();
        AddSumarryToBrowser($('.browser'));
      }
      else
      {
        g_AnouncementManager.AddAnouncement('red', 'Chyba odeslání filtru');
      }
    }
  );
}
function CheckScreenLoaded(a_oForm)
{
  if (v_bAllLoaded) return;
  if ($('.browser > div:eq(1)').prop('scrollHeight') <= $('.browser > div:eq(1)').height())
  {
    RequsetNextData(50, $('.browser tbody tr').size());
  }
}
function RequsetNextData(load, skip, callback)
{
  if (v_bAllLoaded) return;
  var Timeout = setTimeout(function (){
    //console.log('RequsetNextData()');
    SendAjaxRequest(
      'type=browser'+
      '&brtype=getnexdata' +
      '&load=' + load +
      '&skip=' + skip, 
      true, 
      function(response){
        if ($(response).attr('state') == 'ok')
        {
          var v_iScrollTop = $('.browser > div:eq(1)').scrollTop() + 10;
          var v_iScrollLeft = $('.browser > div:eq(1)').scrollLeft();
          $('.browser tbody .haveall').remove();
          $('.browser tbody').append($(response).find('data .browser table tbody').html());          
          $('.browser').attr('scrolltop', v_iScrollTop);
          $('.browser').attr('scrollleft', v_iScrollLeft);
          InitBrowser($('.browser'));
          if (typeof BrowserDataLoadAction === 'function')
            BrowserDataLoadAction();
          
          if ($(response).find('data .browser tbody tr').size() < load)
          {
            if ($(response).find('data .browser tbody tr').size() > 0)
              $('.browser tbody').append('<tr class="haveall"></tr>');
            v_bAllLoaded = true;
          }
          else
            CheckScreenLoaded();
          if (typeof callback === 'function')
            callback();
        }
        else
        {
          g_AnouncementManager.AddAnouncement('red', 'Chyba načítání dalších dat');
        }
      }
    );
    TimeoutBuffer['scrolltimeout'] = 'free';
  }, 300);
  
  if (TimeoutBuffer['scrolltimeout'] != 'free')
  { 
    clearTimeout(TimeoutBuffer['scrolltimeout']);
  }
  TimeoutBuffer['scrolltimeout'] = Timeout;    
}

function ClearFiltersRequest()
{
  SendAjaxRequest(
    'type=browser'+
    '&brtype=resetfilters', 
    true, 
    function(response){
      if ($(response).attr('state') == 'ok')
      {
        var v_iScrollTop = 0;
        var v_iScrollLeft = 0;
        $('.browser').html($(response).find('data .browser').html());
        //SetBrHeaderWidth($('.browser table thead:not(.fix-brs-header)'), $('.browser table thead.fix-brs-header'));          
        $('.browser').attr('scrolltop', v_iScrollTop);
        $('.browser').attr('scrollleft', v_iScrollLeft);
        InitBrowser($('.browser'));
        if (typeof BrowserDataLoadAction === 'function')
          BrowserDataLoadAction();
        AddSumarryToBrowser($('.browser'));
      }
      else
      {
        g_AnouncementManager.AddAnouncement('red', 'Chyba odeslání filtru');
      }
    }
  );
}

function OpenDateFilter(a_oDateFilter)
{
  var html = 
    '<div class="rangedatepicker">'+
      '<table class="rangedatepickers">'+
        '<tr>'+
          '<td colspan="2">'+
            '<div>'+
              '<span style="font-size: 19px;">Výběr období</span>'+
              '<div class="exitbt"><img src="images/cross.png"></div>'+
            '</div>'+
          '</td>'+
        '</tr>'+
        '<tr>'+
          '<td class="fromblock">'+
            '<div><span>Od:</span><input class="normalenter" id="frominp" type="text" value="' + a_oDateFilter.attr('datefrom') + '" name="foromdate"/></div>'+
            '<div id="fromdp" class="datepicker inline"></div>'+
          '</td>'+
          '<td class="toblock">'+
            '<div><span>Do:</span><input class="normalenter" id="toinp" type="text" value="' + a_oDateFilter.attr('dateto') + '" name="todate"/></div>'+
            '<div id="todp" class="datepicker inline"></div>'+
          '</td>'+
        '<tr>'+
        '<tr>'+
          '<td colspan="2">'+
            '<div class="guickbuttons">'+
              '<table>'+
                '<td type="monthsel" month="1">Led</td>'+
                '<td type="monthsel" month="2">Úno</td>'+
                '<td type="monthsel" month="3">Bře</td>'+
                '<td type="monthsel" month="4">Dub</td>'+
                '<td type="monthsel" month="5">Kvě</td>'+
                '<td type="monthsel" month="6">Čvn</td>'+
                '<td type="monthsel" month="7">Čvc</td>'+
                '<td type="monthsel" month="8">Srp</td>'+
                '<td type="monthsel" month="9">Zář</td>'+
                '<td type="monthsel" month="10">Říj</td>'+
                '<td type="monthsel" month="11">Lis</td>'+
                '<td type="monthsel" month="12">Pro</td>'+
              '</table>'+
            '</div>'+
          '</div>' +
          '</td>'+
        '</tr>'+
        '<tr>'+
          '<td colspan="2">'+
            '<div class="guickbuttons">'+
              '<table>'+
                '<tr>'+
                  '<td type="today">dnes</td>'+
                  '<td type="thisweek">tento týden</td>'+
                  '<td type="thismonth">tento měsíc</td>'+
                  '<td type="thisyear">tento rok</td>'+
                '</tr>'+
                '<tr>'+
                  '<td type="yesterday">včera</td>'+
                  '<td type="lastweek">minulý týden</td>'+
                  '<td type="lastmonth">minulý měsíc</td>'+
                  '<td type="lastyear">minulý rok</td>'+
                '</tr>'+
              '</table>'+
            '</div>'+
          '</div>' +
          '</td>'+
        '</tr>'+
      '</table>';
  
  $('.rangedatepicker').remove();
  var v_oObj = $(html);
  v_oObj.hide().appendTo('body');
  
  v_oObj.find('#fromdp').datepicker({
    defaultDate: a_oDateFilter.attr('datefrom'),
    inline: true,
    altField: '#frominp',
    onSelect: function (datestr, datepicker){
      SetFromDate(a_oDateFilter, v_oObj, datestr);
    }
  }).find('.ui-datepicker').addClass('flattheme');
  
  v_oObj.find('#todp').datepicker({
    defaultDate: a_oDateFilter.attr('dateto'),
    inline: true,
    altField: '#toinp',
    onSelect: function (datestr, datepicker){
      SetToDate(a_oDateFilter, v_oObj, datestr);
    }
  }).find('.ui-datepicker').addClass('flattheme');
  
  v_oObj.css({
    top: a_oDateFilter.offset().top + a_oDateFilter.outerHeight(),
    left: (a_oDateFilter.offset().left - 5)
  });
  
  v_oObj.on('change', '#frominp', function(){
    if ($(this).val() == '')
    {
      v_oObj.find('#fromdp .ui-state-active').removeClass('ui-state-active');
      a_oDateFilter.attr('datefrom', '');
      SendDateFilterReq(a_oDateFilter);
    }
    else
    {
      SetFromDate(a_oDateFilter, v_oObj, $(this).val());
      $(this).val(DateToStr(v_oObj.find('#fromdp').datepicker('getDate')));
    }
  });
  
  v_oObj.on('change', '#toinp', function(){
    if ($(this).val() == '')
    {
      v_oObj.find('#todp .ui-state-active').removeClass('ui-state-active');
      a_oDateFilter.attr('dateto', '');
      SendDateFilterReq(a_oDateFilter);
    }
    else
    {
      SetToDate(v_oObj, $(this).val());
      $(this).val(DateToStr(v_oObj.find('#todp').datepicker('getDate')));
    }
  });
  
  v_oObj.on('mouseover', '.exitbt', function(){
    $(this).children('img').attr('src', 'images/crossActive.png');
  });
  
  v_oObj.on('mouseout', '.exitbt', function(){
    $(this).children('img').attr('src', 'images/cross.png');
  });
  
  v_oObj.on('click', '.guickbuttons td', function(){
    var today = new Date();
    switch($(this).attr('type'))
    {
      case 'monthsel' : SetMonthRangeOnFilter(a_oDateFilter, v_oObj, $(this).attr('month')); break;
      case 'today' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(today));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(today));
        break;
      case 'thisweek' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() + (7 - today.getDay()))));
        break;
      case 'thismonth' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth() + 1, 0)));
        break;
      case 'thisyear' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), 0, 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear() + 1, 0, 0)));
        break;
      case 'yesterday' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1)));
        break;
      case 'lastweek' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 1 - 7)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), today.getDate() + (7 - today.getDay() - 7))));
        break;
      case 'lastmonth' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth() - 1, 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), today.getMonth(), 0)));
        break;
      case 'lastyear' : 
        SetFromDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear() - 1, 0, 1)));
        SetToDate(a_oDateFilter, v_oObj, DateToStr(new Date(today.getFullYear(), 0, 0)));
        break;
    }
  });
  v_oObj.on('click', '.exitbt', function(){
    v_oObj.remove();
  });
  var closefnc = function(e){
    if ($('.rangedatepicker:hover').length == 0)
    {
      $('body').unbind('click', closefnc);
      v_oObj.remove();
    }
  };
  $('body').bind('click', closefnc);
  
  if (a_oDateFilter.attr('datefrom') == '')
    SetFromDate(a_oDateFilter, v_oObj, '');
  if (a_oDateFilter.attr('dateto') == '')
    SetToDate(a_oDateFilter, v_oObj, '');
  
  v_oObj.show(); 
}
function SetFromDate(a_oFilterObj, a_oHTMLObj, a_sDateString,  submit = true)
{
  if (a_sDateString == '')
  {
    a_oHTMLObj.find('#frominp').val('');
    return;
  }
  var v_dtFromDate = StrToDate(a_sDateString);
  if (v_dtFromDate == false) return;
  
  if (a_oHTMLObj.find('#tpinp').val() !== '')
  {
    var v_dtToDate = a_oHTMLObj.find('#todp').datepicker('getDate');
    a_oHTMLObj.find('#fromdp').datepicker('setDate', v_dtFromDate);
    if (v_dtFromDate > v_dtToDate)
      a_oHTMLObj.find('#todp').datepicker('setDate', v_dtFromDate);
  }

  a_oFilterObj.attr('datefrom', DateToStr(v_dtFromDate));
  if (submit)
    SendDateFilterReq(a_oFilterObj);
}
function SetToDate(a_oFilterObj, a_oHTMLObj, a_sDateString, submit = true)
{
  if (a_sDateString == '')
  {
    a_oHTMLObj.find('#toinp').val('');
    return;
  }
  var v_dtToDate = StrToDate(a_sDateString);
  if (v_dtToDate == false) return;
  
  if (a_oHTMLObj.find('#frominp').val() !== '')
  {
    var v_dtFromDate = a_oHTMLObj.find('#fromdp').datepicker('getDate');
    a_oHTMLObj.find('#todp').datepicker('setDate', v_dtToDate);
    if (v_dtFromDate > v_dtToDate)
      a_oHTMLObj.find('#fromdp').datepicker('setDate', v_dtToDate);
  }
  
  a_oFilterObj.attr('dateto', DateToStr(v_dtToDate));
  if (submit)
    SendDateFilterReq(a_oFilterObj);
}
function SetMonthRangeOnFilter(a_oFilterObj, a_oHTMLObj, a_sMonthNum)
{
  var v_iMonthNum = parseInt(a_sMonthNum);    
  if (v_iMonthNum === false) return;
  v_iMonthNum -= 1; 
  var year = a_oHTMLObj.find('#fromdp').datepicker('getDate').getFullYear();
  var v_dtFromDate = new Date(year, v_iMonthNum, 1);
  if (v_iMonthNum == 11) year++;
  var v_dtToDate = new Date(year, (v_iMonthNum + 1) % 12, 0);
  SetFromDate(a_oFilterObj, a_oHTMLObj, DateToStr(v_dtFromDate), false);
  SetToDate(a_oFilterObj, a_oHTMLObj, DateToStr(v_dtToDate), false);
  SendDateFilterReq(a_oFilterObj);
}

function AddSumarryToBrowser(v_oBrowser)
{
  if (v_oBrowser.attr('showsummary') !== '1') return;
  SendAjaxRequest(
    'type=browser'+
    '&brtype=getSummary', 
    true, 
    function(response){
      var append = false;
      var v_ElemObj = $('.browsersummary');
      if (v_ElemObj.length == 0)
      {
        v_ElemObj = $('<div class="browsersummary"><span><table><td>Celkem nalezeno záznamů:</td><td class="firstrow records"></td></table></span></div>');
        append = true;
      }
      
      v_ElemObj.find('.records').text($(response).find('summary').attr('count'));
      
      $(response).find('summary field').each(function(){
        var ident = $(this).attr('ident');
        var v_oObj = v_ElemObj.find('.sumfield[ident="' + ident + '"]');
        if (v_oObj.length == 0)
        {
          v_oObj = 
            $('<div class="sumfield" ident="' + ident + '">'+
                '<div class="name">' + $(this).attr('name') + '</div>'+
                '<table>'+
                  '<tr><td>' + $(this).attr('fistrowdesc') + ':</td><td><span class="fistrow"></td></tr>'+
                  '<tr><td>' + $(this).attr('secrowdesc') + ':</td><td class="secrow"></td></tr>'+
                '</table>'+
              '</div>');
          v_ElemObj.append(v_oObj);
        }
        v_oObj.find('.fistrow').text($(this).attr('fistrow'));
        v_oObj.find('.secrow').text($(this).attr('secrow'));
      });
      if (append)
      {
        v_ElemObj.insertAfter('.browserconn');
        $('.browserconn').css('height', 'calc(100% - 36px - ' + v_ElemObj.outerHeight() + 'px)');
        InitBrowser(v_oBrowser);
      }
    }
  );
}
