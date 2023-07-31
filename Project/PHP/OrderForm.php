<?php

class OrderForm
{
    public $i_oTransports;
    public $i_oOrder;
    public $i_bToDelete;
    
    public $i_bLoadSuccess;
    private $i_oParentPage;
    private $i_iTransportCounter;
    
    public function __construct($a_oParent = null, $a_iPK = 0) {
        $this->i_oParentPage = $a_oParent;
        $this->i_bLoadSuccess = false;
        $this->i_bToDelete = false;
        $this->i_oTransports = array();
        $this->i_iTransportCounter = 0;
        if ($a_iPK === 0) {
            $this->i_oOrder = new Order();
            $this->i_oOrder->GetColumnByName('oror_cisloobj')->SetValue($this->GetNewOrdNumber());
            $this->i_oOrder->GetColumnByName('oror_cisloobjrok')->SetValue($this->GetOrdYear());
            $this->i_oTransports[] = new Transport(0, 'tr_' . $this->i_iTransportCounter);
            $this->i_bLoadSuccess = true;
        } else {
            $this->i_oOrder = new Order($a_iPK);
            $this->i_bLoadSuccess = $this->i_oOrder->i_bLoad_Success;
            if ($this->i_bLoadSuccess) {
                $this->i_bLoadSuccess = $this->LoadTransports();
            }
        }
    }
    
