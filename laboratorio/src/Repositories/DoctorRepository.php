<?php

namespace Repositories;

use Domains\Doctor;
use Domains\Nurse;
use Repositories\Exceptions\DomainNotFoundException;

/**
 *
 * @author davido
 */
interface DoctorRepository
{

    /**
     * Finds user by id
     *
     * @param $id
     * @return mixed
     * @throws DomainNotFoundException
     */
    public function findById($id): Doctor;

    /**
     * Fetches one doctor by user_id
     *
     * @param $userId
     * @return Nurse
     */
    public function findByUserId($userId): Doctor;

    /**
     * Register doctor info
     *
     * @param Doctor $doctor
     * @return Doctor
     */
    public function registerDoctor(Doctor $doctor): Doctor;

    /**
     * Updates doctor info
     *
     * @param mixed $doctorId
     * @param Doctor $doctor
     * @return Doctor
     */
    public function updateDoctor($doctorId, Doctor $doctor): Doctor;

    /**
     * Fetches all patients
     *
     */
    public function fetchAll(): array;
}
