<?php

namespace Database;

use function PHPSTORM_META\map;

class MySQLiDBConnection implements DBConnection
{
    protected \mysqli $connection;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    /** {@inheritDoc} */
    public function getConnection(): \mysqli
    {
        return $this->connection;
    }

    /** {@inheritDoc} */
    public function fetchAll(string $tableName, array|string $columns = '*'): array
    {
        $rows = array();

        $tableNameSanitized = $this->connection->real_escape_string($tableName);
        
        if (is_array($columns)) {
            $columns = implode(',', array_map(function(string $columnName)
            {
                return $this->connection->real_escape_string($columnName);
            }, $columns));

        } else if ($columns !== '*') {
            $columns = $this->connection->real_escape_string($columns);
        }

        $statement = $this->connection->prepare("SELECT $columns FROM $tableNameSanitized");

        $statement->execute();

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
           $rows[] = $row;
        } 

        $statement->close();
        
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

        $tableNameSanitized = $this->connection->real_escape_string($tableName);
        
        if (is_array($columns)) {
            $columns = implode(',', array_map(function(string $columnName)
            {
                return $this->connection->real_escape_string($columnName);
            }, $columns));
            
        } else if ($columns !== '*') {
            $columns = $this->connection->real_escape_string($columns);
        }

        $statement = $this->connection->prepare(
            "SELECT $columns FROM $tableNameSanitized LIMIT ? OFFSET ?");

        $offset = $pageNumber * $limit;
        
        $statement->bind_param('ii', $limit, $offset);

        $statement->execute();

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
           $rows[] = $row;
        } 

        $statement->close();

        return $rows;
    }


}