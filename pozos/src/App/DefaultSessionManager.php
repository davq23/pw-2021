<?php

namespace App;

/**
 * Implementation for plain PHP Sessions
 *
 * @author David Quintero <davidquinterogranadillo@gmail.com>
 * @package App
 * @access public
 * @version 1.0
 */
class DefaultSessionManager implements SessionManager
{

    public function __construct(
        string $sessionName,
        int $maxLifeTime = 0,
        string $samesite = 'lax',
        string $domain = '',
        string $path = '/',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        session_name($sessionName);

        session_set_cookie_params([
            'lifetime' => $maxLifeTime,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $samesite
        ]);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->path = $path;
    }

    public function __destruct() {
        // TODO: Implement __destruct() method.
    }

    /**
     * @inheritDoc
     */
    public function add(string $key, $item): void {
        $_SESSION[$key] = $item;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void {
        unset($_SESSION[$key]);
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void {
        session_destroy();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function regenerateId(bool $destroy = false) {
        session_regenerate_id($destroy);
    }

    public function setFlash(string $key, $item): void {
        $_SESSION[self::FLASH_KEY][$key] = $item;
    }

    public function getFlash(string $key) {
        $item = $_SESSION[self::FLASH_KEY][$key] ?? null;

        if ($item) {
            unset($_SESSION[self::FLASH_KEY][$key]);
        }

        return $item;
    }

}
