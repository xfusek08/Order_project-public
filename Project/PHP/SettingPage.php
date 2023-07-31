<?php
class SettingPage extends Page
{
    private $i_oSetting;
    
    public function __construct() {
        parent::__construct();
        $this->i_oSetting = new Setting(1);
        if (!$this->i_oSetting->i_bLoad_Success) {
            $this->AddAlert('red', 'Nastaveni se nepodařilo načíst - chyba databáze.');
        }
    }
    
    public function BuildPage() {
        parent::BuildPage();
?>
        <div class="settingpage">
            <div class="cap">Nastavení:</div>
            <form method="post">
                <input type="hidden" name="pagepost" />
                <input type="hidden" name="yearnum" />
                <table>
                    <tr>
                        <td>Číslo poslední objednávky:</td>
                        <td>
                            <input type="text" name="orset_cisloobj" value="<?php echo $this->i_oSetting->GetColumnByName('orset_cisloobj')->GetValue(); ?>" />
                        </td>
                        <td>(Lze měnit. Roste s každou objednávkou.)
                        <td>
                    </tr>
                    <tr>
                        <td>Rok objednávky:</td>
                        <td>
                            <input type="text" name="orset_cisloobjrok" value="<?php echo $this->i_oSetting->GetColumnByName('orset_cisloobjrok')->GetValue(); ?>" />
                        </td>
                        <td>(Nutno ručně aktualizovat)
                        <td>
                    </tr>
                </table>
                <input type="submit" name="c_submit" value="uložit" />
            </form>
            <div class="cap">Importovat objednávky:</div>
            <form method="post">
                <input type="hidden" name="pagepost" />
                <input type="hidden" name="dataimport" />
                <table>
                    <tr>
                        <td>Cesta k xml souboru s objednavkami:</td>
                        <td>
                            <input type="text" name="orderxmlfile" value="" />
                        </td>
                    </tr>
                </table>
                <input type="submit" name="c_submit" value="uložit" />
            </form>
            <?php
            if (isset($_SESSION['importsummary']))
                echo '<textarea readonly style="width: 100ch; height: 50ch">' . $_SESSION['importsummary'] . "</textarea>";
            ?>
        </div>
<?php
    }
    
    public function ProcessPost() {
        if (isset($_POST['yearnum'])) {
            if (isset($_POST['c_submit'])) {
                $this->i_oSetting->LoadFromPostData();
                if ($this->i_oSetting->SaveToDB(false)) {
                    $this->AddAlert('green', 'Uloženo.');
                } else {
                    $this->AddAlert('red', 'Během ukládání nastala chyba.');
                }
            }
        }
        
        if (isset($_POST['dataimport'])) {
            $v_sXmlFilePath = "";
            if (!isset($_POST['orderxmlfile'])) {
                $this->AddAlert('red', 'Cesta k souboru musí být zadána.');
            } else if ($_POST['orderxmlfile'] == "") {
                $this->AddAlert('red', 'Cesta k souboru musí být zadána.');
            } else {
                $v_sXmlFilePath = $_POST['orderxmlfile'];
                $v_sImportsummary = "";
                if (!$this->OrderXMLImport($v_sXmlFilePath, $v_sImportsummary)) {
                    $this->AddAlert('red', 'Import neproběhl v pořádku.');
                } else {
                    $this->AddAlert('green', 'Import proběhl v pořádku.');
                }
                $_SESSION['importsummary'] = $v_sImportsummary;
            }
        }
    }
    
