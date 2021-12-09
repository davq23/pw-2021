<?php

namespace Repositories\PG;

use Domains\Nurse;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\NurseRepository;

class PGNurseRepository extends PGRepository implements NurseRepository
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
                $nurseRow['birthday']
            );
        }

        return $nurses;
    }

    /** {@inheritDoc} */
    public function findById($id): ?Nurse {
        $nurse = null;

        $statement = pg_prepare($this->pg(), 'nurse_find_by_id', 'SELECT * FROM nurses WHERE id = $1 LIMIT 1');

        $result = pg_execute($this->pg(), 'nurse_find_by_id', array($id));

        while ($row = pg_fetch_assoc($result)) {
            $nurse = new Nurse($id, $row['surnames'], $row['family_names'], $row['birthday']);
        }

        if (is_null($nurse)) {
            throw new DomainNotFoundException();
        }

        return $nurse;
    }

    /** {@inheritDoc} */
    public function searchByName(string $name): array {
        $nurses = array();
        $name .= '%';

        $statement = pg_prepare($this->pg(), 'nurse_search_by_name', 'SELECT * FROM nurses WHERE surnames LIKE $1');

        $result = pg_execute($this->pg(), 'nurse_search_by_name', array($name));

        while ($row = pg_fetch_assoc($result)) {
            $nurses[] = new Nurse($row['id'], $row['surnames'], $row['family_names'], $row['birthday']);
        }

        return $nurses;
    }

    /** {@inheritDoc} */
    public function findByUserId($userId): Nurse {
        $statement = pg_prepare(
            $this->pg(),
            '',
            'SELECT * FROM nurses WHERE user_id = $1 LIMIT 1'
        );

        $result = pg_execute($this->pg(), 'nurse_find_by_user_id', array($userId));

        while ($row = pg_fetch_assoc($result)) {
            $nurse = new Nurse($row['id'], $row['surnames'], $row['family_names'], $row['birthday']);
        }

        return $nurse;
    }

    /** {@inheritDoc} */
    public function registerNurse(Nurse $nurse): Nurse {
        $statement = pg_prepare(
            $this->pg(),
            '',
            'INSERT INTO nurses (surnames, family_names, birthday, user_id) VALUES ($1, $2, $3, $4) RETURNING id'
        );

        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $userId = $nurse->getUserId();

        $result = pg_execute(
            $this->pg(),
            '',
            array(
                $surnames,
                $familyNames,
                $birthday,
                $userId
            )
        );

        $nurse->setId(pg_fetch_assoc($result)['id']);

        return $nurse;
    }

    /** {@inheritDoc} */
    public function updateNurse(Nurse $nurse): Nurse {
        $statement = pg_prepare(
            $this->pg(),
            'update_nurse',
            'UPDATE nurses surnames = $1, family_names = $2, birthday = $3 WHERE id = $4'
        );

        $surnames = $nurse->getSurnames();
        $familyNames = $nurse->getFamilyNames();
        $birthday = $nurse->getBirthday();
        $nurseId = $nurse->getId();

        $result = pg_execute($this->pg(), '', array($surnames, $familyNames, $birthday, $nurseId));

        return $nurse;
    }

}
