<?php

namespace Domains;

use DateTime;
use Domains\Exceptions\InvalidDomainException;

/**
 * Description of Patient
 *
 * @author davido
 */
class Patient extends Person
{
    private string $dni;
    private string $email;

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getDni(): string {
        return $this->dni;
    }

    public function setDni(string $dni): void {
        $this->dni = $dni;
    }

    public function __construct(
        $id,
        string $surnames,
        string $familyNames,
        string $birthday,
        string $dni,
        string $email
    ) {
        parent::__construct($id, $surnames, $familyNames, $birthday, null);

        $this->dni = $dni;
        $this->email = $email;
    }

    public function validate(): void {
        if (!preg_match('/^[\w]+$/', $this->surnames)) {
            throw new InvalidDomainException('Invalid surnames');
        }
        if (!preg_match('/^[\w]+$/', $this->familyNames)) {
            throw new InvalidDomainException('Invalid surnames');
        }
        if (!DateTime::createFromFormat('Y-m-d', $this->birthday)) {
            throw new InvalidDomainException('Invalid date');
        }
        if (!$this->dni || !is_numeric($this->dni) || strlen($this->dni) > 12) {
            throw new InvalidDomainException('Invalid dni');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidDomainException('Invalid email');
        }
    }

    public static function fromArray(array $source): Patient {
        return new Patient(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            $source['birthday'] ?? null,
            $source['dni'] ?? null,
            $source['email'] ?? null,
        );
    }

}
