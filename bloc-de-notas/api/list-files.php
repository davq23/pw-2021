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


function removeDir(string $path)
{
    global $auth;
    return str_replace(__DIR__.'/../workspace/user_'.$auth.'/', '', $path);
}

function filterFiles(string $entryName) 
{
    return strpos($entryName, '.txt') !== false;
}


$files = rglob(__DIR__ . "/../workspace/user_$auth/*");

$filesSanitized = array_map('removeDir', $files);

$filesSanitized = array_filter($filesSanitized, 'filterFiles');
       
header('Content-Type: application/json');

echo json_encode(array_values($filesSanitized));