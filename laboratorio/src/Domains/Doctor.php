<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

/**
 * Description of Doctor
 *
 * @author davido
 */
class Doctor extends Person
{
    private array $credentials;
    private ?array $exams;

    public function __construct(
        $id,
        string $surnames,
        string $familyNames,
        string $birthday,
        $userId,
        array $credentials
    ) {
        parent::__construct($id, $surnames, $familyNames, $birthday, $userId);

        $this->credentials = $credentials;
    }

    public function getCredentials(): array {
        return $this->credentials;
    }

    public function getExams(): ?array {
        return $this->exams;
    }

    public function setCredentials(array $credentials): void {
        $this->credentials = $credentials;
    }

    public function setExams(?array $exams): void {
        $this->exams = $exams;
    }

    public function validate(): void {
        parent::validate();

        if (!is_array($this->credentials) || count($this->credentials) === 0) {
            throw new InvalidDomainException('A doctor must have credentials');
        }
    }

    public static function fromArray(array $source): Doctor {
        return new Doctor(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            $source['birthday'] ?? null,
            $source['user_id'] ?? null,
            $source['credentials'] ?? array()
        );
    }

}
