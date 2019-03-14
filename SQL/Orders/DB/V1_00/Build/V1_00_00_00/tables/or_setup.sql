
echo or_setup

create table or_setup (
  orset_pk            ND_CODE not null,
  orset_cisloobj      numeric(4) not null,
  orset_cisloobjrok   numeric(2) not null,
  orset_pdfdir        varchar(100)
);

alter table or_setup
  add constraint pk_orset
    primary key (orset_pk);

/* Descriptions */
comment on table or_setup is 'setup';
comment on column or_setup.orset_pk            is 'pk';
comment on column or_setup.orset_cisloobj      is 'číslo objednávky';
comment on column or_setup.orset_cisloobjrok   is 'číslo objednávky - rok';
comment on column or_setup.orset_pdfdir        is 'PDF adresář';
