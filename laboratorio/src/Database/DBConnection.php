<?php
namespace Database;

interface DBConnection
{
    /** Get raw connection */
    public function getConnection();

    /** 
     * Fetch all records
     * 
     * @param string $tableName
     * @return array
     */
    public function fetchAll(string $tableName, array|string $columns): array;


    /**
     * Fetch all records paginated by offset
     *
     * @param string $tableName
     * @param integer $limit
     * @param integer $pageNumber
     * @return array
     */
    public function fetchAllOffsetPaginated(
        string $tableName, 
        array|string $columns, 
        int $limit, 
        int $pageNumber
    ): array;
}