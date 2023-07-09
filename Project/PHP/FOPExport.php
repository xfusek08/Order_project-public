<?php
class FOPExport
{
    public static function GetOrderTemplate() {
        return EXPORT_FOLDER . '\\templates\\OrderTemplate.fo';
    }
    
    public static function RunFOP($xml, $a_sTemplate) {
        Logging::WriteLog(LogType::Announcement, 'RunFop() ...');
        
        $xml = str_replace('&', "&amp;", $xml);
        $v_sNewFileIdent = GUID();
        
        $v_sXMLFilePath = EXPORT_FOLDER . '\\' . $v_sNewFileIdent . '.xml';
        $v_sPDFFilePath = EXPORT_FOLDER . '\\' . $v_sNewFileIdent . '.pdf';
        
        if (!file_exists($a_sTemplate)) {
            Logging::WriteLog(LogType::Error, 'Template file not found: ' . $a_sTemplate);
            return '';
        }
        
        try {
            $XMLFile = fopen($v_sXMLFilePath, "w");
            fwrite($XMLFile, $xml);
            fclose($XMLFile);
        } catch (Exception $e) {
            Logging::WriteLog(LogType::Error, 'Error during creating a xml file: ' . $v_sXMLFilePath);
            return '';
        }
        
        $v_sExecString =
            FOP_FOLDER . '\\fop' .
            ' -c ' . FOP_FOLDER . '\\conf\\myfop.xconf' .
            ' -xml ' . $v_sXMLFilePath .
            ' -xsl ' . $a_sTemplate .
            ' -pdf ' . $v_sPDFFilePath;
        try {
            Logging::WriteLog(LogType::Announcement, 'executing: ' . $v_sExecString);
            exec($v_sExecString);
            Logging::WriteLog(LogType::Announcement, 'FOP complete.');
        } catch (Exception $e) {
            Logging::WriteLog(LogType::Announcement, 'Fop error: ' . $e->getMessage());
            return '';
        }
        
        if (!unlink($v_sXMLFilePath)) {
            Logging::WriteLog(LogType::Error, 'Unable to delete file: ' . $v_sXMLFilePath);
        }
        
        if (!file_exists($v_sPDFFilePath)) {
            return '';
        }
        
        return $v_sNewFileIdent . '.pdf';
    }
}
