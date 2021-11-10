<?php

namespace Repositories;

use Domains\Exam;

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
     * Register exam
     *
     * @param Exam $exam
     * @return Exam
     */
    public function registerExam(Exam $exam): Exam;

    /**
     * Get exam count by user ID
     *
     * @param mixed $userId
     * @return integer
     */
    public function getExamCountByUserId($userId): int;
}
