<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Views;

use Domains\Domain;

/**
 * Description of JSONDomainView
 *
 * @author davido
 */
class JSONDomainView extends JSONView
{
    private string $message;
    private Domain $domain;

    public function __construct(string $message, Domain $domain) {
        $this->message = $message;
        $this->domain = $domain;
    }

    public function jsonSerialize(): mixed {
        return array(
            'message' => $this->message,
            'domain' => $this->domain
        );
    }

}
