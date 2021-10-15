<?php

namespace Domains;

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
     * @return \DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }
    protected $id;
    protected string $surnames;
    protected string $familyNames;
    protected \DateTime $birthday;

    public function __construct($id, string $surnames, string $familyNames, \DateTime $birthday)
    {
        $this->surnames = $surnames;
        $this->familyNames = $familyNames;
        $this->birthday = $birthday;
    }

    /** {@inheritDoc} */
    public function validate(): void
    {
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

    public static function fromArray(array $source): Patient
    {
        return new Patient(
            $source['id'] ?? null,
            $source['surnames'] ?? null,
            $source['family_names'] ?? null,
            isset($source['birthday']) ? new \DateTime($source['birthday']) : null
        );
    }
}