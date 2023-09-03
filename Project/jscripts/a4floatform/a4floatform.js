
var gScale = 1;
var gLastScrollTop = 0;
var gHasUnsavedChanges = false;
var gDatalistTimer;
var gDataListSelRowIndex = 0;
var gWaitingForPFD = false;

$(document).ready(function () {
    $(".datepicker").datepicker({
        afterShow: function (input, inst, td) {
            $(this).css({ left: '0px', top: '0px' });
        }
    });
});

function BuildA4FloatForm(xml, fade) {
    gHasUnsavedChanges = false;
    xml = PreProcess(xml);
    
    $(".a4floatform").remove();
    
    var mainObj = $(
        '<div class="a4floatform">' +
        '<div class="a4ff_toolbar">' +
        '<div class="a4ff_toolbar_exitbt"><img src="images/cross.png"/></div>' +
        '</div>' +
        '<div class="a4ff_content"><div class="a4ff_content_doc"></div></div>' +
        '</div>'
    );
    mainObj.appendTo('body');
    
    mainObj.on('mouseover', '.a4ff_toolbar_exitbt, .transport .delbt', function () {
        $(this).children('img').attr('src', 'images/crossActive.png');
    });
    
    mainObj.on('mouseout', '.a4ff_toolbar_exitbt, .transport .delbt', function () {
        $(this).children('img').attr('src', 'images/cross.png');
    });
    
    $('body').on('click', '.a4ff_toolbar_exitbt', function () {
        let form = $(this).closest('.a4floatform');
        CloseForm(form);
    });
    
    mainObj.on('click', '.a4ff_resizebar .zoomin', function () {
        gScale += 0.1;
        $('.a4ff_content > div').css({ transform: 'scale(' + gScale + ')' });
    });
    
    mainObj.on('click', '.a4ff_resizebar .zoomout', function () {
        gScale -= 0.1;
        $('.a4ff_content > div').css({ transform: 'scale(' + gScale + ')' });
    });
    
    $('body').keydown(function (e) {
        if (window.event.ctrlKey && e.keyCode == 13) { // ENTER
            SendForm();
        } else if (e.keyCode == 27) {
            CloseForm(mainObj);
        }
    });
    
    mainObj.on('keyup', '.datalist', function (e) {
        if (e.keyCode == 40) {
            MoveDataListSelectionDown();
        } else if (e.keyCode == 38) {
            MoveDataListSelectionUP();
        } else if (e.keyCode == 27) {
            CloseDataList();
        } else if (e.keyCode == 13) {
            $('#datalist .selected').click();
            FocusNexInput($(this));
        } else {
            if ($(this).attr('datareq') == 'searchdeliverers') {
                ExpandDataList($(this), FillDelivererFromItem);
            } else if ($(this).attr('datareq') == 'searchnaklspot' || $(this).attr('datareq') == 'searchvyklspot') {
                ExpandDataList($(this), FillSpotFromItem, 'custident=' + $('input[name="oror_zakaznikident"]').val());
            } else {
                ExpandDataList($(this));
            }
        }
    });
    
    mainObj.on('focusin', '.datalist', function () {
        if ($(this).attr('datareq') == 'searchdeliverers') {
            ExpandDataList($(this), FillDelivererFromItem);
        } else if ($(this).attr('datareq') == 'searchnaklspot' || $(this).attr('datareq') == 'searchvyklspot') {
            ExpandDataList($(this), FillSpotFromItem, 'custident=' + $('input[name="oror_zakaznikident"]').val());
        } else {
            ExpandDataList($(this));
        }
    });
    
    mainObj.on('focusout', '.datalist', function () {
        if ($('#datalist').length > 0 && !$('#datalist').is(":hover")) {
            CloseDataList();
        } else if (gDatalistTimer) {
            clearTimeout(gDatalistTimer);
            gDatalistTimer = null;
        }
    });
    
    //kliknuti na vydej a zkopirovani hodnoty do sml. ceny
    mainObj.on('keydown', 'input[name="oror_vydej"]', function (e) {
        var v_iPrijem = StrToFloat(mainObj.find('input[name="oror_prijem"]').val());
        var v_iVydej = StrToFloat($(this).val());
        var v_sCustIdent = mainObj.find('input[name="oror_zakaznikident"]').val();
        if (e.keyCode == 13) {
            var smlprice = mainObj.find('input[name="oror_smlcenatext"]');
            if (smlprice.val() == '') {
                smlprice.val($(this).val() + ",- CZK");
            }
            SendAjaxRequest(
                'type=flformreq&formtype=getcustbyident' +
                '&customerid=' + v_sCustIdent,
                true,
                function (response) {
                    var v_iCustPOR = StrToFloat($(response).find('or_customer orcust_prijemproc').text());
                    var v_oBokemCastkaElem = mainObj.find('input[name="oror_bokemcastka"]');
                    if (v_iCustPOR > 0.0 && v_oBokemCastkaElem.val() == '') {
                        v_oBokemCastkaElem.changeVal((v_iPrijem - v_iVydej) * v_iCustPOR / 100);
                        if (mainObj.find('input[name="oror_bokemkdo"]').val() == '') {
                            mainObj.find('input[name="oror_bokemkdo"]').changeVal(v_sCustIdent.charAt(0));
                        }
                    }
                },
            );
        }
    });
    
    var xmlObj = $(xml);
    xmlObj.find("action").each(function () {
        $(
            '<div class="a4ff_toolbar_bt" ident="' + $(this).attr('ident') + '">' +
            '<img src="' + $(this).attr('img') + '" />' + $(this).attr('desc') +
            '</div>'
        ).appendTo(mainObj.children('.a4ff_toolbar'));
    });
    
    $('.a4ff_content').scroll(function () {
        var datalist = $('#datalist');
        if (datalist.length > 0) {
            datalist.css({ top: datalist.position().top + (gLastScrollTop - $(this).scrollTop()) });
            gLastScrollTop = $(this).scrollTop();
        }
    });
    
    var headerHTML = xmlObj.find("header").html();
    var lists = [];
    lists.push(new A4List(mainObj, headerHTML));
    
    xmlObj.find("block").each(function () {
        if (lists[lists.length - 1].AddBlock($(this)) < 0) {
            lists.push(new A4List(mainObj, headerHTML));
            lists[lists.length - 1].AddBlock($(this));
        }
    });
    
    for (var i = 0; i < lists.length; i++) {
        $(lists[i].BuildHTML()).appendTo(mainObj.find('.a4ff_content_doc'));
    }
    
    if (fade) {
        mainObj.css({ visibility: 'visible', display: 'none' }).fadeIn(200);
    } else {
        mainObj.css({ visibility: 'visible' });
    }
    
    mainObj.on('click', '.a4ff_toolbar_bt[ident="save"]', function () {
        SendForm();
    });
    
    mainObj.on('click', '.a4ff_toolbar_bt[ident="export"]', function () {
        ExportRequest();
    });
    
    mainObj.on('click', '.a4ff_toolbar_bt[ident="delete"]', function () {
        RasiceConfirmForm('Varování', 'Opravdu si přeje vymazat objednávku a ztratit data?', function () {
            DeleteRequest();
        });
    });
    
    mainObj.on('click', 'button.addtransport', function () {
        SendAjaxRequest('type=flformreq&formtype=addtransport', true, function (response) {
            var resp = $(response);
            if (resp.find('block').length == 0) {
                g_AnnouncementManager.AddAnnouncement('red', 'Chyba.');
                return;
            }
            xml = RebuildXMLWithChange(xml, function (tmpXML) {
                tmpXML.find('block:nth-last-child(2)').before(resp.find('block'));
            });
        });
    });
    
    mainObj.on('click', '.transport .delbt', function () {
        var transport = $(this).parent('.transport');
        var isEmpty = true;
        
        transport.find('input[type="text"]').each(function () {
            if ($(this).val().length > 0) {
                isEmpty = false;
                return false; // break;
            }
        });
        
        if (!isEmpty) {
            RasiceConfirmForm('Varování', 'Opravdu si přeje vymazat přepravu a ztratit data?', function () {
                xml = DeleteTransport(transport, xml);
            });
        } else {
            xml = DeleteTransport(transport, xml);
        }
    });
    
    mainObj.on('change', 'input', function () {
        gHasUnsavedChanges = true;
    });
    
    $(".datepicker").datepicker();
    
    $(".ui-datepicker").addClass('metaltheme');
    
    return mainObj;
}

