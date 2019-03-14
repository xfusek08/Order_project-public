<?php
class Transport extends DatabaseEntity
{
  public $i_oNakladka;
  public $i_oVykladka;
  public $i_sID;
  public function __construct($a_iPK = 0, $a_sID, $ExternTransaction = false)
  {
    $this->i_sTableName = 'OR_OBJNAKLVYKL';
    $this->i_sPKColName = 'ORONV_PK';
    $this->i_sID = $a_sID;
    parent::__construct($a_iPK, $ExternTransaction);   
    if ($this->i_bLoad_Success)
    {
      $this->i_oNakladka = new Spot($this->GetColumnByName('oronv_nakl')->GetValue(), $ExternTransaction);
      $this->i_oVykladka = new Spot($this->GetColumnByName('oronv_vykl')->GetValue(), $ExternTransaction);
      $this->i_bLoad_Success = $this->i_oVykladka->i_bLoad_Success && $this->i_oNakladka->i_bLoad_Success;
    }
    else
    {
      $this->i_oNakladka = new Spot(0, $ExternTransaction);
      $this->i_oVykladka = new Spot(0, $ExternTransaction);    
    }
    
  }
  protected function DefColumns()
  {
    $this->AddColumn(DataType::Integer, 'oronv_obj');
    $this->AddColumn(DataType::Integer, 'oronv_nakl');
    $this->AddColumn(DataType::Integer, 'oronv_vykl');
    $this->AddColumn(DataType::Integer, 'oronv_vaha');
    $this->AddColumn(DataType::String, 'oronv_poznadr');
    $this->AddColumn(DataType::String, 'oronv_poznobj');
    $this->AddColumn(DataType::String, 'oronv_zbozipopis');
  }
  public function LoadFromPostData($a_sPrefix = '')
  {
    $this->i_oNakladka->LoadFromPostData($this->i_sID . 'nakl_');
    $this->i_oVykladka->LoadFromPostData($this->i_sID . 'vykl_');
    parent::LoadFromPostData($this->i_sID);
  }
  public function GetAsXML($Formated = true)
  {
    $res = '<transport ident="' . $this->i_sID . '">';
    $res .= parent::GetAsXML($Formated);
    $res .= '<nakl>' . $this->i_oNakladka->GetAsXML($Formated) . '</nakl>';
    $res .= '<vykl>' . $this->i_oVykladka->GetAsXML($Formated) . '</vykl>';
    $res .= '</transport>';
    return $res;
  }
  public function GetInvalidDataXML($a_sPrefix = '')
  {
    $res = '<transport ident="' . $this->i_sID . '">';
    $res .= parent::GetInvalidDataXML($a_sPrefix);
    $res .= '<nakl>' . $this->i_oNakladka->GetInvalidDataXML($a_sPrefix . 'nakl_') . '</nakl>';
    $res .= '<vykl>' . $this->i_oVykladka->GetInvalidDataXML($a_sPrefix . 'vykl_') . '</vykl>';
    $res .= '</transport>';
    return $res;
  }
  public function SaveFullToDB($a_iOrderPk, $ExternalTrans)
  {
    if (
      !($this->i_oNakladka->IsDataValid() && 
        $this->i_oVykladka->IsDataValid() && 
        $this->IsDataValid()))
    {
      $this->i_eSaveToDBResult = SaveToDBResult::InvalidData;
      return false;
    }

    if (!($a_iOrderPk && is_numeric($a_iOrderPk))) return false;
    if ($a_iOrderPk <= 0) return false;
    $succes = true;
    
    $this->GetColumnByName('oronv_obj')->SetValue($a_iOrderPk);
    
    $succes = $this->i_oNakladka->SaveToDB($ExternalTrans);
    $succes = $succes && $this->i_oVykladka->SaveToDB($ExternalTrans);
    
    if (!$succes) 
      return false;
    
    $this->GetColumnByName('oronv_nakl')->SetValue($this->i_oNakladka->i_iPK);
    $this->GetColumnByName('oronv_vykl')->SetValue($this->i_oVykladka->i_iPK);

    return $this->SaveToDB($ExternalTrans);
  }
  public function DeleteFromDB($ExternalTrans)
  {
    $succes = parent::DeleteFromDB($ExternalTrans);
    if ($succes)
      $succes = $this->i_oNakladka->DeleteFromDB($ExternalTrans);
    if ($succes)
      $succes = $this->i_oVykladka->DeleteFromDB($ExternalTrans);
    return $succes;
  }
  public function BuildTransportOrderForm($a_bShowDelbt = false)
  {
    $html  = '<div class="transport" ident="' . $this->i_sID . '">';
    if ($a_bShowDelbt)
      $html  .= '<div class="delbt"><img src="images/cross.png" /></div>';
    
    $html  .= '<form method="post" nameprefix="' . $this->i_sID . '">'.
                  '<table>'.
                    '<td>'.
                      '<div class="blockform spot nakl">'.
                        '<table>'.
                          '<tr><td style="font-weight: bold; font-size: 13px">Nakládka:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input '.
                                ' class="datalist"'.
                                ' autocomplete="off"'.
                                ' datareq="searchnaklspot"'.
                                ' type="text"'.
                                ' placeholder="Hledat"'.
                                ' name="c_none"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr><td>Termín:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input style="width: 15%" type="text" name="' . $this->i_sID . 'nakl_orspt_cas1" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_cas1')->GetValueAsString() . '" '.
                                'maxlength="10"/> '.
                              '<input class="datepicker" style="width: 32%" type="text" name="' . $this->i_sID . 'nakl_orspt_date" placeholder="Datum"'.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_date')->GetValueAsString() . '"/> '.
                              '<input style="width: 40%" type="text" name="' . $this->i_sID . 'nakl_orspt_cas3" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_cas3')->GetValueAsString() . '" '.
                                'maxlength="50"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr><td>Adresa:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'nakl_orspt_firma" placeholder="Firma" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_firma')->GetValueAsString() . '" '.
                                'maxlength="40"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'nakl_orspt_firma2" placeholder="Firma 2" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_firma2')->GetValueAsString() . '" '.
                                'maxlength="40"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'nakl_orspt_ulice" placeholder="Ulice" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_ulice')->GetValueAsString() . '" '.
                                'maxlength="100"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input class="uppercase" style="width: 40px;" type="text" name="' . $this->i_sID . 'nakl_orspt_stat" placeholder="Stát" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_stat')->GetValueAsString() . '" '.
                                'maxlength="3" /> - '.
                              '<input style="width: 70px;" type="text" name="' . $this->i_sID . 'nakl_orspt_psc" placeholder="PSČ" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_psc')->GetValueAsString() . '" '.
                                'maxlength="10"/> '.
                              '<input style="width: calc(100% - 135px)"type="text" name="' . $this->i_sID . 'nakl_orspt_mesto" placeholder="Město" '.
                                'value="' . $this->i_oNakladka->GetColumnByName('orspt_mesto')->GetValueAsString() . '" '.
                                'maxlength="100"/>'.
                            '</td>'.
                          '</tr>'.
                        '</table>'.
                      '</div>'.
                    '</td>'.
                    '<td>'.
                      '<div class="blockform spot vykl">'.
                        '<table>'.
                          '<tr><td style="font-weight: bold; font-size: 13px">Vykládka:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input '.
                                ' class="datalist"'.
                                ' autocomplete="off"'.
                                ' datareq="searchvyklspot"'.
                                ' type="text"'.
                                ' placeholder="Hledat"'.
                                ' name="c_none"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr><td>Termín:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input style="width: 15%" type="text" name="' . $this->i_sID . 'vykl_orspt_cas1" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_cas1')->GetValueAsString() . '" '.
                                'maxlength="10"/> '.
                              '<input class="datepicker" style="width: 32%" type="text" name="' . $this->i_sID . 'vykl_orspt_date" placeholder="Datum"'.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_date')->GetValueAsString() . '"/> '.
                              '<input style="width: 40%" type="text" name="' . $this->i_sID . 'vykl_orspt_cas3" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_cas3')->GetValueAsString() . '" '.
                                'maxlength="50"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr><td>Adresa:</td></tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'vykl_orspt_firma" placeholder="Firma" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_firma')->GetValueAsString() . '" '.
                                'maxlength="40"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'vykl_orspt_firma2" placeholder="Firma 2" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_firma2')->GetValueAsString() . '" '.
                                'maxlength="40"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input type="text" name="' . $this->i_sID . 'vykl_orspt_ulice" placeholder="Ulice" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_ulice')->GetValueAsString() . '" '.
                                'maxlength="100"/>'.
                            '</td>'.
                          '</tr>'.
                          '<tr>'.
                            '<td>'.
                              '<input class="uppercase" style="width: 40px;" type="text" name="' . $this->i_sID . 'vykl_orspt_stat" placeholder="Stát" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_stat')->GetValueAsString() . '" '.
                                'maxlength="3" /> - '.
                              '<input style="width: 70px;" type="text" name="' . $this->i_sID . 'vykl_orspt_psc" placeholder="PSČ" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_psc')->GetValueAsString() . '" '.
                                'maxlength="10"/> '.
                              '<input style="width: calc(100% - 135px)"type="text" name="' . $this->i_sID . 'vykl_orspt_mesto" placeholder="Město" '.
                                'value="' . $this->i_oVykladka->GetColumnByName('orspt_mesto')->GetValueAsString() . '" '.
                                'maxlength="100"/>'.
                            '</td>'.
                          '</tr>'.
                        '</table>'.
                      '</div>'.
                    '</td>'.
                  '</table>'.
                  '<div style="padding-left: 10px;">'.
                    '<table>'.
                      '<tr>'.
                        '<td style="font-weight: bold">Zboží:</td>'.
                        '<td style="width: 100%;">'.
                          '<input style="width: calc(100% - 5px);" type="text" name="' . $this->i_sID . 'oronv_zbozipopis" '.
                            'value="' . $this->GetColumnByName('oronv_zbozipopis')->GetValueAsString() . '"'.
                            'maxlength="100"/>'.
                        '</td>'.
                      '</tr>'.
                      '<tr>'.
                        '<td style="font-weight: bold">Hmotnost:</td>'.
                        '<td>cca '.
                          '<input style="width: 100px;" type="text" name="' . $this->i_sID . 'oronv_vaha" '.
                            'value="' . $this->GetColumnByName('oronv_vaha')->GetValueAsString() . '"'.
                            'maxlength="5"/> kg'.
                        '</td>'.
                      '</tr>'.
                      '<tr>'.
                        '<td style="font-weight: bold">ADR:</td>'.
                        '<td>'.
                          '<input style="width: calc(100% - 5px);" type="text" name="' . $this->i_sID . 'oronv_poznadr" '.
                            'value="' . $this->GetColumnByName('oronv_poznadr')->GetValueAsString() . '"'.
                            'maxlength="100"/>'.
                        '</td>'.
                      '</tr>'.
                      '<tr>'.
                        '<td style="font-weight: bold">Poznámka:</td>'.
                        '<td>'.
                          '<input style="width: calc(100% - 5px);" type="text" name="' . $this->i_sID . 'oronv_poznobj" '.
                            'value="' . $this->GetColumnByName('oronv_poznobj')->GetValueAsString() . '"'.
                            'maxlength="100"/>'.
                        '</td>'.
                      '</tr>'.
                      //'<tr><td>Pozn.:</td><td><input type="text" name="pozn" maxlength="40"/></td></td></tr>'.
                      //'<tr><td>Další info:</td><td><input type="text" name="dalsiinfo" maxlength="40"/></td></td></tr>'.
                    '</table>'.
                  '</div>'.
                '</form>'.
              '</div>';
    return $html;
  }
}
