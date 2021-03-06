<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET')
{
    http_response_code(405);
    exit();
}

require_once __DIR__ . '/auth.php';

$auth = auth();

if (!$auth) {
    http_response_code(401);
    exit();
}

require_once __DIR__.'/../utils/functions.php';

$dirs = rglob(__DIR__ . "/../workspace/user_$auth/*");

function removeDir(string $path)
{
    global $auth;
    return str_replace(__DIR__.'/../workspace/user_'.$auth.'/', '', $path);
}

function filterFiles(string $entryName) 
{
    return strpos($entryName, '.txt') === false;
}

$dirsSanitized = array_map('removeDir', $dirs);

$dirsSanitized = array_filter($dirsSanitized, 'filterFiles');
       
header('Content-Type: application/json');

echo json_encode(array_values($dirsSanitized));