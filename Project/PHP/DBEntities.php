<?php
require_once 'DatabaseEntity.php';

class Customer extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_CUSTOMER';
        $this->i_sPKColName = 'ORCUST_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::String, 'orcust_ident');
        $this->AddColumn(DataType::String, 'orcust_firma');
        $this->AddColumn(DataType::String, 'orcust_firma2');
        $this->AddColumn(DataType::String, 'orcust_mesto');
        $this->AddColumn(DataType::String, 'orcust_pozn1');
        $this->AddColumn(DataType::String, 'orcust_pozn2');
        $this->AddColumn(DataType::String, 'orcust_psc');
        $this->AddColumn(DataType::String, 'orcust_stat');
        $this->AddColumn(DataType::String, 'orcust_ulice');
        $this->AddColumn(DataType::String, 'orcust_ico');
        $this->AddColumn(DataType::String, 'orcust_dico');
        $this->AddColumn(DataType::String, 'orcust_color');
        $this->AddColumn(DataType::String, 'orcust_telefon');
        $this->AddColumn(DataType::String, 'orcust_mail');
        $this->AddColumn(DataType::String, 'orcust_prijemproc', true, '0');
    }
}

class CustomerAddress extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_CUSTADDRESS';
        $this->i_sPKColName = 'ORCADR_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::Integer, 'orcadr_customer');
        $this->AddColumn(DataType::String, 'orcadr_cas3');
        $this->AddColumn(DataType::String, 'orcadr_firma', true);
        $this->AddColumn(DataType::String, 'orcadr_firma2');
        $this->AddColumn(DataType::String, 'orcadr_mesto');
        $this->AddColumn(DataType::String, 'orcadr_pozn1');
        $this->AddColumn(DataType::String, 'orcadr_pozn2');
        $this->AddColumn(DataType::String, 'orcadr_psc');
        $this->AddColumn(DataType::String, 'orcadr_stat');
        $this->AddColumn(DataType::String, 'orcadr_ulice');
        $this->AddColumn(DataType::Integer, 'orcadr_naklnum', true, '0');
        $this->AddColumn(DataType::Integer, 'orcadr_vyklnum', true, '0');
        $this->AddColumn(DataType::String, 'orcadr_telnumber');
    }
}

class Deliverer extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_DELIVERER';
        $this->i_sPKColName = 'ORDLV_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::String, 'ordlv_dic');
        $this->AddColumn(DataType::String, 'ordlv_email');
        $this->AddColumn(DataType::String, 'ordlv_firma', true);
        $this->AddColumn(DataType::String, 'ordlv_ic');
        $this->AddColumn(DataType::String, 'ordlv_mesto');
        $this->AddColumn(DataType::String, 'ordlv_raal', true);
        $this->AddColumn(DataType::String, 'ordlv_telnum', true);
        $this->AddColumn(DataType::String, 'ordlv_spz');
        $this->AddColumn(DataType::String, 'ordlv_ulice');
        $this->AddColumn(DataType::String, 'ordlv_blokace');
        $this->AddColumn(DataType::String, 'ordlv_jmeno', true);
        $this->AddColumn(DataType::String, 'ordlv_stat');
        $this->AddColumn(DataType::String, 'ordlv_psc');
        $this->AddColumn(DataType::String, 'ordlv_pozn');
        $this->AddSQLColumn(
            DataType::Integer,
            'ordlv_objnum',
            'select count(1) from or_order where oror_raal = ordlv_raal and oror_isstorno != 1'
        );
        $this->AddSQLColumn(
            DataType::Float,
            'ordlv_obrat',
            'select COALESCE (sum(oror_vydej), 0)  from or_order where oror_raal = ordlv_raal and oror_isstorno != 1'
        );
        $this->AddSQLColumn(
            DataType::Float,
            'ordlv_koeficient',
            'select COALESCE (sum((oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)))/count(1), 0)  from or_order where oror_raal = ordlv_raal and oror_isstorno != 1'
        );
        $this->AddSQLColumn(
            DataType::Float,
            'ordlv_zisk',
            'select COALESCE (sum((oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0))), 0)  from or_order where oror_raal = ordlv_raal and oror_isstorno != 1'
        );
    }
}

