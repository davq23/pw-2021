<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Domains;

/**
 * Description of Person
 *
 * @author davido
 */
class Person implements Domain
{
    protected $id;
    protected string $surnames;
    protected string $familyNames;
    protected string $birthday;
    protected $userId;

    public function getId() {
        return $this->id;
    }

    public function getSurnames(): string {
        return $this->surnames;
    }

    public function getFamilyNames(): string {
        return $this->familyNames;
    }

    public function getBirthday(): string {
        return $this->birthday;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setSurnames(string $surnames): void {
        $this->surnames = $surnames;
    }

    public function setFamilyNames(string $familyNames): void {
        $this->familyNames = $familyNames;
    }

    public function setBirthday(string $birthday): void {
        $this->birthday = $birthday;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function __construct(
        $id,
        string $surnames,
        string $familyNames,
        string $birthday,
        $userId
    ) {
        $this->id = $id;
        $this->surnames = $surnames;
        $this->familyNames = $familyNames;
        $this->birthday = $birthday;
        $this->userId = $userId;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->getId(),
            'surnames' => $this->getSurnames(),
            'family_names' => $this->getFamilyNames(),
            'birthday' => $this->getBirthday()
        );
    }

    /** {@inheritDoc} */
    public function validate(): void {
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

    public static function fromArray(array $source): Domain {
        return new Person(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            $source['birthday'] ?? null,
            $source['user_id'] ?? null,
        );
    }

}
