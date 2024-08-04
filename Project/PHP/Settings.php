<?php
// time formats

define("DATE_TIME_FORMAT", "d.m.Y H:i:s");
define("DATE_FORMAT", "d.m.Y");

//time zone
date_default_timezone_set('Europe/Prague');

// database
define("DATABASE_DNS", 'firebird:dbname=firebird:/firebird/data/ORDER.fdb');
define("DATABASE_USER", "sysdba");
define("DATABASE_PASSWORD", "masterkey");

//Logs
define("LOG_FOLDER", 'logs');

// FOP
define("EXPORT_FOLDER", 'export');
define("FOP_FOLDER", 'fop');

function GetCzechDayName($day) {
    static $names = array('neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota');
    return $names[$day];
}

function BoolTo01Str($var) {
    if (boolval($var)) {
        return '1';
    } else {
        return '0';
    }
}

function Str01ToBoolInt($var) {
    if ($var === '1') {
        return 1;
    } elseif ($var === '0') {
        return 0;
    }
    return null;
}

function BoolTo01($var) {
    if (boolval($var)) {
        return 1;
    } else {
        return 0;
    }
}

function IsTimestamp($var) {
    if (!(is_int($var) || is_float($var))) {
        return false;
    }
    return true;
}

function validateDate($date, $format = DATE_FORMAT) {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validateDateTime($date, $format = DATE_TIME_FORMAT) {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function IncObjCount($ExternTransaction = false) {
    $fields = null;
    if (!MyDatabase::RunQuery(
        $fields,
        "
            update
                or_setup
            set
                orset_cisloobj = (select max(orset_cisloobj) + 1 from or_setup) returning orset_cisloobj
        ",
        $ExternTransaction
    )) {
        return -1;
    }
    return intval($fields[0][0]);
}

function GUID() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
