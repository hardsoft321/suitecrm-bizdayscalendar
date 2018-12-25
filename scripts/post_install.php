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
is_holiday1 NUMBER(1);
is_holiday2 NUMBER(1);
holidays INT;
workdays INT;
BEGIN
    IF d1 > d2 THEN
        RETURN BizDaysInclusive(d2, d1);
    END IF;

    SELECT 1 + TRUNC(d1) - TRUNC(d1, 'IW') INTO dow1 FROM dual;
    SELECT 1 + TRUNC(d2) - TRUNC(d2, 'IW') INTO dow2 FROM dual;

    IF dow1 NOT IN (6,7) THEN
      SELECT LEAST(1, COUNT(*)) INTO is_holiday1
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d1) AND is_holiday = 'H';
    ELSE
      SELECT 1 - LEAST(1, COUNT(*)) INTO is_holiday1
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d1) AND is_holiday = 'W';
    END IF;

    IF dow2 NOT IN (6,7) THEN
      SELECT LEAST(1, COUNT(*)) INTO is_holiday2
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d2) AND is_holiday = 'H';
    ELSE
      SELECT 1 - LEAST(1, COUNT(*)) INTO is_holiday2
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d2) AND is_holiday = 'W';
    END IF;

    SELECT COUNT(*) INTO holidays
    FROM BizDaysCalendar
    WHERE calendar_date >= TRUNC(d1) AND calendar_date <= d2 AND is_holiday = 'H'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') NOT IN (6,7);

    SELECT COUNT(*) INTO workdays
    FROM BizDaysCalendar
    WHERE calendar_date >= TRUNC(d1) AND calendar_date <= d2 AND is_holiday = 'W'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') IN (6,7);

    RETURN GREATEST(6 - dow1, 0)
        + FLOOR( ( (TRUNC(d2)-dow2) - (TRUNC(d1)+8-dow1) + 1 ) / 7 * 5 )
        + LEAST(5, dow2)
        - holidays + workdays
        - 1;
END;");

        echo "Installing pl/sql function BizDaysFloat{$EOL}";
        $db->query("
CREATE OR REPLACE FUNCTION BizDaysFloat(d1_utc IN DATE, d2_utc IN DATE, user_offset_hours FLOAT)
RETURN FLOAT
IS
start_hour CONSTANT FLOAT := 7;
end_hour CONSTANT FLOAT := 20;
d1 DATE;
d2 DATE;
dow1 INT;
dow2 INT;
is_holiday1 NUMBER(1);
is_holiday2 NUMBER(1);
holidays INT;
workdays INT;
BEGIN
    IF d1_utc > d2_utc THEN
        RETURN BizDaysFloat(d2_utc, d1_utc, user_offset_hours);
    END IF;

    SELECT d1_utc + user_offset_hours/24 INTO d1 FROM dual;
    SELECT d2_utc + user_offset_hours/24 INTO d2 FROM dual;

    SELECT 1 + TRUNC(d1) - TRUNC(d1, 'IW') INTO dow1 FROM dual;
    SELECT 1 + TRUNC(d2) - TRUNC(d2, 'IW') INTO dow2 FROM dual;

    IF dow1 NOT IN (6,7) THEN
      SELECT LEAST(1, COUNT(*)) INTO is_holiday1
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d1) AND is_holiday = 'H';
    ELSE
      SELECT 1 - LEAST(1, COUNT(*)) INTO is_holiday1
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d1) AND is_holiday = 'W';
    END IF;

    IF dow2 NOT IN (6,7) THEN
      SELECT LEAST(1, COUNT(*)) INTO is_holiday2
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d2) AND is_holiday = 'H';
    ELSE
      SELECT 1 - LEAST(1, COUNT(*)) INTO is_holiday2
      FROM BizDaysCalendar
      WHERE calendar_date = TRUNC(d2) AND is_holiday = 'W';
    END IF;

    SELECT COUNT(*) INTO holidays
    FROM BizDaysCalendar
    WHERE calendar_date >= TRUNC(d1) AND calendar_date <= d2 AND is_holiday = 'H'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') NOT IN (6,7);

    SELECT COUNT(*) INTO workdays
    FROM BizDaysCalendar
    WHERE calendar_date >= TRUNC(d1) AND calendar_date <= d2 AND is_holiday = 'W'
        AND 1 + TRUNC(calendar_date) - TRUNC(calendar_date, 'IW') IN (6,7);

    RETURN GREATEST(6 - dow1, 0)
        + FLOOR( ( (TRUNC(d2)-dow2) - (TRUNC(d1)+8-dow1) + 1 ) / 7 * 5 )
        + LEAST(5, dow2)
        - holidays + workdays
        + CASE WHEN is_holiday1 = 1 THEN 0 ELSE -1 + GREATEST(0, LEAST(1, ((TRUNC(d1) - d1) * 24 +   end_hour)/(end_hour - start_hour))) END
        + CASE WHEN is_holiday2 = 1 THEN 0 ELSE -1 + GREATEST(0, LEAST(1, ((d2 - TRUNC(d2)) * 24 - start_hour)/(end_hour - start_hour))) END
    ;
END;
");
    }
}