    public function BuildXML(): string {
        ob_start();
?>
    <actions>
        <action ident="save" desc="Uložit" img="images/Save.png"/>
        <action ident="export" desc="Uložit a exportovat" img="images/Export.png"/>
        <action ident="delete" desc="Vymazat" img="images/Delete.png"/>
    </actions>
    <header>
        <div class="caption">
            <span>OBJEDNÁVKA PŘEPRAVY</span>
            č.
            <?= $this->i_oOrder->GetColumnByName('oror_cisloobj')->GetValueAsString() ?>
            /
            <?= $this->i_oOrder->GetColumnByName('oror_cisloobjrok')->GetValueAsString(false) ?>
            -
            <?= $this->i_oOrder->i_iPK > 0 ? 'Úprava' : 'Nová' ?>
        </div>
        <!-- <div class="firma-popis">
            <div>Zdeněk Mráček AUTODOPRAVA</div>
            <div>Vápenická 2368/24,  591 01 Žďár nad  Sázavou </div>
            <div>Tel. +420 731 19 77 11 </div>
            <div>IČO: 75613107, DIČ:CZ7410134776</div>
            <div>Zápis do ŽR MěÚ Žďár nad Sázavou, MU/OŽ/1525</div>
            <div>www.mracekdoprava.cz</div>
            <div>RAAL: B3W</div>
        </div>.
        <div class="firma-logo"><img src="images/logo2.png" /></div> -->
    </header>
    
    <block>
        <html>
            <div class="blockform" style="width: 98%;">
                <form method="post">
                    <div class="caption">Zákazník: </div>
                    <table class="inputtable">
                        <td>
                            <input
                                class="datalist"
                                autocomplete="off"
                                datareq="getcustomers"
                                style="font-size: 16px;font-weight: bold;padding: 5px;width: calc(100% - 6px)"
                                type="text"
                                placeholder="Zkratka"
                                name="oror_zakaznikident"
                                value="<?= $this->i_oOrder->GetColumnByName('oror_zakaznikident')->GetValueAsString() ?>"
                                maxlength="15"
                            />
                        </td>
                    </table>
                    <div class="caption">Základní údaje:</div>
                    <table class="inputtable">
                        <tr>
                            <td>Datum objednávky:</td>
                            <td style="width: 100%;">
                                <input
                                    class="datepicker"
                                    type="text"
                                    name="oror_datum"
                                    style="width: 100px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_datum')->GetValueAsString() ?>"
                                    maxlength="15"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Prijem:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_prijem"
                                    style="width: 100px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_prijem')->GetValueAsString() ?>"
                                /> CZK
                            </td>
                        </tr>
                        <tr>
                            <td>Výdej:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_vydej"
                                    style="width: 100px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_vydej')->GetValueAsString() ?>"
                                /> CZK
                            </td>
                        </tr>
                        <tr>
                            <td>Sml.cena (text):</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_smlcenatext"
                                    maxlength="40"
                                    style="width: 150px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_smlcenatext')->GetValueAsString() ?>"
                                /> +DPH (ALL-IN)
                            </td>
                        </tr>
                        <tr>
                            <td>Bokem komu:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_bokemkdo"
                                    maxlength="3"
                                    style="width: 30px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_bokemkdo')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Bokem kolik:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_bokemcastka"
                                    style="width: 100px;"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_bokemcastka')->GetValueAsString() ?>"
                                /> CZK
                            </td>
                        </tr>
                        <tr>
                            <td>Bokem pozn.:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_bokempozn"
                                    maxlength="40"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_bokempozn')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Složeno:</td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="oror_isslozeno"
                                    <?= $this->i_oOrder->GetColumnByName('oror_isslozeno')->GetValue() ? ' checked="checked" ' : '' ?>
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Storno:</td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="oror_isstorno"
                                    <?= $this->i_oOrder->GetColumnByName('oror_isstorno')->GetValue() ? ' checked="checked" ' : '' ?>
                                />
                            </td>
                        </tr>
                    </table>
                    <div class="caption">Fakturace: </div>
                    <table class="inputtable">
                        <!-- <tr><td colspan="2"><div class="caption"></div></td></tr> -->
                        <tr>
                            <td>Datum splatnosti:</td>
                            <td style="width: 100%;">
                                <input
                                        class="datepicker"
                                        type="text"
                                        name="oror_duedate"
                                        style="width: 100px;"
                                        value="<?= $this->i_oOrder->GetColumnByName('oror_duedate')->GetValueAsString() ?>"
                                        maxlength="15"
                                    />
                            </td>
                        </tr>
                        <tr>
                            <td>Vydaná faktura:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_factvyd"
                                    maxlength="20"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_factvyd')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>Přijatá faktura:</td>
                            <td>
                                <input
                                    type="text"
                                    name="oror_factprij"
                                    maxlength="20"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_factprij')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </html>
    </block>
    
    <block>
        <html>
            <div class="blockform" style="width: 98%;">
                <form method="post">
                    <div class="caption">Dopravce:</div>
                    <table class="inputtable">
                        <tr>
                            <td>
                                <input
                                    class="datalist"
                                    autocomplete="off"
                                    datareq="searchdeliverers"
                                    type="text"
                                    placeholder="Hledat"
                                    name="c_none"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input
                                    type="text"
                                    name="oror_doprfirma"
                                    placeholder="Název firmy"
                                    maxlength="100"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_doprfirma')->GetValueAsString() ?>"
                            />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input
                                    type="text"
                                    name="oror_doprulice"
                                    placeholder="Ulice"
                                    maxlength="100"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_doprulice')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input
                                    class="uppercase"
                                    style="width: 40px;"
                                    type="text"
                                    name="oror_doprstat"
                                    placeholder="Stát"
                                    maxlength="3"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_doprstat')->GetValueAsString() ?>"
                                /> -
                                <input
                                    style="width: 70px;"
                                    type="text"
                                    name="oror_doprpsc"
                                    placeholder="PSČ"
                                    maxlength="10"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_doprpsc')->GetValueAsString() ?>"
                                />
                                <input
                                    style="margin-left: 3px; width: calc(100% - 135px)"
                                    type="text"
                                    name="oror_doprmesto"
                                    placeholder="Město"
                                    maxlength="100"
                                    value="<?= $this->i_oOrder->GetColumnByName('oror_doprmesto')->GetValueAsString() ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="margin-top: 4px;">
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>IČ: </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            name="oror_dopric"
                                                            maxlength="10"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_dopric')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                    <td style="padding-left: 20px">DIČ: </td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            name="oror_doprdic"
                                                            maxlength="12"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_doprdic')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                </tr>
                                                <tr><td style="height: 8px;"></td></tr>
                                                <tr>
                                                    <td>SPZ:</td>
                                                    <td>
                                                        <input
                                                            class="uppercase"
                                                            type="text"
                                                            name="oror_doprspz"
                                                            maxlength="15"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_doprspz')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                    <td style="padding-left: 20px">Tel:</td>
                                                    <td>
                                                        <input
                                                            class="telnumber"
                                                            type="text"
                                                            name="oror_doprtel"
                                                            maxlength="20"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_doprtel')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>RAAL:</td>
                                                    <td>
                                                        <input
                                                            class="uppercase"
                                                            type="text"
                                                            name="oror_raal"
                                                            maxlength="3"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_raal')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                    <td style="padding-left: 20px">Kont.:</td>
                                                    <td>
                                                        <input
                                                            type="text"
                                                            name="oror_doprjmeno"
                                                            maxlength="100"
                                                            value="<?= $this->i_oOrder->GetColumnByName('oror_doprjmeno')->GetValueAsString() ?>"
                                                        />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </html>
    </block>
    
    <block>
        <html>
            <div class="caption" style="font-size: 15px; padding: 3px;">
                Přepravy:
            </div>
            <hr/>
        </html>
    </block>
    
    <?php for ($i = 0; $i < count($this->i_oTransports); $i++): ?>
        <block>
            <html>
                <?= $this->i_oTransports[$i]->BuildTransportOrderForm($i > 0) ?>
                <hr/>
            </html>
        </block>
    <?php endfor; ?>
    
    <block>
        <html>
            <div class="blockform" style="width: 98%; text-align: center;">
                <button class="addtransport">Přidat přepravu</button>
            </div>
        </html>
    </block>
    
    <block>
        <html>
            <div class="blockform" style="width: 98%;">
                <form method="post">
                    <div class="caption">Další informace:</div>
                    <table class="inputtable">
                        <td>
                            <input
                                type="text"
                                name="oror_dalsiinfo"
                                maxlength="100"
                                value="<?= $this->i_oOrder->GetColumnByName('oror_dalsiinfo')->GetValueAsString() ?>"
                            />
                        </td>
                    </table>
                </form>
            </div>
        </html>
    </block>
    
<?php
        return ob_get_clean();
    }
    
