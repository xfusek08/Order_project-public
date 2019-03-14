<?php

class Browser
{
  public $i_aFields;
  public $i_sDBtableName;
  public $i_sPKColName;
  public $i_iSelectedPK;
  public $i_iScrollTop;
  public $i_sOuterWhere;
  public $i_bShowSummary;
  
  // public
  public function __construct($a_sDBTabName, $a_sPKColName)
  {
    $this->i_aFields = array();
    $this->i_sDBtableName = $a_sDBTabName;
    $this->i_sPKColName = $a_sPKColName;
    $this->i_iSelectedPK = 0;
    $this->i_iScrollTop = 0;
    $this->i_sOuterWhere = '';
    $this->i_bShowSummary = false;
  }
  public function BuildBrowserHTML($a_iLoadNum = 0, $a_iSkip = 0)
  {    
    $v_sHTML = 
      '<div class="toolbar">'.
        '<button title="Vyčistit filtry" class="clearfiletrs"><img src="images/ClearFilter.png" /><span>Zrušit filtry<span></button>'.
        '<button title="Vytvořit nový záznam" class="newbt"><img src="images/BrowserNew.png" /><span>Nový záznam<span></button>'.
      '</div>';
    $v_sHTML .= '<div class="browser" scrolltop=' . $this->i_iScrollTop . ' showsummary="' . BoolTo01($this->i_bShowSummary) . '"><table>';
      $v_sHTML .= '<thead>';
        $v_sHTML .= '<th></th>';
        for ($i = 0; $i < count($this->i_aFields); $i++)
        {
          if (!$this->i_aFields[$i]->i_bAttrHiddenValue)
          {
            $v_sHTML .= $this->i_aFields[$i]->GetHeaderCell();
          }
        }        
      $v_sHTML .= '</thead>';
      $v_sHTML .= '<tbody>';        
        // data here
        $fields = null;
        $v_aParams = array();
        $SQL = $this->BuildSQLSelect($a_iLoadNum, $a_iSkip, $v_aParams);
        if (!MyDatabase::RunQuery($fields, $SQL, false, $v_aParams))
        {
          $v_sHTML .= 'Chyba databázového selektu';
          //$v_sHTML .= '<br />';
          //$v_sHTML .= '<pre>';
          //$v_sHTML .= $v_aParams[0];
          //$v_sHTML .= '</pre>';          
        }
        else
        {
          for ($i = 0; $i < count($fields); $i++)
          {
            $v_oArray = $this->GetAllFields();
            for ($ii = 0; $ii < count($v_oArray); $ii++)
            {
              $v_oArray[$ii]->i_sValue = $fields[$i][$ii + 1];              
            }
            $v_sHTML .= '<tr';
            if ($this->i_iSelectedPK == intval($fields[$i][0]))
              $v_sHTML .= ' selected'; 
            $v_sHTML .= ' pk="' . $fields[$i][0] . '"';
            for ($ii = 0; $ii < count($this->i_aFields); $ii++)
            {
              if ($this->i_aFields[$ii]->i_bAttrHiddenValue)
                $v_sHTML .= ' ' . $this->i_aFields[$ii]->i_sColIndent . '="' . $this->i_aFields[$ii]->GetValue() . '"';
            }
            $v_sHTML .= '>';
            $v_sHTML .= '<td>' . (($i + 1) + $a_iSkip) . '</td>';
            for ($ii = 0; $ii < count($this->i_aFields); $ii++)
            {
              if (!$this->i_aFields[$ii]->i_bAttrHiddenValue)
                $v_sHTML .= $this->i_aFields[$ii]->GetDataValCell();
            }
            $v_sHTML .= '</tr>';
          }
        }
      $v_sHTML .= '</tbody>';    
    $v_sHTML .= '</table>';
    
    if (count($fields) == 0)
    {
      $v_sHTML .= '<div class="nodata">Žádná data</div>';
    }
    $v_sHTML .= '</div>';    
    return $v_sHTML;
  }
  
  public function AddField($a_dtDataType, $a_sShowName, $a_sDBName, $a_iwidth = 0)
  {
    $v_oActField = new DataField('c_' . count($this->i_aFields), $a_dtDataType, $a_sShowName, $a_sDBName, $a_iwidth);
    $this->i_aFields[] = $v_oActField;
    return $v_oActField;
  }
  
