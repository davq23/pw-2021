<?php
namespace Repositories;

use Domains\Patient;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;

/**
 * Encompasses all storage-related activities involving patients
 */
interface PatientRepository
{
    /**
     * Fetches all patients
     *
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function fetchAll(int $limit, int $offset = 0): array;

    /**
     * Fetches one patient by id
     *
     * @param $id
     * @return Patient|null
     * @throws DomainNotFoundException
     */
    public function findById($id): ?Patient;

    /**
     * Search patients by name
     *
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function searchByName(string $name): array;

    /**
     * Registers a patient
     *
     * @param Patient $patient
     * @throws DomainNotFoundException
     */
    public function registerPatient(Patient $patient): Patient;
}