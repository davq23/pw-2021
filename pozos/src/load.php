<?php

require_once __DIR__ . '/autoload.php';

use App\App;
use App\DefaultRouter;
use App\DefaultSessionManager;
use Controllers\LoginController;
use Controllers\OilWellController;
use Controllers\PanelController;
use Controllers\SignupController;
use Database\MySQLiDBConnection;

ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . '/../logs/' . date("Y-m-d\.\l\o\g"));

/*
  // PostGreSQL Connection
  $pg = pg_connect('postgresql://lab_user:1234@localhost:5432/lab');
  $dbConnection = new PGDBConnection($pg);
 */


// MySQLi Connection
$mysqli = mysqli_connect('localhost:3306', 'root', '', 'wells');
$dbConnection = new MySQLiDBConnection($mysqli);

/*
  /*PDO Connection
  $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lab', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbConnection = new PDOBConnection($pdo);
 */

$router = new DefaultRouter();
$sessionManager = new DefaultSessionManager();

$router->GET('(:empty)', PanelController::class, 'index');
$router->GET('panel/(:empty)', PanelController::class, 'index');

$router->POST('well/new', OilWellController::class, 'register');
$router->POST('well/update/(:empty)', OilWellController::class, 'update');
$router->POST('well/delete', OilWellController::class, 'delete');
$router->GET('well/update/(:empty)', OilWellController::class, 'updateForm');
$router->GET('well/new', OilWellController::class, 'registerForm');

$router->GET('well/measurements/(:empty)', OilWellController::class, 'getMeasurements');
$router->GET('well/measurements/edit', OilWellController::class, 'editMeasurementForm');
$router->POST('well/measurements/add', OilWellController::class, 'addMeasurement');
$router->POST('well/measurements/edit', OilWellController::class, 'editMeasurement');
$router->POST('well/measurements/delete', OilWellController::class, 'deleteMeasurement');

$router->POST('login/(:empty)', LoginController::class, 'postLoginForm');
$router->GET('login/(:empty)', LoginController::class, 'loginForm');

$router->POST('logout/(:empty)', LoginController::class, 'logout');

$router->GET('signup/(:empty)', SignupController::class, 'signupForm');
$router->POST('signup/(:empty)', SignupController::class, 'postsignupForm');

$app = new App($router, $dbConnection, $sessionManager);
