
create trigger tg_oronv_bfins
  for or_objnaklvykl
  active
  before insert position 0
as
begin
  if (new.oronv_pk is null) then
    new.oronv_pk = gen_id (gn_oronv, 1);
end
^
