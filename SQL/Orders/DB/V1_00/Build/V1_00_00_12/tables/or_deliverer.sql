
echo or_deliverer

alter table or_deliverer drop ordlv_psc;
alter table or_deliverer add ordlv_psc      varchar(10);

comment on column or_deliverer.ordlv_psc    is 'PSČ';
