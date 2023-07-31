
-- SET SQL DIALECT 3;

-- /* CREATE DATABASE 'C:/Orders/DB/ORDER.FDB' PAGE_SIZE 4096 DEFAULT CHARACTER SET UTF8 */

-- /*  Collations */
-- CREATE COLLATION NC_UTF8_CZ FOR UTF8 FROM EXTERNAL ('UNICODE') PAD SPACE CASE INSENSITIVE 'COLL-VERSION=153.72;LOCALE=cs_CZ';


-- /*  Generators or sequences */
-- CREATE GENERATOR GN_IS_TCOMPHIST;
-- CREATE GENERATOR GN_IS_TCOMPONENT;
-- CREATE GENERATOR GN_ORCADR;
-- CREATE GENERATOR GN_ORCUST;
-- CREATE GENERATOR GN_ORDLV;
-- CREATE GENERATOR GN_ORONV;
-- CREATE GENERATOR GN_OROR;
-- CREATE GENERATOR GN_ORSPT;

/* Domain definitions */
-- CREATE DOMAIN ND_BIGNUM AS NUMERIC(18, 0);
-- CREATE DOMAIN ND_BLOBB AS BLOB SUB_TYPE 0 SEGMENT SIZE 80;
-- CREATE DOMAIN ND_BLOBT AS BLOB SUB_TYPE TEXT SEGMENT SIZE 80;
-- CREATE DOMAIN ND_BOOL AS CHAR(1)
--          CHECK (VALUE IN ('0','1'));
-- CREATE DOMAIN INTEGER AS INTEGER;
-- CREATE DOMAIN ND_DATE AS DATE;
-- CREATE DOMAIN ND_DESCRIPTION AS VARCHAR(4000);
-- CREATE DOMAIN ND_ID AS VARCHAR(10);
-- CREATE DOMAIN ND_INT AS INTEGER;
-- CREATE DOMAIN ND_SHORTTEXT AS VARCHAR(20);
-- CREATE DOMAIN ND_TEXT AS VARCHAR(100);
-- CREATE DOMAIN ND_TIMESTAMP AS TIMESTAMP;
-- CREATE DOMAIN ND_WWW AS VARCHAR(300);


/* Table: OR_CUSTADDRESS, Owner: SYSDBA */
CREATE TABLE or_custaddress (
        orcadr_pk INTEGER NOT NULL auto_increment,
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
CONSTRAINT pk_orcadr PRIMARY KEY (orcadr_pk));

/* Table: OR_CUSTOMER, Owner: SYSDBA */
CREATE TABLE or_customer (orcust_pk INTEGER NOT NULL auto_increment,
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
        orcust_ulice VARCHAR(100),
CONSTRAINT pk_orcust PRIMARY KEY (orcust_pk));

/* Table: OR_DELIVERER, Owner: SYSDBA */
CREATE TABLE or_deliverer (ordlv_pk INTEGER NOT NULL auto_increment,
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
        ordlv_firma VARCHAR(100),
CONSTRAINT pk_ordlv PRIMARY KEY (ordlv_pk));

/* Table: OR_OBJNAKLVYKL, Owner: SYSDBA */
CREATE TABLE or_objnaklvykl (oronv_pk INTEGER NOT NULL auto_increment,
        oronv_obj INTEGER NOT NULL,
        oronv_nakl INTEGER NOT NULL,
        oronv_vykl INTEGER NOT NULL,
        oronv_vaha NUMERIC(5, 0),
        oronv_poznadr VARCHAR(100),
        oronv_poznobj VARCHAR(100),
        oronv_zbozipopis VARCHAR(100),
CONSTRAINT pk_oronv PRIMARY KEY (oronv_pk));

/* Table: OR_ORDER, Owner: SYSDBA */
CREATE TABLE or_order (oror_pk INTEGER NOT NULL auto_increment,
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
        oror_doprjmeno VARCHAR(100),
CONSTRAINT pk_oror PRIMARY KEY (oror_pk));

