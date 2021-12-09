<?php

namespace Repositories\MySQLi;

use Domains\Patient;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;

/**
 * Description of MySQLiPatientRepository
 *
 * @author davido
 */
class MySQLiPatientRepository extends MySQLiRepository implements PatientRepository
{

    /** {@inheritDoc} */
    public function deletePatient(Patient $patient): void {
        $statement = $this->mysqli()->prepare(
            'DELETE FROM patients WHERE id = ?'
        );

        $patientId = $patient->getId();

        $statement->bind_param('s', $patientId);

        $statement->execute();
    }

    /** {@inheritDoc} */
    public function findById($id): Patient {
        $patient = null;

        $statement = $this->mysqli()->prepare(
            'SELECT surname, family_name, birthday, dni, email FROM patients WHERE id = ? LIMIT 1'
        );

        $statement->bind_param('i', $id);

        $statement->execute();

        $result = $statement->get_result();

        while ($patientArray = mysqli_fetch_assoc($result)) {
            $patient = new Patient(
                $id,
                $patientArray['surname'],
                $patientArray['family_name'],
                $patientArray['birthday'],
                $patientArray['dni'],
                $patientArray['email']
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
            'INSERT INTO patients (surname, family_name, birthday, dni, email) VALUES (?, ?, ?, ?, ?)'
        );

        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $dni = $patient->getDni();
        $email = $patient->getEmail();

        $statement->bind_param('sssss', $surnames, $familyNames, $birthday, $dni, $email);

        if (!$statement->execute()) {
            throw new Exception($this->mysqli()->error);
        }

        $patient->setId($this->mysqli()->insert_id);

        return $patient;
    }

    public function updatePatient(Patient $patient): Patient {
        $surnames = $patient->getSurnames();
        $familyNames = $patient->getFamilyNames();
        $birthday = $patient->getBirthday();
        $patientId = $patient->getId();
        $email = $patient->getEmail();

        $statement = $this->mysqli()->prepare(
            'UPDATE patients SET surname = ?, family_name = ?, birthday = ?, email = ? WHERE id = ?'
        );

        $statement->bind_param('sssss', $surnames, $familyNames, $birthday, $email, $patientId);

        $statement->execute();

        return $patient;
    }

    /** {@inheritDoc} */
    public function fetchAll(): array {
        return $this->dbConnection->fetchAll('patients');
    }

}
