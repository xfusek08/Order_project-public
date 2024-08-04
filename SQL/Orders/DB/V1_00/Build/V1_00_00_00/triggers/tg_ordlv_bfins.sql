
create trigger tg_ordlv_bfins
  for or_deliver
  active
  before insert position 0
as
begin
  if (new.ordlv_pk is null) then
    new.ordlv_pk = gen_id (gn_ordlv, 1);
  new.ordlv_created = current_timestamp;
end
^
