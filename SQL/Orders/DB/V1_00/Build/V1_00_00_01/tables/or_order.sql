
echo or_order

drop index ui_oror_doprjmeno;

create index ui_oror_doprjmeno
  on or_order (oror_doprjmeno);
