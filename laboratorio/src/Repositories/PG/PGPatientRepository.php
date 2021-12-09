<?php

namespace Repositories\PG;

use Domains\Patient;
use Repositories\PatientRepository;

/**
 * Description of PGPatientRepository
 *
 * @author davido
 */
class PGPatientRepository extends PGRepository implements PatientRepository
{

    /** {@inheritDoc} */
    public function deletePatient(Patient $patient): void {
        $statement = pg_prepare($this->pg(), '',
            'DELETE FROM patients WHERE id = $1'
        );

        $result = pg_execute($this->pg(), '', array($patient->getId()));
    }

    /** {@inheritDoc} */
    public function findById($id): Patient {
        $statement = pg_prepare($this->pg(), '',
            'SELECT username, email, password FROM users WHERE id = $1 LIMIT 1'
        );

        $result = pg_execute($this->pg(), '', array($id));

        while ($row = pg_fetch_assoc($result)) {
            $patient = new Patient(
                $id,
                $row['surnames'],
                $row['family_names'],
                $row['birthday']
            );
        }

        if (!$patient) {
            throw new DomainNotFoundException();
        }

        return $patient;
    }

    /** {@inheritDoc} */
    public function registerPatient(Patient $patient): Patient {
        $statement = pg_prepare($this->pg(), '',
            'INSERT INTO patients (surname, family_name, birthday) VALUES ($1, $2, $3) RETURNING id'
        );

        $result = pg_execute($this->pg(), '', array(
            $patient->getSurnames(),
            $patient->getFamilyNames(),
            $patient->getBirthday()
            )
        );

        $patient->setId(pg_fetch_assoc($result)['id']);

        return $patient;
    }

    /** {@inheritDoc} */
    public function updatePatient(Patient $patient): Patient {
        $statement = pg_prepare($this->pg(), '',
            'UPDATE patients SET surname = ?, family_name = ?, birthday = ? WHERE id = ?'
        );

        $result = pg_execute($this->pg(), '', array(
            $patient->getSurnames(),
            $patient->getFamilyNames(),
            $patient->getBirthday(),
            $patient->getId()
            )
        );

        return $patient;
    }

    /** {@inheritDoc} */
    public function fetchAll(): array {
        return $this->dbConnection->fetchAll('patients');
    }

}
