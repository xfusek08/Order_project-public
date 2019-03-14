
create trigger tg_orcust_bfins
  for or_customer
  active
  before insert position 0
as
begin
  if (new.orcust_pk is null) then
    new.orcust_pk = gen_id (gn_orcust, 1);
  new.orcust_created = current_timestamp;
end
^
