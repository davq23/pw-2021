<?php

namespace Repositories\MySQLi;

use Domains\Exam;
use Repositories\ExamRepository;
use Repositories\Exceptions\DomainNotFoundException;

class MySQLiExamRepository extends MySQLiRepository implements ExamRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $offset): array {
        $exams = array();
        $examArray = $this->dbConnection->fetchAllOffsetPaginated('exams', '*', $limit, $offset);

        foreach ($examArray as $examRow) {
            $exams[] = Exam::fromArray($examRow);
        }

        return $exams;
    }

    /** {@inheritDoc} */
    public function registerExam(Exam $exam): Exam {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO exams (patient_id, nurse_id, description, datetime) VALUES (?, ?, ?, ?)'
        );

        $patientId = $exam->getPatientId();
        $nurseId = $exam->getNurseId();
        $description = $exam->getDescription();
        $datetime = $exam->getDatetime();

        $statement->bind_param('ssss', $patientId, $nurseId, $description, $datetime);

        if (!$statement->execute()) {
            throw new \Exception($this->mysqli()->error);
        }

        $exam->setId($this->mysqli()->insert_id);

        $statement->close();

        return $exam;
    }

    /** {@inheritDoc} */
    public function getExamCountByNurseId($nurseId): int {
        $statement = $this->mysqli()->prepare('SELECT COUNT(id) AS num_exams FROM exams WHERE nurse_id = ?');

        $statement->bind_param('s', $nurseId);

        $numExams = null;
        $statement->bind_result($numExams);

        $statement->execute();

        $statement->fetch();

        $statement->close();

        return $numExams;
    }

    /** {@inheritDoc} */
    public function fetchAllByUserId(int $limit, int $offset, $userId): array {
        $exams = array();

        $statement = $this->mysqli()->prepare('SELECT * FROM exams WHERE user_id = ? LIMIT ? OFFSET ?');

        $statement->bind_param('sii', $userId, $limit, $offset);

        $statement->execute();

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
            $exams[] = new Exam(
                $row['id'],
                $row['doctor_id'],
                $row['nurse_id'],
                $row['patient_id'],
                $row['description'],
                $row['datetime'],
                $row['status'],
                $row['results']
            );
        }

        $statement->close();

        return $exams;
    }

    /** {@inheritDoc} */
    public function fetchById($id): Exam {
        $statement = $this->mysqli()->prepare('SELECT * FROM exams WHERE id = ?');

        $statement->bind_param('s', $id);
        $statement->execute();
        $result = $statement->get_result();

        $examArray = mysqli_fetch_assoc($result);

        if (!$examArray) {
            throw new DomainNotFoundException();
        }

        $exam = new Exam(
            $id,
            $examArray['doctor_id'],
            $examArray['nurse_id'],
            $examArray['patient_id'],
            $examArray['description'],
            $examArray['datetime'],
            $examArray['status'],
            $examArray['results']
        );

        return $exam;
    }

    public function getExamCountByDoctorId($doctorId): int {
        $statement = $this->mysqli()->prepare('SELECT COUNT(id) AS num_exams FROM exams WHERE doctor_id = ?');

        $statement->bind_param('s', $doctorId);

        $numExams = null;
        $statement->bind_result($numExams);

        $statement->execute();

        $statement->fetch();

        $statement->close();

        return $numExams;
    }

    public function registerResults(Exam $exam) {
        $statement = $this->mysqli()->prepare("UPDATE exams SET results = ?, status = 'done' WHERE id = ?");

        $id = $exam->getId();
        $result = $exam->getResults();

        $statement->bind_param('ss', $result, $id);

        if (!$statement->execute()) {
            throw new Exception(mysqli_error($this->mysqli()));
        }

        $statement->close();
    }

}