    public function ProcessAjax() {
        $res = '';
        if ($_POST['formtype'] == 'datalist') {
            if ($_POST['input'] == 'getcustomers') {
                $res = $this->GetCustomerIDListXML($_POST['value']);
            } elseif ($_POST['input'] == 'searchdeliverers') {
                $res = $this->GetDeliverersDataListXML($_POST['value']);
            } elseif ($_POST['input'] == 'searchnaklspot') {
                $res = $this->GetCustAddrDatalistXML($_POST['value'], 'nakl');
            } elseif ($_POST['input'] == 'searchvyklspot') {
                $res = $this->GetCustAddrDatalistXML($_POST['value'], 'vykl');
            }
        } elseif ($_POST['formtype'] == 'getdeliverer') {
            $deliverer = new Deliverer(intval($_POST['pk']));
            if ($deliverer->i_bLoad_Success) {
                $res .= $deliverer->GetAsXML();
            } else {
                $res .= 'chyba';
                $this->i_oParentPage->AddAlert('red', 'Nepodařilo se načíst údaje o dodavateli.');
            }
        } elseif ($_POST['formtype'] == 'getcustaddress') {
            $v_oCustAddress = new CustomerAddress(intval($_POST['pk']));
            if ($v_oCustAddress->i_bLoad_Success) {
                $res .= $v_oCustAddress->GetAsXML();
            } else {
                $res .= 'chyba';
                $this->i_oParentPage->AddAlert('red', 'Nepodařilo se načíst údaje o skladu.');
            }
        } elseif ($_POST['formtype'] == 'submit') {
            $validData = true;
            $this->i_oOrder->LoadFromPostData();
            $validData = $this->i_oOrder->IsDataValid();
            
            for ($i = 0; $i < count($this->i_oTransports); $i++) {
                $this->i_oTransports[$i]->LoadFromPostData();
                $validData = $validData && $this->i_oTransports[$i]->IsDataValid();
            }
            
            if ($validData) {
                $saveToDBResult = $this->SaveFullOrderToDB();
                if ($saveToDBResult == SaveToDBResult::OK) {
                    $this->i_oParentPage->AddAlert('green', 'Objednávka uložena.');
                    $res .= 'ok';
                } elseif ($saveToDBResult == SaveToDBResult::InvalidData) {
                    $validData = false;
                } else {
                    $res .= 'chyba';
                    $this->i_oParentPage->AddAlert('red', 'Chyba databáze. Nepodařilo se uložit objednávku.');
                }
            }
            if (!$validData) {
                $this->i_oParentPage->AddAlert('red', 'Formulář obsahuje nevalidní data.');
                $res .= $this->GetInvalidDataXML();
            }
        } elseif ($_POST['formtype'] == 'export') {
            $xml = '';
            $xml .= '<fullorder>';
            $xml .= $this->i_oOrder->GetAsXML();
            for ($i = 0; $i < count($this->i_oTransports); $i++) {
                $xml .= $this->i_oTransports[$i]->GetAsXML();
            }
            $xml .= '</fullorder>';
            
            $FilePDF = FOPExport::RunFOP($xml, FOPExport::GetOrderTemplate());
            if ($FilePDF == '') {
                $res = 'chyba';
                $this->i_oParentPage->AddAlert('red', 'Selhalo vytváření PDF.');
            } else {
                $res =
                    '<downloadfile '.
                    'downloadname="' . $this->i_oOrder->GetColumnByName('oror_cisloobj')->GetValue() . '-' . $this->i_oOrder->GetColumnByName('oror_cisloobjrok')->GetValue() . '.pdf" '.
                    'sourcename="' . $FilePDF . '"/>';
            }
        } elseif ($_POST['formtype'] == 'addtransport') {
            $this->i_iTransportCounter++;
            $this->i_oTransports[] = new Transport(0, 'tr_' . $this->i_iTransportCounter);
            $res .= '<block><html>';
            $res .= $this->i_oTransports[count($this->i_oTransports) - 1]->BuildTransportOrderForm(true) . '<hr/>';
            $res .= '</html></block>';
        } elseif ($_POST['formtype'] == 'deltransport') {
            $v_iIndex = $this->GetTransportIndexByID($_POST['ident']);
            $isInDB = $this->i_oTransports[$v_iIndex]->i_iPK > 0;
            if ($v_iIndex == 0) {
                Logging::WriteLog(LogType::Error, 'OrderForm: DeleteTransport - transport not found');
                $this->i_oParentPage->AddAlert('red', 'Chyba - přeprava nebyla nalezena.');
                $res .= 'chyba';
            } else {
                if (!$this->DeleteTransport($v_iIndex)) {
                    Logging::WriteLog(LogType::Error, 'OrderForm: DeleteTransport - Transport delete failed.');
                    $this->i_oParentPage->AddAlert('red', 'Chyba - přepravu se nepodařilo vymazat.');
                    $res .= 'chyba';
                } else {
                    if ($isInDB) {
                        $this->i_oParentPage->AddAlert('Green', 'Přeprava vymazána z databáze.');
                    }
                    array_splice($this->i_oTransports, $v_iIndex, 1);
                    $res .= 'ok';
                }
            }
        } elseif ($_POST['formtype'] == 'delorder') {
            if ($this->i_oOrder->i_iPK > 0) {
                if (!$this->DeleteFromDB()) {
                    $this->i_oParentPage->AddAlert('Red', 'Objednávku se nepodařilo vymazat.');
                    $res .= 'chyba';
                } else {
                    $this->i_oParentPage->AddAlert('Green', 'Objednávka vymazána.');
                    $res .= 'ok';
                }
            }
        } elseif ($_POST['formtype'] == 'getcustbyident') {
            $SQL = 'select orcust_pk from or_customer where orcust_ident = ?';
            $fields = null;
            if (MyDatabase::RunQuery($fields, $SQL, false, $_POST['customerid'])) {
                $v_oCust = new Customer($fields[0][0]);
                if ($v_oCust->i_bLoad_Success) {
                    $res .= $v_oCust->GetAsXML();
                } else {
                    $res .= 'error';
                }
            } else {
                $res .= 'error';
            }
        }
        return $res;
    }
    
