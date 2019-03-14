
echo or_deliverer

--create tmp columns
alter table or_deliverer add ordlv_mesto_tmp varchar(100);
alter table or_deliverer add ordlv_ulice_tmp varchar(100);

-- load tmp columns
update or_deliverer set ordlv_mesto_tmp = ordlv_mesto;
update or_deliverer set ordlv_ulice_tmp = ordlv_ulice;

-- drop original columns
alter table or_deliverer drop ordlv_mesto;
alter table or_deliverer drop ordlv_ulice;

-- create new columns
alter table or_deliverer add ordlv_mesto varchar(100);
alter table or_deliverer add ordlv_ulice varchar(100);

-- load new columns
update or_deliverer set ordlv_mesto = ordlv_mesto_tmp;
update or_deliverer set ordlv_ulice = ordlv_ulice_tmp;

-- drop tmp columns
alter table or_deliverer drop ordlv_mesto_tmp;
alter table or_deliverer drop ordlv_ulice_tmp;

-- comment new columns
comment on column or_deliverer.ordlv_mesto is 'město';
comment on column or_deliverer.ordlv_ulice is 'ulice';
