<?php
class FOPExport
{
  public static function GetOrderTemplate()
  {
    return EXPORT_FOLDER . '\\templates\\OrderTemplate.fo';
  }
  public static function RunFOP($xml, $a_sTemplate)
  {
    Logging::WriteLog(LogType::Anouncement, 'RunFop() ...');
    
    $xml = str_replace('&', "&amp;", $xml);
    $v_sNewFileident = GUID();
    
    $v_sXMLFilePath = EXPORT_FOLDER . '\\' . $v_sNewFileident . '.xml';
    $v_sPDFFilePath = EXPORT_FOLDER . '\\' . $v_sNewFileident . '.pdf';
    
    if (!file_exists($a_sTemplate))
    {
      Logging::WriteLog(LogType::Error, 'Template file not found: ' . $a_sTemplate);
      return '';
    }
    
    //xml
    try
    {
      $XMLFile = fopen($v_sXMLFilePath, "w");
      fwrite($XMLFile, $xml);
      fclose($XMLFile);
    }
    catch (Exception $e)
    {
      Logging::WriteLog(LogType::Error, 'Error during creating a xml file: ' . $v_sXMLFilePath);
      return '';
    }
    
    $v_sExecString = 
      FOP_FOLDER . '\\fop'.
      ' -c ' . FOP_FOLDER . '\\conf\\myfop.xconf'.
      ' -xml ' . $v_sXMLFilePath.
      ' -xsl ' . $a_sTemplate.
      ' -pdf ' . $v_sPDFFilePath;
    try
    {
      Logging::WriteLog(LogType::Anouncement, 'executing: ' . $v_sExecString);      
      exec($v_sExecString);      
      Logging::WriteLog(LogType::Anouncement, 'FOP complete.');
    }
    catch(Exception $e)
    {
      Logging::WriteLog(LogType::Anouncement, 'Fop error: ' . $e->getMessage());  
      return '';
    }   
    
    if (!unlink($v_sXMLFilePath))
      Logging::WriteLog(LogType::Error, 'Unable to delete file: ' . $v_sXMLFilePath);
    
    if (!file_exists($v_sPDFFilePath))
      return '';
    
    return $v_sNewFileident . '.pdf';
  }   
}
