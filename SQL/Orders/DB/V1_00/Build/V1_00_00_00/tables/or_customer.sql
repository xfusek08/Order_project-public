
echo or_customer

create table or_customer (
  orcust_pk        ND_CODE not null,
  orcust_ident     varchar(10) not null,
  orcust_created   timestamp not null
);

alter table or_customer
  add constraint pk_orcust
    primary key (orcust_pk);
    
create unique index ui_orcust_ident
  on or_customer (orcust_ident);

/* Generator */
create generator gn_orcust;

/* Descriptions */
comment on table or_customer is 'Zákazník - ident';
comment on column or_customer.orcust_pk         is 'pk';
comment on column or_customer.orcust_ident      is 'ident (zkratka)';
comment on column or_customer.orcust_created    is 'vytvořeno';
