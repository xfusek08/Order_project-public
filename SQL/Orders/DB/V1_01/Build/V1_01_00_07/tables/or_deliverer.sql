
echo or_deliverer

--create tmp columns
alter table or_deliverer add ordlv_jmeno_tmp varchar(100);
alter table or_deliverer add ordlv_firma_tmp varchar(100);

-- load tmp columns
update or_deliverer set ordlv_jmeno_tmp = ordlv_jmeno;
update or_deliverer set ordlv_firma_tmp = ordlv_firma;

-- drop original columns
alter table or_deliverer drop ordlv_jmeno;
alter table or_deliverer drop ordlv_firma;

-- create new columns
alter table or_deliverer add ordlv_jmeno varchar(100);
alter table or_deliverer add ordlv_firma varchar(100);

-- load new columns
update or_deliverer set ordlv_jmeno = ordlv_jmeno_tmp;
update or_deliverer set ordlv_firma = ordlv_firma_tmp;

-- drop tmp columns
alter table or_deliverer drop ordlv_jmeno_tmp;
alter table or_deliverer drop ordlv_firma_tmp;

-- comment new columns
comment on column or_deliverer.ordlv_jmeno is 'jméno';
comment on column or_deliverer.ordlv_firma is 'firma';
