<?php

namespace App;

class DefaultSessionManager implements SessionManager
{

    /**
     * @inheritDoc
     */
    public function add(string $key, $item): void
    {
        // TODO: Implement add() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @inheritDoc
     */
    public function regenerateId(bool $destroy = false)
    {
        // TODO: Implement regenerateId() method.
    }
}