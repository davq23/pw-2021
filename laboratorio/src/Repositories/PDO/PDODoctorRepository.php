<?php

namespace Repositories\PDO;

use Domains\Doctor;
use Repositories\DoctorRepository;

/**
 * Description of PDODoctorRepository
 *
 * @author davido
 */
class PDODoctorRepository extends PDORepository implements DoctorRepository
{

    //put your code here
    public function findById($id): Doctor {
        $doctor = null;

        $statement = $this->pdo()->prepare(
            'SELECT surnames, family_names, birthday, credentials, user_id FROM doctors WHERE id = ? LIMIT 1'
        );

        $statement->bindParam(1, $id);

        $statement->execute();

        while ($doctorArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $doctor = $doctors[] = new Doctor(
                $doctorArray['id'],
                $doctorArray['surnames'],
                $doctorArray['family_names'],
                $doctorArray['birthday'],
                $doctor['credentials']
            );
        }

        if (is_null($doctor)) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    public function findByUserId($userId): Doctor {
        $doctor = null;

        $statement = $this->pdo()->prepare(
            'SELECT surnames, family_names, birthday, credentials, user_id FROM doctors WHERE user_id = ? LIMIT 1'
        );

        $statement->bindParam(1, $userId);

        $statement->execute();

        while ($doctorArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $doctor = $doctors[] = new Doctor(
                $doctorArray['id'],
                $doctorArray['surnames'],
                $doctorArray['family_names'],
                $doctorArray['birthday'],
                $doctor['credentials']
            );
        }

        if (is_null($doctor)) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    public function registerDoctor(Doctor $doctor): Doctor {
        $statement = $this->pdo()->prepare(
            'INSERT INTO doctors (surnames, family_names, birthday, user_id, credentials) VALUES (?, ?, ?, ?, ?)'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $userId = $doctor->getUserId();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $statement->bindParam(1, $surnames);
        $statement->bindParam(2, $familyNames);
        $statement->bindParam(3, $birthday);
        $statement->bindParam(4, $userId);
        $statement->bindParam(5, $credentialsStr);

        $statement->execute();

        $doctor->setId($this->pdo()->lastInsertId());

        return $doctor;
    }

    public function updateDoctor($doctorId, Doctor $doctor): Doctor {
        $statement = $this->pdo()->prepare(
            'UPDATE doctors SET surnames = ?, family_names = ?, birthday = ?, credentials = ? WHERE id = ?'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $statement->bindParam(1, $surnames);
        $statement->bindParam(2, $familyNames);
        $statement->bindParam(3, $birthday);
        $statement->bindParam(4, $credentialsStr);
        $statement->bindParam(5, $doctorId);

        $statement->execute();

        return $doctor;
    }

}
