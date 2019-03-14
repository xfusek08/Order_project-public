select
    OROR_PK,
    OROR_DATUM,
    OROR_ZAKAZNIKIDENT,
    OROR_CISLOOBJ,
    OROR_CISLOOBJROK,    
    nakl.ORSPT_STAT as naklstat,
    nakl.ORSPT_PSC as naklpsc,
    nakl.ORSPT_MESTO as naklmesto,
    vykl.ORSPT_STAT as vyklstat,
    vykl.ORSPT_PSC as vyklpsc,
    vykl.ORSPT_MESTO as vyklmesto,    
    ORONV_VAHA as vaha,
    OROR_ZISK
  from
    OR_ORDER
    left outer join OR_OBJNAKLVYKL on ORONV_OBJ = OROR_PK
    left outer join OR_SPOT nakl on nakl.ORSPT_PK = ORONV_NAKL
    left outer join OR_SPOT vykl on vykl.ORSPT_PK = ORONV_VYKL
    
    
    