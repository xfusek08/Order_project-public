
echo or_order

--create tmp columns
alter table or_order add oror_bokempozn_tmp varchar(100);

-- load tmp columns
update or_order set oror_bokempozn_tmp = oror_bokempozn;

-- drop original columns
alter table or_order drop oror_bokempozn;

-- create new columns
alter table or_order add oror_bokempozn varchar(100);

-- load new columns
update or_order set oror_bokempozn = oror_bokempozn_tmp;

-- drop tmp columns
alter table or_order drop oror_bokempozn_tmp;

-- comment new columns
comment on column or_order.oror_bokempozn      is 'bokem - poznámka';
