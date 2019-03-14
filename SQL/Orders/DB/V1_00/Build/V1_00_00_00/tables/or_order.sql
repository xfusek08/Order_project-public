
echo or_order

create table or_order (
  oror_pk             ND_CODE not null,
  oror_bokemcaskta    numeric(5),
  oror_bokemkdo       varchar(3),
  oror_datum          date not null,
  oror_duedate        date,
  oror_factprij       varchar(20),
  oror_factvyd        varchar(20),
  oror_cisloobj       numeric(4) not null,
  oror_cisloobjrok    numeric(2),
  oror_pozn           varchar(50),
  oror_prijem         numeric(6,2) not null,
  oror_raal           varchar(3) not null,
  oror_isslozeno      ND_BOOL default '0' not null,
  oror_isstorno       ND_BOOL default '0' not null,
  oror_vydej          numeric(6,2) not null,
  oror_zakaznikident  varchar(15) not null,
  oror_zisk           numeric(6,2),
  
  -- dopravce
  oror_doprdic        varchar(12),
  oror_dopric         varchar(10),
  oror_doprjmeno      varchar(30),
  oror_doprmesto      varchar(20),
  oror_doprpsc        varchar(5),
  oror_doprspz        varchar(15),
  oror_doprtel        varchar(15),
  oror_doprulice      varchar(30),

  oror_created        timestamp not null
);

alter table or_order
  add constraint pk_oror
    primary key (oror_pk);
    
create unique index ui_oror_doprjmeno
  on or_order (oror_doprjmeno);
  
/* Generator */
create generator gn_oror;

/* Descriptions */
comment on table or_order is 'Objednavky - historizace';
comment on column or_order.oror_pk             is 'pk';
comment on column or_order.oror_datum          is 'datum objednávky';
comment on column or_order.oror_duedate        is 'datum splatnosti';
comment on column or_order.oror_factprij       is 'číslo přijaté faktury';
comment on column or_order.oror_factvyd        is 'číslo vydané faktury';
comment on column or_order.oror_cisloobj       is 'číslo objednávky';
comment on column or_order.oror_cisloobjrok    is 'číslo objednávky - rok';
comment on column or_order.oror_pozn           is 'poznámka';
comment on column or_order.oror_prijem         is 'příjem';
comment on column or_order.oror_raal           is 'raal';
comment on column or_order.oror_isslozeno      is 'složeno';
comment on column or_order.oror_isstorno       is 'stornováno';
comment on column or_order.oror_vydej          is 'výdej';
comment on column or_order.oror_zakaznikident  is 'zákazník';
comment on column or_order.oror_zisk           is 'zisk';

comment on column or_order.oror_doprdic        is 'dopravce - DIČ';
comment on column or_order.oror_dopric         is 'dopravce - IČ';
comment on column or_order.oror_doprjmeno      is 'dopravce - jméno';
comment on column or_order.oror_doprmesto      is 'dopravce - město';
comment on column or_order.oror_doprpsc        is 'dopravce - PSČ';
comment on column or_order.oror_doprspz        is 'dopravce - SPZ';
comment on column or_order.oror_doprtel        is 'dopravce - telefon';
comment on column or_order.oror_doprulice      is 'dopravce - ulice';

comment on column or_order.oror_created        is 'vytvořeno';
  