function A4List(mainObj, headerHTML) {
    this.headerHTML = headerHTML;
    this.blocks = [];
    this.mainObj = mainObj;
    
    this.AddBlock = function (block) {
        this.blocks.push(block);
        var page = $(this.BuildHTML());
        page.addClass('tmp');
        page.appendTo(this.mainObj.children('.a4ff_content'));
        var prevHeight = page.height();
        page.css('height', '0');
        
        if (page.prop('scrollHeight') > prevHeight - 20) {
            this.mainObj.find('.tmp').remove();
            this.RemoveBlock(this.blocks.length - 1);
            return -1;
        }
        
        this.mainObj.find('.tmp').remove();
        return this.blocks.length - 1;
    };
    
    this.RemoveBlock = function (index) {
        this.blocks.splice(index);
    };
    
    this.BuildHTML = function () {
        var html = '<div class="a4ff_a4list">';
        html += '<div class="header">' + this.headerHTML + '</div>';
        for (var i = 0; i < this.blocks.length; i++) {
            html += this.blocks[i].html();
        }
        html += '</div>';
        return html;
    }
}

function PreProcess(xml) {
    var xmlObj = $(xml);
    xmlObj.find('block').each(function (index) {
        var text = $(this).children('text').html();
        if (text) {
            var lines = text.split('<br>');
            xmlObj.find('block:eq(' + index + ')').remove();
            for (var i = 0; i < lines.length; i++) {
                xmlObj.append('<block><span>' + lines[i] + '<br></span></block>');
            }
        }
    });
    return '<xml>' + xmlObj.html() + '</xml>';
}

