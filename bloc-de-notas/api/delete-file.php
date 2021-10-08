<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);
    exit();
}

$filenameRaw = filter_input(INPUT_POST, 'filename');

if (!$filenameRaw)
{
    http_response_code(400);
    exit('Invalid filename');
}

require_once  __DIR__ .  '/../utils/functions.php';

$filenameSanitized = filterSanitizePath(preg_replace('/\.([A-z0-9]+)$/', '', $filenameRaw));

$filePath = __DIR__ . "/../workspace/$filenameSanitized.txt";

if (unlink($filePath) === false)
{
    http_response_code(500);
    exit('Unknown error');
}

exit('Directory erased');


