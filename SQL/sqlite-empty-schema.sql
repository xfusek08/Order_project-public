
DROP TABLE IF EXISTS OR_CUSTOMER;
DROP TABLE IF EXISTS OR_CUSTADDRESS;
DROP TABLE IF EXISTS OR_DELIVERER;
DROP TABLE IF EXISTS OR_OBJNAKLVYKL;
DROP TABLE IF EXISTS OR_ORDER;
DROP TABLE IF EXISTS OR_SETUP;
DROP TABLE IF EXISTS OR_SPOT;

drop INDEX if exists ui_orcust_ident;
drop INDEX if exists ui_ordlv_raal;
drop INDEX if exists ui_oronv_objnaklvykl;

drop VIEW if exists OR_DELIVERER_V;

/* Table: OR_CUSTOMER, Owner: SYSDBA */
CREATE TABLE or_customer (
    orcust_pk PRIMARY KEY,
    orcust_created TIMESTAMP NOT NULL,
    orcust_firma VARCHAR(40),
    orcust_firma2 VARCHAR(40),
    orcust_pozn1 VARCHAR(40),
    orcust_pozn2 VARCHAR(40),
    orcust_stat VARCHAR(3),
    orcust_ico VARCHAR(8),
    orcust_dico VARCHAR(12),
    orcust_telefon VARCHAR(20),
    orcust_mail VARCHAR(40),
    orcust_color VARCHAR(20),
    orcust_psc VARCHAR(10),
    orcust_ident VARCHAR(20),
    orcust_prijemproc NUMERIC(3, 0) default 0 NOT NULL,
    orcust_mesto VARCHAR(100),
    orcust_ulice VARCHAR(100)
);


/* Table: OR_CUSTADDRESS, Owner: SYSDBA */
CREATE TABLE or_custaddress (
    orcadr_pk PRIMARY KEY,
    orcadr_customer INTEGER NOT NULL,
    orcadr_firma VARCHAR(40),
    orcadr_firma2 VARCHAR(40),
    orcadr_pozn1 VARCHAR(40),
    orcadr_pozn2 VARCHAR(40),
    orcadr_stat VARCHAR(3),
    orcadr_created TIMESTAMP NOT NULL,
    orcadr_naklnum NUMERIC(10, 0),
    orcadr_vyklnum NUMERIC(10, 0),
    orcadr_telnumber VARCHAR(20),
    orcadr_psc VARCHAR(10),
    orcadr_cas3 VARCHAR(50),
    orcadr_mesto VARCHAR(100),
    orcadr_ulice VARCHAR(100),
    FOREIGN KEY (orcadr_customer) REFERENCES or_customer (orcust_pk) ON DELETE CASCADE
);

/* Table: OR_DELIVERER, Owner: SYSDBA */
CREATE TABLE or_deliverer (
    ordlv_pk PRIMARY KEY,
    ordlv_dic VARCHAR(12),
    ordlv_email VARCHAR(30),
    ordlv_ic VARCHAR(10),
    ordlv_raal VARCHAR(3),
    ordlv_spz VARCHAR(15),
    ordlv_blokace VARCHAR(40),
    ordlv_created TIMESTAMP NOT NULL,
    ordlv_objnum NUMERIC(5, 0),
    ordlv_obrat NUMERIC(18, 2),
    ordlv_zisk NUMERIC(18, 2),
    ordlv_koeficient NUMERIC(18, 2),
    ordlv_pozn VARCHAR(50),
    ordlv_stat VARCHAR(3),
    ordlv_telnum VARCHAR(30),
    ordlv_psc VARCHAR(10),
    ordlv_mesto VARCHAR(100),
    ordlv_ulice VARCHAR(100),
    ordlv_jmeno VARCHAR(100),
    ordlv_firma VARCHAR(100)
);


