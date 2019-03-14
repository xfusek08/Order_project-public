
echo or_custaddress

alter table or_custaddress drop orcadr_psc;
alter table or_custaddress add orcadr_psc      varchar(10);

comment on column or_custaddress.orcadr_psc    is 'PSČ';
