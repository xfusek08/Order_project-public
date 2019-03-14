
echo or_deliver

create table or_deliver (
  ordlv_pk        ND_CODE not null,
  ordlv_dic       varchar(12),
  ordlv_email     varchar(30),
  ordlv_firma     varchar(30) not null,
  ordlv_ic        varchar(10),
  ordlv_jemno     varchar(15),
  ordlv_mesto     varchar(20),
  ordlv_ponzamka  varchar(50),
  ordlv_psc       varchar(5),
  ordlv_raal      varchar(3),
  ordlv_telephone varchar(10),
  ordlv_spz       varchar(15),
  ordlv_ulice     varchar(30),
  ordlv_created   timestamp not null
);

alter table or_deliver
  add constraint pk_ordlv
    primary key (ordlv_pk);
    
create unique index ui_ordlv_firma
  on or_deliver (ordlv_firma);
create unique index ui_ordlv_raal
  on or_deliver (ordlv_raal);

/* Generator */
create generator gn_ordlv;

/* Descriptions */
comment on table or_deliver is 'Objednavky evidence';
comment on column or_deliver.ordlv_pk         is 'pk';
comment on column or_deliver.ordlv_dic        is 'DIČ';
comment on column or_deliver.ordlv_email      is 'e-mail';
comment on column or_deliver.ordlv_firma      is 'firma';
comment on column or_deliver.ordlv_ic         is 'IČ';
comment on column or_deliver.ordlv_jemno      is 'jméno';
comment on column or_deliver.ordlv_mesto      is 'město';
comment on column or_deliver.ordlv_ponzamka   is 'poznámka';
comment on column or_deliver.ordlv_psc        is 'PSČ';
comment on column or_deliver.ordlv_raal       is 'raal';
comment on column or_deliver.ordlv_telephone  is 'telephone';
comment on column or_deliver.ordlv_spz        is 'SPZ';
comment on column or_deliver.ordlv_ulice      is 'ulice';
comment on column or_deliver.ordlv_created    is 'vytvořeno';
