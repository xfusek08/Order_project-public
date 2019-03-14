
create trigger tg_oradr_bfins
  for or_address
  active
  before insert position 0
as
begin
  if (new.oradr_pk is null) then
    new.oradr_pk = gen_id (gn_oradr, 1);
  new.oradr_created = current_timestamp;
end
^
