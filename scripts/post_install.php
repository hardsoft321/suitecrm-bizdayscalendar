<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */
function post_install()
{
    global $db;
    $ID_NAME = 'bizdayscalendar';
    $EOL = PHP_SAPI === 'cli' ? PHP_EOL : '<br />';
    $query = "SELECT id, version FROM upgrade_history WHERE id_name = '{$ID_NAME}' ORDER BY date_entered DESC";
    $result = $db->query($query);
    $previous_version = "";
    while($row = $db->fetchByAssoc($result)) {
        if(strnatcmp($row['version'], $previous_version) > 0) {
            $previous_version = $row['version'];
        }
    }
    echo "Previous version: $previous_version{$EOL}";
    $files = glob(__DIR__."/data*.sql");
    natsort($files);
    $count = 0;
    foreach($files as $file) {
        if(!preg_match('#scripts/data\-?(.*)\.sql$#', $file, $matches)) {
            sugar_die('preg_match error on '.var_export($file, true));
        }
        $version = $matches[1];
        echo "Version: $version... ";
        if($previous_version && strnatcmp($previous_version, $version) >= 0) {
            echo "skip{$EOL}";
            continue;
        }
        echo "installing{$EOL}";
        $sql_file = file_get_contents($file);
        $sql = explode("\n", $sql_file);
        foreach($sql as $query) {
            $query = rtrim($query, '; ');
            if($query) {
                $res = $db->query($query, true);
                $count += $db->getRowCount($res);
            }
        }
    }
    echo "Inserted rows: $count{$EOL}";

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
    SELECT to_char(d1, 'D') INTO dow1 FROM dual;
    SELECT to_char(d2, 'D') INTO dow2 FROM dual;
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
        AND to_char(calendar_date, 'D') NOT IN (6,7);
    SELECT COUNT(*) INTO workdays
    FROM BizDaysCalendar
    WHERE calendar_date >= d1 AND calendar_date <= d2 AND is_holiday = 'W'
        AND to_char(calendar_date, 'D') IN (6,7);
    RETURN (days-1)-holidays+workdays;
END;");
    }
}
