<?php


require_once __DIR__ . '/../utils/functions.php';

$filenameSanitized = filterSanitizePath(str_replace('\.([A-z0-9]+)$', '', urldecode(filter_input(INPUT_GET, 'filename'))));

$fileHandle = fopen(__DIR__ . "/../workspace/$filenameSanitized", 'r');

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

echo json_encode(array(
    'filename' => $filenameSanitized,
    'fileContents' => htmlspecialchars($read)
));


