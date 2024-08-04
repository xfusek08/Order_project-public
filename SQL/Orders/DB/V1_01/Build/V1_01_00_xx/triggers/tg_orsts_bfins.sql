
create trigger tg_orsts_bfins
  for or_states
  active
  before insert position 0
as
begin
  if (new.orsts_pk is null) then
    new.orsts_pk = gen_id (gn_orsts, 1);
  new.orsts_created = current_timestamp;
end
^
