<?php

namespace Domains;

class Nurse extends Person
{
    private array $exams;

    public function getExams(): array {
        return $this->exams;
    }

    public function setExams(array $exams): void {
        $this->exams = $exams;
    }

    public static function fromArray(array $source): Domain {
        return new Nurse(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            $source['birthday'] ?? null,
            $source['user_id'] ?? null,
        );
    }

}
