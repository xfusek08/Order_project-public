
echo or_custaddress

--create tmp columns
alter table or_custaddress add orcadr_cas3_tmp varchar(50);

-- load tmp columns
update or_custaddress set orcadr_cas3_tmp = orcadr_cas3;

-- drop original columns
alter table or_custaddress drop orcadr_cas3;

-- create new columns
alter table or_custaddress add orcadr_cas3 varchar(50);

-- load new columns
update or_custaddress set orcadr_cas3 = orcadr_cas3_tmp;

-- drop tmp columns
alter table or_custaddress drop orcadr_cas3_tmp;

-- comment new columns
comment on column or_custaddress.orcadr_cas3 is 'Čas 3';
