
echo or_order

alter table or_order add oror_dalsiinfo        varchar(40) not null;
alter table or_order add oror_smlcenatext      varchar(40) not null;

comment on column or_order.oror_dalsiinfo      is 'další info';
comment on column or_order.oror_smlcenatext    is 'Smluvní cena - text pro tisk';
