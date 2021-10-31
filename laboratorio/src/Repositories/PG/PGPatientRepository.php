<?php

namespace Repositories\PG;

use Domains\Patient;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;

class PGPatientRepository extends PGRepository implements PatientRepository
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

        $statement = pg_prepare($this->pg(), 'patient_find_by_id', 'SELECT * FROM patients WHERE id = $1 LIMIT 1');

        $result = pg_execute($this->pg(), 'patient_find_by_id', array($id));

        while($row = pg_fetch_assoc($result)) {
            $patient = new Patient($id, $row['surnames'], $row['family_names'], $row['birthday']);
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
        $name .= '%';

        $statement = pg_prepare($this->pg(), 'patient_search_by_name', 'SELECT * FROM patients WHERE surnames LIKE $1');
    
        $result = pg_execute($this->pg(), 'patient_search_by_name', array($name));

        while($row = pg_fetch_assoc($result)) {
            $patients[] = new Patient($row['id'], $row['surnames'], $row['family_names'], $row['birthday']);
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Patient
    {
        $statement = pg_prepare(
            $this->pg(), 
            '', 
            'SELECT * FROM patients WHERE user_id = $1 LIMIT 1'
        );

        $result = pg_execute($this->pg(), 'patient_find_by_user_id', array($userId));

        while ($row = pg_fetch_assoc($result)) {
            $patient = new Patient($row['id'], $row['surnames'], $row['family_names'], $row['birthday']);
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function registerPatient(Patient $patient): Patient
    {
        $statement = pg_prepare(
            $this->pg(), 
            '', 
            'INSERT INTO patients (surnames, family_names, birthday) VALUES ($1, $2, $3) RETURNING id'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();

        $result = pg_execute($this->pg(), '', array($surnames, $familyNames, $birthday));

        $patient->setId(pg_fetch_assoc($result)['id']);

        return $patient;
    }

    /** {@inheritDoc} */
    public function updatePatient(Patient $patient): Patient
    {
        $statement = pg_prepare(
            $this->pg(), 
            'update_patient', 
            'UPDATE patients surnames = $1, family_names = $2, birthday = $3 WHERE id = $4'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $patientId = $patient->getId();

        $result = pg_execute($this->pg(), '', array($surnames, $familyNames, $birthday, $patientId));

        return $patient;
    }
}