
echo or_spot

alter table or_spot add orspt_cas1 varchar(5);
alter table or_spot add orspt_cas3 varchar(10);

comment on column or_spot.orspt_cas1 is 'Čas 1';
comment on column or_spot.orspt_cas3 is 'Čas 3';
