<?php

namespace Database;

use Exception;

class PGDBConnection implements DBConnection
{
    protected $connection;

    public function __construct($connection)
    {
        if (!$connection) {
            throw new Exception('Connection object is invalid');
        }

        $this->connection = $connection;
    }

    public function __destruct()
    {
        pg_close($this->connection);
    }

    /** {@inheritDoc} */
    public function getConnection()
    {
        return $this->connection;
    }

    /** {@inheritDoc} */
    public function fetchAll(string $tableName, $columns = '*'): array
    {
        $rows = array();

        $tableNameSanitized = pg_escape_string($tableName);
        
        if (is_array($columns)) {
            $columns = implode(',', array_map(function(string $columnName)
            {
                return pg_escape_string($this->connection, $columnName);
            }, $columns));

        } else if ($columns !== '*') {
            $columns = pg_escape_string($this->connection);
        }

        $statement = pg_prepare($this->connection, 'fetch_all', "SELECT $columns FROM $tableNameSanitized");

        $result = pg_execute($this->connection, 'fetch_all', array());

        while ($row = pg_fetch_assoc($result)) {
           $rows[] = $row;
        } 

        return $rows;
    }

    /** {@inheritDoc} */
    public function fetchAllOffsetPaginated(
        string $tableName, 
        $columns, 
        int $limit, 
        int $pageNumber
    ): array {
        $rows = array();

        $tableNameSanitized = pg_escape_string($tableName);
        
        if (is_array($columns)) {
            $columns = implode(',', array_map(function(string $columnName)
            {
                return pg_escape_string($this->connection, $columnName);
            }, $columns));

        } else if ($columns !== '*') {
            $columns = pg_escape_string($this->connection);
        }

        $statement = pg_prepare($this->connection, 'fetch_all_paginated',
            "SELECT $columns FROM $tableNameSanitized LIMIT $1 OFFSET $2");

        $offset = $pageNumber * $limit;

        $result = pg_execute($this->connection, '', array($limit, $offset));

        while ($row = pg_fetch_assoc($result)) {
           $rows[] = $row;
        } 

        return $rows;
    }


}