<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author hardsoft321.org
 */
$dictionary['BizDaysCalendar'] = array(
    'table' => 'BizDaysCalendar',
    'fields' => array (
        array('name' =>'calendar_date', 'type' =>'date'),
        array('name' =>'is_holiday', 'type' =>'char', 'len'=>'1')
     ),
     'indices' => array (
         array('name' =>'BizDaysCalendar_pk', 'type' =>'primary', 'fields'=>array('calendar_date')),
     ),
);
