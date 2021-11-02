<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
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

if (!isset($_GET['filename'])) {
    http_response_code(400);
    echo "Filename is not defined.";
    exit();
}


$filenameSanitized = filterSanitizePath(str_replace('\.([A-z0-9]+)$', '', urldecode(filter_input(INPUT_GET, 'filename'))));

$path = __DIR__ . "/../workspace/user_$auth/$filenameSanitized";

if (!file_exists($path)) {
    http_response_code(404);
    echo "File does not exist.";
    exit();
}

//Define header information
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="' . basename($path) . '"');
header('Content-Length: ' . filesize($path));
header('Pragma: public');

//Clear system output buffer
flush();

//Read the size of the file
readfile($path);

//Terminate from the script
exit();