    public function SaveFullOrderToDB() {
        $success = true;
        $v_bInvalidData = false;
        $v_bNew = $this->i_oOrder->i_iPK === 0;
        try {
            MyDatabase::$PDO->beginTransaction();
            
            // zvednout objnum counter o 1
            if ($v_bNew) {
                $success = IncObjCount(true);
            }
            
            if ($success) {
                $success = $this->i_oOrder->SaveToDB(true);
            }
            
            if ($success) {
                for ($i = 0; $i < count($this->i_oTransports); $i++) {
                    $success = $this->i_oTransports[$i]->SaveFullToDB($this->i_oOrder->i_iPK, true);
                    if (!$success) {
                        $SaveToDBResult = $this->i_oTransports[$i]->i_eSaveToDBResult;
                        //$v_bInvalidData = $this->i_oTransports[$i]->i_eSaveToDBResult == SaveToDBResult::InvalidData;
                        $v_bInvalidData = $SaveToDBResult == SaveToDBResult::InvalidData;
                        Logging::WriteLog(
                            LogType::Error,
                            "Failed save order to DB failed on transport: " . $this->i_oTransports[$i]->i_sID .
                                " v_bInvalidData: " . BoolTo01Str($v_bInvalidData) .
                                " SaveToDBResult: " . $SaveToDBResult
                        );
                        break;
                    }
                }
            } else {
                $v_bInvalidData = $this->i_oOrder->i_eSaveToDBResult == SaveToDBResult::InvalidData;
            }
            
            if ($success) {
                $success = $this->UpdateOrCreateDeliverer(true);
            }
            
            // update nebo create zakaznika
            if ($success) {
                $saveToDBResult = $this->UpdateCustomer($v_bNew, true);
                $success = $saveToDBResult == SaveToDBResult::OK;
                if ($saveToDBResult == SaveToDBResult::InvalidData) {
                    $v_bInvalidData = true;
                } else if ($saveToDBResult == SaveToDBResult::Error) {
                    Logging::WriteLog(LogType::Error, 'SaveFullOrderToDB() - Error during customer update');
                }
            }
            
            if ($success) {
                MyDatabase::$PDO->commit();
            } else {
                Logging::WriteLog(LogType::Announcement, "RollBack");
                MyDatabase::$PDO->rollBack();
            }
        } catch (PDOException $e) {
            Logging::WriteLog(LogType::Error, $e->getMessage());
            Logging::WriteLog(LogType::Announcement, "RollBack");
            MyDatabase::$PDO->rollBack();
            $success = false;
        }
        
        if ($v_bNew && !$success) {
            $this->i_oOrder->i_iPK = 0;
            for ($i = 0; $i < count($this->i_oTransports); $i++) {
                $this->i_oTransports[$i]->i_iPK = 0;
            }
        }
        
        if ($v_bInvalidData)
            return SaveToDBResult::InvalidData;
        if ($success) {
            return SaveToDBResult::OK;
        }
        
        return SaveToDBResult::Error;
    }
    
