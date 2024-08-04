
echo or_address

create table or_address (
  oradr_pk         ND_CODE not null,
  oradr_customer   ND_CODE not null,
  oradr_cas3       varchar(10),
  oradr_date       date,
  oradr_firma      varchar(40),
  oradr_firma2     varchar(40),
  oradr_mesto      varchar(30),
  oradr_pozn1      varchar(40),
  oradr_pozn2      varchar(40),
  oradr_psc        varchar(7),
  oradr_stat       varchar(3),
  oradr_ulice      varchar(40),
  oradr_created    timestamp not null
);

alter table or_address
  add constraint pk_oradr
    primary key (oradr_pk);
    
alter table or_address
  add constraint fk_oradr_customer
    foreign key (oradr_customer)
    references or_customer (orcust_pk)
    on delete cascade;

/* Generator */
create generator gn_oradr;

/* Descriptions */
comment on table or_address is 'Místa - Nakládka/vykládka';
comment on column or_address.oradr_pk              is 'pk';
comment on column or_address.oradr_customer        is 'zákazník';
comment on column or_address.oradr_cas3            is 'čas 3';
comment on column or_address.oradr_date            is 'datum příjezdu';
comment on column or_address.oradr_firma           is 'firma 1';
comment on column or_address.oradr_firma2          is 'firma 2';
comment on column or_address.oradr_mesto           is 'město';
comment on column or_address.oradr_pozn1           is 'poznámka 1';
comment on column or_address.oradr_pozn2           is 'poznámka 2';
comment on column or_address.oradr_psc             is 'PSČ';
comment on column or_address.oradr_stat            is 'stát';
comment on column or_address.oradr_ulice           is 'ulice';
comment on column or_address.oradr_created         is 'vytvořeno';
