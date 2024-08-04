
echo or_order

--create tmp columns
alter table or_order add oror_dalsiinfo_tmp varchar(100);

-- load tmp columns
update or_order set oror_dalsiinfo_tmp = oror_dalsiinfo;

-- drop original columns
alter table or_order drop oror_dalsiinfo;

-- create new columns
alter table or_order add oror_dalsiinfo varchar(100);
alter table or_order add oror_bokempozn varchar(40);

-- load new columns
update or_order set oror_dalsiinfo = oror_dalsiinfo_tmp;

-- drop tmp columns
alter table or_order drop oror_dalsiinfo_tmp;

-- comment new columns
comment on column or_order.oror_dalsiinfo      is 'další info';
comment on column or_order.oror_bokempozn      is 'bokem - poznámka';
