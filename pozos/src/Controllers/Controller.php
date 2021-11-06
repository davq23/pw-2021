<?php

namespace Controllers;

use App\SessionManager;
use App\Exceptions\UnauthorizedRequestException;

class Controller
{
    public const AJAX_REQUEST = 1;

    /**
     * Gets JSON body from request
     * @return array|false
     */
    public function getJsonBody() {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * @param string $url
     * @param bool $replace
     */
    public function redirect(string $url, bool $replace = false) {
        header("Location:" . BASE_URL . "$url", $replace ? 301 : 302);
        exit();
    }

    /**
     * @throws UnauthorizedRequestException
     */
    public function auth(
        SessionManager $sessionManager,
        bool $logged = true,
        int $requestType = 0,
        array $roles = array()
    ) {
        $userId = $sessionManager->get('user_id');

        if ($logged && !$userId || !$logged && $userId) {
            throw new UnauthorizedRequestException($logged ? 'login' : 'panel', $requestType);
        }

        return $userId;
    }

}
