<?php

namespace Controllers;

class Controller
{
    /**
     * Gets JSON body from request
     * @return array|false
     */
    public function getJsonBody()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}