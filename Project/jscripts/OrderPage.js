var v_oOpenForm = null;
var v_sSelectedPK = '0';

$(document).ready(function () {
    var a4ffdata = $('body').find('a4ff_data');
    if (a4ffdata.length > 0) {
        v_oOpenForm = BuildA4FloatForm(a4ffdata.html(), false);
        a4ffdata.remove();
    }
    
    $('body').on('click', '.newbt', function () {
        SendAjaxRequest('type=neworder', true, function (response) {
            var xml = $(response).html();
            v_oOpenForm = BuildA4FloatForm(xml, true);
        });
    });
    
    $('body').on('click', '.browser tbody tr', function () {
        SelectOrderRow($(this));
    });
    
    $('body').on('click', '.browser tbody tr.selected', function () {
        OpenSelOrder();
    });
    
    $('body').on('dblclick', '.browser tbody tr', function () {
        SelectOrderRow($(this), OpenSelOrder);
    });
    
    BrowserDataLoadAction = function () {
        $('.browser table tr').each(function () {
            var storno = $(this).attr('isstorno') == '1';
            var slozeno = $(this).find('td:eq(15)').attr('boolval') == '1';
            var vyklmiss = $(this).attr('vyklmissed') == '1';
            var color = $(this).attr('custcolor');
            var invoiced = $(this).find('td:eq(16)').text().length > 0 && !storno;
            $(this).find('td:not(:first-child)').each(function () {
                if (storno) {
                    $(this).css({ textDecoration: 'line-through', color: 'red' });
                }
                
                if ($(this).index() == 3 && color.length > 0) {
                    $(this).css('backgroundColor', color);
                }
                
                if (($(this).index() == 1) && invoiced && slozeno && !storno) {
                    $(this).css('backgroundColor', 'rgb(80,140,255)');
                }
                
                if ($(this).index() == 12 && !storno && vyklmiss) {
                    $(this).css('backgroundColor', 'rgb(255,80,80)');
                }
            });
        });
        SelectRow(v_sSelectedPK);
    };
    
    OnBrowseRowChangeCallBack = function (index) {
        SelectOrderRow($('.browser tbody tr:eq(' + index + ')'));
    };
    
    OnBrowseSelectCallBack = function () {
        OpenSelOrder();
    };
    
    BrowserDataLoadAction();
});

function SelectOrderRow(a_oRow, CallBack) {
    v_sSelectedPK = a_oRow.attr('pk');
    SelectRow(v_sSelectedPK);
    if (typeof (CallBack) === 'function') {
        CallBack(a_oRow);
    }
}

function OpenSelOrder(CallBack) {
    OpenOrder(v_sSelectedPK);
}

function OpenOrder(a_sPK, CallBack) {
    SendAjaxRequest('type=editorder&pk=' + a_sPK, true, function (response) {
        var xml = $(response).html();
        v_oOpenForm = BuildA4FloatForm(xml, true);
        if (typeof (CallBack) === 'function') {
            CallBack();
        }
    });
}
