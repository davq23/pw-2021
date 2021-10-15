<?php

namespace Repositories\PDO;

use Domains\Patient;
use Repositories\PatientRepository;

/**
 * PatientRepository utilizing a PDODBConnection
 */
class PDOPatientRepository extends PDORepository implements PatientRepository
{
    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $offset = 0): array
    {
        $patients = array();

        $statement = $this->pdo()->prepare('SELECT * FROM patients LIMIT ? OFFSET ?');


        $statement->bindParam(1, $limit, \PDO::PARAM_INT);
        $statement->bindParam(2, $offset, \PDO::PARAM_INT);

        $statement->execute();

        while ($patientArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $patients[] = new Patient(
                $patientArray['id'],
                $patientArray['surnames'],
                $patientArray['family_names'],
                new \DateTime($patientArray['birthday'])
            );
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function findById($id): ?Patient
    {
        $patient = null;

        $statement = $this->pdo()->prepare('SELECT * FROM patients WHERE id = ? LIMIT 1');

        $statement->bindParam(1, $id);

        $statement->execute();

        while ($patientArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $patient = $patients[] = new Patient(
                $patientArray['id'],
                $patientArray['surnames'],
                $patientArray['family_names'],
                new \DateTime($patientArray['birthday'])
            );
        } 

        return $patient;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array
    {
        $patients = array();

        $statement = $this->pdo()->prepare('SELECT * FROM patients WHERE name LIKE ?');

        $statement->bindParam(1, $name, \PDO::PARAM_STR);

        $statement->execute();

        while ($patientArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $patients[] = $patients[] = new Patient(
                $patientArray['id'],
                $patientArray['surnames'],
                $patientArray['family_names'],
                new \DateTime($patientArray['birthday'])
            );
        }

        return $patients;
    }

    /** {@inheritDoc} */
    public function registerPatient(Patient $patient): void
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO (surnames, family_names, birthday) VALUES (?, ?, ?)'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday()->format('YYYY-mm-dd');

        $statement->bindParam(1, $surnames);
        $statement->bindParam(2, $familyNames);
        $statement->bindParam(3, $birthday);

        $statement->execute();

        $patient->setId($this->pdo()->lastInsertId());
    }
}