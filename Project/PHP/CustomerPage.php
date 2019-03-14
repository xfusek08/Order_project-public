<?php

class CustomerPage extends Page
{
  public $i_oCustomer;
  public $i_oCustomerAddress;
  public $i_bFocusCustomer;
  public $i_bFocusAdress;
  
  public function __construct()
  {
    $this->i_oBrowser = new Browser('or_custaddress', 'orcadr_pk');
    $this->i_oBrowser->i_sOuterWhere = '1 = 2'; // nema smysl cokoliv vypisovat, pokud neni vybran zakaznik


    $this->i_oBrowser->AddField(DataType::String, 'Stát', 'orcadr_stat')->i_iColWidth = 40;
    $this->i_oBrowser->AddField(DataType::String, 'PSČ', 'orcadr_psc')->i_iColWidth = 40;
    $this->i_oBrowser->AddField(DataType::String, 'Město', 'orcadr_mesto')->i_iOrderByIndex = 1;
    
    $this->i_oBrowser->AddField(DataType::String, 'Firma', 'orcadr_firma');
    
    $this->i_oBrowser->AddField(DataType::Integer, 'Nakládky', 'orcadr_naklnum')->i_sCss = 
      'font-size: 11px;white-space: normal;';
    $this->i_oBrowser->AddField(DataType::Integer, 'Vykládky', 'orcadr_vyklnum')->i_sCss = 
      'font-size: 11px;white-space: normal;';
    $this->i_oBrowser->AddField(DataType::Integer, 'Celkem', 
      '(COALESCE (orcadr_vyklnum, 0) + COALESCE (orcadr_naklnum, 0))')->i_sCss = 
      'font-size: 11px;white-space: normal;';
    
    $v_oActField = $this->i_oBrowser->AddField(DataType::Integer, 'customer', 'orcadr_customer');
    $v_oActField->i_bAttrHiddenValue = true;
    $v_oActField->i_sColIndent = 'fkcustomer';

    $this->i_bFocusAdress = true;
    $this->i_bFocusCustomer = false;
    $this->i_sFiles = 
      '<script type="text/javascript" src="jscripts/CustomerPage.js"></script>'.
      '<link rel="stylesheet" href="css/CustomerPage.css" type="text/css" media="screen" />';

    $this->ChangeCustomer();
  }
  public function BuildPage()
  {
    parent::BuildPage();
    ?>
    <div class="cap">Přehled zákazníků</div>
    <div class="custpage">
      <div class="custlist">        
        <div class="conn">
          <?php echo $this->GetCustHTMLList($this->i_oCustomer->i_iPK); ?>
          <div title="Přidat nového zákazníka" class="newcustomer<?php if ($this->i_oCustomer->i_iPK == 0) echo ' selected'?>"><img src="images/NewPlus.png" /><span>Nový</span></div>
        </div>      
      </div>
      <div class="detailconn" style="border-right: 3px double rgb(120,120,120)">
        <div class="dbpageform">
          <div class="cap">Detail zákazníka:</div>
          <form method="post">
            <input type="hidden" name="pagepost" />
            <input type="hidden" name="custpost" />
            <table class="formtab">
              <td>
                <div class="formgr">
                  <div class="cap">Firma:</div>
                  <div class="conn">
                    <table>
                      <tr>
                        <td style="font-weight: bold;">Indent:</td>
                        <td>
                          <input 
                            type="text" size="16" name="orcust_ident" style="width: calc(100% - 28px);"
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_ident')->GetValueAsString(); ?>" 
                            maxlength="20" required <?php if ($this->i_bFocusCustomer) { echo 'autofocus'; } ?> 
                          />
                          <input 
                            type="text" name="orcust_color" class="colorinput"
                            value="<?php 
                                     if ($this->i_oCustomer->GetColumnByName('orcust_color')->GetValueAsString() != '')
                                       echo $this->i_oCustomer->GetColumnByName('orcust_color')->GetValueAsString();
                                     else
                                       echo '#ffffff';
                                   ?>"
                          />                                                    
                        </td>                    
                      </tr>  
                      <tr>
                        <td>Název:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_firma" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_firma')->GetValueAsString(); ?>" 
                            maxlength="40"/>
                        </td>
                      </tr>
                      <tr>
                        <td>Název 2:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_firma2" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_firma2')->GetValueAsString(); ?>" 
                            maxlength="40"/>
                        </td>
                      </tr>
                      <tr>
                        <td>Ulice:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_ulice"
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_ulice')->GetValueAsString(); ?>" 
                            maxlength="100"/>
                        </td>
                      </tr>                      
                      <tr>
                        <td>Město:</td>
                        <td>
                          <input class="uppercase" type="text" placeholder="Stát" size="1" name="orcust_stat" style="width: 40px;" 
                                 value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_stat')->GetValueAsString() ?>" 
                                 maxlength="3"/> - 
                          <input type="text" placeholder="PSČ" size="5" name="orcust_psc" style="width: 60px;"  
                                 value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_psc')->GetValueAsString() ?>" 
                                 maxlength="10"/>
                          <input type="text" size="15" name="orcust_mesto" style="width: 130px"  
                                 value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_mesto')->GetValueAsString() ?>" 
                                 maxlength="100"/>
                        </td>
                      </tr>
                      <tr>
                        <td>IČ:</td>
                        <td>
                          <input 
                            type="text" size="12" name="orcust_ico" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_ico')->GetValueAsString(); ?>" 
                            maxlength="8"/>
                        </td>
                      </tr>
                      <tr>
                        <td>DIČ:</td>
                        <td>
                          <input 
                            type="text" size="12" name="orcust_dico" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_dico')->GetValueAsString(); ?>" 
                            maxlength="12"/>
                        </td>
                      </tr>                      
                    </table>
                  </div>                
                </div>
              </td>
            </table>
            <table class="formtab">
              <td>
                <div class="formgr">
                  <div class="cap">Kontakt:</div>
                  <div class="conn">
                    <table style="width: 100%">
                      <tr>
                        <td style="font-weight: bold;">Telefon:</td>
                        <td>
                          <input 
                            class="telnumber" type="text" size="20" name="orcust_telefon" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_telefon')->GetValueAsString(); ?>" 
                            maxlength="20" required/>
                        </td>
                      </tr>
                      <tr>
                        <td>E-mail:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_mail" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_mail')->GetValueAsString(); ?>" 
                            maxlength="40"/>
                        </td>
                      </tr>
                      <tr>
                        <td>Poznámka:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_pozn1" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_pozn1')->GetValueAsString(); ?>" 
                            maxlength="40"/>
                        </td>
                      </tr>
                      <tr>
                        <td>Poznámka 2:</td>
                        <td>
                          <input 
                            type="text" size="20" name="orcust_pozn2" 
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_pozn2')->GetValueAsString(); ?>" 
                            maxlength="40"/>
                        </td>
                      </tr>
                    </table>
                  </div>          
                </div>        
              </td>
            </table>
            <table class="formtab">
              <td>
                <div class="formgr">
                  <div class="cap">Ostatní:</div>
                  <div class="conn">
                    <table>
                      <tr>
                        <td style="font-weight: bold;">Procento z příjmů:</td>
                        <td>
                          <input 
                            type="text" size="3" name="orcust_prijemproc" style="width: 30px;"
                            value="<?php echo $this->i_oCustomer->GetColumnByName('orcust_prijemproc')->GetValueAsString(); ?>" 
                            maxlength="3" required /> %
                        </td>
                      </tr>
                    </table>
                  </div>          
                </div>        
              </td>
            </table>
            <div class="actions">
              <button type="submit" name="c_submit" value="Uložit" <?php if ($this->i_oCustomer->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Save.png" /><span>Uložit</span></button>
              <button type="submit" name="c_delete" value="Odstranit" <?php if ($this->i_oCustomer->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Delete.png" /><span>Odstranit</span></button>
            </div>
            <div tabindex="0" class="focusroll" style="height: 0;"></div>
          </form>
        </div>        
      </div>
      <div class="storagesconn">
        <div class="browserconn" style="height: calc(100% - 300px)">
          <?php
          echo $this->i_oBrowser->BuildBrowserHTML(50, 0);
          ?>
        </div>
        <div class="dbpageform">
          <div class="cap">Detail skladu:</div>
          <form method="post">
            <fieldset <?php if ($this->i_oCustomer->i_iPK < 1) echo 'disabled'; ?>>
              <input type="hidden" name="pagepost" />
              <input type="hidden" name="storagepost" />
              <table class="formtab">
                <td>
                  <div class="formgr" style="height: 169px;">
                    <div class="cap">Adresa:</div>
                    <div class="conn">
                      <table>
                        <tr>
                          <td style="font-weight: bold;">Firma:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_firma" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_firma')->GetValueAsString(); ?>" 
                              maxlength="40" required <?php if ($this->i_bFocusAdress) { echo 'autofocus'; } ?> />
                          </td>
                        </tr>
                        <tr>
                          <td>Firma 2:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_firma2" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_firma2')->GetValueAsString(); ?>" 
                              maxlength="40"/>
                          </td>
                        </tr>
                        <tr>
                          <td>Ulice:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_ulice" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_ulice')->GetValueAsString(); ?>" 
                              maxlength="100"/>
                          </td>
                        </tr>
                        <tr>
                          <td>Město:</td>
                          <td>
                            <input class="uppercase" type="text" placeholder="Stát" size="1" name="orcadr_stat" style="width: 40px;" 
                                   value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_stat')->GetValueAsString() ?>" 
                                   maxlength="3"/> - 
                            <input type="text" placeholder="PSČ" size="5" name="orcadr_psc" style="width: 60px;"  
                                   value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_psc')->GetValueAsString() ?>" 
                                   maxlength="10"/>
                            <input type="text" size="15" name="orcadr_mesto" style="width: 130px"  
                                   value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_mesto')->GetValueAsString() ?>" 
                                   maxlength="100"/>
                          </td>
                        </tr>
                        <td>Telefon:</td>
                        <td>
                          <input 
                            class="telnumber" type="text" size="20" name="orcadr_telnumber" 
                            value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_telnumber')->GetValueAsString(); ?>" 
                            maxlength="20"/>
                        </td>
                      </table>
                    </div>          
                  </div>        
                </td>
                <td>
                  <div class="formgr" style="height: 80px;">
                    <div class="cap">Další info:</div>
                    <div class="conn">
                      <table>
                        <tr>
                          <td>Pracovní doba:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_cas3" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_cas3')->GetValueAsString(); ?>" 
                              maxlength="50"/>
                          </td>
                        </tr>
                        <tr>
                          <td>Poznámka:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_pozn1" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_pozn1')->GetValueAsString(); ?>" 
                              maxlength="40"/>
                          </td>
                        </tr>
                        <tr>
                          <td>Poznámka 2:</td>                        
                          <td>
                            <input 
                              type="text" size="20" name="orcadr_pozn2" 
                              value="<?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_pozn2')->GetValueAsString(); ?>" 
                              maxlength="40"/>
                          </td>
                        </tr>
                      </table>
                    </div>          
                  </div>        
                  <div class="formgr dottedtop" style="height: 69px;">
                    <div class="cap">Statistiky:</div>
                    <div class="conn">
                      <table class="stattabl">
                        <tr>
                          <td>Počet nakládek:</td>
                          <td>Počet vykládek:</td>
                          <td>Celkem:</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="val naklnum">
                              <?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_naklnum')->GetValueAsString(); ?>
                            </div>
                          </td>
                          <td>
                            <div class="val vyklnum">
                              <?php echo $this->i_oCustomerAddress->GetColumnByName('orcadr_vyklnum')->GetValueAsString(); ?>
                            </div>
                          </td>
                          <td>
                            <div class="val totalnum">
                              <?php 
                                echo 
                                $this->i_oCustomerAddress->GetColumnByName('orcadr_vyklnum')->GetValue() +
                                $this->i_oCustomerAddress->GetColumnByName('orcadr_naklnum')->GetValue()
                              ?>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </div>          
                  </div>        
                </td>
              </table>
              <div class="actions">
                <button type="submit" name="c_submit" value="Uložit" <?php if ($this->i_oCustomerAddress->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Save.png" /><span>Uložit</span></button>
                <button type="submit" name="c_delete" value="Odstranit" <?php if ($this->i_oCustomerAddress->i_iPK == 0){ echo 'disabled'; }?>><img src="images/Delete.png" /><span>Odstranit</span></button>
              </div>
              <div tabindex="0" class="focusroll" style="height: 0;"></div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>        
    <link rel="stylesheet" href="css/evol-colorpicker.min.css" type="text/css" media="screen" />
    <script type="text/javascript" src="jscripts/evol-colorpicker.min.js"></script>
    <?php
  }
  public function ProccessPost()
  {
    if (isset($_POST['custpost']))
      $this->ProcessCustFormSubmit();          
    else if (isset($_POST['storagepost']))
      $this->ProcessCustAddressFormSubmit();
  }
  public function ProcessGet()
  {
    if (isset($_GET['ClearC']))
    {
      $this->ChangeCustomer();
      $this->i_bFocusCustomer = true;
    }
    else if (isset($_GET['ClearA']))
    {
      $this->ChangeCustAddress();
      $this->i_bFocusAdress = true;
    }
    else if(isset ($_GET['cust']))
    {
      $this->ChangeCustomer(intval($_GET['cust']));      
      $this->i_bFocusCustomer = false;
    }
    if (isset($_GET['brscroll']))
      $this->i_oBrowser->i_iScrollTop = intval($_GET['brscroll']);    
  }
  public function ProcessAjax()
  {
    if (parent::ProcessAjax())
      return;
    
    $res = '<respxml>';
    if ($_POST['type'] == 'custaddrsel')
    {
      $this->ChangeCustAddress(intval($_POST['caddr']));
      $res .= $this->i_oCustomerAddress->GetAsXML();
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
  
  public static function GetCustHTMLList($a_iSelectedPK = 0)
  {
    $v_sRes = '';
    $SQL = 'select orcust_pk, orcust_ident, orcust_color from or_customer order by orcust_ident collate NC_UTF8_CZ';
    $fields = null;
    if (!MyDatabase::RunQuery($fields, $SQL, false))
      $v_sRes = '<div>Chyba</div>';
    else if (count($fields) > 0)
      for ($i = 0; $i < count($fields); $i++)
      {
        $v_iPK = intval($fields[$i]['ORCUST_PK']);
        $v_sRes .= '<div class="customer';
          if ($v_iPK === $a_iSelectedPK)
            $v_sRes .= " selected ";            
          $v_sRes .='" pk="' . $v_iPK . '">';
        $v_sRes .= '<div class="ident">' . $fields[$i]['ORCUST_IDENT'] . '</div>';
        $v_sRes .= '<div class="colorprev"><div style="background-color: ' . $fields[$i]['ORCUST_COLOR'] . '"></div></div>';
        $v_sRes .= '</div>';
      }
    /*
    else
      $v_sRes = '<div class="nodata">Žádná data</div>';
    */
    return $v_sRes;
  }
  
  public function ChangeCustomer($a_iPK = 0)
  {
    if ($a_iPK > 0)
    {
      $Customer = new Customer($a_iPK);
      if ($Customer->i_bLoad_Success)
        $this->i_oCustomer = $Customer;
      else 
      {
        // nestane se nic
        $this->AddAlert('red', 'Nastala chyba při změně zákazníka. Zákazníka se nepodařilo správně načíst.');
        return false; 
      }
    }
    else
      $this->i_oCustomer = new Customer();
    
    $this->i_oBrowser->i_sOuterWhere = 'fkcustomer = ' . $this->i_oCustomer->i_iPK;    
    $this->i_oBrowser->i_iScrollTop = 0;

    $Succes = $this->ChangeCustAddress(); // zatim zadny defautne vybrany      

    if ($this->i_oCustomer->i_iPK == 0 || !$Succes)
      return $Succes;
    
    // pokud bude 0 skladu a vybran existujici zakaznik, vyplnime formular
      
    $Val = 0;
    if (!MyDatabase::GetOneValue($Val, 'select first 1 1 from or_custaddress where orcadr_customer = ?', $this->i_oCustomer->i_iPK))
    {
      $this->AddAlert('red', 'Chyba databáze při plnění dat do formuláře skladů.');
    }
    else
    {    
      if (intval($Val) !== 1)
      {
        $this->i_oCustomerAddress->GetColumnByName('orcadr_firma')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_firma')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_firma2')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_firma2')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_stat')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_stat')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_ulice')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_ulice')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_psc')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_psc')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_mesto')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_mesto')->GetValue());

        $this->i_oCustomerAddress->GetColumnByName('orcadr_telnumber')->SetValue(
          $this->i_oCustomer->GetColumnByName('orcust_telefon')->GetValue());
        
        $this->i_oCustomerAddress->GetColumnByName('orcadr_pozn1')->SetValue("Výchozí adresa.");
        
        $this->i_bFocusAdress = true;        
      }
    }
    return true;
  }