function SendForm(callback) {
    var globalForm = $('<form method="post"></form>');
    var forms = [];
    
    $('.a4ff_content').find('form').each(function (index) {
        forms.push($(this).clone());
    });
    
    for (var i = 0; i < forms.length; i++) {
        forms[i].find('input').each(function () {
            var input = $(this).clone();
            input.attr('value', $(this).val());
            globalForm.append(input);
        });
    }
    
    SendAjaxRequest(
        'type=flformreq' +
        '&formtype=submit&' + globalForm.serialize(),
        true,
        function (response) {
            var respxml = $(response);
            if (respxml.find('invaliformddata').length > 0) {
                respxml.find('order > invaliddata > input').each(function () {
                    HighlightInvalidInput($(this).attr('name'), $(this).attr('message'));
                });
                respxml.find('transport').each(function () {
                    var ident = $(this).attr('ident');
                    $(this).find('invaliddata input').each(function () {
                        HighlightInvalidInput(ident + $(this).attr('name'), $(this).attr('message'), $('body .transport[ident="' + ident + '"]'));
                    });
                });
            } else if (respxml[0].textContent != "chyba") {
                gHasUnsavedChanges = false;
                if (typeof (callback) === 'function') {
                    callback(response);
                } else {
                    CloseForm($('.a4floatform'));
                }
            }
        }
    );
}

function ExpandDataList(input, selCallback, addReqParams) {
    if (gDatalistTimer) { clearTimeout(gDatalistTimer); gDatalistTimer = null; }
    gDatalistTimer = setTimeout(function () {
        SendAjaxRequest(
            'type=flformreq' +
            '&formtype=datalist' +
            '&input=' + input.attr('datareq') +
            '&value=' + input.val() + ((addReqParams) ? '&' + addReqParams : ''),
            true,
            function (response) {
                $('#datalist').remove();
                var html = '<div id="datalist">';
                var counter = 0;
                $(response).find('item').each(function () {
                    html +=
                        '<div class="item">' + $(this).html() + '</div>';
                    counter++;
                });
                if (counter == 0) {
                    html += '<div class="nodata">Nalezeny žádné položky.</div>';
                }
                html += '</div>';
                var obj = $(html).insertAfter(input);
                obj.width(input.width());
                
                gLastScrollTop = $('.a4ff_content').scrollTop();
                
                var v_iNewTop = input.offset().top + input.outerHeight();
                obj.css({ top: v_iNewTop });
                obj.css({ left: input.offset().left - 1 });
                
                obj.on('click', '.item', function () {
                    if (typeof (selCallback) === 'function') {
                        selCallback(input, $(this));
                    } else {
                        input.changeVal($(this).text());
                    }
                    CloseDataList();
                });
                obj.on('mouseenter', '.item', function () {
                    gDataListSelRowIndex = $(this).index();
                    SelectDataOptionByIndex(true);
                });
                obj.mouseleave(function () {
                    gDataListSelRowIndex = -1;
                    SelectDataOptionByIndex();
                });
                gDataListSelRowIndex = -1;
            }
        );
    }, 300);
}

