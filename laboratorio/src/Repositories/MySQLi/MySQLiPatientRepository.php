<?php

namespace Repositories\MySQLi;

use Domains\Patient;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;

class MySQLiPatientRepository extends MySQLiRepository implements PatientRepository
{

    /**
     * @inheritDoc
     */
    public function fetchAll(int $limit, int $pageNumber = 0): array
    {
        $patients = array();
        $patientArray = $this->dbConnection->fetchAllOffsetPaginated('patients', '*', $limit, $pageNumber);

        foreach ($patientArray as $patientRow) {
            $patients[] = new Patient(
                $patientRow['id'],
                $patientRow['surnames'],
                $patientRow['family_names'],
                new \DateTime($patientRow['birthday'])
            );
        }

        return $patients;
    }

    /**
     * @inheritDoc
     */
    public function findById($id): ?Patient
    {
        $patient = null;
        $id = null;
        $surnames = null;
        $familyNames = null;
        $birthday = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM patients WHERE id = ? LIMIT 1');
        $statement->bind_param('i', $id);

        $statement->bind_result($id, $surnames, $familyNames, new \DateTime($birthday));

        $statement->execute();

        while($statement->fetch()) {
            $patient = new Patient($id, $surnames, $familyNames, new \DateTime($birthday));
        }

        if (is_null($patient)) {
            throw new DomainNotFoundException();
        }

        return $patient;
    }

    /**
     * @inheritDoc
     */
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
            $patients[] = new Patient($id, $surnames, $familyNames, new \DateTime($birthday));
        }

        return $patients;
    }

    /**
     * @inheritDoc
     */
    public function registerPatient(Patient $patient): Patient
    {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO patients (surnames, family_names, birthday) VALUES (?, ?, ?)'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday()->format('YYYY-mm-dd');

        $statement->bind_param('sss', $surnames, $familyNames, $birthday);

        $statement->execute();

        $patient->setId($this->mysqli()->insert_id);

        return $patient;
    }
}