
echo or_deliverer

alter table or_deliverer drop ordlv_telnum;

alter table or_deliverer add ordlv_telnum varchar(30);

comment on column or_deliverer.ordlv_telnum is 'telefonní číslo';
