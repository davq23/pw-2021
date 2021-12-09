<?php

namespace Repositories\PG;

use Domains\Exam;
use Repositories\ExamRepository;

class PGExamRepository extends PGRepository implements ExamRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $offset): array {
        $exams = array();
        $examArray = $this->dbConnection->fetchAllOffsetPaginated('exams', '*', $limit, $offset);

        foreach ($examArray as $examRow) {
            $exams[] = new Exam();
        }

        return $exams;
    }

    /** {@inheritDoc} */
    public function registerExam(Exam $exam): Exam {
        return new Exam();
    }

    /** {@inheritDoc} */
    public function getExamCountByNurseId($userId): int {
        $statement = pg_prepare(
            $this->pg(),
            '',
            'SELECT COUNT(id) AS num_exams FROM exams WHERE user_id = $1'
        );

        $result = pg_execute($this->pg(), '', array($userId));

        $numExams = pg_fetch_assoc($result)['num_exams'];

        return $numExams;
    }

    /** {@inheritDoc} */
    public function fetchAllByUserId(int $limit, int $offset, $userId): array {
        $exams = array();

        $statement = pg_prepare(
            $this->pg(),
            'SELECT * FROM exams WHERE user_id = $1 LIMIT $2 OFFSET $3'
        );

        $result = pg_execute($this->pg(), '', array($userId, $limit, $offset));

        while ($row = pg_fetch_assoc($result)) {
            $exams[] = new Exam();
        }

        return $exams;
    }

}
