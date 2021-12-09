<?php

namespace Repositories\MySQLi;

use Domains\Doctor;
use Repositories\DoctorRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Exception;

/**
 * Description of MySQLiDoctorRepository
 *
 * @author davido
 */
class MySQLiDoctorRepository extends MySQLiRepository implements DoctorRepository
{

    //put your code here
    public function findById($id): Doctor {
        $doctor = null;
        $statement = $this->mysqli()->prepare(
            'SELECT surnames, family_names, birthday, credentials, user_id FROM doctors WHERE id = ? LIMIT 1'
        );

        $statement->bind_param('s', $id);

        if (!$statement->execute()) {
            throw new Exception(mysqli_error($this->mysqli()));
        }

        $result = $statement->get_result();

        while ($doctorArray = mysqli_fetch_assoc($result)) {
            $doctor = new Doctor(
                $id,
                $doctorArray['surnames'],
                $doctorArray['family_names'],
                $doctorArray['birthday'],
                $doctorArray['user_id'],
                explode(',', $doctorArray['credentials'])
            );
        }

        if (!$doctor) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    public function findByUserId($userId): Doctor {
        $doctor = null;
        $statement = $this->mysqli()->prepare(
            'SELECT id, surnames, family_names, birthday, credentials, user_id FROM doctors WHERE user_id = ? LIMIT 1'
        );

        $statement->bind_param('s', $userId);

        if (!$statement->execute()) {
            throw new Exception(mysqli_error($this->mysqli()));
        }

        $result = $statement->get_result();

        while ($doctorArray = mysqli_fetch_assoc($result)) {
            $doctor = new Doctor(
                $doctorArray['id'],
                $doctorArray['surnames'],
                $doctorArray['family_names'],
                $doctorArray['birthday'],
                $userId,
                explode(',', $doctorArray['credentials'])
            );
        }

        if (!$doctor) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    public function registerDoctor(Doctor $doctor): Doctor {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO doctors (surnames, family_names, birthday, user_id, credentials) VALUES (?, ?, ?, ?, ?)'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $userId = $doctor->getUserId();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $statement->bind_param(
            'sssss',
            $surnames,
            $familyNames,
            $birthday,
            $userId,
            $credentialsStr
        );

        if (!$statement->execute()) {
            throw new Exception($this->mysqli()->error);
        }

        return $doctor;
    }

    public function updateDoctor($doctorId, Doctor $doctor): Doctor {
        $statement = $this->mysqli()->prepare(
            'UPDATE doctors SET surnames = ?, family_names = ?, birthday = ?, credentials = ? WHERE id = ?'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $statement->bind_param(
            'sssss',
            $surnames,
            $familyNames,
            $birthday,
            $credentialsStr,
            $doctorId
        );

        if (!$statement->execute()) {
            throw new Exception($this->mysqli()->error);
        }

        return $doctor;
    }

    /** {@inheritDoc} */
    public function fetchAll(): array {
        return $this->dbConnection->fetchAll('doctors');
    }

}
