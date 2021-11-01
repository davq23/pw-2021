<?php
require_once __DIR__ . '/autoload.php';

use App\App;
use App\DefaultRouter;
use App\DefaultSessionManager;
use Controllers\ExamController;
use Controllers\LoginController;
use Controllers\PanelController;
use Controllers\PatientController;
use Controllers\SignupController;
use Database\MySQLiDBConnection;
use Database\PDODBConnection;
use Database\PGDBConnection;

ini_set("log_errors", 1);
ini_set("error_log", __DIR__.'/../logs/'.date("Y-m-d\.\l\o\g"));

// PostGreSQL Connection
$pg = pg_connect('postgresql://lab_user:1234@localhost:5432/lab');
$dbConnection = new PGDBConnection($pg);

/*
// MySQLi Connection
$mysqli = mysqli_connect('localhost', 'root', '', 'lab', 3306);
$dbConnetion = new MySQLiDBConnection($mysqli);
*/

/*
/*PDO Connection
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lab', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbConnection = new PDOBConnection($pg);
*/

$router = new DefaultRouter();
$sessionManager = new DefaultSessionManager();

$router->GET('(:empty)', PanelController::class, 'redirectPanel');
$router->GET('panel/(:empty)', PanelController::class, 'index');
$router->GET('patients/(:empty)', PatientController::class, 'index');
$router->GET('patients/register', PatientController::class, 'registerForm');
$router->POST('patients/(:empty)', PatientController::class, 'register');
$router->GET('exams/(:empty)', ExamController::class, 'index');
$router->GET('login/(:empty)', LoginController::class, 'loginForm');
$router->POST('login/(:empty)', LoginController::class, 'postLoginForm');
$router->POST('logout/(:empty)', LoginController::class, 'logout');
$router->GET('signup/(:empty)', SignupController::class, 'signupForm');
$router->POST('signup/(:empty)', SignupController::class, 'postsignupForm');

$app = new App($router, $dbConnection, $sessionManager);
