<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);
    exit();
}

require_once __DIR__ . '/../utils/functions.php';

$filenameRaw = filterSanitizePath(filter_input(INPUT_POST, 'filename'));
$fileContents = filter_input(INPUT_POST, 'file-contents');
$buttonActivated = filter_input(INPUT_POST, 'create-file');

if (!$filenameRaw || $fileContents === false || is_null($fileContents))
{
    http_response_code(422);
    exit('Invalid file');
}

$filenameSanitized = filterSanitizePath(preg_replace('/\.([A-z0-9]+)$/', '', $filenameRaw));

$filePath = __DIR__ . "/../workspace/$filenameSanitized.txt";

if (!file_exists($filePath))
{
    http_response_code(404);
    print_r($filenameSanitized);
    exit('File doesn\'t exist');
}

$fileHandle = fopen($filePath, 'w');

if ($fileHandle === false)
{
    http_response_code(500);
    echo 'Unable to create file';
    exit();
}

if (fwrite($fileHandle, $fileContents, mb_strlen($fileContents)) === false)
{
    http_response_code(500);
    echo 'Error while writing file';
    fclose($fileHandle);
    exit();
}

fclose($fileHandle);

echo 'File edited';