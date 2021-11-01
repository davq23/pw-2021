<?php

namespace Repositories\MySQLi;

use Domains\Exam;
use Repositories\ExamRepository;

class MySQLiExamRepository extends MySQLiRepository implements ExamRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $offset): array
    {
        $exams = array();
        $examArray = $this->dbConnection->fetchAllOffsetPaginated('exams', '*', $limit, $offset);

        foreach ($examArray as $examRow) {
            $exams[] = new Exam();
        }

        return $exams;
    }

    /** {@inheritDoc} */
    public function registerExam(Exam $exam): Exam
    {
        return new Exam();
    }

    /** {@inheritDoc} */
    public function getExamCountByUserId($userId): int
    {
        $statement = $this->mysqli()->prepare('SELECT COUNT(id) AS num_exams FROM exams WHERE user_id = ?');

        $statement->bind_param('s', $userId);

        $numExams = null;
        $statement->bind_result($numExams);

        $statement->execute();

        $statement->fetch();

        $statement->close();

        return $numExams;
    }
}