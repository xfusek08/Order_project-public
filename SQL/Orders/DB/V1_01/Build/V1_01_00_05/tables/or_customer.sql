
echo or_customer

alter table or_customer add orcust_prijemproc    numeric(3) default 0 not null;

/* Descriptions */
comment on column or_customer.orcust_prijemproc  is 'Procento z příjmů';
