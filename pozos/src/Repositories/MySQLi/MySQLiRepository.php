<?php

namespace Repositories\MySQLi;

use Database\MySQLiDBConnection;

class MySQLiRepository
{
    protected ?MySQLiDBConnection $dbConnection;

    public function __construct(MySQLiDBConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function __destruct() {
        $this->dbConnection = null;
    }

    /**
     * @return MySQLiDBConnection|null
     */
    public function getDbConnection(): ?MySQLiDBConnection {
        return $this->dbConnection;
    }

    /**
     * Returns raw mysqli connection
     *
     * @return \mysqli
     */
    public function mysqli(): \mysqli {
        return $this->dbConnection->getConnection();
    }

}
