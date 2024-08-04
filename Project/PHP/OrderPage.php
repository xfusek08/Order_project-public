<?php

class OrderPage extends Page
{
    private $i_oOrderForm;
    public $i_oBrowser;
    
    public function __construct() {
        parent::__construct();
        
        $this->i_oBrowser = new Browser(
            "
            or_order
                left outer join or_customer cust on cust.orcust_ident = oror_zakaznikident
                left outer join or_objnaklvykl on oronv_obj = oror_pk
                left outer join or_spot nakl on nakl.orspt_pk = oronv_nakl
                left outer join or_spot vykl on vykl.orspt_pk = oronv_vykl
            group by
                oror_pk,
                oror_cisloobj,
                oror_cisloobjrok,
                oror_zakaznikident,
                oror_datum,
                oror_prijem,
                oror_vydej,
                oror_doprjmeno,
                oror_raal,
                oror_duedate,
                oror_factvyd,
                oror_factprij,
                oror_bokemkdo,
                oror_bokemcastka,
                oror_isstorno,
                oror_isslozeno,
                oror_bokempozn,
                orcust_color
            ",
            'oror_pk'
        );
        
        // $v_oActField = $this->i_oBrowser->AddField(DataType::String, 'Čislo',
        // 'LPAD(oror_cisloobj || \'/\' || oror_cisloobjrok, 7, \'0\')', 50);
        // $v_oActField->i_sCss = 'font-size: 13px;';
        // $v_oActField->i_iOrderByIndex = 1;
        // $v_oActField->i_bOrdDesc = true;
        // $v_oActField->i_sColIndent = 'cisloob';
        
        $this->i_oBrowser->i_bShowSummary = true;
        
        // Číslo
        $v_oActField = $this->i_oBrowser->AddField(DataType::Integer, 'Číslo', 'oror_cisloobj', 40);
        $v_oActField->i_iOrderByIndex = 2;
        $v_oActField->i_bOrdDesc = true;
        $v_oActField->i_sCss = 'font-size: 13px;';
        
        // Rok
        $v_oActField = $this->i_oBrowser->AddField(DataType::Integer, 'Rok', 'oror_cisloobjrok', 40);
        $v_oActField->i_iOrderByIndex = 1;
        $v_oActField->i_bOrdDesc = true;
        $v_oActField->i_sCss = 'font-size: 13px;';
        $v_oActField->i_bFormatted = false;
        
        // Zákazník
        $this->i_oBrowser->AddField(DataType::String, 'Zákazník', 'oror_zakaznikident', 60);
        
        // Datum
        $this->i_oBrowser->AddField(DataType::DateTrnc, 'Datum', 'oror_datum')->i_bIsSummary = true;
        
        // Nakládka
        $this->i_oBrowser->AddField(
            DataType::String,
            'Nakládka',
            'cast(list((nakl.orspt_stat || \' - \' || nakl.orspt_psc || \' | \' || nakl.orspt_mesto), \'<hr/>\') as varchar(500))'
        )->i_sCss =
            'border-right: none;';
        
        // Vykládka
        $this->i_oBrowser->AddField(
            DataType::String,
            'Vykládka',
            'cast(list((vykl.orspt_stat || \' - \' || vykl.orspt_psc || \' | \' || vykl.orspt_mesto), \'<hr/>\') as varchar(500))'
        )->i_sCss =
            'border-right: none;';
        
        // Příjem
        $v_oActField = $this->i_oBrowser->AddField(DataType::Float, 'Příjem [CZK]', 'oror_prijem', 80);
        $v_oActField->i_sCss = 'font-size: 13px;';;
        $v_oActField->i_bIsSummary = true;
        
        // Výdej
        $v_oActField = $this->i_oBrowser->AddField(DataType::Float, 'Výdej [CZK]', 'oror_vydej', 80);
        $v_oActField->i_sCss = 'font-size: 13px;';
        $v_oActField->i_bIsSummary = true;
        
        // Zisk
        $v_oActField = $this->i_oBrowser->AddField(DataType::Float, 'Zisk [CZK]', '(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0))');
        $v_oActField->i_sCss = 'font-weight: bold; font-size: 13px;';
        $v_oActField->i_bIsSummary = true;
        
        // Dopravce
        $this->i_oBrowser->AddField(DataType::String, 'Dopravce', 'oror_doprjmeno');
        
        // RAAL
        $this->i_oBrowser->AddField(DataType::String, 'RAAL', 'oror_raal', 30)->i_sCss = 'font-weight: bold;';
        
        // Vykládka - datum
        $this->i_oBrowser->AddField(
            DataType::String,
            'Vykládka',
            'list(cast(extract(DAY from vykl.orspt_date) as varchar(5)) || \'.\' || cast(extract(MONTH from vykl.orspt_date) as varchar(5)) || \'.\', \'<hr/>\')',
            40
        );
        
        // Zboží
        $this->i_oBrowser->AddField(
            DataType::String,
            'Zboží',
            'cast(list(oronv_zbozipopis,  \'<hr/>\') as varchar(500))'
        )->i_sCss = 'min-width: 150px; white-space: normal;';
        
        // Hmotnost
        $this->i_oBrowser->AddField(
            DataType::Integer,
            'Hmotnost [kg]',
            'cast(list(oronv_vaha,  \'<hr/>\') as varchar(500))'
        );
        
        // Počet (Složeno)
        $this->i_oBrowser->AddField(DataType::Bool, 'Složeno', 'oror_isslozeno');
        
        // Datum splatnosti
        $this->i_oBrowser->AddField(DataType::DateTrnc, 'Splatnost', 'oror_duedate', 40);
        
        // Vydaná faktura
        $this->i_oBrowser->AddField(DataType::String, 'Vyd. faktura', 'oror_factvyd');
        
        // Přijatá faktura
        $this->i_oBrowser->AddField(DataType::String, 'Přij. faktura', 'oror_factprij');
        
        // Bokem komu
        $this->i_oBrowser->AddField(DataType::String, 'Bokem komu', 'oror_bokemkdo');
        
        // Bokem kolik
        $this->i_oBrowser->AddField(DataType::Float, 'Bokem kolik [CZK]', 'oror_bokemcastka')->i_bIsSummary = true;
        
        // Bokem poznámka
        $this->i_oBrowser->AddField(DataType::String, 'Bokem pozn', 'oror_bokempozn');
        
        // hidden as attributes -----------------------------------
        
        // Storno
        $v_oActField = $this->i_oBrowser->AddField(DataType::Bool, 'Storno', 'oror_isstorno');
        $v_oActField->i_bAttrHiddenValue = true;
        $v_oActField->i_sColIndent = 'isstorno';
        
        // Promeškáno
        $v_oActField = $this->i_oBrowser->AddField(
            DataType::Bool,
            'Promeškáno',
            'list(case when' .
                '(vykl.orspt_date is not null) and' .
                '(oror_isslozeno = 0) and' .
                '(vykl.orspt_date < current_date)' .
                'then 1 else 0 end, \'<hr/>\')'
        );
        $v_oActField->i_bAttrHiddenValue = true;
        $v_oActField->i_sColIndent = 'vyklmissed';
        
        // Barva zákazníka
        $v_oActField = $this->i_oBrowser->AddField(DataType::String, 'Barva', 'orcust_color');
        $v_oActField->i_bAttrHiddenValue = true;
        $v_oActField->i_sColIndent = 'custcolor';
        
        // Konec browseru -----------------------------------------
        
        $this->i_sFiles =
            '<script type="text/javascript" src="jscripts/OrderPage.js"></script>' .
            '<script type="text/javascript" src="jscripts/a4floatform/a4floatform.js"></script>' .
            '<link rel="stylesheet" href="css/a4floatform/a4floatform.css" type="text/css" media="screen" />' .
            '<link rel="stylesheet" href="css/OrderPage.css" type="text/css" media="screen" />';
    }
    
