<?php


use vodopadik\User;

require_once 'vendor/Autoloader.php';

$autoloader = new Autoloader();

$autoloader->addNamespace('vodopadik', __DIR__ . DIRECTORY_SEPARATOR . 'src');
$autoloader->register();

$user = new User('John', 'Smith');
var_dump($user);
