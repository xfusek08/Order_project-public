<?php
session_start();

require_once './PHP/Settings.php';
require_once './PHP/Enums.php';
require_once './PHP/Logs.php';
require_once './PHP/FOPExport.php';
require_once './PHP/Database.php';
require_once './PHP/DataField.php';
require_once './PHP/Browser.php';
require_once './PHP/Page.php';
require_once './PHP/OverviewPage.php';
require_once './PHP/DBEntities.php';
require_once './PHP/SettingPage.php';
require_once './PHP/Transport.php';
require_once './PHP/OrderForm.php';
require_once './PHP/OrderPage.php';
require_once './PHP/CustomerPage.php';
require_once './PHP/DelivererPage.php';

function InitPage($a_bReload) {
    switch ($_SESSION['actpage']) {
        case 'home':
            if (isset($_SESSION['oweviewpage']) && !$a_bReload)
                return unserialize($_SESSION['oweviewpage']);
            else
                return new OverviewPage();
        case 'ord':
            if (isset($_SESSION['orderpage']) && !$a_bReload)
                return unserialize($_SESSION['orderpage']);
            else
                return new OrderPage();
        case 'cust':
            if (isset($_SESSION['customerpage']) && !$a_bReload)
                return unserialize($_SESSION['customerpage']);
            else
                return new CustomerPage();
        case 'dopr':
            if (isset($_SESSION['delivererpage']) && !$a_bReload)
                return unserialize($_SESSION['delivererpage']);
            else
                return new DelivererPage();
        case 'set':
            return new SettingPage();
        default:
            $_SESSION['actpage'] = 'ord';
            return new OrderPage();
            break;
    }
}

$NewPage = false;

if (isset($_GET['page'])) {
    $_SESSION['actpage'] = $_GET['page'];
    $NewPage = true;
} else if (!isset($_SESSION['actpage'])) {
    $_SESSION['actpage'] = 'ord';
}

