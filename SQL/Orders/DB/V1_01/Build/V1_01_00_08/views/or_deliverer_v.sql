
echo or_deliverer_v

create view or_deliverer_v as
  select
      ordlv_pk,
      ordlv_raal,
      ordlv_firma,
      ordlv_mesto,
      ordlv_jmeno,
      ordlv_telnum,
      ordlv_pozn,
      ordlv_blokace,
      count(1) as ordercount,
      COALESCE (sum(oror_vydej), 0) as obrat,
      sum((oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0))) as zisk,
      COALESCE (sum((oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)))/count(1), 0) koeficient
    from
      or_order
      left join or_deliverer on
        ordlv_raal = oror_raal
    where
      oror_isstorno != 1
    group by
      ordlv_pk,
      ordlv_raal,
      ordlv_firma,
      ordlv_mesto,
      ordlv_jmeno,
      ordlv_telnum,
      ordlv_pozn,
      ordlv_blokace;
