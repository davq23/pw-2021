<?php

namespace Repositories;

use Domains\Patient;

/**
 *
 * @author davido
 */
interface PatientRepository
{

    /**
     * Finds a patient by id
     *
     * @param $id
     * @return Patient
     */
    public function findById($id): Patient;

    /**
     * Registers a patient
     *
     * @param Patient $patient
     */
    public function registerPatient(Patient $patient): Patient;

    /**
     * Updates a patient
     *
     * @param Patient $patient
     */
    public function updatePatient(Patient $patient): Patient;

    /**
     * Deletes a patient
     *
     * @param Patient $patient
     */
    public function deletePatient(Patient $patient): void;

    /**
     * Fetches all patients
     *
     */
    public function fetchAll(): array;
}
