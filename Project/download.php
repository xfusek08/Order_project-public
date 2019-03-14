<?php
require_once './PHP/Settings.php';
require_once './PHP/Enums.php';
require_once './PHP/Logs.php';
require_once './PHP/FOPExport.php';

$filename =  EXPORT_FOLDER . '\\' . $_GET['filename'];

if(file_exists($filename))
{
  header('Content-Disposition: attachment; filename="'. $_GET['downloadas'] . '"');
  header('Content-Length: ' . filesize($filename));
  readfile($filename);
  if (!unlink($filename))
    Logging::WriteLog(LogType::Error, 'Unable to delete file: ' . $filename);

  exit;
}
else
{
  echo 'File not found';
}