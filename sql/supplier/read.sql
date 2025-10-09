SELECT 
    spl.*,
    DATE_FORMAT(spl.dt_created, '%d/%m/%Y %T') as dtCreated, 
    DATE_FORMAT(spl.dt_updated, '%d/%m/%Y %T') as dtUpdated 
  FROM `SND_SUPPLIER` spl