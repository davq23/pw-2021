<?php

namespace Repositories\PDO;

use Domains\Nurse;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\NurseRepository;

/**
 * nurseRepository utilizing a PDODBConnection
 */
class PDONurseRepository extends PDORepository implements NurseRepository
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

        $statement = $this->pdo()->prepare('SELECT * FROM nurses WHERE id = ? LIMIT 1');

        $statement->bindParam(1, $id);

        $statement->execute();

        while ($nurseArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $nurse = $nurses[] = new Nurse(
                $nurseArray['id'],
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday']
            );
        }

        if (is_null($nurse)) {
            throw new DomainNotFoundException();
        }

        return $nurse;
    }

    /**
     * Fetches one nurse by user_id
     *
     * @param $userId
     * @return Nurse
     */
    public function findByUserId($userId): Nurse {
        $nurse = null;

        $statement = $this->pdo()->prepare('SELECT * FROM nurses WHERE user_id = ? LIMIT 1');

        $statement->bindParam(1, $userId);

        $statement->execute();

        while ($nurseArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $nurse = $nurses[] = new Nurse(
                $nurseArray['id'],
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday']
            );
        }

        if (is_null($nurse)) {
            throw new DomainNotFoundException();
        }

        return $nurse;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array {
        $nurses = array();

        $statement = $this->pdo()->prepare('SELECT * FROM nurses WHERE name LIKE ?');

        $statement->bindParam(1, $name, \PDO::PARAM_STR);

        $statement->execute();

        while ($nurseArray = $statement->fetch((\PDO::FETCH_ASSOC))) {
            $nurses[] = $nurses[] = new Nurse(
                $nurseArray['id'],
                $nurseArray['surnames'],
                $nurseArray['family_names'],
                $nurseArray['birthday']
            );
        }

        return $nurses;
    }

    /** {@inheritDoc} */
    public function registerNurse(Nurse $nurse): Nurse {
        $statement = $this->pdo()->prepare(
            'INSERT INTO nurses (surnames, family_names, birthday, user_id) VALUES (?, ?, ?, ?)'
        );

        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $userId = $nurse->getUserId();

        $statement->bindParam(1, $surnames);
        $statement->bindParam(2, $familyNames);
        $statement->bindParam(3, $birthday);
        $statement->bindParam(4, $userId);

        $statement->execute();

        $nurse->setId($this->pdo()->lastInsertId());

        return $nurse;
    }

    /** {@inheritDoc} */
    public function updateNurse(Nurse $nurse): Nurse {
        $statement = $this->pdo()->prepare(
            'UPDATE nurses surnames = ?, family_names = ?, birthday = ? WHERE id = ?'
        );

        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $nurseId = $nurse->getId();

        $statement->bindParam(1, $surnames);
        $statement->bindParam(2, $familyNames);
        $statement->bindParam(3, $birthday);
        $statement->bindParam(4, $nurseId);

        $statement->execute();

        return $nurse;
    }

}
