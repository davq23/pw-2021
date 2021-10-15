<?php

require_once '../src/load.php';
/** @var App\App $app */

try {
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
