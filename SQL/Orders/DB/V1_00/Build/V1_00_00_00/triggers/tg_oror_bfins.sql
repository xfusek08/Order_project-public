
create trigger tg_oror_bfins
  for or_order
  active
  before insert position 0
as
begin
  if (new.oror_pk is null) then
    new.oror_pk = gen_id (gn_oror, 1);
  new.oror_created = current_timestamp;
end
^
