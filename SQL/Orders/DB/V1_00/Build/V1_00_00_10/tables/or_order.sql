
echo or_order

alter table or_order drop oror_cisloobj;
alter table or_order drop oror_cisloobjrok;
alter table or_order drop oror_isslozeno;
alter table or_order drop oror_isstorno;
alter table or_order drop oror_doprtel;

alter table or_order add oror_cisloobj       numeric(10) not null;
alter table or_order add oror_cisloobjrok    numeric(10);
alter table or_order add oror_isslozeno      numeric(1) default 0 not null;
alter table or_order add oror_isstorno       numeric(1) default 0 not null;
alter table or_order add oror_doprtel        varchar(30);

comment on column or_order.oror_cisloobj       is 'číslo objednávky';
comment on column or_order.oror_cisloobjrok    is 'číslo objednávky - rok';
comment on column or_order.oror_isslozeno      is 'složeno';
comment on column or_order.oror_isstorno       is 'stornováno';
comment on column or_order.oror_doprtel        is 'dopravce - telefon';
