<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

require_once __DIR__ . '/auth.php';

$auth = auth();

if (!$auth) {
    http_response_code(401);
    exit();
}

require_once __DIR__ . '/../utils/functions.php';

$filenameRaw = filterSanitizePath(filter_input(INPUT_POST, 'filename'));
$buttonActivated = filter_input(INPUT_POST, 'create-file');

if (!$filenameRaw) {
    http_response_code(422);
    exit('Invalid file');
}

$filenameSanitized = filterSanitizePath(preg_replace('/\.([A-z0-9]+)$/', '', $filenameRaw));

$filePath = __DIR__ . "/../workspace/user_$auth/$filenameSanitized.txt";

if (file_exists($filePath)) {
    http_response_code(400);
    exit('File already exists');
}

$fileHandle = fopen($filePath, 'w');

if ($fileHandle === false) {
    http_response_code(500);
    echo 'Unable to create file';
    exit();
}

if (fwrite($fileHandle, '', 0) === false) {
    http_response_code(500);
    echo 'Error while writing file';
    fclose($fileHandle);
    exit();
}

fclose($fileHandle);

echo 'File created';
