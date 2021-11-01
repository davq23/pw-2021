<?php

namespace Repositories\MySQLi;

use Domains\Patient;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;

class MySQLiPatientRepository extends MySQLiRepository implements PatientRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $pageNumber = 0): array
    {
        $patients = array();
        $patientArray = $this->dbConnection->fetchAllOffsetPaginated('patients', '*', $limit, $pageNumber);

        foreach ($patientArray as $patientRow) {
            $patients[] = new Patient(
                $patientRow['id'],
                $patientRow['surnames'],
                $patientRow['family_names'],
                $patientRow['birthday']
            );
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findById($id): ?Patient
    {
        $patient = null;
        $id = null;
        $surnames = null;
        $familyNames = null;
        $birthday = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM patients WHERE id = ? LIMIT 1');
        $statement->bind_param('i', $id);

        $statement->bind_result($id, $surnames, $familyNames, $birthday);

        $statement->execute();

        while($statement->fetch()) {
            $patient = new Patient($id, $surnames, $familyNames, $birthday);
        }

        if (is_null($patient)) {
            throw new DomainNotFoundException();
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array
    {
        $patients = array();
        $id = null;
        $surnames = null;
        $familyNames = null;
        $birthday = null;
        $name .= '%';

        $statement = $this->mysqli()->prepare('SELECT * FROM patients WHERE name LIKE ?');
        $statement->bind_param('s', $name);

        $statement->bind_result($id, $surnames, $familyNames, $birthday);

        $statement->execute();

        while($statement->fetch()) {
            $patients[] = new Patient($id, $surnames, $familyNames, $birthday);
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Patient
    {
        $id = null;
        $surnames = null;
        $familyNames = null;
        $birthday = null;
        $patient = null;

        $statement = $this->mysqli()->prepare(
            'SELECT * FROM patients WHERE user_id = ? LIMIT 1'
        );

        $statement->bind_param('s', $userId);
        $statement->bind_result($id, $surnames, $familyNames, $birthday);

        $statement->execute();

        while ($statement->fetch($id, $surnames, $familyNames, $birthday)) {
            $patient = new Patient($id, $surnames, $familyNames, $birthday);
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function registerPatient(Patient $patient): Patient
    {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO patients (surnames, family_names, birthday) VALUES (?, ?, ?)'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();

        $statement->bind_param('sss', $surnames, $familyNames, $birthday);

        $statement->execute();

        return $patient;
    }

    /** {@inheritDoc} */
    public function updatePatient(Patient $patient): Patient
    {
        $statement = $this->mysqli()->prepare(
            'UPDATE patients surnames = ?, family_names = ?, birthday = ? WHERE id = ?'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $patientId = $patient->getId();

        $statement->bind_param('ssss', $surnames, $familyNames, $birthday, $patientId);

        $statement->execute();

        $patient->setId($this->mysqli()->insert_id);

        return $patient;
    }
}