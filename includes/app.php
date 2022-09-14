<?php
use Dotenv\Dotenv;
use Model\ActiveRecord;
require __DIR__ . "/../vendor/autoload.php";    //importo clases

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require "funciones.php";    //importo funciones
require "config/database.php";  //importo bd

//Conectar DB
$db = conectarDB();


ActiveRecord::setDB($db);
