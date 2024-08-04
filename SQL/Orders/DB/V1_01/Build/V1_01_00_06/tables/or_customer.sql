
echo or_customer

--create tmp columns
alter table or_customer add orcust_mesto_tmp varchar(100);
alter table or_customer add orcust_ulice_tmp varchar(100);

-- load tmp columns
update or_customer set orcust_mesto_tmp = orcust_mesto;
update or_customer set orcust_ulice_tmp = orcust_ulice;

-- drop original columns
alter table or_customer drop orcust_mesto;
alter table or_customer drop orcust_ulice;

-- create new columns
alter table or_customer add orcust_mesto varchar(100);
alter table or_customer add orcust_ulice varchar(100);

-- load new columns
update or_customer set orcust_mesto = orcust_mesto_tmp;
update or_customer set orcust_ulice = orcust_ulice_tmp;

-- drop tmp columns
alter table or_customer drop orcust_mesto_tmp;
alter table or_customer drop orcust_ulice_tmp;

-- comment new columns
comment on column or_customer.orcust_mesto is 'město';
comment on column or_customer.orcust_ulice is 'ulice';
