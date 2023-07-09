<?php

class OverviewPage extends Page
{
    public function __construct() {
    }

    public function BuildPage() {
        parent::BuildPage();
        $SQL = "
            select
                oror_cisloobjrok,
                sum(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)) as zisk
            from
                or_order
            group by
                oror_cisloobjrok
            order by
                oror_cisloobjrok desc
        ";
        $fields = null;
        
        if (!MyDatabase::RunQuery($fields, $SQL, false)) {
            echo 'Chyba databáze';
            return;
        }
?>
        <table>
            <thead>
                <th>Rok</th>
                <th>Zisk</th>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($fields); $i++): ?>
                    <tr>
                        <td><?= $fields[$i]['OROR_CISLOOBJROK']; ?></td>
                        <td><?= $fields[$i]['ZISK']; ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
<?php
    }
}
