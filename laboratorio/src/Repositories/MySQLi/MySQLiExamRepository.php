<?php

namespace Repositories\MySQLi;

use Domains\Exam;

class MySQLiExamRepository extends MySQLiRepository implements \Repositories\ExamRepository
{

    /**
     * @inheritDoc
     */
    public function fetchAll(int $limit, int $offset): array
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * @inheritDoc
     */
    public function registerExam(Exam $exam)
    {
        // TODO: Implement registerExam() method.
    }
}