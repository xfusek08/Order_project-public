
echo or_spot

alter table or_spot drop orspt_psc;
alter table or_spot add orspt_psc      varchar(10);

comment on column or_spot.orspt_psc    is 'PSČ';
