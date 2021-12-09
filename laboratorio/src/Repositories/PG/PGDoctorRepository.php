<?php

namespace Repositories\PG;

use Domains\Doctor;
use Repositories\DoctorRepository;
use Repositories\Exceptions\DomainNotFoundException;

/**
 * Description of PGDoctorRepository
 *
 * @author davido
 */
class PGDoctorRepository extends PGRepository implements DoctorRepository
{

    /** {@inheritDoc} */
    public function findById($id): Doctor {
        $doctor = null;

        pg_prepare(
            $this->pg(),
            '',
            'SELECT surnames, family_names, birthday, credentials, user_id FROM doctors WHERE id = $1 LIMIT 1'
        );

        $result = pg_execute($this->pg(), '', $id);

        if (!$result) {
            throw new \Exception(pg_last_error($this->pg()));
        }

        while ($row = pg_fetch_assoc($result)) {
            $doctor = new Doctor(
                $id,
                $row['surnames'],
                $row['family_names'],
                $row['birthday'],
                $row['user_id'],
                explode(',', $row['credentials'])
            );
        }

        if (!$doctor) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Doctor {
        $doctor = null;

        pg_prepare(
            $this->pg(),
            '',
            'SELECT id, surnames, family_names, birthday, credentials FROM doctors WHERE user_id = $1 LIMIT 1'
        );

        $result = pg_execute($this->pg(), '', $userId);

        if (!$result) {
            throw new \Exception(pg_last_error($this->pg()));
        }

        while ($row = pg_fetch_assoc($result)) {
            $doctor = new Doctor(
                $row['id'],
                $row['surnames'],
                $row['family_names'],
                $row['birthday'],
                $userId,
                explode(',', $row['credentials'])
            );
        }

        if (!$doctor) {
            throw new DomainNotFoundException();
        }

        return $doctor;
    }

    /** {@inheritDoc} */
    public function registerDoctor(Doctor $doctor): Doctor {
        pg_prepare(
            $this->pg(),
            '',
            'INSERT INTO doctors (surnames, family_names, birthday, user_id, credentials) VALUES ($1, $2, $3, $4, $5) RETURNING id'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $userId = $doctor->getUserId();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $result = pg_execute(
            $this->pg(),
            '',
            $surnames,
            $familyNames,
            $birthday,
            $userId,
            $credentialsStr
        );

        if (!$result) {
            throw new \Exception(pg_last_error($this->pg()));
        }

        $doctor->setId(pg_fetch_assoc($result)['id']);

        return $doctor;
    }

    /** {@inheritDoc} */
    public function updateDoctor($doctorId, Doctor $doctor): Doctor {
        pg_prepare(
            $this->pg(),
            '',
            'UPDATE doctors SET surnames = $1, family_names = $2, birthday = $3, credentials = $4 WHERE id = $5'
        );

        $surnames = $doctor->getSurnames();
        $familyNames = $doctor->getFamilyNames();
        $birthday = $doctor->getBirthday();
        $credentialsStr = implode(',', $doctor->getCredentials());

        $result = pg_execute(
            $this->pg(),
            '',
            $surnames,
            $familyNames,
            $birthday,
            $credentialsStr,
            $doctorId
        );

        if (!$result) {
            throw new \Exception(pg_last_error($this->pg()));
        }

        return $doctor;
    }

    /** {@inheritDoc} */
    public function fetchAll(): array {
        return $this->dbConnection->fetchAll('doctors');
    }

}
