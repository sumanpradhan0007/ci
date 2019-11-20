<?php
  // configure database
  $db['default'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'XXXX', // Your username if required.
    'password' => 'XXXX', // Your password if any.
    'database' => 'XXXX_DB', // Your database name.
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
  );
?>
