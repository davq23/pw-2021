<?php
require __DIR__ . '/autoload.php';

use App\App;
use App\DefaultRouter;
use Controllers\PatientController;
use Database\PDODBConnection;

$router = new DefaultRouter();
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lab', 'root', '');

$pdoDBConnection = new PDODBConnection($pdo);

$router->GET('patient/(:empty)', PatientController::class, 'index');
$router->POST('patient/(:empty)', PatientController::class, 'register');

$app = new App($router, $pdoDBConnection);