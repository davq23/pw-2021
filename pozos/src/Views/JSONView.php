<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Views;

/**
 * Description of JSONView
 *
 * @author davido
 */
abstract class JSONView implements View, \JsonSerializable
{

    public function render(): string {
        header('Content-Type: application/json');

        return json_encode($this);
    }

}
