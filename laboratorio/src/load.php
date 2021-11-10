<?php

require_once __DIR__ . '/autoload.php';

use App\App;
use App\DefaultRouter;
use App\DefaultSecretKeyManager;
use App\DefaultSessionManager;
use Controllers\DoctorController;
use Controllers\ExamController;
use Controllers\LoginController;
use Controllers\PanelController;
use Controllers\PatientController;
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
$mysqli = mysqli_connect('localhost:3306', 'root', '', 'lab');
$dbConnection = new MySQLiDBConnection($mysqli);

/*
  /*PDO Connection
  $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=lab', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbConnection = new PDOBConnection($pdo);
 */

$router = new DefaultRouter();
$sessionManager = new DefaultSessionManager('LAB_SESSID');

// Panel routes
$router->GET('(:empty)', PanelController::class, 'index');
$router->GET('panel/(:empty)', PanelController::class, 'index');

// Patient routes
$router->GET('patients/(:empty)', PatientController::class, 'index');
$router->POST('patients/(:empty)', PatientController::class, 'register');
$router->POST('patients/update', PatientController::class, 'update');
$router->GET('patients/register', PatientController::class, 'registerForm');

// Doctor routes
$router->GET('doctors/register/(:empty)', DoctorController::class, 'registerForm');
$router->POST('doctors/(:empty)', DoctorController::class, 'register');
$router->POST('doctors/update', DoctorController::class, 'update');

// Exam routes
$router->GET('exams/(:empty)', ExamController::class, 'index');

// Session control
$router->GET('login/(:empty)', LoginController::class, 'loginForm');
$router->POST('login/(:empty)', LoginController::class, 'postLoginForm');
$router->POST('logout/(:empty)', LoginController::class, 'logout');
$router->GET('signup/(:empty)', SignupController::class, 'signupForm');
$router->GET('doctors/signup', SignupController::class, 'signupDoctorForm');
$router->POST('signup/(:empty)', SignupController::class, 'postsignupForm');

$app = new App($router, $dbConnection, $sessionManager);

$app->injectClass(
    DefaultSecretKeyManager::class,
    '$2a$12$tlnRX0QGXgJ3U4xKQXFiiOXgj8vJG3/umvmhwQxGL41J92Cu8zEVS'
);
