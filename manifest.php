<?php
$manifest = array(
    'name' => 'bizdayscalendar',
    'acceptable_sugar_versions' => array(),
    'acceptable_sugar_flavors' => array('CE'),
    'author' => 'hardsoft321',
    'description' => 'Календарь рабочих дней',
    'is_uninstallable' => true,
    'published_date' => '2016-07-20',
    'type' => 'module',
    'remove_tables' => 'prompt',
    'version' => '1.23.0',
);
$installdefs = array(
    'id' => 'bizdayscalendar',
    'relationships' => array(
        array(
            'module' => '',
            'meta_data' => '<basepath>/source/relationships/bizdayscalendarMetaData.php',
        ),
    ),
);
