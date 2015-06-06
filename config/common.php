<?php

$config = array();

$config['db.options'] = array(
  'dbname'  => 'pictoporn',
  'host'    => '127.0.0.1',
  'driver'  => 'pdo_mysql',
  'charset' => 'utf8',
    'driverOptions' => array(1002 => 'SET NAMES utf8'));


$config['hubtraffic.options'] = array(
  'host' => 'http://www.pornhub.com/webmasters',
  'key' => '1234567890ABCDE'
);