  // private
  private function ChangeCustAddress($a_iPK = 0)
  {
    if ($a_iPK > 0 && $this->i_oCustomer->i_iPK > 0)
    {
      $CustomerAddress = new CustomerAddress($a_iPK);
      if ($CustomerAddress->i_bLoad_Success)
        $this->i_oCustomerAddress = $CustomerAddress;
      else 
      {
        $this->AddAlert('red', 'Nastala chyba při změně skladu. Sklad se nepodařilo správně načíst.');
        return false;
      }
    }
    else
    {
      $this->i_oCustomerAddress = new CustomerAddress();
      $this->i_oCustomerAddress->GetColumnByName('orcadr_customer')->SetValue($this->i_oCustomer->i_iPK);
    }
    
    $this->i_oBrowser->i_iSelectedPK = $this->i_oCustomerAddress->i_iPK;
    return true;    
  }
  
  private function ProcessCustFormSubmit()
  {
    $this->i_bFocusAdress = false;        
    $this->i_bFocusCustomer = false;        
    if (isset($_POST['c_delete']))
    {
      if($this->i_oCustomer->DeleteFromDB(false))
      {
        $this->ChangeCustomer();
        $this->AddAlert('green', 'Vymazáno.');
      }
      else
      {
        $this->AddAlert('red', 'Běhěm mazání zákazníka nastala chyba.');
      }
    }
    else if (isset ($_POST['c_submit']))
    {
      $this->i_oCustomer->LoadFromPostData();
      if ($this->i_oCustomer->SaveToDB(false))
      {
        if ($this->ChangeCustomer($this->i_oCustomer->i_iPK))
          $this->AddAlert('green', 'Uloženo.');
      }
      else
        $this->AddAlert('red', 'Běhěm ukládání zákazníka nastala chyba.');
    }
  }
  private function ProcessCustAddressFormSubmit()
  {
    if (isset($_POST['c_delete']))
    {
      if($this->i_oCustomerAddress->DeleteFromDB(false))
      {
        $this->ChangeCustAddress();
        $this->AddAlert('green', 'Vymazáno.');
      }
      else
      {
        $this->AddAlert('red', 'Běhěm mazání zákazníka nastala chyba.');
      }
    }
    else if (isset ($_POST['c_submit']))
    {
      $this->i_oCustomerAddress->LoadFromPostData();            
      if ($this->i_oCustomerAddress->SaveToDB(false))
      {
        if ($this->ChangeCustAddress($this->i_oCustomerAddress->i_iPK))
          $this->AddAlert('green', 'Uloženo.');
      }
      else
      {
        $this->AddAlert('red', 'Běhěm ukládání zákazníka nastala chyba.');
      }
    }
    // TODO: co tady ? -- dojde se k tomu
    $this->i_bFocusAdress = false;        
    $this->i_bFocusCustomer = false;        
  }
}