    public function BuildPage() {
        parent::BuildPage();
?>
        <div class="cap">Přehled objednávek</div>
        <div class="browserconn" style="height: calc(100% - 36px);">
            <?= $this->i_oBrowser->BuildBrowserHTML(50, 0) ?>
        </div>
        
        <?php if ($this->i_oOrderForm !== null): ?>
            <a4ff_data>
                <xml>
                    <?= $this->i_oOrderForm->BuildXML() ?>
                </xml>
            </a4ff_data>
        <?php endif; ?>
<?php
    }
    
    public function ProcessAjax() {
        if (parent::ProcessAjax()) {
            return;
        }
        ?>
        <respxml>
            <?php if ($_POST['type'] == 'neworder'): ?>
                <?php $this->i_oOrderForm = new OrderForm($this); ?>
                <formxml><?= $this->i_oOrderForm->BuildXML()?></formxml>
            <?php elseif ($_POST['type'] == 'editorder'): ?>
                <?php $this->i_oOrderForm = new OrderForm($this, intval($_POST['pk'])); ?>
                <?php if (!$this->i_oOrderForm->i_bLoadSuccess): ?>
                    <?php $this->AddAlert('red', 'Selhalo načítání objednávky.'); ?>
                    chyba
                <?php else: ?>
                    <formxml>
                        <?= $this->i_oOrderForm->BuildXML() ?>
                    </formxml>
                <?php endif; ?>
            <?php elseif ($_POST['type'] == 'closeform'): ?>
                <?php $this->i_oOrderForm = null; ?>
                <result>success</result>
            <?php elseif ($_POST['type'] = 'flformreq'): ?>
                <?= $this->i_oOrderForm->ProcessAjax() ?>
                <?php
                if ($this->i_oOrderForm->i_bToDelete) {
                    $this->i_oOrderForm = null;
                }
                ?>
            <?php endif; ?>
        </respxml>
        <?php
    }
}
