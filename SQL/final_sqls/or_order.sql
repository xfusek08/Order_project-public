SET
    AUTODDL ON;

DROP TRIGGER TG_OROR_BFINS;

ALTER TABLE
    OR_OBJNAKLVYKL DROP CONSTRAINT FK_ORONV_OBJ;

ALTER TABLE
    OR_ORDER DROP CONSTRAINT PK_OROR;

DROP VIEW OR_DELIVERER_V;

/**************** DROPPING COMPLETE ***************/
CREATE VIEW OR_DELIVERER_V (
    ORDLV_PK,
    ORDLV_RAAL,
    ORDLV_FIRMA,
    ORDLV_MESTO,
    ORDLV_JMENO,
    ORDLV_TELNUM,
    ORDLV_POZN,
    ORDLV_BLOKACE,
    ORDERCOUNT,
    OBRAT,
    ZISK,
    KOEFICIENT
) AS
select
    ordlv_pk,
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
from
    or_deliverer
    left outer join (
        select
            oror_raal,
            count(1) as ordercount,
            sum(oror_vydej) as obrat,
            sum(
                oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)
            ) as zisk
        from
            (
                select
                    oror_raal,
                    oror_vydej,
                    oror_prijem,
                    oror_bokemcastka
                from
                    or_order
                where
                    oror_isstorno != 1
            )
        group by
            oror_raal
    ) on oror_raal = ordlv_raal;

SET
    TERM ^;

CREATE TRIGGER TG_OROR_BFINS FOR OR_ORDER ACTIVE BEFORE
INSERT
    POSITION 0 as begin if (new.oror_pk is null) then new.oror_pk = gen_id (gn_oror, 1);

new.oror_created = current_timestamp;

end ^
SET
    TERM;

^
ALTER TABLE
    OR_ORDER
ADD
    CONSTRAINT PK_OROR PRIMARY KEY (OROR_PK);

ALTER TABLE
    OR_OBJNAKLVYKL
ADD
    CONSTRAINT FK_ORONV_OBJ FOREIGN KEY (ORONV_OBJ) REFERENCES OR_ORDER (OROR_PK) ON DELETE CASCADE;

GRANT DELETE,
INSERT
,
    REFERENCES,
SELECT
,
UPDATE
    ON OR_DELIVERER_V TO SYSDBA WITH GRANT OPTION;

GRANT DELETE,
INSERT
,
    REFERENCES,
SELECT
,
UPDATE
    ON OR_ORDER TO SYSDBA WITH GRANT OPTION;