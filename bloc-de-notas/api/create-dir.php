<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);
    exit();
}

require_once __DIR__.'/../utils/functions.php';

$dirnameRaw = filter_input(INPUT_POST, 'dirname');

if (!$dirnameRaw)
{
    http_response_code(400);
    exit('Invalid directory');
}

$dirname = filterSanitizePath($dirnameRaw);

$directoryCount = substr_count($dirname, '/');

if ($directoryCount > 3)
{
    http_response_code(507);
    exit('Directory depth must be less than 3');
}

$buttonActivated = filter_input(INPUT_POST, 'make-dir');


if (mkdir(__DIR__ . '/../workspace/'.$dirname) === false)
{
    http_response_code(500);
    echo 'Error while writing directory';
    exit();
}

exit('Directory created');
