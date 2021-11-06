<?php

namespace Repositories\PDO;

use Database\PDODBConnection;
use PDO;

/**
 * PDORepository utilizing a PDODBConnection
 */
class PDORepository
{
    protected ?PDODBConnection $dbConnection;

    public function __construct(PDODBConnection $connection)
    {
        $this->dbConnection = $connection;
    }

    public function __destruct()
    {
        $this->dbConnection = null;
    }

    public function getConnection(): ?PDODBConnection
    {
        return $this->dbConnection;
    }

    /**
     * Returns underlying PDO connection instance
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->dbConnection->getConnection();
    }
}