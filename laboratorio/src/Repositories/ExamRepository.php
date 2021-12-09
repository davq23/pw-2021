<?php

namespace Repositories;

use Domains\Exam;
use Domains\ExamType;
use Repositories\Exceptions\DomainNotFoundException;

interface ExamRepository
{

    /**
     * Fetch all exams
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function fetchAll(int $limit, int $offset): array;

    /**
     * Fetch all exams by user ID
     *
     * @param int $limit
     * @param int $offset
     * @param type $userId
     * @return array
     */
    public function fetchAllByUserId(int $limit, int $offset, $userId): array;

    /**
     *
     * @param $id
     * @return Exam
     * @throws DomainNotFoundException
     */
    public function fetchById($id): Exam;

    /**
     * Register exam
     *
     * @param Exam $exam
     * @return Exam
     */
    public function registerExam(Exam $exam): Exam;

    /**
     * Registers exam results
     *
     * @param Exam $exam
     */
    public function registerResults(Exam $exam);

    /**
     * Get exam count by nurse ID
     *
     * @param mixed $nurseId
     * @return integer
     */
    public function getExamCountByNurseId($nurseId): int;

    /**
     * Get exam count by doctor ID
     *
     * @param mixed $userId
     * @return integer
     */
    public function getExamCountByDoctorId($doctorId): int;
}