    public function OrderXMLImport($a_sXmlFlePath, &$a_sImportsummary) {
        Logging::WriteLog(LogType::Announcement, 'OrderXMLImport() ...');
        
        $time_start = microtime(true);
        $v_bError = false;
        $v_sErrorMessage = "";
        $v_sContent = "";
        $v_iOrderSuccessCount = 0;
        $v_iOrderErrorCount = 0;
        
        try {
            if (!file_exists($a_sXmlFlePath)) {
                $v_bError = true;
                $v_sErrorMessage = "Source file not found.";
                return false;
            }
            
            $v_oXML = simplexml_load_string(file_get_contents($a_sXmlFlePath));
            
            if ($v_oXML == false) {
                $v_bError = true;
                $v_sErrorMessage = 'Invalid xml' . PHP_EOL;
                return false;
            }
            
            foreach ($v_oXML->order as $order) {
                $v_sResDesc = '';
                if ($this->ImportOneOrderFromElement($order, $v_sResDesc)) {
                    $v_iOrderSuccessCount++;
                } else {
                    $v_iOrderErrorCount++;
                }
                $v_sContent .= 'Order: ' . $order['number'] . ' ' . $order['year'] . ' - ' . $order['customer'] . ' - ' . $v_sResDesc . PHP_EOL;
            }
        } catch (Exception $e) {
            Logging::WriteLog(LogType::Error, 'Raised exception: ' . $e->getMessage());
            $v_bError = true;
            $v_sErrorMessage .= 'Raised exception: ' . $e->getMessage();
        } finally {
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            
            Logging::WriteLog(LogType::Announcement, 'OrderXMLImport() Ended.');
            
            $a_sImportsummary = 'Import summary:' . PHP_EOL;
            $a_sImportsummary .= 'Processing file: "' . $a_sXmlFlePath . '"' . PHP_EOL;
            $a_sImportsummary .= "Start time: " . date('d.m.y H:i:s', $time_start)  . PHP_EOL;
            $a_sImportsummary .= "End time: " . date('d.m.y H:i:s', $time_end)  . PHP_EOL;
            $a_sImportsummary .= "Total elapsed: " . date('i:s:u', $time)  . PHP_EOL . PHP_EOL;
            if ($v_bError) {
                $a_sImportsummary .= "Ended with error:" . PHP_EOL;
                $a_sImportsummary .= $v_sErrorMessage . PHP_EOL;
            } else {
                $a_sImportsummary .= "Import succeeded:" . PHP_EOL;
                $a_sImportsummary .= $v_sContent . PHP_EOL;
            }
        }
        return true;
    }
    public function ImportOneOrderFromElement($a_oXmlOrder, &$a_sResDesc) {
        $v_bSuccess = true;
        $SQL = 'select orcust_pk from or_customer where orcust_ident = ?';
        $fields = null;
        
        if (!MyDatabase::RunQuery($fields, $SQL, false, (string)$a_oXmlOrder['customer'])) {
            $a_sResDesc = 'error';
            return false;
        }
        
        if (count($fields) === 0) {
            //echo 'new customer : ' . $a_oXmlOrder['customer'] . '<br/>';
            $customer = new Customer();
            $customer->GetColumnByName('orcust_ident')->SetValueFromString((string)$a_oXmlOrder['customer']);
            $customer->GetColumnByName('orcust_telefon')->SetValueFromString('----------');
            if (!$customer->SaveToDB(false)) {
                $a_sResDesc = 'error';
                return false;
            }
        }
        
        $v_oOrderForm = new OrderForm();
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_cisloobj')->SetValueFromString((string)$a_oXmlOrder['number']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_cisloobjrok')->SetValueFromString((string)$a_oXmlOrder['year']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_datum')->SetValueFromString((string)$a_oXmlOrder['date']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_zakaznikident')->SetValueFromString((string)$a_oXmlOrder['customer']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_prijem')->SetValueFromString((string)$a_oXmlOrder['prijem']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_vydej')->SetValueFromString((string)$a_oXmlOrder['vydej']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_doprfirma')->SetValueFromString((string)$a_oXmlOrder['doprnazev']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_raal')->SetValueFromString((string)$a_oXmlOrder['doprraal']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_duedate')->SetValueFromString((string)$a_oXmlOrder['splatdate']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_factprij')->SetValueFromString((string)$a_oXmlOrder['prijfac']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_factvyd')->SetValueFromString((string)$a_oXmlOrder['vydfact']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_isstorno')->SetValueFromString((string)$a_oXmlOrder['storno']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_bokempozn')->SetValueFromString((string)$a_oXmlOrder['bokem']);
        $v_oOrderForm->i_oTransports[0]->GetColumnByName('oronv_vaha')->SetValueFromString((string)$a_oXmlOrder->transport['hmotnost']);
        $v_oOrderForm->i_oTransports[0]->GetColumnByName('oronv_zbozipopis')->SetValueFromString((string)$a_oXmlOrder->transport['zbozi']);
        $v_oOrderForm->i_oTransports[0]->i_oNakladka->GetColumnByName('orspt_psc')->SetValueFromString(
            (string)$a_oXmlOrder->transport->nakl['psc']
        );
        $v_oOrderForm->i_oTransports[0]->i_oNakladka->GetColumnByName('orspt_stat')->SetValueFromString(
            (string)$a_oXmlOrder->transport->nakl['stat']
        );
        $v_oOrderForm->i_oTransports[0]->i_oNakladka->GetColumnByName('orspt_mesto')->SetValueFromString(
            (string)$a_oXmlOrder->transport->nakl['mesto']
        );
        $v_oOrderForm->i_oTransports[0]->i_oVykladka->GetColumnByName('orspt_psc')->SetValueFromString(
            (string)$a_oXmlOrder->transport->vykl['psc']
        );
        $v_oOrderForm->i_oTransports[0]->i_oVykladka->GetColumnByName('orspt_stat')->SetValueFromString(
            (string)$a_oXmlOrder->transport->vykl['stat']
        );
        $v_oOrderForm->i_oTransports[0]->i_oVykladka->GetColumnByName('orspt_mesto')->SetValueFromString(
            (string)$a_oXmlOrder->transport->vykl['mesto']
        );
        $v_oOrderForm->i_oTransports[0]->i_oVykladka->GetColumnByName('orspt_date')->SetValueFromString(
            (string)$a_oXmlOrder->transport->vykl['date']
        );
        
        // pseudo hodnoty
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_doprjmeno')->SetValueFromString((string)$a_oXmlOrder['doprnazev']);
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_doprtel')->SetValueFromString('--------');
        $v_oOrderForm->i_oOrder->GetColumnByName('oror_isslozeno')->SetValueFromString('1');
        $v_oOrderForm->i_oTransports[0]->i_oNakladka->GetColumnByName('orspt_firma')->SetValueFromString('---------');
        $v_oOrderForm->i_oTransports[0]->i_oVykladka->GetColumnByName('orspt_firma')->SetValueFromString('---------');
        
        Logging::WriteLog(LogType::Announcement, 'Saving order: ' . $v_oOrderForm->i_oOrder->GetColumnByName('oror_cisloobj')->GetValueAsString());
        $validData = $v_oOrderForm->i_oOrder->IsDataValid();
        for ($i = 0; $i < count($v_oOrderForm->i_oTransports); $i++) {
            $v_oOrderForm->i_oTransports[$i]->LoadFromPostData();
            $validData = $validData && $v_oOrderForm->i_oTransports[$i]->IsDataValid();
        }
        if ($validData) {
            $saveToDBResult = $v_oOrderForm->SaveFullOrderToDB();
            if ($saveToDBResult == SaveToDBResult::OK) {
                $a_sResDesc = 'ok';
                $v_bSuccess = true;
            } else if ($saveToDBResult == SaveToDBResult::InvalidData) {
                $a_sResDesc = 'invalid data 2 ';
                $v_bSuccess = false;
            } else {
                $a_sResDesc = 'error 2';
                $v_bSuccess = false;
            }
        } else {
            $a_sResDesc = 'invalid data';
            $v_bSuccess = false;
        }
        if (!$v_bSuccess) {
            //$a_sResDesc .= PHP_EOL . $v_oOrderForm->GetInvalidDataXML();
        }
        return $v_bSuccess;
    }
}
