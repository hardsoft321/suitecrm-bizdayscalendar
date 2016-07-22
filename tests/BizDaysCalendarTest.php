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
    WHEN BizDaysInclusive('2016-02-02', '2016-02-01') !=-1 THEN 'error3'
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
    ELSE 'no_errors'
    END AS error_code
FROM dual
SQL;
        return $sql;
    }
}
