
echo or_spot

alter table or_spot add orspt_term varchar(100);

update or_spot set orspt_term =
  orspt_cas1 || ' ' ||
  LPAD(Extract(DAY from orspt_date), 2, '0') || '.' ||
  LPAD(Extract(MONTH from orspt_date), 2, '0') || '.' ||
  LPAD(Extract(YEAR from orspt_date), 4, '0')
  || ' ' || orspt_cas3;

alter table or_spot drop orspt_cas1;
alter table or_spot drop orspt_cas3;

comment on column or_spot.orspt_term is 'Termín';
