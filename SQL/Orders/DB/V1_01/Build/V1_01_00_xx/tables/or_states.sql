
echo or_states

create table or_states (
  orsts_pk            ND_CODE not null,
  orsts_engfullname   varchar(80),
  orsts_czefullname   varchar(80),
  orsts_iso           varchar(2),
  orsts_iso3          varchar(3),
  orsts_numcode       numeric(6)
);

/* Generator */
create generator gn_orsts;

/* Descriptions */
comment on table or_states is 'Seznam všech států';
comment on column or_states.orsts_pk              is 'pk';
comment on column or_states.orsts_engfullname     is 'Celý název státu ENG';
comment on column or_states.orsts_czefullname     is 'Celý název státu CZE';
comment on column or_states.orsts_iso             is 'ISO 2 znaky';
comment on column or_states.orsts_iso3            is 'ISO 3 znaky';
comment on column or_states.orsts_numcode         is 'Číselné označení';
                                                                                                      