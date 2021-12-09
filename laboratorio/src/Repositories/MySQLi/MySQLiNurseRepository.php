<?php

namespace Repositories\MySQLi;

use Domains\Nurse;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\NurseRepository;

class MySQLiNurseRepository extends MySQLiRepository implements NurseRepository
{

    /** {@inheritDoc} */
    public function fetchAll(int $limit, int $pageNumber = 0): array {
        $nurses = array();
        $nurseArray = $this->dbConnection->fetchAllOffsetPaginated('nurses', '*', $limit, $pageNumber);

        foreach ($nurseArray as $nurseRow) {
            $nurses[] = new Nurse(
                $nurseRow['id'],
                $nurseRow['surnames'],
                $nurseRow['family_names'],
                $nurseRow['birthday'],
                $nurseRow['user_id']
            );
        }

        return $nurses;
    }

    /** {@inheritDoc} */
    public function findById($id): ?Nurse {
        $nurse = null;

        $statement = $this->mysqli()->prepare(
            'SELECT surnames, family_names, birthday, user_id FROM nurses WHERE id = ? LIMIT 1'
        );

        $statement->bind_param('i', $id);

        $statement->execute();

        $result = $statement->get_result();

        while ($nurseArray = mysqli_fetch_assoc($result)) {
            $nurse = new Nurse(
                $id,
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday'],
                $nurseArray['user_id']
            );
        }

        if (!$nurse) {
            throw new DomainNotFoundException();
        }

        return $nurse;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array {
        $nurses = array();
        $name .= '%';

        $statement = $this->mysqli()->prepare(
            'SELECT id, surnames, family_names, birthday, user_id FROM nurses WHERE name LIKE ?'
        );

        $statement->bind_param('s', $name);

        $statement->execute();

        $result = $statement->get_result();

        while ($nurseArray = mysqli_fetch_assoc($result)) {
            $nurses[] = new Nurse(
                $nurseArray['id'],
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday'],
                $nurseArray['user_id'],
            );
        }

        return $nurses;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Nurse {
        $statement = $this->mysqli()->prepare(
            'SELECT id, surnames, family_names, birthday FROM nurses WHERE user_id = ? LIMIT 1'
        );

        $statement->bind_param('s', $userId);

        $statement->execute();

        $result = $statement->get_result();

        while ($nurseArray = mysqli_fetch_assoc($result)) {
            $nurse = new Nurse(
                $nurseArray['id'],
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday'],
                $userId
            );
        }

        if (!$nurse) {
            throw new DomainNotFoundException();
        }

        return $nurse;
    }

    /** {@inheritDoc} */
    public function registerNurse(Nurse $nurse): Nurse {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO nurses (surnames, family_names, birthday, user_id) VALUES (?, ?, ?, ?)'
        );

        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $userId = $nurse->getUserId();

        $statement->bind_param('ssss', $surnames, $familyNames, $birthday, $userId);

        $statement->execute();

        return $nurse;
    }

    /** {@inheritDoc} */
    public function updateNurse(Nurse $nurse): Nurse {
        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $nurseId = $nurse->getId();

        $statement = $this->mysqli()->prepare(
            'UPDATE nurses SET surnames = ?, family_names = ?, birthday = ? WHERE id = ?'
        );

        $statement->bind_param('ssss', $surnames, $familyNames, $birthday, $nurseId);

        $statement->execute();

        $nurse->setId($this->mysqli()->insert_id);

        return $nurse;
    }

}
