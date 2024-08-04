
echo or_order

alter table or_order add oror_doprfirma varchar(30);
alter table or_order add oror_doprstat varchar(3);

comment on column or_order.oror_doprfirma is 'dopravce - firma';
comment on column or_order.oror_doprstat is 'dopravce - stát';
