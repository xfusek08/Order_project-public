
echo or_order

drop index ui_oror_doprjmeno;

--create tmp columns
alter table or_order add oror_doprjmeno_tmp varchar(100);

-- load tmp columns
update or_order set oror_doprjmeno_tmp = oror_doprjmeno;

-- drop original columns
alter table or_order drop oror_doprjmeno;

-- create new columns
alter table or_order add oror_doprjmeno varchar(100);

-- load new columns
update or_order set oror_doprjmeno = oror_doprjmeno_tmp;

-- drop tmp columns
alter table or_order drop oror_doprjmeno_tmp;

-- comment new columns
comment on column or_order.oror_doprjmeno is 'dopravce - jméno';
