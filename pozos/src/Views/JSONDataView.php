<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Views;

use JsonSerializable;

/**
 * Description of JSONDataView
 *
 * @author davido
 */
class JSONDataView extends JSONView implements JsonSerializable
{
    private int $resultCount;
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
        $this->resultCount = count($data);
    }

    public function jsonSerialize() {
        return array(
            'data' => $this->data,
            'result_count' => $this->resultCount
        );
    }

}