  public function AddFieldGroup($a_sName, $a_afields)
  {
    $v_oActField = new DataField('c_' . count($this->i_aFields));
    $v_oActField->i_sName = $a_sName;
    $v_oActField->i_aFields = $a_afields;
    $this->i_aFields[] = $v_oActField;
    return $v_oActField;    
  }
  
  public function GetAllFields()
  {
    $v_aFields = array();
    for ($i = 0; $i < count($this->i_aFields); $i++)
    {
      $v_aFields = array_merge($v_aFields, $this->i_aFields[$i]->GetLowLevelFields());       
    }
    return $v_aFields; 
  }
  
  public function ProcessAjax()
  {
    $v_iSkip = 0;
    $v_iLoad = 50;
    if ($_POST['brtype'] == 'setheadfilter')
    {
      try
      {
        $v_oActDataField;
        foreach ($this->i_aFields as $v_oField)
        {
          if ($v_oField->i_sColIndent == $_POST['headindent'])
          {
            $v_oActDataField = $v_oField;
            break;
          }
        }
        if ($_POST['datatype'] == 'text')
          $v_oActDataField->i_sFilterText = $_POST['text'];
        else if ($_POST['datatype'] == 'number')
        {
          if (floatval($_POST['val']) || $_POST['val'] == '0')
            $v_oActDataField->i_iFilterNumber = floatval($_POST['val']);
          else
            $v_oActDataField->i_iFilterNumber = null;

          if (intval($_POST['operindex']) || $_POST['operindex'] == '0')
            $v_oActDataField->i_FilterNumOper = intval($_POST['operindex']); // const je dosazovan z Order typu
        }
        else if ($_POST['datatype'] == 'order')
        {
          foreach ($this->i_aFields as $v_oField)
          {
            $v_oField->i_iOrderByIndex = 0;          
          }     
          $v_oActDataField->i_iOrderByIndex = 1;
          $v_oActDataField->i_bOrdDesc = $_POST['order'] == 'desc';
        }
        else if ($_POST['datatype'] == 'orderadd')
        {
          if ($_POST['order'] == 'desc')
          {
            if ($v_oActDataField->i_bOrdDesc && $v_oActDataField->i_iOrderByIndex > 0)
            {
              $this->RemoveOrderIndex($v_oActDataField);          
            }
            else
            {
              $v_oActDataField->i_bOrdDesc = true;
              if ($v_oActDataField->i_iOrderByIndex == 0)
                $v_oActDataField->i_iOrderByIndex = $this->GetLastOrderIndex() + 1;
            }
          }
          else
          {
            if (!$v_oActDataField->i_bOrdDesc && $v_oActDataField->i_iOrderByIndex > 0)
            {
              $this->RemoveOrderIndex($v_oActDataField);          
            }
            else
            {
              $v_oActDataField->i_bOrdDesc = false;
              if ($v_oActDataField->i_iOrderByIndex == 0)
                $v_oActDataField->i_iOrderByIndex = $this->GetLastOrderIndex() + 1;
            }
          }
        }
        else if ($_POST['datatype'] == 'date')
        {
          if (isset($_POST['datefrom']) && $_POST['datefrom'] !== '')
            $v_oActDataField->i_dFilterDateFrom = strtotime($_POST['datefrom']);
          else
            $v_oActDataField->i_dFilterDateFrom = null;

          if (isset($_POST['dateto']) && $_POST['dateto'] !== '')
            $v_oActDataField->i_dFilterDateTo = strtotime($_POST['dateto']);
          else
            $v_oActDataField->i_dFilterDateTo = null;
        }
      }
      catch (Exception $e)
      {
        $v_sRes = '<respxml state="error">';
        $v_sRes .= '</respxml>'; 
        Logging::WriteLog(LogType::Error, 'Browser ajax error: ' . $e->getMessage());
        echo $v_sRes;
        return false;      
      }
    }
    else if ($_POST['brtype'] == 'getnexdata')
    {
      $v_iSkip = intval($_POST['skip']);
      $v_iLoad = intval($_POST['load']);
    }
    
    $v_sRes = '<respxml state="ok">';
    $v_sRes .= '<data>';
    if ($_POST['brtype'] == 'getSummary')
      $v_sRes .= $this->GetSummaryXML();
    else
      $v_sRes .= $this->BuildBrowserHTML($v_iLoad, $v_iSkip);
    $v_sRes .= '</data>';
    $v_sRes .= '</respxml>';    
    echo $v_sRes;
    return true;
  }
  // private 
  private function BuildSQLSelect($a_iLoadNum, $a_iSkip, &$a_aParams)
  {
    if (count($this->i_aFields) == 0)
    {
      return ''; 
    }
    $v_aAllFields = $this->GetAllFields();
    
    $v_sSQL = 'select';
    
    if ($a_iLoadNum > 0)
      $v_sSQL .= ' first ' . $a_iLoadNum;
    if ($a_iSkip > 0)
      $v_sSQL .= ' skip ' . $a_iSkip;
    
    $v_sSQL .= ' * from (select';
    
    $v_sSQL .= ' ' . $this->i_sPKColName . ',';
    $v_oActField = null;
    for ($i = 0; $i < count($v_aAllFields); $i++)
    {
      $v_oActField = $v_aAllFields[$i];
      $v_sSQL .= ' ';
      if ($v_oActField->i_sColName == '')
         $v_sSQL .= '\' \'';
      else
      {
        $v_sSQL .= $v_oActField->i_sColName . ' as ' . $v_oActField->i_sColIndent;
      }
      if (($i + 1) < count($v_aAllFields))
        $v_sSQL .= ',';                   
    }
    $v_sSQL .= ' from ' . $this->i_sDBtableName . ') where ';    
    
    $v_iPrelenght = count_chars($v_sSQL);
    $v_sWhereSQL = '';
    for ($i = 0; $i < count($this->i_aFields); $i++)
    {
      $v_oActField = $this->i_aFields[$i];
      $v_s = '';
      
      if ($v_sWhereSQL != '')
        $v_sWhereSQL = ' and ';
      
      $v_s = $v_oActField->GetSelectCondition($a_aParams);

      if ($v_s != '')
      {
        $v_sWhereSQL .= '(' . $v_s . ')'; 
        $v_sSQL .= $v_sWhereSQL;
      }
    }
    $v_iActlenght = count_chars($v_sSQL);
    if ($v_iActlenght == $v_iPrelenght)
    {
      if ($this->i_sOuterWhere != '')
        $v_sSQL .= $this->i_sOuterWhere; 
      else
        $v_sSQL = substr($v_sSQL, 0, -7); // odebereme where
    }
    else if ($this->i_sOuterWhere != '')
      $v_sSQL .= ' and ' . $this->i_sOuterWhere; 

    $v_iPrelenght = count_chars($v_sSQL);
    $v_sSQL .= ' order by ' . $this->GetOrderByListSQL();
    if (count_chars($v_sSQL) == $v_iPrelenght)
    {
      $v_sSQL = substr($v_sSQL, 0, -10); // odemereme order by
    }
    
    //Logging::WriteLog(LogType::Anouncement, 'SQL: ' . $v_sSQL);
    return $v_sSQL;     
  }
  private function GetLastOrderIndex()
  {
    $v_iCount = 0;
    for ($i = 0; $i < count($this->i_aFields); $i++)
    {
      if ($this->i_aFields[$i]->i_iOrderByIndex > 0)
        $v_iCount++;
    }
    return $v_iCount;
  }
  private function RemoveOrderIndex($a_oField)
  {
    for ($i = 0; $i < count($this->i_aFields); $i++)
    {
      if ($this->i_aFields[$i]->i_iOrderByIndex > $a_oField->i_iOrderByIndex)
        $this->i_aFields[$i]->i_iOrderByIndex--;        
    }        
    $a_oField->i_iOrderByIndex = 0;
  }
  private function GetOrderByListSQL()
  {
    $v_sRes = '';
    $v_aList = new SplFixedArray(count($this->i_aFields));
    for ($i = 0; $i < count($this->i_aFields); $i++)
    {
      if ($this->i_aFields[$i]->i_iOrderByIndex > 0)
      {
        $v_s = $this->i_aFields[$i]->i_sColIndent;
        if ($this->i_aFields[$i]->i_DataType == DataType::String)
          $v_s .= ' collate NC_UTF8_CZ';   
        if ($this->i_aFields[$i]->i_bOrdDesc)
          $v_s .= ' desc';   
        $v_aList[$this->i_aFields[$i]->i_iOrderByIndex] = $v_s;
      }
    }
    /*
    echo '<pre>';
    echo print_r($v_aList);
    echo '</pre>';
    */
    for ($i = 0; $i < count($v_aList); $i++)
    {
      if ($v_aList[$i] != '')
      {
        $v_sRes .= $v_aList[$i] . ', ';      
      }
    }   
    $v_sRes = substr($v_sRes, 0, -2);
    return $v_sRes;
  }
  
