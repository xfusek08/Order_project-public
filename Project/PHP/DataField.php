<?php
class DataField
{
    public $i_sName;
    public $i_iColWidth;
    public $i_DataType;
    public $i_sColName;
    public $i_sValue;
    public $i_sCss;
    public $i_sHeaderCss;
    
    public $i_sFilterText;
    public $i_iFilterNumber;
    public $i_dFilterDateFrom;
    public $i_dFilterDateTo;
    public $i_FilterNumOper;
    public $i_bOrdDesc;
    public $i_iOrderByIndex;
    public $i_sColIndent;
    
    public $i_iMaxLength;
    public $i_bAttrHiddenValue;
    public $i_bFormatted;
    
    public $i_bIsSummary;
    
    public $i_aFields;
    
    public function __construct($a_sColIndent, $a_dtDataType = DataType::None, $a_sShowName = '', $a_sDBName = '', $a_iWidth = 0) {
        $this->i_sName = $a_sShowName;
        $this->i_iColWidth = $a_iWidth;
        $this->i_DataType = $a_dtDataType;
        $this->i_sColName = $a_sDBName;
        $this->i_sValue = '';
        $this->i_aFields = array();
        $this->i_sCss = '';
        
        $this->i_sFilterText = '';
        $this->i_iFilterNumber = null;
        $this->i_dFilterDateFrom = null;
        $this->i_dFilterDateTo = null;
        $this->i_FilterNumOper = Operator::Equal;
        $this->i_bOrdDesc = false;
        $this->i_iOrderByIndex = 0;
        $this->i_sColIndent = $a_sColIndent;
        
        $this->i_iMaxLength = 0;
        $this->i_bAttrHiddenValue = false;
        $this->i_bFormatted = true;
        $this->i_bIsSummary = false;
    }
    
    public function GetLowLevelFields() {
        if (count($this->i_aFields) > 0) {
            $ResArray = array();
            for ($i = 0; $i < count($this->i_aFields); $i++) {
                $ResArray = array_merge($ResArray, $this->i_aFields[$i]->GetLowLevelFields());
            }
            return $ResArray;
        }
        return array($this);
    }
    
    public function GetHeaderCell() {
        $v_sRes = '<th class="headcol" ident="' . $this->i_sColIndent . '">';
        $v_sRes .= '<span title="' . $this->i_sName . '">' . $this->i_sName . '</span>';
        
        // filters
        $v_sRes .= '<div class="filter">';
        if ($this->i_DataType == DataType::String || count($this->i_aFields) > 0) {
            $v_sRes .= '<input type="text" value="' . $this->i_sFilterText . '" name="textfilter"';
            if ($this->i_iMaxLength > 0) {
                $v_sRes .= ' maxlength="' . $this->i_iMaxLength . '" ';
            }
            if ($this->i_iColWidth > 0) {
                $v_sRes .= ' style="max-width:' . $this->i_iColWidth . 'px; min-width:' . $this->i_iColWidth . 'px;" ';
            }
            $v_sRes .= '/>';
        } elseif ($this->i_DataType == DataType::Date || $this->i_DataType == DataType::DateTrnc) {
            $v_sRes .=
                '<div class="datefilter"' .
                ' title="vybrat období"' .
                ' datefrom="' . (($this->i_dFilterDateFrom == null) ? '' : date('d.m.Y', $this->i_dFilterDateFrom)) . '"' .
                ' dateto="' . (($this->i_dFilterDateFrom == null) ? '' : date('d.m.Y', $this->i_dFilterDateTo)) . '"' .
                '><img src="images/calendar.png"/></div>';
        } elseif ($this->i_DataType == DataType::Integer || $this->i_DataType == DataType::Float) {
            if ($this->i_iColWidth < 65 && $this->i_iColWidth > 0) {
                $v_sRes .= '<div>';
            }
            
            $v_sRes .= '<div><select class="operselect" name="numfilteroper">';
            
            $v_sRes .= '<option value="' . Operator::Equal . '"';
            if (Operator::Equal == $this->i_FilterNumOper) {
                $v_sRes .= ' selected ';
            }
            $v_sRes .= '>=</option>';
            
            $v_sRes .= '<option value="' . Operator::IsBigger . '"';
            if (Operator::IsBigger == $this->i_FilterNumOper) {
                $v_sRes .= ' selected ';
            }
            $v_sRes .= '>></option>';
            
            $v_sRes .= '<option value="' . Operator::IsBiggerEg . '"';
            if (Operator::IsBiggerEg == $this->i_FilterNumOper) {
                $v_sRes .= ' selected ';
            }
            $v_sRes .= '>>=</option>';
            
            $v_sRes .= '<option value="' . Operator::IsSmaller . '">';
            if (Operator::IsSmaller == $this->i_FilterNumOper) {
                $v_sRes .= ' selected ';
            }
            $v_sRes .= '<</option>';
            
            $v_sRes .= '<option value="' . Operator::IsSmallerEq . '"';
            if (Operator::IsSmallerEq == $this->i_FilterNumOper) {
                $v_sRes .= ' selected ';
            }
            $v_sRes .= '><=</option>';
            
            $v_sRes .= '</select></div>';
            $v_sRes .= '<div><input type="number" value="' . $this->i_iFilterNumber . '" name="numfilterval"/></div>';
            
            if ($this->i_iColWidth < 65 && $this->i_iColWidth > 0) {
                $v_sRes .= '</div>';
            }
        }
        
        $v_sRes .= '<div>';
        if ($this->i_iOrderByIndex > 0 && !$this->i_bOrdDesc) {
            $v_sRes .= '<div class="ordrbt up"><img src="images/ArrowUpAct.png" /></div>';
        } else {
            $v_sRes .= '<div class="ordrbt up"><img src="images/ArrowUp.png" /></div>';
        }
        
        if ($this->i_iOrderByIndex > 0 && $this->i_bOrdDesc) {
            $v_sRes .= '<div class="ordrbt down"><img src="images/ArrowDownAct.png" /></div>';
        } else {
            $v_sRes .= '<div class="ordrbt down"><img src="images/ArrowDown.png" /></div>';
        }
        $v_sRes .= '</div>';
        
        $v_sRes .= '</div>';
        
        $v_sRes .= '</th>';
        return $v_sRes;
    }
    
