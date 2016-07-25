<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author hardsoft321.org
 */
function post_install()
{
    global $db;
    $EOL = PHP_SAPI === 'cli' ? PHP_EOL : '<br />';
    $sql_file = file_get_contents(__DIR__.'/data.sql');
    $sql = explode("\n", $sql_file);
    foreach($sql as $query) {
        $query = rtrim($query, '; ');
        if($query) {
            $res = $db->query($query, true);
            $count += $db->getRowCount($res);
        }
    }
    echo "Number of rows affected: $count{$EOL}";

    if($db->dbType == 'mysql') { //TODO:
//DROP FUNCTION IF EXISTS BizDaysInclusive;
//DELIMITER |
//
//CREATE FUNCTION BizDaysInclusive( d1 DATE, d2 DATE )
//RETURNS INT
//DETERMINISTIC
//BEGIN
// DECLARE dow1, dow2, days INT;
// DECLARE holidays, workdays INT;
// SET dow1 = DAYOFWEEK(d1);
// SET dow2 = DAYOFWEEK(d2);
// SET days = FLOOR( DATEDIFF(d2,d1)/7 ) * 5 +
//            CASE
//              WHEN dow1=1 AND dow2=7 THEN 5
//              WHEN dow1 IN(7,1) AND dow2 IN (7,1) THEN 0
//              WHEN dow1=dow2 THEN 1
//              WHEN dow1 IN(7,1) AND dow2 NOT IN (7,1) THEN dow2-1
//              WHEN dow1 NOT IN(7,1) AND dow2 IN(7,1) THEN 7-dow1
//              WHEN dow1<=dow2 THEN dow2-dow1+1
//              WHEN dow1>dow2 THEN 5-(dow1-dow2-1)
//              ELSE 0
//            END;
// SELECT COUNT(*)
// INTO holidays
// FROM BizDaysCalendar
// WHERE calendar_date >= d1 AND calendar_date <= d2 AND is_holiday =
//'H' AND DAYOFWEEK(calendar_date) NOT IN (1,7);
// SELECT COUNT(*)
// INTO workdays
// FROM BizDaysCalendar
// WHERE calendar_date >= d1 AND calendar_date <= d2 AND is_holiday =
//'W' AND DAYOFWEEK(calendar_date) IN (1,7);
// RETURN (days-1)-holidays+workdays;
//END;
//|
//DELIMITER ;
    }
    elseif($db->dbType == 'oci8') {
        echo "Installing pl/sql function BizDaysInclusive{$EOL}";
        $db->query("
CREATE OR REPLACE FUNCTION BizDaysInclusive(d1 IN DATE, d2 IN DATE)
RETURN INT
IS
dow1 INT;
dow2 INT;
days INT;
holidays INT;
workdays INT;
BEGIN
    SELECT 1 + TRUNC(d1) - TRUNC(d1, 'IW') INTO dow1 FROM dual;
    SELECT 1 + TRUNC(d2) - TRUNC(d2, 'IW') INTO dow2 FROM dual;
    SELECT FLOOR((d2 - d1) / 7) * 5 +
            CASE
              WHEN dow1=7 AND dow2=6 THEN 5
              WHEN dow1 IN (6,7) AND dow2 IN (6,7) THEN 0
              WHEN dow1=dow2 THEN 1
              WHEN dow1 IN (6,7) AND dow2 NOT IN (6,7) THEN dow2
              WHEN dow1 NOT IN (6,7) AND dow2 IN(6,7) THEN 6-dow1
              WHEN dow1<=dow2 THEN dow2-dow1+1
              WHEN dow1>dow2 THEN 5-(dow1-dow2-1)
              ELSE 0
            END INTO days FROM dual;
    SELECT COUNT(*) INTO holidays
    FROM BizDaysCalendar
    WHERE calendar_date >= d1 AND calendar_date <= d2 AND is_holiday = 'H'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') NOT IN (6,7);
    SELECT COUNT(*) INTO workdays
    FROM BizDaysCalendar
    WHERE calendar_date >= d1 AND calendar_date <= d2 AND is_holiday = 'W'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') IN (6,7);
    RETURN (days-1)-holidays+workdays;
END;");
    }
}
