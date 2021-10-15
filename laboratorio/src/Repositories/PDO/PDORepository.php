<?php

namespace Repositories\PDO;

use Database\PDODBConnection;
use PDO;

/**
 * PDORepository utilizing a PDODBConnection
 */
class PDORepository
{
    private ?PDODBConnection $connection;

    public function __construct(PDODBConnection $connection)
    {
        $this->connection = $connection;
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * Returns underlying PDO connection instance
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->connection->getConnection();
    }
}