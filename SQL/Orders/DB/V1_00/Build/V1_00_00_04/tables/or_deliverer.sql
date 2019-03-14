
echo or_deliverer

alter table or_deliverer drop ordlv_telephone;
alter table or_deliverer drop ordlv_psc;
alter table or_deliverer drop ordlv_ponzamka;

alter table or_deliverer add ordlv_telnum varchar(20);
alter table or_deliverer add ordlv_psc varchar(7);
alter table or_deliverer add ordlv_pozn varchar(50);

comment on column or_deliverer.ordlv_telnum is 'telefonní číslo';
comment on column or_deliverer.ordlv_psc is 'psč';
comment on column or_deliverer.ordlv_pozn is 'poznámka';
