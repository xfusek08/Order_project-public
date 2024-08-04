
create trigger tg_orcadr_bfins
  for or_custaddress
  active
  before insert position 0
as
begin
  if (new.orcadr_pk is null) then
    new.orcadr_pk = gen_id (gn_orcadr, 1);
  new.orcadr_created = current_timestamp;
end
^
