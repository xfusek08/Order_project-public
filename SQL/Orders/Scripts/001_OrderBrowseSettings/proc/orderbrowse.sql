
echo orderbrowse

execute block 
as
  declare browsePK integer;
  declare fieldPK integer;
begin
  insert into or_browserset (
      orbrs_name, 
      orbrs_tablname)
    values (
      'orders', 
      'or_order')
    returning orbrs_pk into :browsePK;   
   
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Datum pořízení', 'oror_datum', 'D', 100, 1);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Zákazník', 'oror_zakaznikident', 'S', 100, 2);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Zákazník', 'oror_zakaznikident', 'S', 100, 3);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Číslo objednávky', 'oror_cisloobj', 'N', 100, 4);      
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Rok', 'oror_cisloobjrok', 'N', 100, 5);
    
  -- nakádka
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Nakládka', null, 'S', 300, 6) returning orbrf_pk into :fieldPK;
    
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Stát', null, 'S', 100, 1);
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Psč', null, 'S', 100, 2);
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Město', null, 'S', 100, 3);

  -- vykládka
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Vykládka', null, 'S', 300, 7) returning orbrf_pk into :fieldPK;
    
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Stát', null, 'S', 100, 1);
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Psč', null, 'S', 100, 2);
    insert into or_browserfield (orbrf_browser, orbrf_fieldover, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
      values (:browsePK, :fieldPK,'Město', null, 'S', 100, 3);
  
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Cena - příjem', 'oror_prijem', 'N', 100, 8);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Cena - výdej', 'oror_vydej', 'N', 100, 9);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Raal', 'oror_raal', 'S', 100, 10);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Datum vykládky', null, 'D', 100, 11);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Dopravce', 'oror_doprjmeno', 'S', 100, 12);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Nakl - Váha', null, 'N', 100, 13);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Počet palet', null, 'N', 100, 14);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Poznámka', 'oror_pozn', 'S', 100, 15);          
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Datum splatnosti', 'oror_duedate', 'D', 100, 16);          
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Přijatá faktura', 'oror_factprij', 'S', 100, 17);          
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Vydaná faktura', 'oror_factvyd', 'S', 100, 18);          
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Zisk', 'oror_zisk', 'N', 100, 19);          
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Složeno', 'oror_isslozeno', 'B', 100, 20);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Bokem kdo', 'oror_bokemkdo', 'S', 100, 21);
  insert into or_browserfield (orbrf_browser, orbrf_showname, orbrf_colname, orbrf_datatype, orbrf_colwidth, orbrf_colorder)
    values (:browsePK, 'Bokem částka', 'oror_bokemcaskta', 'N', 100, 22);
end;
^
