<?php

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

class Exam implements Domain
{

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        // TODO: Implement validate() method.
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $source): Domain
    {
        // TODO: Implement fromArray() method.
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}