/* Table: OR_SETUP, Owner: SYSDBA */
CREATE TABLE or_setup (orset_pk INTEGER NOT NULL auto_increment,
        orset_cisloobj NUMERIC(4, 0) NOT NULL,
        orset_cisloobjrok NUMERIC(2, 0) NOT NULL,
        orset_pdfdir VARCHAR(100),
CONSTRAINT pk_orset PRIMARY KEY (orset_pk));

/* Table: OR_SPOT, Owner: SYSDBA */
CREATE TABLE or_spot (orspt_pk INTEGER NOT NULL auto_increment,
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
        orspt_ulice VARCHAR(100),
CONSTRAINT pk_orspt PRIMARY KEY (orspt_pk));

-- /* Table: ROOT, Owner: SYSDBA */
-- CREATE TABLE ROOT (X INTEGER);

/*  Index definitions for all user tables */
CREATE UNIQUE INDEX ui_orcust_ident ON or_customer (orcust_ident);
CREATE UNIQUE INDEX ui_ordlv_raal ON or_deliverer (ordlv_raal);
CREATE UNIQUE INDEX ui_oronv_objnaklvykl ON or_objnaklvykl (oronv_obj, oronv_nakl, oronv_vykl);

-- ALTER TABLE IS_TCOMPHIST ADD CONSTRAINT FK_IS_TCOMPHISTORY FOREIGN KEY (ISTCH_FCOMPONENT) REFERENCES IS_TCOMPONENT (IST_PK);

alter TABLE or_custaddress ADD CONSTRAINT fk_orcadr_customer FOREIGN KEY (orcadr_customer) REFERENCES or_customer (orcust_pk) ON DELETE CASCADE;

ALTER TABLE or_objnaklvykl ADD CONSTRAINT fk_oronv_nakl FOREIGN KEY (oronv_nakl) REFERENCES or_spot (orspt_pk);

ALTER TABLE or_objnaklvykl ADD CONSTRAINT fk_oronv_obj FOREIGN KEY (oronv_obj) REFERENCES or_order (oror_pk) ON DELETE CASCADE;

ALTER TABLE or_objnaklvykl ADD CONSTRAINT fk_oronv_vykl FOREIGN KEY (oronv_vykl) REFERENCES or_spot (orspt_pk);

-- /* View: OR_DELIVERER_V, Owner: SYSDBA */
-- CREATE VIEW OR_DELIVERER_V (ORDLV_PK, ORDLV_RAAL, ORDLV_FIRMA, ORDLV_MESTO, ORDLV_JMENO, ORDLV_TELNUM, ORDLV_POZN, ORDLV_BLOKACE, ORDERCOUNT, OBRAT, ZISK, KOEFICIENT) AS

--   select
-- 		  ordlv_pk,
-- 		  ordlv_raal,
-- 		  ordlv_firma,
-- 		  ordlv_mesto,
-- 		  ordlv_jmeno,
-- 		  ordlv_telnum,
-- 		  ordlv_pozn,
-- 		  ordlv_blokace,
-- 		  COALESCE (ordercount, 0) as ordercount,
-- 		  COALESCE (obrat, 0) as obrat,
-- 		  COALESCE (zisk, 0) as zisk,
-- 		  COALESCE (zisk / ordercount, 0) as koeficient
-- 		from
-- 		  or_deliverer
-- 		  left outer join (
-- 		    select
-- 		      oror_raal,
-- 		      count(1) as ordercount,
-- 		      sum(oror_vydej) as obrat,
-- 		      sum(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)) as zisk
-- 		    from
-- 		      (select oror_raal, oror_vydej, oror_prijem, oror_bokemcastka from or_order where oror_isstorno != 1)
-- 		    group by oror_raal
-- 		  ) on oror_raal = ordlv_raal
-- ;

-- /*  Exceptions */
-- CREATE EXCEPTION EX_NO_DATA_FOUND 'No data found';
-- CREATE EXCEPTION EX_VAL_IS_NULL 'Value is null';
-- SET TERM ^ ;

-- /* Triggers only will work for SQL triggers */
-- CREATE TRIGGER TG_ORCADR_BFINS FOR OR_CUSTADDRESS
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.orcadr_pk is null) then
--     new.orcadr_pk = gen_id (gn_orcadr, 1);
--   new.orcadr_created = current_timestamp;
-- end ^