$ActPage = InitPage(isset($_GET['reload']));
if (isset($_POST['ajax'])) {
    if ($_POST['type'] == 'getYearSummary') {
        echo GetYearSummary();
        exit;
    }
    if ($_POST['type'] == 'browser' && $_POST['brtype'] == 'resetfilters') {
        $v_oTmpPage = InitPage(true);
        $ActPage->i_oBrowser = $v_oTmpPage->i_oBrowser;
        if ($_SESSION['actpage'] == 'cust')
            $ActPage->ChangeCustomer($ActPage->i_oCustomer->i_iPK);
        $v_oTmpPage = null;
    }
    $ActPage->ProcessAjaxGlobal();
    SerializeActPage($ActPage);
    exit;
} else if (isset($_POST['pagepost'])) {
    $ActPage->ProcessPost();
} else {
    $ActPage->ProcessGet();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content='cs' />
    <title title="Objednávky">Objednávky</title>
    <link rel="shortcut icon" href="images/logoico.png" />
    <link rel="stylesheet" href="css/MainStyles.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/Browser.css" type="text/css" media="screen" />
    <script type="text/javascript" src="jscripts/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="jscripts/jQuerry-ui_1.11.4.min.js"></script>
    <script type="text/javascript" src="jscripts/utils.js"></script>
    <script type="text/javascript" src="jscripts/Browser.js"></script>
    <script type="text/javascript" src="jscripts/MainScripts.js"></script>
    <?php
    if ($ActPage->i_sFiles != '')
        echo $ActPage->i_sFiles;
    ?>
</head>

<body>
    <div class="maincontent">
        <div class="left">
            <div>
                <div>
                    <div class="intro">
                        <a href="?page=ord"></a>
                    </div>
                    <div>
                        <ul>
                            <!--<a href="?page=home"><li <?php //if ($_SESSION['actpage'] == 'home'){ echo('class="selected"'); }
                                                            ?>>Přehled</li></a> -->
                            <a href="?page=ord">
                                <li <?php if ($_SESSION['actpage'] == 'ord') {
                                        echo ('class="selected"');
                                    } ?>>Objednávky</li>
                            </a>
                            <a href="?page=cust">
                                <li <?php if ($_SESSION['actpage'] == 'cust') {
                                        echo ('class="selected"');
                                    } ?>>Zákazníci</li>
                            </a>
                            <a href="?page=dopr">
                                <li <?php if ($_SESSION['actpage'] == 'dopr') {
                                        echo ('class="selected"');
                                    } ?>>Dopravci</li>
                            </a>
                        </ul>
                        <hr />
                        <ul>
                            <a href="?page=set">
                                <li <?php if ($_SESSION['actpage'] == 'set') {
                                        echo ('class="selected"');
                                    } ?>>Nastavení</li>
                            </a>
                        </ul>
                    </div>
                </div>
                <div class="yearsummary">
                    <table>
                        <thead>
                            <th>
                                <div>
                                    <div>Rok</div>
                                    <div class="numth">měsíc</div>
                                </div>
                            </th>
                            <th>
                                <div>
                                    <div>Počet</div>
                                    <div style="padding-left: 3px;font-size: 10px;">objednávek</div>
                                </div>
                            </th>
                            <th>
                                <div>
                                    <div>Zisk</div>
                                    <div style="font-size: 10px;">[CZK]</div>
                                </div>
                            </th>
                        </thead>
                    </table>
                    <div class="scrolltable">
                        <table style="width: 100%">
                        </table>
                    </div>
                </div>
                <div class="version">Verze v0.8b (beta)</div>
            </div>
        </div>
        <div class="right">
            <div>
                <?php
                $ActPage->BuildPage();
                ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php

SerializeActPage($ActPage);
function SerializeActPage($ActPage) {
    switch ($_SESSION['actpage']) {
        case 'home':
            $_SESSION['oweviewpage'] = serialize($ActPage);
            break;
        case 'ord':
            $_SESSION['orderpage'] = serialize($ActPage);
            break;
        case 'cust':
            $_SESSION['customerpage'] = serialize($ActPage);
            break;
        case 'dopr':
            $_SESSION['delivererpage'] = serialize($ActPage);
            break;
        case 'set':
            break;
    }
}

function GetYearSummary() {
    $SQL = 'select count(1) as cnt, oror_cisloobjrok, sum(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)) as zisk from or_order where oror_isstorno = 0 group by oror_cisloobjrok order by oror_cisloobjrok desc';
    $fields = null;
    if (!MyDatabase::RunQuery($fields, $SQL, false)) {
        Logging::WriteLog(LogType::Error, 'GetYearSummary() - error on select');
        return 'Chyba databáze';
    }
    $resp = '<respxml><yearsummary>';
    for ($i = 0; $i < count($fields); $i++) {
        $resp .=
            '<year' .
            ' yearnum="' . $fields[$i]['OROR_CISLOOBJROK'] . '"' .
            ' profit="' . number_format(floatval($fields[$i]['ZISK']), 2, ',', ' ') . '"' .
            ' count="' . number_format(intval($fields[$i]['CNT']), 0, '', ' ') . '"' .
            '>';
        $SQL =
            'select' .
            '    count(1) as cnt,' .
            '    extract(month from oror_datum) as monthnum,' .
            '    sum(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)) as zisk' .
            '  from' .
            '    or_order' .
            '  where' .
            '    oror_isstorno = 0 and' .
            '    extract(year from oror_datum) = ?' .
            '  group by monthnum' .
            '  order by monthnum desc';
        $mothQuery = null;
        if (!MyDatabase::RunQuery($mothQuery, $SQL, false, $fields[$i]['OROR_CISLOOBJROK'])) {
            Logging::WriteLog(LogType::Error, 'GetYearSummary() - error on select');
            return 'Chyba databáze';
        }
        for ($j = 0; $j < count($mothQuery); $j++) {
            $resp .=
                '<month' .
                ' monthnum="' . $mothQuery[$j]['MONTHNUM'] . '"' .
                ' profit="' . number_format(floatval($mothQuery[$j]['ZISK']), 2, ',', ' ') . '"' .
                ' count="' . number_format(intval($mothQuery[$j]['CNT']), 0, '', ' ') . '"' .
                '/>';
        }
        $resp .= '</year>';
    }
    $resp .= '</yearsummary></respxml>';
    return $resp;
}