    public function GetInvalidDataXML() {
        $res = '<invaliformddata>';
        $res .= '<order>' . $this->i_oOrder->GetInvalidDataXML() . '</order>';
        $res .= '<transports>';
        for ($i = 0; $i < count($this->i_oTransports); $i++) {
            $res .= $this->i_oTransports[$i]->GetInvalidDataXML();
        }
        $res .= '</transports>';
        $res .= '</invaliformddata>';
        return $res;
    }
    
    private function GetCustomerIDListXML($a_sTextFilter) {
        $v_sRes = '<customers>';
        $SQL = 'select first 20 orcust_pk, orcust_ident, orcust_color from or_customer ';
        if ($a_sTextFilter != '') {
            $SQL .= 'where upper(orcust_ident) like \'%\' || upper(?) || \'%\' ';
        }
        $SQL .= ' order by orcust_ident collate NC_UTF8_CZ';
        
        $fields = null;
        if (!MyDatabase::RunQuery($fields, $SQL, false, $a_sTextFilter)) {
            if ($this->i_oParentPage !== null) {
                $this->i_oParentPage->AddAlert('red', 'chyba databáze');
            }
            return 'error';
        }
        if (count($fields) > 0) {
            for ($i = 0; $i < count($fields); $i++) {
                $v_sRes .= '<item>';
                $v_sRes .= '<div class="customer" pk="' . $fields[$i]['ORCUST_PK'] . '">';
                $v_sRes .= '<div class="colorprev"><div style="background-color: ' . $fields[$i]['ORCUST_COLOR'] . '"></div></div>';
                $v_sRes .= '<div class="ident">' . $fields[$i]['ORCUST_IDENT'] . '</div>';
                $v_sRes .= '</div>';
                $v_sRes .= '</item>';
            }
        }
        
        $v_sRes .= '</customers>';
        return $v_sRes;
    }
    
    private function GetDeliverersDataListXML($a_sTextFilter) {
        $v_sRes = '<deliverers>';
        $SQL = "
            select first 20 *
            from (
                select
                    ordlv_pk,
                    ordlv_dic,
                    ordlv_firma,
                    ordlv_ic,
                    ordlv_mesto,
                    ordlv_raal,
                    ordlv_spz,
                    ordlv_ulice,
                    ordlv_blokace,
                    ordlv_jmeno,
                    ordlv_stat,
                    ordlv_psc,
                    ordlv_telnum,
                    ordlv_firma || ordlv_raal || ordlv_psc || ordlv_mesto || ordlv_dic || ordlv_ic || ordlv_ulice as textfil
                from
                    or_deliverer
            )";
        
        $v_aFilters = '';
        if ($a_sTextFilter != '') {
            $SQL .= 'where';
            $v_aFilters = explode(' ', $a_sTextFilter);
            for ($i = 0; $i < count($v_aFilters); $i++) {
                $SQL .= ' (upper(textfil) like \'%\' || upper(?) || \'%\')';
                if ($i + 1 < count($v_aFilters)) {
                    $SQL .= ' and';
                }
            }
        }
        $SQL .= ' order by ordlv_raal collate NC_UTF8_CZ';
        
        $fields = null;
        if (!MyDatabase::RunQuery($fields, $SQL, false, $v_aFilters)) {
            if ($this->i_oParentPage !== null) {
                $this->i_oParentPage->AddAlert('red', 'Chyba databáze.');
            }
            return 'error';
        }
        
        if (count($fields) > 0) {
            for ($i = 0; $i < count($fields); $i++) {
                $v_sRes .= '<item>';
                $v_sRes .= '<div class="datalistitem" pk="' . $fields[$i]['ORDLV_PK'] . '">';
                $v_sRes .= '<div style="font-size: 15px; font-weight: bold;';
                if ($fields[$i]['ORDLV_BLOKACE']) {
                    $v_sRes .= ' color: red;';
                }
                $v_sRes .= '">' . $fields[$i]['ORDLV_RAAL'] . '</div>';
                $v_sRes .= '<table>';
                $v_sRes .= '<tr><td colspan="4">' . $fields[$i]['ORDLV_FIRMA'] . '</td></tr>';
                $v_sRes .= '<tr><td colspan="4">' . $fields[$i]['ORDLV_ULICE'] . '</td></tr>';
                $v_sRes .= "
                    <tr>
                        <td>{$fields[$i]['ORDLV_STAT']}</td>
                        <td>-</td>
                        <td>{$fields[$i]['ORDLV_PSC']} | </td>
                        <td>{$fields[$i]['ORDLV_MESTO']}</td>
                    </tr>
                ";
                
                $v_sRes .= '</table>';
                if ($fields[$i]['ORDLV_BLOKACE']) {
                    $v_sRes .= '<div class="blockinfo">' . $fields[$i]['ORDLV_BLOKACE'] . '</div>';
                }
                $v_sRes .= '</div>';
                $v_sRes .= '</item>';
            }
        }
        
        $v_sRes .= '</deliverers>';
        return $v_sRes;
    }
    