    public function GetDataValCell() {
        $v_sRes = '<td style="';
        
        if ($this->i_sCss != '') {
            $v_sRes .= $this->i_sCss;
        }
        
        if ($this->i_iColWidth > 0) {
            $v_sRes .= 'max-width:' . $this->i_iColWidth . 'px; min-width:' . $this->i_iColWidth . 'px;';
        }
        
        if ($this->i_DataType == DataType::Integer || $this->i_DataType == DataType::Float) {
            $v_sRes .= 'text-align: right;';
        }
        
        if ($this->i_DataType == DataType::Bool) {
            $v_sRes .= 'text-align: center;';
        }
        
        $v_sRes .= '"';
        
        if ($this->i_DataType == DataType::Bool) {
            $v_sRes .= ' boolval="' . BoolTo01Str($this->GetValue()) . '" ';
        }
        
        $v_sRes .= '>';
        if (count($this->i_aFields) > 0) {
            for ($i = 0; $i < count($this->i_aFields); $i++) {
                $v_sRes .= $this->i_aFields[$i]->GetDataValCell();
            }
        } elseif ($this->i_DataType == DataType::Bool) {
            switch ($this->GetValue()) {
                case 0:
                    $v_sRes .= '<img src="images/checkbox-unchecked.png"/>';
                    break;
                case 1:
                    $v_sRes .= '<img src="images/checkbox-checked.png"/>';
                    break;
            }
        } else {
            $v_sRes .= $this->GetValue();
        }
        $v_sRes .= '</td>';
        return $v_sRes;
    }
    
    public function GetValue() {
        if ($this->i_sValue == '') {
            return '';
        }
        switch ($this->i_DataType) {
            case DataType::String:    return $this->i_sValue;
            case DataType::Float:     return number_format(floatval($this->i_sValue), 2, ',', ' ');
            case DataType::Date:      return date(DATE_FORMAT, strtotime($this->i_sValue));
            case DataType::DateTrnc:  return date('d.m.', strtotime($this->i_sValue));
            case DataType::Timestamp: return date(DATE_TIME_FORMAT, strtotime($this->i_sValue));
            case DataType::Bool:      return $this->i_sValue;
            case DataType::Integer:   return (
                $this->i_bFormatted
                    ? number_format(intval($this->i_sValue), 0, '', ' ')
                    : intval($this->i_sValue)
            );
        }
    }
    
    public function GetSelectCondition(&$a_aParams) {
        $v_sRes = '';
        if ($this->i_DataType == DataType::String && $this->i_sFilterText != '') {
            $v_sRes .= 'upper(' . $this->i_sColIndent . ') like \'%\' || upper(?) || \'%\'';
            $a_aParams[] = $this->i_sFilterText;
        } elseif (($this->i_DataType == DataType::Integer || $this->i_DataType == DataType::Float) && $this->i_iFilterNumber !== null) {
            $opr = '';
            switch ($this->i_FilterNumOper) {
                case Operator::Equal:
                    $opr = '=';
                    break;
                case Operator::IsBigger:
                    $opr = '>';
                    break;
                case Operator::IsBiggerEg:
                    $opr = '>=';
                    break;
                case Operator::IsSmaller:
                    $opr = '<';
                    break;
                case Operator::IsSmallerEq:
                    $opr = '<=';
                    break;
            }
            if ($opr != '') {
                $v_sRes .= $this->i_sColIndent . ' ' . $opr . ' ?';
                $a_aParams[] = $this->i_iFilterNumber;
            }
        } elseif ($this->i_DataType == DataType::Date || $this->i_DataType == DataType::DateTrnc) {
            if ($this->i_dFilterDateFrom !== null) {
                $v_sRes .= $this->i_sColIndent . ' >= ?';
                $a_aParams[] = date('d.m.Y', $this->i_dFilterDateFrom);
            }
            if ($this->i_dFilterDateTo !== null) {
                if ($this->i_dFilterDateFrom !== null) {
                    $v_sRes .= ' and ';
                }
                $v_sRes .= $this->i_sColIndent . ' <= ?';
                $a_aParams[] = date('d.m.Y', $this->i_dFilterDateTo);
            }
        }
        return $v_sRes;
    }
}
