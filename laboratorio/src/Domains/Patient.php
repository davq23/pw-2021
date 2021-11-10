<?php

namespace Domains;

use DateTime;
use Domains\Exceptions\InvalidDomainException;

class Patient extends Person
{
    private array $exams;

    public function getExams(): array {
        return $this->exams;
    }

    public function setExams(array $exams): void {
        $this->exams = $exams;
    }

}
