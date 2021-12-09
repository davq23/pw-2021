<?php

namespace Repositories\PDO;

use Domains\Exam;
use PDO;
use Repositories\ExamRepository;

class PDOExamRepository extends PDORepository implements ExamRepository
{

    /**
     * @inheritDoc
     */
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
        $statement = $this->pdo()->prepare('SELECT COUNT(id) AS num_exams FROM exams WHERE user_id = ?');

        $statement->bindParam(1, $userId);

        $statement->execute();

        $numExams = $statement->fetch(PDO::FETCH_ASSOC)['num_exams'];

        return $numExams;
    }

    /** {@inheritDoc} */
    public function fetchAllByUserId(int $limit, int $offset, $userId): array {
        $statement = $this->pdo()->prepare(
            'SELECT * FROM exams WHERE user_id = ? LIMIT ? OFFSET ?'
        );

        $statement->bindParam(1, $userId);
        $statement->bindParam(2, $limit, PDO::PARAM_INT);
        $statement->bindParam(3, $offset, PDO::PARAM_INT);

        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $exams[] = new Exam();
        }

        return $numExams;
    }

}
