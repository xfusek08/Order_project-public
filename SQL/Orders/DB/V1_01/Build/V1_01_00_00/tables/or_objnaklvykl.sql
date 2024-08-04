
echo or_objnaklvykl

--create tmp columns
alter table or_objnaklvykl add oronv_poznadr_tmp varchar(100);
alter table or_objnaklvykl add oronv_poznobj_tmp varchar(100);
alter table or_objnaklvykl add oronv_zbozipopis_tmp varchar(100);

-- load tmp columns
update or_objnaklvykl set oronv_poznadr_tmp = oronv_poznadr;
update or_objnaklvykl set oronv_poznobj_tmp = oronv_poznobj;
update or_objnaklvykl set oronv_zbozipopis_tmp = oronv_zbozipopis;

-- drop original columns
alter table or_objnaklvykl drop oronv_poznadr;
alter table or_objnaklvykl drop oronv_poznobj;
alter table or_objnaklvykl drop oronv_zbozipopis;

-- create new columns
alter table or_objnaklvykl add oronv_poznadr varchar(100);
alter table or_objnaklvykl add oronv_poznobj varchar(100);
alter table or_objnaklvykl add oronv_zbozipopis varchar(100);

-- load new columns
update or_objnaklvykl set oronv_poznadr = oronv_poznadr_tmp;
update or_objnaklvykl set oronv_poznobj = oronv_poznobj_tmp;
update or_objnaklvykl set oronv_zbozipopis = oronv_zbozipopis_tmp;

-- drop tmp columns
alter table or_objnaklvykl drop oronv_poznadr_tmp;
alter table or_objnaklvykl drop oronv_poznobj_tmp;
alter table or_objnaklvykl drop oronv_zbozipopis_tmp;

-- comment new columns
comment on column or_objnaklvykl.oronv_poznadr      is 'poznámka - ADR';
comment on column or_objnaklvykl.oronv_poznobj      is 'poznámka';
comment on column or_objnaklvykl.oronv_zbozipopis   is 'popis zboží';
