
echo or_customer

alter table or_customer drop orcust_cas3;

alter table or_customer add orcust_color varchar(20);

comment on column or_customer.orcust_color is 'barva';
