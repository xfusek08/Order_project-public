
echo or_order

--create tmp columns
alter table or_order add oror_bokemcastka_tmp numeric(5);

-- load tmp columns
update or_order set oror_bokemcastka_tmp = oror_bokemcaskta;

-- drop original columns
alter table or_order drop oror_bokemcaskta;

-- create new columns
alter table or_order add oror_bokemcastka numeric(5);

-- load new columns
update or_order set oror_bokemcastka = oror_bokemcastka_tmp;

-- drop tmp columns
alter table or_order drop oror_bokemcastka_tmp;

-- comment new columns
comment on column or_order.oror_bokemcastka is 'Bokem - částka';
comment on column or_order.oror_bokemkdo is 'Bokem - kdo';
