<?php

namespace Repositories\MySQLi;

use Domains\Patient;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;

class MySQLiPatientRepository extends MySQLiRepository implements PatientRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $pageNumber = 0): array {
        $patients = array();
        $patientArray = $this->dbConnection->fetchAllOffsetPaginated('patients', '*', $limit, $pageNumber);

        foreach ($patientArray as $patientRow) {
            $patients[] = new Patient(
                $patientRow['id'],
                $patientRow['surnames'],
                $patientRow['family_names'],
                $patientRow['birthday'],
                $patientRow['user_id']
            );
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findById($id): ?Patient {
        $patient = null;

        $statement = $this->mysqli()->prepare(
            'SELECT surnames, family_names, birthday, user_id FROM patients WHERE id = ? LIMIT 1'
        );

        $statement->bind_param('i', $id);

        $statement->execute();

        $result = $statement->get_result();

        while ($patientArray = mysqli_fetch_assoc($result)) {
            $patient = new Patient(
                $id,
                $patientArray['surnames'],
                $patientArray['family_names'],
                $patientArray['birthday'],
                $patientArray['user_id']
            );
        }

        if (!$patient) {
            throw new DomainNotFoundException();
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array {
        $patients = array();
        $name .= '%';

        $statement = $this->mysqli()->prepare(
            'SELECT id, surnames, family_names, birthday, user_id FROM patients WHERE name LIKE ?'
        );

        $statement->bind_param('s', $name);

        $statement->execute();

        $result = $statement->get_result();

        while ($patientArray = mysqli_fetch_assoc($result)) {
            $patients[] = new Patient(
                $patientArray['id'],
                $patientArray['surnames'],
                $patientArray['family_names'],
                $patientArray['birthday'],
                $patientArray['user_id'],
            );
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Patient {
        $statement = $this->mysqli()->prepare(
            'SELECT id, surnames, family_names, birthday FROM patients WHERE user_id = ? LIMIT 1'
        );

        $statement->bind_param('s', $userId);

        $statement->execute();

        $result = $statement->get_result();

        while ($patientArray = mysqli_fetch_assoc($result)) {
            $patient = new Patient(
                $patientArray['id'],
                $patientArray['surnames'],
                $patientArray['family_names'],
                $patientArray['birthday'],
                $userId
            );
        }

        if (!$patient) {
            throw new DomainNotFoundException();
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function registerPatient(Patient $patient): Patient {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO patients (surnames, family_names, birthday, user_id) VALUES (?, ?, ?, ?)'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $userId = $patient->getUserId();

        $statement->bind_param('ssss', $surnames, $familyNames, $birthday, $userId);

        $statement->execute();

        return $patient;
    }

    /** {@inheritDoc} */
    public function updatePatient(Patient $patient): Patient {
        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $patientId = $patient->getId();

        $statement = $this->mysqli()->prepare(
            'UPDATE patients SET surnames = ?, family_names = ?, birthday = ? WHERE id = ?'
        );

        $statement->bind_param('ssss', $surnames, $familyNames, $birthday, $patientId);

        $statement->execute();

        $patient->setId($this->mysqli()->insert_id);

        return $patient;
    }

}
