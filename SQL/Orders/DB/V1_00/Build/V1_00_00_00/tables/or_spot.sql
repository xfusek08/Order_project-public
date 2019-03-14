
echo or_spot

create table or_spot (
  orspt_pk         ND_CODE not null,
  orspt_date       date,
  orspt_exptxtpre  varchar(2),
  orspt_exptxtpost varchar(10),
  orspt_firma      varchar(40),
  orspt_firma2     varchar(40),
  orspt_mesto      varchar(30),
  orspt_pozn1      varchar(40),
  orspt_pozn2      varchar(40),
  orspt_psc        varchar(7),
  orspt_stat        varchar(3),
  orspt_ulice      varchar(40)
);

alter table or_spot
  add constraint pk_orspt
    primary key (orspt_pk);

/* Generator */
create generator gn_orspt;

/* Descriptions */
comment on table or_spot is 'Místa - Nakládka/vykládka';
comment on column or_spot.orspt_pk              is 'pk';
comment on column or_spot.orspt_date            is 'datum příjezdu';           
comment on column or_spot.orspt_firma           is 'firma 1';      
comment on column or_spot.orspt_firma2          is 'firma 2';     
comment on column or_spot.orspt_mesto           is 'město';      
comment on column or_spot.orspt_pozn1           is 'poznámka 1';      
comment on column or_spot.orspt_pozn2           is 'poznámka 2';      
comment on column or_spot.orspt_psc             is 'PSČ';        
comment on column or_spot.orspt_stat            is 'stát';       
comment on column or_spot.orspt_ulice           is 'ulice';      
