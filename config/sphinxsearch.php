<?php
return array(
    'host'    => '192.168.10.10', //Такие же хост и порт у вас должны быть прописаны в конфиге Сфинкса
    'port'    => 9312, //для данной конфигурации соответственно listen = 192.168.10.10:9312
    'timeout' => 30,
    'indexes' => array(
        'index_name' => array ( 'table' => 'table_name', 'column' => 'column_name' )
    )
);
