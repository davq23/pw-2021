<?php

namespace App;

/**
 * Manages App sessions
 *
 */
interface SessionManager
{
    public const FLASH_KEY = '_____FLASH_____';

    /**
     * Add item to session storage
     *
     * @param string $key
     * @param $item
     */
    public function add(string $key, $item): void;

    /**
     * Delete item from session storage
     *
     * @param string $key
     */
    public function delete(string $key): void;

    /**
     * Destroy current session
     *
     */
    public function destroy(): void;

    /**
     * Get item from session
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Regenerates session id
     *
     * @param bool $destroy
     * @return mixed
     */
    public function regenerateId(bool $destroy = false);

    /**
     * Sets flash message
     *
     * @param string $key
     * @return void
     */
    public function setFlash(string $key, $item): void;

    /**
     * Gets flash message, deleting it in the process
     *
     * @param string $key
     * @return mixed
     */
    public function getFlash(string $key);
}
