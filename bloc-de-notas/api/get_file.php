<?php

require_once __DIR__ . '/auth.php';

$auth = auth();

if (!$auth) {
    http_response_code(401);
    exit();
}

require_once __DIR__ . '/../utils/functions.php';

$filenameSanitized = filterSanitizePath(str_replace('\.([A-z0-9]+)$', '', urldecode(filter_input(INPUT_GET, 'filename'))));

$fileHandle = fopen(__DIR__ . "/../workspace/user_$auth/$filenameSanitized", 'r');

if ($fileHandle === false)
{
    http_response_code(500);
    echo 'Unable to create file';
    exit();
}

$read = fread($fileHandle, 20000);

if ($read === false)
{
    http_response_code(500);
    echo 'Error while writing file';
    fclose($fileHandle);
    exit();
}

fclose($fileHandle);

header('Content-Type: application/json');

echo json_encode(array(
    'filename' => $filenameSanitized,
    'fileContents' => htmlspecialchars($read)
));


