<?php
class DelivererPage extends Page
{
  private $i_oDeliverer; 
  private $i_bFocusRaal;
  public function __construct()
  {
    $this->i_oBrowser = new Browser('or_deliverer_v', 'ordlv_pk');
    $this->i_oBrowser->AddField(DataType::String, 'Raal', 'ordlv_raal')->i_iMaxlenght = 3;
    $v_oField = $this->i_oBrowser->AddField(DataType::String, 'Firma', 'ordlv_firma');
      $v_oField->i_sCss = 'font-weight: bold;';
      $v_oField->i_iOrderByIndex = 1;

    $this->i_oBrowser->AddField(DataType::String, 'Město', 'ordlv_mesto');
    $this->i_oBrowser->AddField(DataType::String, 'Dispečer', 'ordlv_jmeno')->i_sCss = 
      'font-weight: bold;';
    $this->i_oBrowser->AddField(DataType::String, 'Telefon', 'ordlv_telnum')->i_sCss = 
      'font-weight: bold;';

    $this->i_oBrowser->AddField(DataType::String, 'Poznámka', 'ordlv_pozn')->i_sCss = 
      'font-size: 11px;white-space: normal;';
    $this->i_oBrowser->AddField(DataType::String, 'Blokace', 'ordlv_blokace')->i_sCss = 
      'font-size: 11px;white-space: normal;';

    $this->i_oBrowser->AddField(DataType::Integer, 'Objednávky', 'ordercount');
    $this->i_oBrowser->AddField(DataType::Float, 'Obrat [CZK]', 'obrat');
    $this->i_oBrowser->AddField(DataType::Float, 'Zisk [CZK]','zisk');
    $this->i_oBrowser->AddField(DataType::Float, 'Koeficient [CZK]', 'koeficient');
    
    $this->i_bFocusRaal = true;
    $this->i_sFiles = 
      '<script type="text/javascript" src="jscripts/DelivererPage.js"></script>'.
      '<link rel="stylesheet" href="css/DelivererPage.css" type="text/css" media="screen" />';

    $this->NewDeliverer();
  }
  public function BuildPage()
  {
    parent::BuildPage();
    ?>
    <div class="cap">Přehled dopravců</div>
    <div class="browserconn" style="height: calc(100% - 280px)">
      <?php
      echo $this->i_oBrowser->BuildBrowserHTML(50,0);
      ?>
    </div>
    <div class="dbpageform" style="min-width: 900px;">
      <div class="cap">Detail dopravce:</div>
      <form method="post">
        <input type="hidden" name="pagepost" />
        <table class="formtab">
          <td>
            <div class="formgr"  style="height: 180px">
              <div class="cap">Firma:</div>
              <div class="conn">
                <table>
                  <tr>
                    <td style="font-weight: bold;">Raal:</td>
                    <td>
                      <input 
                        class="uppercase" style="font-weight: bold;" type="text" size="1" name="ordlv_raal"
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_raal')->GetValueAsString() ?>" 
                        maxlength="3" required <?php if ($this->i_bFocusRaal) { echo 'autofocus'; } ?> />
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: bold;">Název:</td>
                    <td>
                      <input 
                        style="font-weight: bold;" type="text" size="25" name="ordlv_firma" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_firma')->GetValueAsString() ?>" 
                        maxlength="100" required/>
                    </td>
                  </tr>
                  <tr>
                    <td>Ulice:</td>
                    <td>
                      <input 
                        type="text" size="25" name="ordlv_ulice" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_ulice')->GetValueAsString() ?>" 
                        maxlength="100"/>
                    </td>
                  </tr>
                  <tr>
                    <td>Město:</td>
                    <td>
                      <input class="uppercase" type="text" placeholder="Stát" size="1" name="ordlv_stat" style="width: 40px;" 
                             value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_stat')->GetValueAsString() ?>" 
                             maxlength="3"/> - 
                      <input type="text" placeholder="PSČ" size="5" name="ordlv_psc" style="width: 60px;" 
                             value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_psc')->GetValueAsString() ?>" 
                             maxlength="10"/>
                      <input type="text" size="15" name="ordlv_mesto" style="width: calc(100% - 128px)" 
                             value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_mesto')->GetValueAsString() ?>" 
                             maxlength="100"/>
                    </td>
                  </tr>
                  <tr>
                    <td>IČ:</td>
                    <td>
                      <input 
                        type="text" size="12" name="ordlv_ic" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_ic')->GetValueAsString() ?>" 
                        maxlength="10"/>
                    </td>
                  </tr>
                  <tr>
                    <td>DIČ:</td>
                    <td>
                      <input 
                        type="text" size="12" name="ordlv_dic" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_dic')->GetValueAsString() ?>" 
                        maxlength="12"/>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </td>
          <td>
            <div class="formgr">
              <div class="cap">Dispečer - kontakt:</div>
              <div class="conn">
                <table>
                  <tr>
                    <td style="font-weight: bold;">Jméno:</td>
                    <td>
                      <input 
                        style="font-weight: bold;" type="text" size="25" name="ordlv_jmeno" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_jmeno')->GetValueAsString() ?>" 
                        maxlength="100" required/>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: bold;">Telefon:</td>
                    <td>
                      <input 
                        class="telnumber" style="font-weight: bold;" type="text" size="25" name="ordlv_telnum" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_telnum')->GetValueAsString() ?>" 
                        maxlength="20" required/>
                    </td>
                  </tr>
                  <tr>
                    <td>E-mail:</td>
                    <td>
                      <input 
                        type="text" size="25" name="ordlv_email" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_email')->GetValueAsString() ?>" 
                        maxlength="40"/>
                    </td>
                  </tr>
                </table>
              </div>          
            </div>
            <div class="formgr dottedtop" style="height: 68px">
              <div class="cap">Statistiky:</div>
              <div class="conn">
                <table class="stattabl">
                  <tr>
                    <td>Objednávky:</td>
                    <td>Obrat:</td>
                    <td>Zisk:</td>
                    <td>Koeficient:</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="val objnum"><?php echo $this->i_oDeliverer->GetColumnByName('ordlv_objnum')->GetValueAsString() /*echo number_format(floatval($this->i_oDeliverer->i_iObjNum), 0, '', ' ')*/ ?></div>
                    </td>
                    <td>
                      <div class="val obratp"><?php echo $this->i_oDeliverer->GetColumnByName('ordlv_obrat')->GetValueAsString() /*echo number_format(floatval($this->i_oDeliverer->i_fObrat), 2, ',', ' ')*/ ?> CZK</div>
                    </td>
                    <td>
                      <div class="val zisk"><?php echo $this->i_oDeliverer->GetColumnByName('ordlv_zisk')->GetValueAsString() /*echo number_format(floatval($this->i_oDeliverer->i_fZisk), 2, ',', ' ')*/ ?> CZK</div>
                    </td>
                    <td>
                      <div class="val koeficient"><?php echo $this->i_oDeliverer->GetColumnByName('ordlv_koeficient')->GetValueAsString() /*echo number_format(floatval($this->i_oDeliverer->i_fKoeficient), 2, ',', ' ')*/ ?> CZK</div>
                    </td>
                  </tr>
                </table>
              </div>
            </div> 
          </td>
          <td>
            <div class="formgr">
              <div class="cap">Další info:</div>
              <div class="conn">
                <table>
                  <tr>
                    <td>SPZ:</td>
                    <td>
                      <input 
                        class="uppercase" type="text" size="12" name="ordlv_spz" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_spz')->GetValueAsString() ?>" 
                        maxlength="15"/>
                    </td>
                  </tr>
                  <tr>
                    <td>Poznámka:</td>
                    <td>
                      <input 
                        type="text" size="25" name="ordlv_pozn" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_pozn')->GetValueAsString() ?>" 
                        maxlength="50"/>
                    </td>
                  </tr>
                  <tr>
                    <td>Blokace:</td>
                    <td>
                      <input 
                        style="font-weight: bold; color: rgb(255,0,0);" type="text" size="25" name="ordlv_blokace" 
                        value="<?php echo $this->i_oDeliverer->GetColumnByName('ordlv_blokace')->GetValueAsString() ?>" 
                        maxlength="40"/>
                    </td>
                  </tr>
                </table>
              </div>          
            </div>        
          </td>
        </table>
        <div class="actions">
          <button type="submit" name="c_submit" value="Uložit" <?php if ($this->i_oDeliverer->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Save.png" /><span>Uložit</span></button>
          <button type="submit" name="c_delete" value="Odstranit" <?php if ($this->i_oDeliverer->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Delete.png" /><span>Odstranit</span></button>
        </div>
        <div tabindex="0" class="focusroll" style="height: 0;"></div>
      </form>
    </div>
    <?php
  }
  public function ProccessPost()
  {
    if (isset($_POST['c_delete']))
    {
      if($this->i_oDeliverer->DeleteFromDB(false))
      {
        if (isset($_POST['nextpk']))
          $this->NewDeliverer(intval($_POST['nextpk']));        
        else
          $this->NewDeliverer();   
        $this->AddAlert('green', 'Vymazáno.'); 
      }
    }
    else if (isset ($_POST['c_submit']))
    {
      $this->i_oDeliverer->i_iPK = 0;
      $this->i_oDeliverer->LoadFromPostData();
      if (!$this->i_oDeliverer->IsDataValid())
      {
        $this->AddAlert('red', 'Formulář obsahuje nevalidní data.');        
        return;
      }
      
      $fields = null;
      if (!MyDatabase::RunQuery(
        $fields, 
        'select ordlv_pk from or_deliverer where ordlv_raal = ?', 
        false, 
        $this->i_oDeliverer->GetColumnByName('ordlv_raal')->GetValue())) // zadany rall z formulare
      {
        $this->AddAlert('red', 'Běhěm ukládání dopravce nastala chyba.');        
      }
      else
      {
        if (count($fields) > 0)
        {
          // pokud existuje raal upravujeme dopravce
          $this->i_oDeliverer->i_iPK = intval($fields[0]['ORDLV_PK']);
        }
        if ($this->i_oDeliverer->SaveToDB(false))
        {
          if ($this->NewDeliverer($this->i_oDeliverer->i_iPK))
            $this->AddAlert('green', 'Uloženo.');
        }
        else
          $this->AddAlert('red', 'Běhěm ukládání dopravce nastala chyba.');
      }
    }
    $this->i_bFocusRaal = false;
  }
  public function ProcessGet()
  {
    if (isset($_GET['Clear']))
    {
      $this->NewDeliverer();
      $this->i_oBrowser->i_iScrollTop = 0;
      $this->i_bFocusRaal = true;
    }
    else if(isset ($_GET['dopr']))
    {
      $this->NewDeliverer(intval($_GET['dopr']));      
      $this->i_bFocusRaal = false;
    }
    if (isset($_GET['brscroll']))
      $this->i_oBrowser->i_iScrollTop = intval($_GET['brscroll']);
  }
  public function ProcessAjax()
  {
    if (parent::ProcessAjax())
      return;
    
    $res = '<respxml>';
    if ($_POST['type'] == 'raalins')
    {
      $SQL = 'select ordlv_pk from or_deliverer where ordlv_raal = ?';
      $fields = null;
      if (MyDatabase::RunQuery($fields, $SQL, false, $_POST['raal']))
      {
        if (count($fields) > 0 && intval($fields[0][0]))
        {
          if ($this->NewDeliverer(intval($fields[0][0])))
            $res .= $this->i_oDeliverer->GetAsXML();
        }
      }
      else
        $this->AddAlert ('red', 'Chyba při ověřování raal.');
    }
    else if ($_POST['type'] == 'dorpsel')
    {
      $this->NewDeliverer(intval($_POST['dopr']));
      $res .= $this->i_oDeliverer->GetAsXML();
      if (isset($_POST['brscroll']))
        $this->i_oBrowser->i_iScrollTop = intval($_POST['brscroll']);
    }
    else if ($_POST['type'] == 'brscroll')
    {
      $this->i_oBrowser->i_iScrollTop = intval($_POST['scroll']);
    }
    $res .= '</respxml>';
    echo $res;
  }
  private function NewDeliverer($a_iPK = 0)
  {
    if ($a_iPK > 0)
    {
      $Deliverer = new Deliverer($a_iPK);
      if ($Deliverer->i_bLoad_Success)
        $this->i_oDeliverer = $Deliverer;
      else 
      {
        $this->AddAlert('red', 'Nastala chyba při změně dopravce. Dobravce se nepodařilo správně načíst.');
        // $this->i_oBrowser->i_iSelectedPK = 0;
        return false;
      }
    }
    else
      $this->i_oDeliverer = new Deliverer();
    
    $this->i_oBrowser->i_iSelectedPK = $this->i_oDeliverer->i_iPK;
    return true;
  }
}
