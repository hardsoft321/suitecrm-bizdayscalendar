<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */
class BizDaysCalendarTest
{
    public function getBizDaysInclusiveTestQuery()
    {
        $sql = <<<'SQL'
SELECT CASE
    WHEN BizDaysInclusive('2016-02-01', '2016-02-02') != 1 THEN 'error1'
    WHEN BizDaysInclusive('2016-02-01', '2016-02-01') != 0 THEN 'error2'
    WHEN BizDaysInclusive('2016-02-02', '2016-02-01') != 1 THEN 'error3'
    WHEN BizDaysInclusive('2016-02-15', '2016-02-17') != 2 THEN 'error4'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-20') != 1 THEN 'error5'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-21') != 1 THEN 'error6'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-22') != 1 THEN 'error7'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-23') != 1 THEN 'error8'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-24') != 2 THEN 'error9'
    WHEN BizDaysInclusive('2016-02-19', '2016-02-25') != 3 THEN 'error10'
    WHEN BizDaysInclusive('2016-01-01', '2016-12-31') != 246 THEN 'error2016'
    WHEN BizDaysInclusive('2016-02-07', '2016-02-13') != 4 THEN 'error11'
    WHEN BizDaysInclusive('2016-02-07', '2016-02-14') != 4 THEN 'error12'
    WHEN BizDaysInclusive('2016-02-07', '2016-02-15') != 5 THEN 'error13'
    WHEN BizDaysInclusive('2016-02-06', '2016-02-13') != 4 THEN 'error14'
    WHEN BizDaysInclusive('2016-02-06', '2016-02-14') != 4 THEN 'error15'
    WHEN BizDaysInclusive('2016-02-06', '2016-02-15') != 5 THEN 'error16'
    WHEN BizDaysInclusive('2016-02-05', '2016-02-12') != 5 THEN 'error17'
    WHEN BizDaysInclusive('2016-02-05', '2016-02-13') != 5 THEN 'error18'
    WHEN BizDaysInclusive('2016-02-05', '2016-02-14') != 5 THEN 'error19'
    WHEN BizDaysInclusive('2016-02-05', '2016-02-15') != 6 THEN 'error20'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-09') != 4 THEN 'error21'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-10') != 5 THEN 'error22'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-11') != 6 THEN 'error23'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-16') != 9 THEN 'error24'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-17') !=10 THEN 'error25'
    WHEN BizDaysInclusive('2016-02-03', '2016-02-18') !=11 THEN 'error26'
    WHEN BizDaysInclusive('2016-02-12', '2016-02-13') != 0 THEN 'error27'
    WHEN BizDaysInclusive('2017-01-01', '2017-12-31') != 246 THEN 'error2017'
    WHEN BizDaysInclusive('2017-03-08', '2017-03-09') != 0 THEN 'error28'
    WHEN BizDaysInclusive('2017-03-08 00:00:01', '2017-03-09') != 0 THEN 'error29'
    WHEN BizDaysInclusive('2018-12-17 00:00:01', '2018-12-24') != 5 THEN 'error30'
    ELSE 'no_errors'
    END AS error_code
