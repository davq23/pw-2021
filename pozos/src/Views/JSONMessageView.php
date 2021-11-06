<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Views;

/**
 * Description of JSONMessageView
 *
 * @author davido
 */
class JSONMessageView extends JSONView
{
    private int $code;
    private string $message;

    public function __construct(int $code, string $message) {
        $this->code = $code;
        $this->message = $message;
    }

    public function jsonSerialize(): mixed {
        return array(
            'code' => $this->code,
            'message' => $this->message
        );
    }

    public function render(): string {
        http_response_code($this->code);

        return parent::render();
    }

}
