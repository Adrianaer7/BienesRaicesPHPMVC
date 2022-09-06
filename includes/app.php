<?php
require "funciones.php";    //importo funciones
require "config/database.php";  //importo bd
require __DIR__ . "/../vendor/autoload.php";    //importo clases

//Conectar DB
$db = conectarDB();

use Model\ActiveRecord;

ActiveRecord::setDB($db);
