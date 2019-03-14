
echo or_order

--create tmp columns
alter table or_order add oror_doprmesto_tmp varchar(100);
alter table or_order add oror_doprulice_tmp varchar(100);

-- load tmp columns
update or_order set oror_doprmesto_tmp = oror_doprmesto;
update or_order set oror_doprulice_tmp = oror_doprulice;

-- drop original columns
alter table or_order drop oror_doprmesto;
alter table or_order drop oror_doprulice;

-- create new columns
alter table or_order add oror_doprmesto varchar(100);
alter table or_order add oror_doprulice varchar(100);

-- load new columns
update or_order set oror_doprmesto = oror_doprmesto_tmp;
update or_order set oror_doprulice = oror_doprulice_tmp;

-- drop tmp columns
alter table or_order drop oror_doprmesto_tmp;
alter table or_order drop oror_doprulice_tmp;

-- comment new columns
comment on column or_order.oror_doprmesto is 'dopravce - město';
comment on column or_order.oror_doprulice is 'dopravce - ulice';
