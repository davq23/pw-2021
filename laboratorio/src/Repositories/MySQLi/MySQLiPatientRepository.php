<?php

namespace Repositories\MySQLi;

use Domains\Patient;
use Exception;

class MySQLiPatientRepository extends MySQLiRepository implements \Repositories\PatientRepository
{

    /**
     * @inheritDoc
     */
    public function fetchAll(int $limit, int $offset = 0): array
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * @inheritDoc
     */
    public function findById($id): ?Patient
    {
        // TODO: Implement findById() method.
    }

    /**
     * @inheritDoc
     */
    public function searchByName(string $name): array
    {
        // TODO: Implement searchByName() method.
    }

    /**
     * @inheritDoc
     */
    public function registerPatient(Patient $patient): void
    {
        // TODO: Implement registerPatient() method.
    }
}