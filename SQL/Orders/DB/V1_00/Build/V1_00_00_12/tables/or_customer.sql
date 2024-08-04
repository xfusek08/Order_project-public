
echo or_customer

alter table or_customer drop orcust_psc;
alter table or_customer add orcust_psc      varchar(10);

comment on column or_customer.orcust_psc    is 'PSČ';
