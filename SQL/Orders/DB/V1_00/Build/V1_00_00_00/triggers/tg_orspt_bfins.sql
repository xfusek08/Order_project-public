
create trigger tg_orspt_bfins
  for or_spot
  active
  before insert position 0
as
begin
  if (new.orspt_pk is null) then
    new.orspt_pk = gen_id (gn_orspt, 1);
end
^
