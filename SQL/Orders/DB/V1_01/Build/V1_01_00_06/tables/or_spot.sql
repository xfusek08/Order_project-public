
echo or_spot

--create tmp columns
alter table or_spot add orspt_mesto_tmp varchar(100);
alter table or_spot add orspt_ulice_tmp varchar(100);

-- load tmp columns
update or_spot set orspt_mesto_tmp = orspt_mesto;
update or_spot set orspt_ulice_tmp = orspt_ulice;

-- drop original columns
alter table or_spot drop orspt_mesto;
alter table or_spot drop orspt_ulice;

-- create new columns
alter table or_spot add orspt_mesto varchar(100);
alter table or_spot add orspt_ulice varchar(100);

-- load new columns
update or_spot set orspt_mesto = orspt_mesto_tmp;
update or_spot set orspt_ulice = orspt_ulice_tmp;

-- drop tmp columns
alter table or_spot drop orspt_mesto_tmp;
alter table or_spot drop orspt_ulice_tmp;

-- comment new columns
comment on column or_spot.orspt_mesto is 'město';
comment on column or_spot.orspt_ulice is 'ulice';
