<?php

namespace Domains;

use DateTime;
use Domains\Exceptions\InvalidDomainException;

class Exam implements Domain
{
    private DateTime $bookingDate;
    private DateTime $plannedDate;
    private DateTime $actualDate;
    private string $description;

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
        return new Exam();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}