    private function GetCustAddrDatalistXML($a_sTextFilter, $a_sSearchFor) {
        if (!isset($_POST['custident'])) {
            return 'nodata';
        }
        
        if ($_POST['custident'] == '') {
            return 'nodata';
        }
        
        $v_sRes = '<custaddress>';
        $SQL = "
            select first 20
                *
            from (
                select
                    orcadr_pk,
                    orcadr_firma,
                    orcadr_firma2,
                    orcadr_mesto,
                    orcadr_stat,
                    orcadr_ulice,
                    orcadr_psc,
                    orcadr_cas3,
                    orcadr_naklnum,
                    orcadr_vyklnum,
                    orcadr_firma || orcadr_firma2 || orcadr_mesto || orcadr_stat || orcadr_ulice || orcadr_psc as textfil
                from
                    or_custaddress
                where
                    orcadr_customer = (select orcust_pk from or_customer where orcust_ident = ?)
            )
        ";
        
        $v_aFilters = '';
        if ($a_sTextFilter != '') {
            $SQL .= 'where';
            $v_aFilters = explode(' ', $_POST['custident'] . ' ' . $a_sTextFilter);
            
            for ($i = 1; $i < count($v_aFilters); $i++) {
                $SQL .= ' (upper(textfil) like \'%\' || upper(?) || \'%\')';
                if ($i + 1 < count($v_aFilters)) {
                    $SQL .= ' and';
                }
            }
        } else {
            $v_aFilters = $_POST['custident'];
        }
        
        if ($a_sSearchFor == 'nakl'){
            $SQL .= ' order by orcadr_naklnum desc';
        } else if ($a_sSearchFor == 'vykl') {
            $SQL .= ' order by orcadr_vyklnum desc';
        }
        
        $fields = null;
        if (!MyDatabase::RunQuery($fields, $SQL, false, $v_aFilters)) {
            if ($this->i_oParentPage !== null) {
                $this->i_oParentPage->AddAlert('red', 'Chyba databáze.');
            }
            Logging::WriteLog(LogType::Announcement, '$v_aFilters: ' . print_r($v_aFilters, true));
            return 'error';
        }
        
        if (count($fields) > 0) {
            for ($i = 0; $i < count($fields); $i++) {
                ob_start();
                ?>
                    <item>
                        <div class="datalistitem" pk="<?= $fields[$i]['ORCADR_PK'] ?>">
                            <table>
                                <tr><td style="font-weight: bold" colspan="4"><?= $fields[$i]['ORCADR_FIRMA'] ?></td></tr>`
                                <tr><td colspan="4"><?= $fields[$i]['ORCADR_FIRMA2'] ?></td></tr>
                                <tr><td colspan="4"><?= $fields[$i]['ORCADR_ULICE'] ?></td></tr>
                                <tr>
                                    <td><?= $fields[$i]['ORCADR_STAT'] ?></td>
                                    <td>-</td>
                                    <td><?= $fields[$i]['ORCADR_PSC'] ?> | </td>
                                    <td><?= $fields[$i]['ORCADR_MESTO'] ?></td>
                                </tr>
                                <?php if ($a_sSearchFor == 'nakl'): ?>
                                    <div class="cornerinfo">Nakládek: <?= $fields[$i]['ORCADR_NAKLNUM'] ?></div>
                                <?php elseif ($a_sSearchFor == 'vykl'): ?>
                                    <div class="cornerinfo">Vykládek: <?= $fields[$i]['ORCADR_VYKLNUM'] ?></div>
                                <?php endif; ?>
                            </table>
                        </div>
                    </item>
                <?php
                $v_sRes .= ob_get_clean();
            }
        }
        
        $v_sRes .= '</custaddress>';
        return $v_sRes;
    }
    
    public function UpdateOrCreateDeliverer($ExternTransaction) {
        $SQL = 'select ordlv_pk from or_deliverer where ordlv_raal = ?';
        $fields = null;
        if (!MyDatabase::RunQuery(
            $fields,
            $SQL,
            $ExternTransaction,
            $this->i_oOrder->GetColumnByName('oror_raal')->GetValueAsString()
        )) {
            return false;
        }
        
        $v_oDeliverer = null;
        if (count($fields) > 0) {
            $v_oDeliverer = new Deliverer(intval($fields[0][0]), $ExternTransaction);
            if (!$v_oDeliverer->i_bLoad_Success) {
                return false;
            }
        } else {
            $v_oDeliverer = new Deliverer();
            $v_oDeliverer->GetColumnByName('ordlv_raal')->SetValue($this->i_oOrder->GetColumnByName('oror_raal')->GetValue());
        }
        
        $v_oDeliverer->GetColumnByName('ordlv_dic')->SetValue($this->i_oOrder->GetColumnByName('oror_doprdic')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_ic')->SetValue($this->i_oOrder->GetColumnByName('oror_dopric')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_firma')->SetValue($this->i_oOrder->GetColumnByName('oror_doprfirma')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_stat')->SetValue($this->i_oOrder->GetColumnByName('oror_doprstat')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_jmeno')->SetValue($this->i_oOrder->GetColumnByName('oror_doprjmeno')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_mesto')->SetValue($this->i_oOrder->GetColumnByName('oror_doprmesto')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_psc')->SetValue($this->i_oOrder->GetColumnByName('oror_doprpsc')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_spz')->SetValue($this->i_oOrder->GetColumnByName('oror_doprspz')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_telnum')->SetValue($this->i_oOrder->GetColumnByName('oror_doprtel')->GetValue());
        $v_oDeliverer->GetColumnByName('ordlv_ulice')->SetValue($this->i_oOrder->GetColumnByName('oror_doprulice')->GetValue());
        return $v_oDeliverer->SaveToDB($ExternTransaction);
    }
    
    public function UpdateCustomer($NewOrder, $ExternTransaction) {
        $SQL = 'select orcust_pk from or_customer where orcust_ident = ?';
        $fields = null;
        if (!MyDatabase::RunQuery(
            $fields,
            $SQL,
            $ExternTransaction,
            $this->i_oOrder->GetColumnByName('oror_zakaznikident')->GetValueAsString()
        )) {
            Logging::WriteLog(LogType::Error, 'UpdateCustomer() - Error during searching customer pk by ident.');
            return SaveToDBResult::Error;
        }
        
        if (count($fields) === 0) {
            $this->i_oOrder->GetColumnByName('oror_zakaznikident')->i_bValid = false;
            $this->i_oOrder->GetColumnByName('oror_zakaznikident')->i_sInvalidDataMsg = 'Zákazník neexistuje.';
            return SaveToDBResult::InvalidData;
        }
        
        $v_oCustomerPK = intval($fields[0][0]);
        
        for ($i = 0; $i < count($this->i_oTransports); $i++) {
            for ($n = 0; $n < 2; $n++) {
                $v_oSpot = null;
                
                if ($n === 0) {
                    $v_oSpot = $this->i_oTransports[$i]->i_oNakladka;
                } else if ($n === 1) {
                    $v_oSpot = $this->i_oTransports[$i]->i_oVykladka;
                }
                
                $SQL = "
                    select
                        orcadr_pk
                    from
                        or_custaddress
                    where
                        orcadr_customer  = ?
                        and orcadr_firma = ?
                        and orcadr_psc   = ?
                        and orcadr_mesto = ?
                ";
                
                $fields = null;
                $params = array();
                $params[] = $v_oCustomerPK;
                $params[] = $v_oSpot->GetColumnByName('orspt_firma')->GetValue();
                $params[] = $v_oSpot->GetColumnByName('orspt_psc')->GetValue();
                $params[] = $v_oSpot->GetColumnByName('orspt_mesto')->GetValue();
                
                if ($v_oSpot->GetColumnByName('orspt_firma2')->GetValue() !== null) {
                    $SQL .= ' and orcadr_firma2 = ?';
                    $params[] = $v_oSpot->GetColumnByName('orspt_firma2')->GetValue();
                }
                $SQL .= ';';
                
                if (!MyDatabase::RunQuery($fields, $SQL, $ExternTransaction, $params)) {
                    Logging::WriteLog(LogType::Error, 'UpdateCustomer() - Error during searching customer address pk by company name.');
                    return SaveToDBResult::Error;
                }
                
                $v_oCustAddr = null;
                if (count($fields) === 0) {
                    $v_oCustAddr = new CustomerAddress(0, $ExternTransaction);
                    $v_oCustAddr->GetColumnByName('orcadr_customer')->SetValue($v_oCustomerPK);
                    $v_oCustAddr->GetColumnByName('orcadr_firma')->SetValue($v_oSpot->GetColumnByName('orspt_firma')->GetValue());
                    $v_oCustAddr->GetColumnByName('orcadr_firma2')->SetValue($v_oSpot->GetColumnByName('orspt_firma2')->GetValue());
                } else {
                    $v_oCustAddr = new CustomerAddress(intval($fields[0][0]), $ExternTransaction);
                    if (!$v_oCustAddr->i_bLoad_Success) {
                        Logging::WriteLog(LogType::Error, 'UpdateCustomer() - Could not load customer address.');
                        return SaveToDBResult::Error;
                    }
                }
                
                $v_oCustAddr->GetColumnByName('orcadr_cas3')->SetValue($v_oSpot->GetColumnByName('orspt_cas3')->GetValue());
                $v_oCustAddr->GetColumnByName('orcadr_ulice')->SetValue($v_oSpot->GetColumnByName('orspt_ulice')->GetValue());
                $v_oCustAddr->GetColumnByName('orcadr_stat')->SetValue($v_oSpot->GetColumnByName('orspt_stat')->GetValue());
                $v_oCustAddr->GetColumnByName('orcadr_psc')->SetValue($v_oSpot->GetColumnByName('orspt_psc')->GetValue());
                $v_oCustAddr->GetColumnByName('orcadr_mesto')->SetValue($v_oSpot->GetColumnByName('orspt_mesto')->GetValue());
                
                if (($n === 0) && $NewOrder) { //nakl
                    $v_oCustAddr->GetColumnByName('orcadr_naklnum')->SetValue(
                        $v_oCustAddr->GetColumnByName('orcadr_naklnum')->GetValue() + 1
                    );
                } else if (($n === 1) && $NewOrder) { //vykl
                    $v_oCustAddr->GetColumnByName('orcadr_vyklnum')->SetValue(
                        $v_oCustAddr->GetColumnByName('orcadr_vyklnum')->GetValue() + 1
                    );
                }
                
                if (!$v_oCustAddr->SaveToDB($ExternTransaction)) {
                    Logging::WriteLog(LogType::Error, 'UpdateCustomer() - Could not save customer address.');
                    return SaveToDBResult::Error;
                }
            }
        }
        return SaveToDBResult::OK;
    }
    
    public function DeleteFromDB($ExternTransaction = false) {
        $success = true;
        try {
            if (!$ExternTransaction) {
                MyDatabase::$PDO->beginTransaction();
            }
            
            $success = $this->i_oOrder->DeleteFromDB(true);
            
            if ($success) {
                for ($i = 0; $i < count($this->i_oTransports); $i++) {
                    if (!$this->DeleteTransport($i, true)) {
                        Logging::WriteLog(LogType::Error, 'OrderForm->DeleteFromDB - Failed to Delete transport on index ' . $i);
                        $success = false;
                        break;
                    }
                }
            }
            
            if ($success) {
                if (!$ExternTransaction)
                    MyDatabase::$PDO->commit();
                return true;
            }
            
            if (!$ExternTransaction) {
                Logging::WriteLog(LogType::Announcement, "RollBack");
                MyDatabase::$PDO->rollBack();
            }
            return false;
        } catch (PDOException $e) {
            if (!$ExternTransaction) {
                Logging::WriteLog(LogType::Error, $e->getMessage());
                Logging::WriteLog(LogType::Announcement, "RollBack");
                MyDatabase::$PDO->rollBack();
            }
            return false;
        }
    }
    
    public function DeleteTransport($a_iIndex, $ExternTransaction = false) {
        try {
            if (!$ExternTransaction)
                MyDatabase::$PDO->beginTransaction();
            
            if ($this->i_oTransports[$a_iIndex]->DeleteFromDB(true)) {
                if (!$ExternTransaction)
                    MyDatabase::$PDO->commit();
                return true;
            }
            
            if (!$ExternTransaction) {
                Logging::WriteLog(LogType::Announcement, "RollBack");
                MyDatabase::$PDO->rollBack();
            }
            return false;
        } catch (PDOException $e) {
            if (!$ExternTransaction) {
                Logging::WriteLog(LogType::Error, $e->getMessage());
                Logging::WriteLog(LogType::Announcement, "RollBack");
                MyDatabase::$PDO->rollBack();
            }
            return false;
        }
    }
    
    private function GetNewOrdNumber() {
        $val = 0;
        $SQL = 'select orset_cisloobj from or_setup';
        MyDatabase::GetOneValue($val, $SQL);
        return intval($val) + 1;
    }
    
    private function GetOrdYear() {
        $val = 0;
        $SQL = 'select orset_cisloobjrok from or_setup';
        MyDatabase::GetOneValue($val, $SQL);
        return intval($val);
    }
    
    private function LoadTransports($ExternTransaction = false) {
        $SQL = 'select oronv_pk from or_objnaklvykl where oronv_obj = ?';
        $fields = null;
        if (!MyDatabase::RunQuery($fields, $SQL, $ExternTransaction, $this->i_oOrder->i_iPK)) {
            Logging::WriteLog(LogType::Error, 'LoadTransports() - could not search for transports.');
            return false;
        }
        if (count($fields) == 0) {
            Logging::WriteLog(LogType::Error, 'LoadTransports() - No transports found -> inconsistent data.');
            return false;
        }
        for ($i = 0; $i < count($fields); $i++) {
            $this->i_iTransportCounter++;
            $this->i_oTransports[] = new Transport(intval($fields[$i][0]), 'tr_' . $this->i_iTransportCounter, $ExternTransaction);
            if (!$this->i_oTransports[$i]->i_bLoad_Success) {
                Logging::WriteLog(
                    LogType::Error,
                    'LoadTransports() - Transports loading failed on index: ' . $i . ' pk=' . $fields[$i][0]
                );
                return false;
            }
        }
        return true;
    }
    
    private function GetTransportIndexByID($a_sID) {
        for ($i = 0; $i < count($this->i_oTransports); $i++) {
            if ($this->i_oTransports[$i]->i_sID === $a_sID)
                return $i;
        }
        return 0;
    }
}
