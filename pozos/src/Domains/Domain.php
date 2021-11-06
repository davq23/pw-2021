<?php

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

interface Domain extends \JsonSerializable
{
    /**
     * Checks the validity of the domain
     *
     * @return void
     * @throws InvalidDomainException
     */
    public function validate(): void;

    /**
     * Creates a domain class out of an associative array
     *
     * @param array $source
     * @return Domain
     */
    public static function fromArray(array $source): Domain;
}