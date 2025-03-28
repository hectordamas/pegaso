UPDATE safact 
SET codestatus = 11 
WHERE id IN (
    SELECT id FROM (SELECT id FROM safact WHERE codestatus NOT IN (11, 12, 13) LIMIT 100) AS subquery
);

UPDATE safact 
SET codestatus = 12 
WHERE id IN (
    SELECT id FROM (SELECT id FROM safact WHERE codestatus NOT IN (11, 12, 13) LIMIT 100) AS subquery
);

UPDATE safact 
SET codestatus = 13 
WHERE id IN (
    SELECT id FROM (SELECT id FROM safact WHERE codestatus NOT IN (11, 12, 13) LIMIT 100) AS subquery
);