-- CREATE TRIGGER TG_ORCUST_BFINS FOR OR_CUSTOMER
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.orcust_pk is null) then
--     new.orcust_pk = gen_id (gn_orcust, 1);
--   new.orcust_created = current_timestamp;
-- end ^

-- CREATE TRIGGER TG_ORDLV_BFINS FOR OR_DELIVERER
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.ordlv_pk is null) then
--     new.ordlv_pk = gen_id (gn_ordlv, 1);
--   new.ordlv_created = current_timestamp;
-- end ^

-- CREATE TRIGGER TG_ORONV_BFINS FOR OR_OBJNAKLVYKL
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.oronv_pk is null) then
--     new.oronv_pk = gen_id (gn_oronv, 1);
-- end ^

-- CREATE TRIGGER TG_OROR_BFINS FOR OR_ORDER
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.oror_pk is null) then
--     new.oror_pk = gen_id (gn_oror, 1);
--   new.oror_created = current_timestamp;
-- end ^

-- CREATE TRIGGER TG_ORSPT_BFINS FOR OR_SPOT
-- ACTIVE BEFORE INSERT POSITION 0
-- as
-- begin
--   if (new.orspt_pk is null) then
--     new.orspt_pk = gen_id (gn_orspt, 1);
-- end ^

-- COMMIT WORK ^
-- SET TERM ; ^

