<?php

use core\App;
use core\database\Connection;
use core\database\QueryBuilder;
header('Access-Control-Allow-Origin: *');

App::bind('config', require 'config.php');

$app['config'] = require 'config.php';

App::bind(
  'database', new QueryBuilder(
    Connection::make(App::get('config')['database'])
  )
);

function view($nameView, $data = [])
{
  if($data) {
    extract($data);
  }
  json_encode($data);
  return require "app/views/{$nameView}.view.php";
}

function redirect($path) 
{
  $link = "http://". $_SERVER['HTTP_HOST']. "/" .$path;
  return header("Location: {$link}");
}
