<?php
namespace Database;

use PDO;

class PDODBConnection implements DBConnection
{
    protected ?PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
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

    /** {@inheritDoc} */
    public function fetchAll(string $tableName, array|string $columns = '*'): array
    {
        $rows = array();

        
        if (is_array($columns)) {
            $columns = implode(',', $columns);

        } else if ($columns !== '*') {
            $columns = $columns;
        }

        $statement = $this->connection->prepare("SELECT $columns FROM $tableName");

        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        $statement->closeCursor();
        
        return $rows;
    }

    /** {@inheritDoc} */
    public function fetchAllOffsetPaginated(
        string $tableName, 
        array|string $columns, 
        int $limit, 
        int $pageNumber
    ): array {
        $rows = array();

        if (is_array($columns)) {
            $columns = implode(',', $columns);

        } else if ($columns !== '*') {
            $columns = $columns;
        }

        $offset = $pageNumber * $limit;

        $statement = $this->connection->prepare("SELECT $columns FROM $tableName LIMIT ? OFFSET ?");

        $statement->bindParam(1, $limit, PDO::PARAM_INT);
        $statement->bindParam(2, $offset, PDO::PARAM_INT);

        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        $statement->closeCursor();

        return $rows;
    }
}