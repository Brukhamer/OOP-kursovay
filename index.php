<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

use app\core\Router;
require "autoload.php";
$route = new Router();
$route->run();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Главная страница</title>
    </head>
    <body>

    </body>
</html>
