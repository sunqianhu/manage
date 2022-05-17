<?php
session_start();
require_once '../vendor/autoload.php';

use app\Route;

$route = null;

$route = new Route();
$route->run();
?>