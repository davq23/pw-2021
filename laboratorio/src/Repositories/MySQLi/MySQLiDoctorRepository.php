<?php

namespace Repositories\MySQLi;

use Domains\Doctor;
use Repositories\DoctorRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Swoole\MySQL\Exception;

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
            'SELECT surnames, family_names, birthday, credentials, user_id FROM doctors WHERE user_id = ? LIMIT 1'
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
        return $doctor;
    }

    public function updateDoctor($doctorId, Doctor $doctor): Doctor {
        return $doctor;
    }

}