function MoveDataListSelectionDown() {
    gDataListSelRowIndex++;
    if (gDataListSelRowIndex >= $("#datalist > div").length) {
        gDataListSelRowIndex = 0;
    }
    SelectDataOptionByIndex();
}

function MoveDataListSelectionUP() {
    gDataListSelRowIndex--;
    if (gDataListSelRowIndex < 0) {
        gDataListSelRowIndex = $("#datalist > div").length - 1;
    }
    SelectDataOptionByIndex();
}

function SelectDataOptionByIndex(noScroll) {
    $("#datalist > div").removeClass("selected");
    if (gDataListSelRowIndex >= 0) {
        var datalist = $("#datalist");
        var selItem = datalist.children(".item").eq(gDataListSelRowIndex).addClass("selected");
        if (!noScroll) {
            var scrollTop = datalist.scrollTop();
            if (selItem.position().top + selItem.outerHeight() > datalist.height()) {
                datalist.scrollTop(selItem.position().top - datalist.height() + selItem.outerHeight() + scrollTop);
            } else if (selItem.position().top + 1 < 0) {
                datalist.scrollTop(scrollTop + selItem.position().top);
            }
        }
    }
}

function CloseDataList() {
    $('#datalist').remove();
    gDataListSelRowIndex = 0;
}

function FillDelivererFromItem(input, item) {
    SendAjaxRequest(
        'type=flformreq' +
        '&formtype=getdeliverer' +
        '&pk=' + $(item.html()).attr('pk'),
        true,
        function (response) {
            var resp = $(response);
            $('input[name="oror_doprfirma"]').changeVal(resp.find('ordlv_firma').text());
            $('input[name="oror_doprulice"]').changeVal(resp.find('ordlv_ulice').text());
            $('input[name="oror_doprstat"]').changeVal(resp.find('ordlv_stat ').text());
            $('input[name="oror_doprpsc"]').changeVal(resp.find('ordlv_psc').text());
            $('input[name="oror_doprmesto"]').changeVal(resp.find('ordlv_mesto').text());
            $('input[name="oror_doprtel"]').changeVal(resp.find('ordlv_telnum').text());
            $('input[name="oror_doprspz"]').changeVal(resp.find('ordlv_spz').text());
            $('input[name="oror_dopric"]').changeVal(resp.find('ordlv_ic').text());
            $('input[name="oror_doprdic"]').changeVal(resp.find('ordlv_dic').text());
            $('input[name="oror_raal"]').changeVal(resp.find('ordlv_raal').text());
            $('input[name="oror_doprjmeno"]').changeVal(resp.find('ordlv_jmeno').text());
        }
    );
}

