
echo or_deliverer

drop table or_deliver;
drop generator gn_ordlv;

create table or_deliverer (
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
  ordlv_blokace   varchar(40),
  ordlv_created   timestamp not null
);

alter table or_deliverer
  add constraint pk_ordlv
    primary key (ordlv_pk);
    
create unique index ui_ordlv_raal
  on or_deliverer (ordlv_raal);

/* Generator */
create generator gn_ordlv;

/* Descriptions */
comment on table or_deliverer is 'Objednavky evidence';
comment on column or_deliverer.ordlv_pk         is 'pk';
comment on column or_deliverer.ordlv_dic        is 'DIČ';
comment on column or_deliverer.ordlv_email      is 'e-mail';
comment on column or_deliverer.ordlv_firma      is 'firma';
comment on column or_deliverer.ordlv_ic         is 'IČ';
comment on column or_deliverer.ordlv_jemno      is 'jméno';
comment on column or_deliverer.ordlv_mesto      is 'město';
comment on column or_deliverer.ordlv_ponzamka   is 'poznámka';
comment on column or_deliverer.ordlv_psc        is 'PSČ';
comment on column or_deliverer.ordlv_raal       is 'raal';
comment on column or_deliverer.ordlv_telephone  is 'telephone';
comment on column or_deliverer.ordlv_spz        is 'SPZ';
comment on column or_deliverer.ordlv_ulice      is 'ulice';
comment on column or_deliverer.ordlv_blokace    is 'blokace';
comment on column or_deliverer.ordlv_created    is 'vytvořeno';