  public function GetSummaryXML()
  {
    $fields = null;
    $v_aParams = array();
    $SQL = 'select count(1),';
    $v_aAllFields = $this->GetAllFields();
    $v_aSummarizedFields = array();
    for ($i = 0; $i < count($v_aAllFields); $i++)
    {
      $v_oActField = $v_aAllFields[$i];
      if ($v_oActField->i_bIsSummary)
      {
        switch($v_oActField->i_DataType)
        {
          case DataType::Float:
            $SQL .= ' avg(' . $v_oActField->i_sColIndent . ') as ' . $v_oActField->i_sColIndent . '_avg,';
            $SQL .= ' sum(' . $v_oActField->i_sColIndent . ') as ' . $v_oActField->i_sColIndent . '_sum,';
            $v_aSummarizedFields[] = $v_oActField;
            break;
          case DataType::Date:
          case DataType::DateTrnc:
            $SQL .= ' min(' . $v_oActField->i_sColIndent . ') as ' . $v_oActField->i_sColIndent . '_min,';
            $SQL .= ' max(' . $v_oActField->i_sColIndent . ') as ' . $v_oActField->i_sColIndent . '_max,';
            $v_aSummarizedFields[] = $v_oActField;
            break;
        }
      }
    }
    $SQL = substr($SQL, 0, -1); // odstranime carku
    $SQL .= ' from (';
    $SQL .= $this->BuildSQLSelect(0, 0, $v_aParams);
    $SQL .= ')';
    if (!MyDatabase::RunQuery($fields, $SQL, false, $v_aParams))
    {
      Logging::WriteLog(LogType::Error, 'Browser->GetSummaryXML(): Database error');
      return '<alert><color>red</color><message>Shrnutí se nepodařilo načíst.</message></alert>';
    } 
    $v_bRes = '<summary count="' . $fields[0]['COUNT'] . '">';
    for ($i = 0; $i < count($v_aSummarizedFields); $i++)
    {
      switch($v_aSummarizedFields[$i]->i_DataType)
      {
        case DataType::Float:
          $v_bRes .= 
            '<field'.
            ' name="' . $v_aSummarizedFields[$i]->i_sName . '"'.
            ' ident="' . $v_aSummarizedFields[$i]->i_sColIndent . '"'.
            ' fistrowdesc="Celkem"' .
            ' secrowdesc="Průměr"' .
            ' fistrow="' . number_format(floatval($fields[0][strtoupper($v_aSummarizedFields[$i]->i_sColIndent) . '_SUM']), 2, '.', ' ') . '"'.
            ' secrow="' . number_format(floatval($fields[0][strtoupper($v_aSummarizedFields[$i]->i_sColIndent) . '_AVG']), 2, '.', ' ') . '"'.
            '/>';
          break;
        case DataType::Date:
        case DataType::DateTrnc:
          
          $v_sDateFrom = '';
          $v_sDateTo = '';
          
          if ($v_aSummarizedFields[$i]->i_dFilterDateFrom == null)
          {
            $v_sDateFrom = $fields[0][strtoupper($v_aSummarizedFields[$i]->i_sColIndent) . '_MIN'];
            if ($v_sDateFrom != '')
              $v_sDateFrom =  date('d.m.Y', strtotime($v_sDateFrom));
          }
          else
            $v_sDateFrom =  date('d.m.Y', $v_aSummarizedFields[$i]->i_dFilterDateFrom);
          
          if ($v_aSummarizedFields[$i]->i_dFilterDateTo == null)
          {
            $v_sDateTo = $fields[0][strtoupper($v_aSummarizedFields[$i]->i_sColIndent) . '_MAX'];
            if ($v_sDateTo != '')
              $v_sDateTo =  date('d.m.Y', strtotime($v_sDateTo));
          }
          else
            $v_sDateTo =  date('d.m.Y', $v_aSummarizedFields[$i]->i_dFilterDateTo);
          
          $v_bRes .= 
            '<field'.
            ' name="' . $v_aSummarizedFields[$i]->i_sName . '"'.
            ' ident="' . $v_aSummarizedFields[$i]->i_sColIndent . '"'.
            ' fistrowdesc="od"' .
            ' secrowdesc="do"' .
            ' fistrow="' . $v_sDateFrom . '"'.
            ' secrow="' . $v_sDateTo . '"'.
            '/>';
          break;
      }
    } 
    $v_bRes .= '</summary>';
    
    return $v_bRes;   
  }
}

