<?php

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

class Exam implements Domain
{
    private $id;
    private $doctorId;
    private $nurseId;
    private $patientId;
    private string $description;
    private string $datetime;
    private string $status;
    private ?string $results;

    public function getResults(): ?string {
        return $this->results;
    }

    public function setResults(?string $results): void {
        $this->results = $results;
    }

    public function __construct(
        $id,
        $doctorId,
        $nurseId,
        $patientId,
        string $description,
        string $datetime,
        string $status,
        ?string $results = null
    ) {
        $this->id = $id;
        $this->doctorId = $doctorId;
        $this->nurseId = $nurseId;
        $this->patientId = $patientId;
        $this->description = $description;
        $this->datetime = $datetime;
        $this->status = $status;
        $this->results = $results;
    }

    public function getNurseId() {
        return $this->nurseId;
    }

    public function getId() {
        return $this->id;
    }

    public function getDoctorId() {
        return $this->doctorId;
    }

    public function getPatientId() {
        return $this->patientId;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setDoctorId($doctorId): void {
        $this->doctorId = $doctorId;
    }

    public function setPatientId($patientId): void {
        $this->patientId = $patientId;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDatetime(): string {
        return $this->datetime;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setDatetime(string $datetime): void {
        $this->datetime = $datetime;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setNurseId($nurseId): void {
        $this->nurseId = $nurseId;
    }

    /**
     * @inheritDoc
     */
    public function validate(): void {
        if (mb_strlen($this->description) < 20 || mb_strlen($this->description) > 255) {
            throw new InvalidDomainException('Description must be between 20 and 255 chars long');
        }

        if (
            $this->status === 'done' && !$this->results
        ) {
            throw new InvalidDomainException('Exam can not be marked as done without results');
        }

        if (
            !is_null($this->results) &&
            (mb_strlen($this->results) < 50 || 2000 < mb_strlen($this->results))
        ) {
            throw new InvalidDomainException('Results must be between 50 and 2000 chars long');
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $source): Domain {
        return new Exam(
            $source['id'] ?? null,
            $source['doctor_id'] ?? null,
            $source['nurse_id'] ?? null,
            $source['patient_id'] ?? null,
            $source['description'] ?? '',
            $source['datetime'] ?? '',
            $source['status'] ?? '',
            $source['results'] ?? null
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
// TODO: Implement jsonSerialize() method.
    }

}
