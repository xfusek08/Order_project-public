
echo or_customer

--create tmp columns
alter table or_customer add orcust_ident_tmp varchar(20);

-- load tmp columns
update or_customer set orcust_ident_tmp = orcust_ident;

-- drop original columns
drop index ui_orcust_ident;
alter table or_customer drop orcust_ident;

-- create new columns
alter table or_customer add orcust_ident varchar(20);
create unique index ui_orcust_ident
  on or_customer (orcust_ident);

-- load new columns
update or_customer set orcust_ident = orcust_ident_tmp;

-- drop tmp columns
alter table or_customer drop orcust_ident_tmp;

-- comment new columns
comment on column or_customer.orcust_ident      is 'ident (zkratka)';
