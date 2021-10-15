<?php
namespace Database;

use PDO;

class PDODBConnection implements DBConnection
{
    protected ?PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    /** @inheritDoc */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
}