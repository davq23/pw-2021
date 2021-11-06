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
     * @param array|string $columns
     * @return array
     */
    public function fetchAll(string $tableName, $columns): array;


    /**
     * Fetch all records paginated by offset
     *
     * @param string $tableName
     * @param array|string $columns
     * @param integer $limit
     * @param integer $pageNumber
     * @return array
     */
    public function fetchAllOffsetPaginated(
        string $tableName, 
        $columns, 
        int $limit, 
        int $pageNumber
    ): array;
}