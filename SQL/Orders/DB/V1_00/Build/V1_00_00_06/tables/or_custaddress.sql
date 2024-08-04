
echo or_custaddress

alter table or_custaddress add orcadr_telnumber varchar(20); 

comment on column or_custaddress.orcadr_vyklnum is 'Telefonní číslo';
