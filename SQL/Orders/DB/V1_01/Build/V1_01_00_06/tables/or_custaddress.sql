
echo or_custaddress

--create tmp columns
alter table or_custaddress add orcadr_mesto_tmp varchar(100);
alter table or_custaddress add orcadr_ulice_tmp varchar(100);

-- load tmp columns
update or_custaddress set orcadr_mesto_tmp = orcadr_mesto;
update or_custaddress set orcadr_ulice_tmp = orcadr_ulice;

-- drop original columns
alter table or_custaddress drop orcadr_mesto;
alter table or_custaddress drop orcadr_ulice;

-- create new columns
alter table or_custaddress add orcadr_mesto varchar(100);
alter table or_custaddress add orcadr_ulice varchar(100);

-- load new columns
update or_custaddress set orcadr_mesto = orcadr_mesto_tmp;
update or_custaddress set orcadr_ulice = orcadr_ulice_tmp;

-- drop tmp columns
alter table or_custaddress drop orcadr_mesto_tmp;
alter table or_custaddress drop orcadr_ulice_tmp;

-- comment new columns
comment on column or_custaddress.orcadr_mesto is 'město';
comment on column or_custaddress.orcadr_ulice is 'ulice';
