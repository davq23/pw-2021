<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET')
{
    http_response_code(405);
    exit();
}

require_once __DIR__.'/../utils/functions.php';


$dirs = rglob(__DIR__ . '/../workspace/*');

function removeDir(string $path)
{
    return str_replace(__DIR__.'/../workspace/', '', $path);
}


$dirsSanitized = array_map('removeDir', $dirs);
       
header('Content-Type: application/json');

echo json_encode($dirsSanitized);