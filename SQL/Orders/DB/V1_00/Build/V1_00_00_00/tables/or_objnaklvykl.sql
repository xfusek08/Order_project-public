
echo or_objnaklvykl

create table or_objnaklvykl (
  oronv_pk          ND_CODE not null,
  oronv_obj         ND_CODE not null,
  oronv_nakl        ND_CODE not null,
  oronv_vykl        ND_CODE not null,
  oronv_vaha        numeric(5),
  oronv_dalsiinfo   varchar(40),
  oronv_poznadr     varchar(40),
  oronv_poznobj     varchar(40)
);

alter table or_objnaklvykl
  add constraint pk_oronv
    primary key (oronv_pk);
    
alter table or_objnaklvykl
  add constraint fk_oronv_obj
    foreign key (oronv_obj)
    references or_order (oror_pk)
    on delete cascade;
    
alter table or_objnaklvykl
  add constraint fk_oronv_nakl
    foreign key (oronv_nakl)
    references or_spot (orspt_pk);

alter table or_objnaklvykl
  add constraint fk_oronv_vykl
    foreign key (oronv_vykl)
    references or_spot (orspt_pk);
    
create unique index ui_oronv_objnaklvykl
  on or_objnaklvykl (oronv_obj, oronv_nakl, oronv_vykl);

/* Generator */
create generator gn_oronv;

/* Descriptions */
comment on table or_objnaklvykl is 'Objednávka  - historizace - Nakládka/vykládka';
comment on column or_objnaklvykl.oronv_pk             is 'pk';
comment on column or_objnaklvykl.oronv_obj            is 'objednávka';
comment on column or_objnaklvykl.oronv_nakl           is 'nakládka';
comment on column or_objnaklvykl.oronv_vykl           is 'vykládka';
comment on column or_objnaklvykl.oronv_vaha           is 'váha';
comment on column or_objnaklvykl.oronv_dalsiinfo      is 'další info';
comment on column or_objnaklvykl.oronv_poznadr        is 'poznámka - ADR';
comment on column or_objnaklvykl.oronv_poznobj        is 'poznámka - objednávka';
