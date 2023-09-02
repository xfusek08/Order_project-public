<?php
require_once './PHP/Settings.php';
require_once './PHP/Enums.php';
require_once './PHP/Logs.php';
require_once './PHP/FOPExport.php';

$filename =  EXPORT_FOLDER . DIRECTORY_SEPARATOR . $_GET['filename'];

if (!file_exists($filename)) {
    die ("File not found: $filename");
}

header('Content-Disposition: attachment; filename="' . $_GET['downloads'] . '"');
header('Content-Length: ' . filesize($filename));
readfile($filename);
if (!unlink($filename)) {
    Logging::WriteLog(LogType::Error, 'Unable to delete file: ' . $filename);
}