class Order extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_ORDER';
        $this->i_sPKColName = 'OROR_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::Integer, 'oror_bokemcastka');
        $this->AddColumn(DataType::String, 'oror_bokemkdo');
        $this->AddColumn(DataType::String, 'oror_bokempozn');
        $this->AddColumn(DataType::Date, 'oror_datum', true, date(DATE_FORMAT));
        $this->AddColumn(DataType::Date, 'oror_duedate');
        $this->AddColumn(DataType::String, 'oror_factprij');
        $this->AddColumn(DataType::String, 'oror_factvyd');
        $this->AddColumn(DataType::Integer, 'oror_cisloobj', true);
        $this->AddColumn(DataType::Integer, 'oror_cisloobjrok')->i_bUnformatted = true;
        $this->AddColumn(DataType::String, 'oror_pozn');
        $this->AddColumn(DataType::Float, 'oror_prijem', true);
        $this->AddColumn(DataType::Float, 'oror_vydej', true);
        $this->AddColumn(DataType::String, 'oror_raal', true);
        $this->AddColumn(DataType::Bool, 'oror_isslozeno', true, false);
        $this->AddColumn(DataType::Bool, 'oror_isstorno', true, false);
        $this->AddColumn(DataType::String, 'oror_zakaznikident', true);
        $this->AddColumn(DataType::Float, 'oror_zisk');
        $this->AddColumn(DataType::String, 'oror_dalsiinfo');
        $this->AddColumn(DataType::String, 'oror_smlcenatext');
        $this->AddColumn(DataType::String, 'oror_doprdic');
        $this->AddColumn(DataType::String, 'oror_dopric');
        $this->AddColumn(DataType::String, 'oror_doprfirma', true);
        $this->AddColumn(DataType::String, 'oror_doprstat');
        $this->AddColumn(DataType::String, 'oror_doprjmeno', true);
        $this->AddColumn(DataType::String, 'oror_doprmesto');
        $this->AddColumn(DataType::String, 'oror_doprpsc');
        $this->AddColumn(DataType::String, 'oror_doprspz');
        $this->AddColumn(DataType::String, 'oror_doprtel', true);
        $this->AddColumn(DataType::String, 'oror_doprulice');
    }
}

class Spot extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_SPOT';
        $this->i_sPKColName = 'ORSPT_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::Date, 'orspt_date');
        $this->AddColumn(DataType::String, 'orspt_exptxtpre');
        $this->AddColumn(DataType::String, 'orspt_exptxtpost');
        $this->AddColumn(DataType::String, 'orspt_firma', true);
        $this->AddColumn(DataType::String, 'orspt_firma2');
        $this->AddColumn(DataType::String, 'orspt_mesto');
        $this->AddColumn(DataType::String, 'orspt_pozn1');
        $this->AddColumn(DataType::String, 'orspt_pozn2');
        $this->AddColumn(DataType::String, 'orspt_psc');
        $this->AddColumn(DataType::String, 'orspt_stat');
        $this->AddColumn(DataType::String, 'orspt_ulice');
        $this->AddColumn(DataType::String, 'orspt_term');
        $this->AddColumn(DataType::String, 'orspt_cas1');
        $this->AddColumn(DataType::String, 'orspt_cas3');
    }
}

class Setting extends DatabaseEntity
{
    public function __construct($a_iPK = 0, $ExternTransaction = false) {
        $this->i_sTableName = 'OR_SETUP';
        $this->i_sPKColName = 'ORSET_PK';
        parent::__construct($a_iPK, $ExternTransaction);
    }
    
    protected function DefColumns() {
        $this->AddColumn(DataType::Integer, 'orset_cisloobj');
        $this->AddColumn(DataType::Integer, 'orset_cisloobjrok');
        $this->AddColumn(DataType::String, 'orset_pdfdir');
    }
}