function FillSpotFromItem(input, item) {
    SendAjaxRequest(
        'type=flformreq' +
        '&formtype=getcustaddress' +
        '&pk=' + $(item.html()).attr('pk'),
        true,
        function (response) {
            var resp = $(response);
            var form = input.parents('.blockform');
            var prefix = '';
            if (form.parents('form').attr('nameprefix').length > 0) {
                prefix = form.parents('form').attr('nameprefix');
            }
            
            if (input.attr('datareq') == 'searchnaklspot') {
                prefix += 'nakl_';
            } else if (input.attr('datareq') == 'searchvyklspot') {
                prefix += 'vykl_';
            }
            
            form.find('input[name="' + prefix + 'orspt_term"]').changeVal(resp.find('orcadr_cas3').text());
            form.find('input[name="' + prefix + 'orspt_term"]').attr('cas3', resp.find('orcadr_cas3').text());
            form.find('input[name="' + prefix + 'orspt_firma"]').changeVal(resp.find('orcadr_firma').text());
            form.find('input[name="' + prefix + 'orspt_firma2"]').changeVal(resp.find('orcadr_firma2').text());
            form.find('input[name="' + prefix + 'orspt_mesto"]').changeVal(resp.find('orcadr_mesto').text());
            form.find('input[name="' + prefix + 'orspt_ulice"]').changeVal(resp.find('orcadr_ulice').text());
            form.find('input[name="' + prefix + 'orspt_stat"]').changeVal(resp.find('orcadr_stat').text());
            form.find('input[name="' + prefix + 'orspt_psc"]').changeVal(resp.find('orcadr_psc').text());
        }
    );
}

function CloseForm(form) {
    var v_fCloseProc = function () {
        SendAjaxRequest('type=closeform', true, function (response) {
            if ($(response).find('result').text() === 'success') {
                form.fadeOut(200, function () {
                    form.remove();
                    RefreshBrowseData();
                    LoadYearSummary();
                });
            } else {
                g_AnnouncementManager.AddAnnouncement('red', 'Chyba při zavírání formuláře.');
            }
        });
    };
    
    if (gHasUnsavedChanges) {
        RasiceConfirmForm(
            'Upozornění',
            'Byly provedeny změny, zavřít neuložený formulář?',
            v_fCloseProc,
            [
                { submit: true, message: 'Ano' },
                { submit: false, message: 'Zrušit' }
            ],
        );
    } else {
        v_fCloseProc();
    }
}

function ExportRequest() {
    if (!gWaitingForPFD) {
        gWaitingForPFD = true;
        SendForm(function (_) {
            SendAjaxRequest(
                'type=flformreq' +
                '&formtype=export',
                true,
                function (response) {
                    gWaitingForPFD = false;
                    if ($(response).find('downloadfile').length > 0) {
                        var url = location.href;
                        window.open(
                            url.substring(0, url.lastIndexOf("/") + 1)
                            + 'download.php?'
                            + 'filename=' + $(response).find('downloadfile').attr('sourcename')
                            + '&downloads=' + $(response).find('downloadfile').attr('downloadname')
                        );
                    }
                }
            );
        });
    }
}

function DeleteTransport(transport, xml) {
    SendAjaxRequest(
        'type=flformreq&formtype=deltransport&ident=' + transport.attr('ident'),
        true,
        function (response) {
            if ($(response).text() == 'chyba') {
                g_AnnouncementManager.AddAnnouncement('red', 'Chyba.');
                return xml;
            }
            xml = RebuildXMLWithChange(xml, function (tmpXML) {
                tmpXML
                    .find('.transport[ident="' + transport.attr('ident') + '"]')
                    .parent('block')
                    .remove()
            });
        }
    );
}

function DeleteRequest() {
    SendAjaxRequest(
        'type=flformreq&formtype=delorder',
        true,
        function (response) {
            if ($(response).text() == 'chyba') {
                g_AnnouncementManager.AddAnnouncement('red', 'Chyba.');
                return;
            }
            CloseForm($('.a4floatform'));
        }
    );
}

function RebuildXMLWithChange(xml, CallBack) {
    var dataArray = [];
    $('.a4ff_content').find('input').each(function () {
        var input = $(this);
        dataArray.push(new function () {
            this.ident = input.attr('name');
            this.value = input.val();
        });
    });
    
    var tmpXML = $('<tmp>' + xml + '</tmp>');
    var scrollTop = $('.a4ff_content').scrollTop();
    
    if (typeof (CallBack) === 'function') {
        CallBack(tmpXML);
    }
    
    console.log(tmpXML.html());
    BuildA4FloatForm(tmpXML.html(), false);
    for (var i = 0; i < dataArray.length; i++) {
        $('.a4ff_content').find('input[name="' + dataArray[i].ident + '"]').changeVal(dataArray[i].value);
    }
    
    xml = tmpXML.html();
    $('.a4ff_content').scrollTop(scrollTop);
}