-- /* Comments for database objects. */
-- COMMENT ON TABLE        OR_CUSTADDRESS IS 'Adresa zĂˇkaznĂ­ka';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_CUSTOMER IS 'zĂˇkaznĂ­k';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_FIRMA IS 'firma 1';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_FIRMA2 IS 'firma 2';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_POZN1 IS 'poznĂˇmka 1';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_POZN2 IS 'poznĂˇmka 2';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_STAT IS 'stĂˇt';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_CREATED IS 'vytvoĹ™eno';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_NAKLNUM IS 'PoÄŤet naklĂˇdek';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_VYKLNUM IS 'TelefonnĂ­ ÄŤĂ­slo';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_PSC IS 'PSÄŚ';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_CAS3 IS 'ÄŚas 3';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_MESTO IS 'mÄ›sto';
-- COMMENT ON    COLUMN    OR_CUSTADDRESS.ORCADR_ULICE IS 'ulice';
-- COMMENT ON TABLE        OR_CUSTOMER IS 'ZĂˇkaznĂ­k - ident';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_CREATED IS 'vytvoĹ™eno';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_FIRMA IS 'firma';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_FIRMA2 IS 'firma2';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_POZN1 IS 'poznĂˇmka';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_POZN2 IS 'poznĂˇmka 2';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_STAT IS 'StĂˇt';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_ICO IS 'IÄŚO';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_DICO IS 'DIÄŚO';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_TELEFON IS 'telefon';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_MAIL IS 'mail';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_COLOR IS 'barva';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_PSC IS 'PSÄŚ';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_IDENT IS 'ident (zkratka)';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_PRIJEMPROC IS 'Procento z pĹ™Ă­jmĹŻ';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_MESTO IS 'mÄ›sto';
-- COMMENT ON    COLUMN    OR_CUSTOMER.ORCUST_ULICE IS 'ulice';
-- COMMENT ON TABLE        OR_DELIVERER IS 'Objednavky evidence';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_DIC IS 'DIÄŚ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_EMAIL IS 'e-mail';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_IC IS 'IÄŚ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_RAAL IS 'raal';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_SPZ IS 'SPZ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_BLOKACE IS 'blokace';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_CREATED IS 'vytvoĹ™eno';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_OBJNUM IS 'mnoĹľstvĂ­ objednĂˇvek';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_OBRAT IS 'obrat - souÄŤet pĹ™Ă­jmĹŻ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_ZISK IS 'zisk - souÄŤet vĂ˝dajĹŻ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_KOEFICIENT IS 'koeficient';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_POZN IS 'poznĂˇmka';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_STAT IS 'stĂˇt';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_TELNUM IS 'telefonnĂ­ ÄŤĂ­slo';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_PSC IS 'PSÄŚ';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_MESTO IS 'mÄ›sto';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_ULICE IS 'ulice';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_JMENO IS 'jmĂ©no';
-- COMMENT ON    COLUMN    OR_DELIVERER.ORDLV_FIRMA IS 'firma';
-- COMMENT ON TABLE        OR_OBJNAKLVYKL IS 'ObjednĂˇvka  - historizace - NaklĂˇdka/vyklĂˇdka';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_OBJ IS 'objednĂˇvka';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_NAKL IS 'naklĂˇdka';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_VYKL IS 'vyklĂˇdka';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_VAHA IS 'vĂˇha';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_POZNADR IS 'poznĂˇmka - ADR';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_POZNOBJ IS 'poznĂˇmka';
-- COMMENT ON    COLUMN    OR_OBJNAKLVYKL.ORONV_ZBOZIPOPIS IS 'popis zboĹľĂ­';
-- COMMENT ON TABLE        OR_ORDER IS 'Objednavky - historizace';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_BOKEMKDO IS 'Bokem - kdo';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DATUM IS 'datum objednĂˇvky';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DUEDATE IS 'datum splatnosti';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_FACTPRIJ IS 'ÄŤĂ­slo pĹ™ijatĂ© faktury';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_FACTVYD IS 'ÄŤĂ­slo vydanĂ© faktury';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_POZN IS 'poznĂˇmka';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_PRIJEM IS 'pĹ™Ă­jem';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_RAAL IS 'raal';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_VYDEJ IS 'vĂ˝dej';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_ZAKAZNIKIDENT IS 'zĂˇkaznĂ­k';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_ZISK IS 'zisk';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRDIC IS 'dopravce - DIÄŚ';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRIC IS 'dopravce - IÄŚ';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRSPZ IS 'dopravce - SPZ';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_CREATED IS 'vytvoĹ™eno';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRFIRMA IS 'dopravce - firma';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRSTAT IS 'dopravce - stĂˇt';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_CISLOOBJ IS 'ÄŤĂ­slo objednĂˇvky';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_CISLOOBJROK IS 'ÄŤĂ­slo objednĂˇvky - rok';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_ISSLOZENO IS 'sloĹľeno';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_ISSTORNO IS 'stornovĂˇno';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRTEL IS 'dopravce - telefon';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_SMLCENATEXT IS 'SmluvnĂ­ cena - text pro tisk';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRPSC IS 'dopravce - PSÄŚ';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DALSIINFO IS 'dalĹˇĂ­ info';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_BOKEMPOZN IS 'bokem - poznĂˇmka';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_BOKEMCASTKA IS 'Bokem - ÄŤĂˇstka';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRMESTO IS 'dopravce - mÄ›sto';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRULICE IS 'dopravce - ulice';
-- COMMENT ON    COLUMN    OR_ORDER.OROR_DOPRJMENO IS 'dopravce - jmĂ©no';
-- COMMENT ON TABLE        OR_SETUP IS 'setup';
-- COMMENT ON    COLUMN    OR_SETUP.ORSET_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_SETUP.ORSET_CISLOOBJ IS 'ÄŤĂ­slo objednĂˇvky';
-- COMMENT ON    COLUMN    OR_SETUP.ORSET_CISLOOBJROK IS 'ÄŤĂ­slo objednĂˇvky - rok';
-- COMMENT ON    COLUMN    OR_SETUP.ORSET_PDFDIR IS 'PDF adresĂˇĹ™';
-- COMMENT ON TABLE        OR_SPOT IS 'MĂ­sta - NaklĂˇdka/vyklĂˇdka';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_PK IS 'pk';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_DATE IS 'datum pĹ™Ă­jezdu';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_FIRMA IS 'firma 1';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_FIRMA2 IS 'firma 2';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_POZN1 IS 'poznĂˇmka 1';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_POZN2 IS 'poznĂˇmka 2';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_STAT IS 'stĂˇt';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_PSC IS 'PSÄŚ';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_TERM IS 'TermĂ­n';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_CAS1 IS 'ÄŚas 1';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_CAS3 IS 'ÄŚas 3';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_MESTO IS 'mÄ›sto';
-- COMMENT ON    COLUMN    OR_SPOT.ORSPT_ULICE IS 'ulice';
