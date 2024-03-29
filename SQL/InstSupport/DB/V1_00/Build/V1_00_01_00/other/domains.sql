
DROP DOMAIN nd_blobt;
DROP DOMAIN nd_description;
DROP DOMAIN nd_id;
DROP DOMAIN nd_text;
DROP DOMAIN nd_www;

-------------------------------------

CREATE DOMAIN nd_blobt
  AS BLOB SUB_TYPE 1 SEGMENT SIZE 80 CHARACTER SET UTF8;

CREATE DOMAIN nd_description
  AS VARCHAR(4000) CHARACTER SET UTF8;

CREATE DOMAIN nd_id
  AS VARCHAR(10) CHARACTER SET UTF8;

CREATE DOMAIN nd_text
  AS VARCHAR(100) CHARACTER SET UTF8;

CREATE DOMAIN nd_www
  AS VARCHAR(300) CHARACTER SET UTF8;