FROM dual
SQL;
        return $sql;
    }

    public function getBizDaysFloatTestQuery()
    {
        $sql = <<<'SQL'
SELECT CASE
    WHEN NVL(BizDaysFloat('2016-02-01 07:00:00', '2016-02-02 07:00:00', 0), -1) != 1 THEN 'error1'
    WHEN NVL(BizDaysFloat('2016-02-01 07:00:00', '2016-02-01 07:00:00', 0), -1) != 0 THEN 'error2'
    WHEN NVL(BizDaysFloat('2016-02-02 07:00:00', '2016-02-01 07:00:00', 0), -1) != 1 THEN 'error3'
    WHEN NVL(BizDaysFloat('2016-02-15 07:00:00', '2016-02-17 07:00:00', 0), -1) != 2 THEN 'error4'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-20 07:00:00', 0), -1) != 1 THEN 'error5'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-21 07:00:00', 0), -1) != 2 THEN 'error6'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-22 07:00:00', 0), -1) != 2 THEN 'error7'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-23 07:00:00', 0), -1) != 2 THEN 'error8'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-24 07:00:00', 0), -1) != 2 THEN 'error9'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-25 07:00:00', 0), -1) != 3 THEN 'error10'
    WHEN NVL(BizDaysFloat('2016-01-01 07:00:00', '2016-12-31 07:00:00', 0), -1) != 247 THEN 'error2016'
    WHEN NVL(BizDaysFloat('2016-02-07 07:00:00', '2016-02-13 07:00:00', 0), -1) != 5 THEN 'error11'
    WHEN NVL(BizDaysFloat('2016-02-07 07:00:00', '2016-02-14 07:00:00', 0), -1) != 5 THEN 'error12'
    WHEN NVL(BizDaysFloat('2016-02-07 07:00:00', '2016-02-15 07:00:00', 0), -1) != 5 THEN 'error13'
    WHEN NVL(BizDaysFloat('2016-02-06 07:00:00', '2016-02-13 07:00:00', 0), -1) != 5 THEN 'error14'
    WHEN NVL(BizDaysFloat('2016-02-06 07:00:00', '2016-02-14 07:00:00', 0), -1) != 5 THEN 'error15'
    WHEN NVL(BizDaysFloat('2016-02-06 07:00:00', '2016-02-15 07:00:00', 0), -1) != 5 THEN 'error16'
    WHEN NVL(BizDaysFloat('2016-02-05 07:00:00', '2016-02-12 07:00:00', 0), -1) != 5 THEN 'error17'
    WHEN NVL(BizDaysFloat('2016-02-05 07:00:00', '2016-02-13 07:00:00', 0), -1) != 6 THEN 'error18'
    WHEN NVL(BizDaysFloat('2016-02-05 07:00:00', '2016-02-14 07:00:00', 0), -1) != 6 THEN 'error19'
    WHEN NVL(BizDaysFloat('2016-02-05 07:00:00', '2016-02-15 07:00:00', 0), -1) != 6 THEN 'error20'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-09 07:00:00', 0), -1) != 4 THEN 'error21'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-10 07:00:00', 0), -1) != 5 THEN 'error22'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-11 07:00:00', 0), -1) != 6 THEN 'error23'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-16 07:00:00', 0), -1) != 9 THEN 'error24'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-17 07:00:00', 0), -1) !=10 THEN 'error25'
    WHEN NVL(BizDaysFloat('2016-02-03 07:00:00', '2016-02-18 07:00:00', 0), -1) !=11 THEN 'error26'
    WHEN NVL(BizDaysFloat('2016-02-12 07:00:00', '2016-02-13 07:00:00', 0), -1) != 1 THEN 'error27'
    WHEN NVL(BizDaysFloat('2017-01-01 07:00:00', '2017-12-31 07:00:00', 0), -1) != 247 THEN 'error2017'
    WHEN NVL(BizDaysFloat('2016-03-08 07:00:00', '2016-03-09 20:00:00', 0), -1) != 1 THEN 'error28'
    WHEN NVL(BizDaysFloat('2017-03-07 07:00:00', '2017-03-08 20:00:00', 0), -1) != 1 THEN 'error29'
    WHEN NVL(BizDaysFloat('2017-04-03', '2017-04-03'                  , 0), -1) != 0 THEN 'error30'
    WHEN NVL(BizDaysFloat('2017-04-04 07:00:00', '2017-04-10 20:00:00', 0), -1) != 5 THEN 'error31'
    WHEN NVL(BizDaysFloat('2016-03-08 13:30:00', '2016-03-09 20:00:00', 0), -1) != 1 THEN 'error32'
    WHEN NVL(BizDaysFloat('2017-03-07 07:00:00', '2017-03-08 13:00:00', 0), -1) != 1 THEN 'error33'
    WHEN NVL(BizDaysFloat('2017-03-08 14:00:00', '2017-03-09 21:00:00', 0), -1) != 1 THEN 'error34'
    WHEN NVL(BizDaysFloat('2017-03-07 13:30:00', '2017-03-08 12:00:00', 0), -1) != 0.5 THEN 'error35'
    WHEN NVL(BizDaysFloat('2017-03-08 08:00:01', '2017-03-09 21:00:00', 0), -1) != 1 THEN 'error36'
    WHEN NVL(BizDaysFloat('2017-03-08', '2017-03-09 20:00:00'         , 0), -1) != 1 THEN 'error37'
    WHEN NVL(BizDaysFloat('2017-04-23 07:00:00', '2017-04-24 20:00:00', 0), -1) != 1 THEN 'error38'
    WHEN NVL(BizDaysFloat('2012-03-07 07:00:00', '2012-03-11 20:00:00', 0), -1) != 2 THEN 'error39'
    WHEN NVL(BizDaysFloat('2012-03-07 07:00:00', '2012-03-11 13:30:00', 0), -1) != 1.5 THEN 'error40'
    WHEN NVL(BizDaysFloat('2012-03-11 07:00:00', '2012-03-12 20:00:00', 0), -1) != 2 THEN 'error41'
    WHEN NVL(BizDaysFloat('2012-03-11 13:30:00', '2012-03-12 20:00:00', 0), -1) != 1.5 THEN 'error42'
    WHEN NVL(BizDaysFloat('2012-03-11 13:30:00', '2012-03-12 20:00:00', 0), -1) != 1.5 THEN 'error43'
    WHEN NVL(BizDaysFloat('2012-03-11 07:00:00', '2012-03-12 13:30:00', 0), -1) != 1.5 THEN 'error44'
    WHEN NVL(BizDaysFloat('2016-02-20 00:00:00', '2016-02-24 20:00:00', 0), -1) != 2 THEN 'error45'
    WHEN NVL(BizDaysFloat('2016-02-20 13:30:00', '2016-02-24 20:00:00', 0), -1) != 1.5 THEN 'error46'
    WHEN NVL(BizDaysFloat('2016-02-20 13:30:00', '2016-02-24 20:00:00', 0), -1) != 1.5 THEN 'error47'
    WHEN NVL(BizDaysFloat('2016-02-20 07:00:00', '2016-02-24 13:30:00', 0), -1) != 1.5 THEN 'error48'
    WHEN NVL(BizDaysFloat('2016-02-19 07:00:00', '2016-02-20 13:30:00', 0), -1) != 1.5 THEN 'error49'
    WHEN NVL(BizDaysFloat('2017-04-23 08:00:00', '2017-04-24 13:30:00', 0), -1) != 0.5 THEN 'error50'
    WHEN NVL(BizDaysFloat('2017-04-21 07:00:00', '2017-04-22 13:30:00', 0), -1) != 1 THEN 'error51'
    WHEN NVL(BizDaysFloat('2017-04-21 07:00:00', '2017-04-22 13:30:00', 0), -1) != 1 THEN 'error52'
    WHEN NVL(BizDaysFloat('2017-04-21 07:00:00', '2017-04-21 20:00:00', 0), -1) != 1 THEN 'error53'
    WHEN NVL(BizDaysFloat('2017-04-21 07:00:00', '2017-04-21 20:00:00', 6.5), -1) != 0.5 THEN 'error54'
    WHEN NVL(BizDaysFloat('2017-04-21 07:00:00', '2017-04-21 20:00:00', 12), -1) != 1/13 THEN 'error55'
    WHEN NVL(BizDaysFloat('2017-05-01 08:00:00', '2017-05-02 18:00:00', 0), -1) != 11/13 THEN 'error56'
    WHEN NVL(BizDaysFloat('2017-04-30 20:00:00', '2017-05-02 06:00:00', 12), -1) != 11/13 THEN 'error57'
    WHEN NVL(BizDaysFloat('2017-04-23 19:00:00', '2017-04-24 08:00:00', 12), -1) != 1 THEN 'error58'
    WHEN NVL(BizDaysFloat('2017-04-24 19:00:00', '2017-04-25 08:00:00', 12), -1) != 1 THEN 'error59'
    WHEN NVL(BizDaysFloat('2017-05-01 19:00:00', '2017-05-02 08:00:00', 12), -1) != 1 THEN 'error60'
    WHEN NVL(BizDaysFloat('2017-05-01 19:00:00', '2017-05-02 01:30:00', 12), -1) != 0.5 THEN 'error61'
    WHEN NVL(BizDaysFloat('2017-05-02 01:30:00', '2017-05-02 08:00:00', 12), -1) != 0.5 THEN 'error62'
    WHEN NVL(BizDaysFloat('2017-05-01 19:00:00', '2017-05-02 08:00:00', 0), -1) != 1/13 THEN 'error63'
    WHEN NVL(BizDaysFloat('2017-05-02 07:00:00', '2017-05-03 20:00:00', 0), -1) != 2 THEN 'error64'
    WHEN NVL(BizDaysFloat('2017-05-01 19:00:00', '2017-05-03 08:00:00', 12), -1) != 2 THEN 'error65'
    WHEN NVL(BizDaysFloat('2017-04-24 08:00:00', '2017-04-25 20:00:00', 0), -1) != 1+12/13 THEN 'error66'
    WHEN NVL(BizDaysFloat('2017-04-23 20:00:00', '2017-04-25 08:00:00', 12), -1) != 1+12/13 THEN 'error67'
    WHEN NVL(BizDaysFloat('2017-05-02 08:00:00', '2017-05-03 20:00:00', 0), -1) != 1+12/13 THEN 'error68'
    WHEN NVL(BizDaysFloat('2017-05-01 20:00:00', '2017-05-03 08:00:00', 12), -1) != 1+12/13 THEN 'error69'
    WHEN NVL(BizDaysFloat('2017-03-07 07:00:00', '2017-03-09 20:00:00', 0), -1) != 2 THEN 'error70'
    WHEN NVL(BizDaysFloat('2017-03-06 19:00:00', '2017-03-09 08:00:00', 12), -1) != 2 THEN 'error71'
    WHEN NVL(BizDaysFloat('2017-03-07 07:00:00', '2017-03-09 08:00:00', 0), -1) != 1+1/13 THEN 'error72'
    WHEN NVL(BizDaysFloat('2017-03-06 19:00:00', '2017-03-08 20:00:00', 12), -1) != 1+1/13 THEN 'error73'
    WHEN ROUND(NVL(BizDaysFloat('2017-03-07 07:00:00', '2017-03-07 20:00:00', 0, 6, 21), -1), 10) != ROUND(13/15, 10) THEN 'error74'
    WHEN ROUND(NVL(BizDaysFloat('2017-03-07 20:00:00', '2017-03-07 07:00:00', 0, 6, 21), -1), 10) != ROUND(13/15, 10) THEN 'error75'
    ELSE 'no_errors'
    END AS error_code
FROM dual;
SQL;
        return $sql;
    }

    public function getBizDaysMinutesTestQuery()
    {
        $sql = <<<'SQL'
SELECT CASE
    WHEN ROUND(NVL(BizDaysMinutes('2019-10-11 07:00:00', '2019-10-11 07:05:00', 0), -1), 10) != 5 THEN 'error1'
    WHEN ROUND(NVL(BizDaysMinutes('2019-10-11 07:00:00', '2019-10-11 07:05:30', 0), -1), 10) != 5.5 THEN 'error2'
    WHEN ROUND(NVL(BizDaysMinutes('2019-10-11 07:00:00', '2019-10-11 07:05:00', 0, 8), -1), 10) != 0 THEN 'error3'
    WHEN ROUND(NVL(BizDaysMinutes('2019-10-10 18:00:00', '2019-10-11 18:00:00', 0, 9, 18), -1), 10) != 9*60 THEN 'error4'
    WHEN ROUND(NVL(BizDaysMinutes('2019-10-10 14:00:00', '2019-10-10 23:00:00', 3, 9, 18), -1), 10) != 60 THEN 'error5'
    ELSE 'no_errors'
    END AS error_code
FROM dual;
SQL;
        return $sql;
    }
}
