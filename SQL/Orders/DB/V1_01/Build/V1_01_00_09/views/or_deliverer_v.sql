
echo or_deliverer_v

drop view or_deliverer_v;

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
		      sum(oror_prijem - oror_vydej - coalesce (oror_bokemcastka, 0)) as zisk
		    from
		      (select oror_raal, oror_vydej, oror_prijem, oror_bokemcastka from or_order where oror_isstorno != 1)
		    group by oror_raal
		  ) on oror_raal = ordlv_raal;
