
echo or_custaddress

alter table or_custaddress add orcadr_naklnum numeric(10); 
alter table or_custaddress add orcadr_vyklnum numeric(10); 

comment on column or_custaddress.orcadr_naklnum is 'Počet nakládek';
comment on column or_custaddress.orcadr_vyklnum is 'Počet vykládek';
