
echo or_customer

alter table or_customer add orcust_cas3        varchar(10);
alter table or_customer add orcust_firma       varchar(40);
alter table or_customer add orcust_firma2      varchar(40);
alter table or_customer add orcust_mesto       varchar(30);
alter table or_customer add orcust_pozn1       varchar(40);
alter table or_customer add orcust_pozn2       varchar(40);
alter table or_customer add orcust_psc         varchar(7);
alter table or_customer add orcust_stat        varchar(3);
alter table or_customer add orcust_ulice       varchar(40);
alter table or_customer add orcust_ico         varchar(8);
alter table or_customer add orcust_dico        varchar(12);
alter table or_customer add orcust_telefon     varchar(20);
alter table or_customer add orcust_mail        varchar(40);

/* Descriptions */
comment on column or_customer.orcust_cas3        is 'čas3';
comment on column or_customer.orcust_firma       is 'firma';
comment on column or_customer.orcust_firma2      is 'firma2';
comment on column or_customer.orcust_mesto       is 'město';
comment on column or_customer.orcust_pozn1       is 'poznámka';
comment on column or_customer.orcust_pozn2       is 'poznámka 2';
comment on column or_customer.orcust_psc         is 'PSČ';
comment on column or_customer.orcust_stat        is 'Stát';
comment on column or_customer.orcust_ulice       is 'Ulice';
comment on column or_customer.orcust_ico         is 'IČO';
comment on column or_customer.orcust_dico        is 'DIČO';
comment on column or_customer.orcust_telefon     is 'telefon';
comment on column or_customer.orcust_mail        is 'mail';
