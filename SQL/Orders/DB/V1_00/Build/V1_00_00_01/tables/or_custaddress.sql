
echo or_custaddress

create table or_custaddress (
  orcadr_pk         ND_CODE not null,
  orcadr_customer   ND_CODE not null,
  orcadr_cas3       varchar(10),
  orcadr_firma      varchar(40),
  orcadr_firma2     varchar(40),
  orcadr_mesto      varchar(30),
  orcadr_pozn1      varchar(40),
  orcadr_pozn2      varchar(40),
  orcadr_psc        varchar(7),
  orcadr_stat       varchar(3),
  orcadr_ulice      varchar(40),
  orcadr_created    timestamp not null
);

alter table or_custaddress
  add constraint pk_orcadr
    primary key (orcadr_pk);
    
alter table or_custaddress
  add constraint fk_orcadr_customer
    foreign key (orcadr_customer)
    references or_customer (orcust_pk)
    on delete cascade;

/* Generator */
create generator gn_orcadr;

/* Descriptions */
comment on table or_custaddress is 'Adresa zákazníka';
comment on column or_custaddress.orcadr_pk              is 'pk';
comment on column or_custaddress.orcadr_customer        is 'zákazník';
comment on column or_custaddress.orcadr_cas3            is 'čas 3';
comment on column or_custaddress.orcadr_firma           is 'firma 1';      
comment on column or_custaddress.orcadr_firma2          is 'firma 2';     
comment on column or_custaddress.orcadr_mesto           is 'město';      
comment on column or_custaddress.orcadr_pozn1           is 'poznámka 1';      
comment on column or_custaddress.orcadr_pozn2           is 'poznámka 2';      
comment on column or_custaddress.orcadr_psc             is 'PSČ';        
comment on column or_custaddress.orcadr_stat            is 'stát';       
comment on column or_custaddress.orcadr_ulice           is 'ulice';      
comment on column or_custaddress.orcadr_created         is 'vytvořeno';