/* Table: OR_ORDER, Owner: SYSDBA */
CREATE TABLE or_order (
    oror_pk PRIMARY KEY,
    oror_bokemkdo VARCHAR(3),
    oror_datum DATE NOT NULL,
    oror_duedate DATE,
    oror_factprij VARCHAR(20),
    oror_factvyd VARCHAR(20),
    oror_pozn VARCHAR(50),
    oror_prijem NUMERIC(6, 2) NOT NULL,
    oror_raal VARCHAR(3) NOT NULL,
    oror_vydej NUMERIC(6, 2) NOT NULL,
    oror_zakaznikident VARCHAR(15) NOT NULL,
    oror_zisk NUMERIC(6, 2),
    oror_doprdic VARCHAR(12),
    oror_dopric VARCHAR(10),
    oror_doprspz VARCHAR(15),
    oror_created TIMESTAMP NOT NULL,
    oror_doprfirma VARCHAR(30),
    oror_doprstat VARCHAR(3),
    oror_cisloobj NUMERIC(10, 0) NOT NULL,
    oror_cisloobjrok NUMERIC(10, 0),
    oror_isslozeno NUMERIC(1, 0) default 0 NOT NULL,
    oror_isstorno NUMERIC(1, 0) default 0 NOT NULL,
    oror_doprtel VARCHAR(30),
    oror_smlcenatext VARCHAR(40) NOT NULL,
    oror_doprpsc VARCHAR(10),
    oror_dalsiinfo VARCHAR(100),
    oror_bokempozn VARCHAR(100),
    oror_bokemcastka NUMERIC(5, 0),
    oror_doprmesto VARCHAR(100),
    oror_doprulice VARCHAR(100),
    oror_doprjmeno VARCHAR(100)
);

/* Table: OR_SETUP, Owner: SYSDBA */
CREATE TABLE or_setup (
    orset_pk PRIMARY KEY,
    orset_cisloobj NUMERIC(4, 0) NOT NULL,
    orset_cisloobjrok NUMERIC(2, 0) NOT NULL,
    orset_pdfdir VARCHAR(100)
);

/* Table: OR_SPOT, Owner: SYSDBA */
CREATE TABLE or_spot (
    orspt_pk PRIMARY KEY,
    orspt_date DATE,
    orspt_exptxtpre VARCHAR(2),
    orspt_exptxtpost VARCHAR(10),
    orspt_firma VARCHAR(40),
    orspt_firma2 VARCHAR(40),
    orspt_pozn1 VARCHAR(40),
    orspt_pozn2 VARCHAR(40),
    orspt_stat VARCHAR(3),
    orspt_psc VARCHAR(10),
    orspt_term VARCHAR(100),
    orspt_cas1 VARCHAR(10),
    orspt_cas3 VARCHAR(50),
    orspt_mesto VARCHAR(100),
    orspt_ulice VARCHAR(100)
);

/* Table: OR_OBJNAKLVYKL, Owner: SYSDBA */
CREATE TABLE or_objnaklvykl (
    oronv_pk PRIMARY KEY,
    oronv_obj INTEGER NOT NULL,
    oronv_nakl INTEGER NOT NULL,
    oronv_vykl INTEGER NOT NULL,
    oronv_vaha NUMERIC(5, 0),
    oronv_poznadr VARCHAR(100),
    oronv_poznobj VARCHAR(100),
    oronv_zbozipopis VARCHAR(100),
    FOREIGN KEY (oronv_obj) REFERENCES or_order (oror_pk) ON DELETE CASCADE,
    FOREIGN KEY (oronv_nakl) REFERENCES or_spot (orspt_pk),
    FOREIGN KEY (oronv_vykl) REFERENCES or_spot (orspt_pk)
);


/*  Index definitions for all user tables */

CREATE UNIQUE INDEX ui_orcust_ident ON or_customer (orcust_ident);
CREATE UNIQUE INDEX ui_ordlv_raal ON or_deliverer (ordlv_raal);
CREATE UNIQUE INDEX ui_oronv_objnaklvykl ON or_objnaklvykl (oronv_obj, oronv_nakl, oronv_vykl);

-- View: OR_DELIVERER_V, Owner: SYSDBA
CREATE MATERIALIZED VIEW OR_DELIVERER_V AS
select ordlv_pk,
    ordlv_raal,
    ordlv_firma,
    ordlv_mesto,
    ordlv_jmeno,
    ordlv_telnum,
    ordlv_pozn,
    ordlv_blokace,
    COALESCE (ordercount, 0) as ordercount,
    COALESCE (obrat, 0) as obrat,
    COALESCE (zisk, 0) as zisk,
    COALESCE (zisk / ordercount, 0) as koeficient
from or_deliverer
    left outer join (
        select oror_raal,
            count(1) as ordercount,
            sum(oror_vydej) as obrat,
            sum(
                oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)
            ) as zisk
        from (
                select oror_raal,
                    oror_vydej,
                    oror_prijem,
                    oror_bokemcastka
                from or_order
                where oror_isstorno != 1
            )
        group by oror_raal
    ) on oror_raal = ordlv_raal;