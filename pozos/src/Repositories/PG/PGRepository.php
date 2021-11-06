<?php

namespace Repositories\PG;

use Database\PGDBConnection;

class PGRepository
{
    protected ?PGDBConnection $dbConnection;

    public function __construct(PGDBConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function __destruct()
    {
        $this->dbConnection = null;
    }

    public function getConnection(): ?PGDBConnection
    {
        return $this->dbConnection;
    }

    /**
     * Returns underlying pg connection resource
     *
     * @return resource
     */
    public function pg()
    {
        return $this->dbConnection->getConnection();
    }
}