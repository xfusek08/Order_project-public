
echo or_order

alter table or_order drop oror_doprpsc;
alter table or_order add oror_doprpsc      varchar(10);

comment on column or_order.oror_doprpsc    is 'dopravce - PSČ';
