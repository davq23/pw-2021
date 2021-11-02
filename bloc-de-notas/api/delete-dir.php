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

$dirnameRaw = filter_input(INPUT_POST, 'dirname');

if (!$dirnameRaw) {
    http_response_code(400);
    exit('Invalid directory');
}

$dirname = filterSanitizePath($dirnameRaw);

error_log($dirname);

if (!rmdir(__DIR__ . '/../workspace/user_' . $auth .'/'. $dirname)) {
    http_response_code(500);
    exit('Non-empty or unexistent directory');
}

exit('Directory deleted');
