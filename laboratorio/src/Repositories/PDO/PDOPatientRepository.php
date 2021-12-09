<?php

namespace Repositories\PDO;

use Domains\Patient;
use Repositories\PatientRepository;

/**
 * Description of PDOPatientRepository
 *
 * @author davido
 */
class PDOPatientRepository extends PDORepository implements PatientRepository
{

    //put your code here
    public function deletePatient(Patient $patient): void {

    }

    public function fetchAll(): array {

    }

    public function findById($id): Patient {

    }

    public function registerPatient(Patient $patient): Patient {

    }

    public function updatePatient(Patient $patient): Patient {

    }

}
