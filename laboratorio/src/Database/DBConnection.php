<?php
namespace Database;

interface DBConnection
{
    /** Get raw connection */
    public function getConnection();
}