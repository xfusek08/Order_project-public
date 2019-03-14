
echo or_deliverer

alter table or_deliverer drop ordlv_jemno;

alter table or_deliverer add ordlv_jmeno          varchar(40);
alter table or_deliverer add ordlv_objnum         numeric(5);
alter table or_deliverer add ordlv_obrat          numeric(18,2);
alter table or_deliverer add ordlv_zisk           numeric(18,2);
alter table or_deliverer add ordlv_koeficient     numeric(18,2);

comment on column or_deliverer.ordlv_jmeno      is 'jméno';
comment on column or_deliverer.ordlv_objnum     is 'množství objednávek';
comment on column or_deliverer.ordlv_obrat      is 'obrat - součet příjmů';
comment on column or_deliverer.ordlv_zisk       is 'zisk - součet výdajů';
comment on column or_deliverer.ordlv_koeficient is 'koeficient';
