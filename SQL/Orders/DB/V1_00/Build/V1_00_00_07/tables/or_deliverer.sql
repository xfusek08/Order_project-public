
echo or_deliverer

alter table or_deliverer add ordlv_stat varchar(3);

comment on column or_deliverer.ordlv_stat is 'stát';
