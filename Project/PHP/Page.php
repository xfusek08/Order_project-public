<?php
class Page
{
    public $i_oBrowser;
    public $i_sFiles;
    public $i_aAlerts;
    
    public function __construct() {
        $this->i_oBrowser = null;
        $this->i_sFiles = '';
        $this->i_aAlerts = array();
    }
    
    public function BuildPage() {
        echo $this->GetAlertXML();
        $this->CleanAlerts();
    }
    
    public function ProcessPost() {}
    
    public function ProcessGet() {}
    
    public function ProcessAjaxGlobal() {
        $this->ProcessAjax();
        echo $this->GetAlertXML();
        $this->CleanAlerts();
    }
    
    public function ProcessAjax() {
        if ($_POST['type'] == 'browser' && $this->i_oBrowser !== null) {
            if (!$this->i_oBrowser->ProcessAjax()) {
                $this->AddAlert('red', 'Chyba při načítání parametrů pro browser.');
            }
            return true;
        }
        return false;
    }
    
    public function AddAlert($a_sColor, $a_sMessage) {
        $this->i_aAlerts[] = new Alert($a_sColor, $a_sMessage);
    }
    
    public function CleanAlerts() {
        $this->i_aAlerts = array();
    }
    
    // private
    private function GetAlertXML() {
        $v_sAlertHTML = '<alerts>';
        for ($i = 0; $i < count($this->i_aAlerts); $i++) {
            $v_sAlertHTML .= '<alert><color>' . $this->i_aAlerts[$i]->i_sColor . '</color><message>' . $this->i_aAlerts[$i]->i_sMessage . '</message></alert>';
        }
        $v_sAlertHTML .= '</alerts>';
        return $v_sAlertHTML;
    }
}

class Alert
{
    public $i_sMessage;
    public $i_sColor;
    
    public function __construct($a_sColor, $a_sMessage) {
        $this->i_sColor = $a_sColor;
        $this->i_sMessage = $a_sMessage;
        Logging::WriteLog(LogType::Announcement, 'Alert created: ' . $this->i_sColor . '; ' . $this->i_sMessage);
    }
}
