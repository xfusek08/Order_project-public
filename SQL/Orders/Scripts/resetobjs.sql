delete from OR_ORDER;
delete from OR_OBJNAKLVYKL;
delete from OR_SPOT;
update OR_SETUP set OR_SETUP.ORSET_CISLOOBJ = 0;
update OR_CUSTADDRESS set OR_CUSTADDRESS.ORCADR_NAKLNUM = 0, OR_CUSTADDRESS.ORCADR_VYKLNUM = 0;