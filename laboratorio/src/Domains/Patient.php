<?php

namespace Domains;

use DateTime;
use Domains\Exceptions\InvalidDomainException;

class Patient implements Domain
{
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSurnames(): string
    {
        return $this->surnames;
    }

    /**
     * @param string $surnames
     */
    public function setSurnames(string $surnames): void
    {
        $this->surnames = $surnames;
    }

    /**
     * @return string
     */
    public function getFamilyNames(): string
    {
        return $this->familyNames;
    }

    /**
     * @param string $familyNames
     */
    public function setFamilyNames(string $familyNames): void
    {
        $this->familyNames = $familyNames;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getAge(): int
    {
        return DateTime::createFromFormat('Y-m-d', $this->birthday)->diff(new DateTime())->y;
    }

    protected $id;
    protected string $surnames;
    protected string $familyNames;
    protected string $birthday;

    public function __construct($id, string $surnames, string $familyNames, string $birthday)
    {
        $this->surnames = $surnames;
        $this->familyNames = $familyNames;
        $this->birthday = $birthday;
    }

    /** {@inheritDoc} */
    public function validate(): void
    {
        if (!preg_match('/^[\w]+\s[\w]+$/', $this->surnames)) {
            throw new InvalidDomainException('Invalid surnames');
        }

        if (!preg_match('/^[\w]+\s[\w]+$/', $this->familyNames)) {
            throw new InvalidDomainException('Invalid family names');
        }

        if (!DateTime::createFromFormat('Y-m-d', $this->birthday)) {
            throw new InvalidDomainException('Invalid date');
        }
    }

    /** {@inheritDoc} */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'surnames' => $this->getSurnames(),
            'family_names' => $this->getFamilyNames(),
            'birthday' => $this->getBirthday()
        );
    }

    /** {@inheritDoc} */
    public static function fromArray(array $source): Patient
    {
        return new Patient(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            isset($source['birthday']) ? $source['birthday'] : null
        );
    }
}