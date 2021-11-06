<?php

namespace Repositories\MySQLi;

use Domains\Measurement;
use Domains\OilWell;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\OilWellRepository;

/**
 * Description of MySQLiOilWellRepository
 *
 * @author davido
 */
class MySQLiOilWellRepository extends MySQLiRepository implements OilWellRepository
{

    public function findAllOilWells(): array {
        $oilWells = array();

        $result = $this->mysqli()->query('SELECT id, name, depth, estimated_reserves FROM oil_wells');

        while ($row = mysqli_fetch_assoc($result)) {
            $oilWells[] = new OilWell($row['id'], $row['name'], $row['depth'], $row['estimated_reserves']);
        }

        return $oilWells;
    }

    public function findById($id): OilWell {
        $oilWell = null;

        $statement = $this->mysqli()->prepare(
            'SELECT name, depth, estimated_reserves FROM oil_wells WHERE id = ?'
        );

        $statement->bind_param('s', $id);

        $statement->execute() or die($this->mysqli()->error);

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
            $oilWell = new OilWell($id, $row['name'], $row['depth'], $row['estimated_reserves']);
        }

        if (!$oilWell) {
            throw new DomainNotFoundException();
        }

        return $oilWell;
    }

    public function registerOilWell(OilWell $oilWell): OilWell {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO oil_wells (name, depth, estimated_reserves) VALUES (?, ?, ?)'
        );

        $name = $oilWell->getName();
        $depth = $oilWell->getDepth();
        $estimatedReserves = $oilWell->getEstimatedReserves();

        $statement->bind_param('sss', $name, $depth, $estimatedReserves);

        if (!$statement->execute())
            throw new Exception($this->mysqli()->error);

        $oilWell->setId($this->mysqli()->insert_id);

        return $oilWell;
    }

    public function updateOilWell(OilWell $oilWell): OilWell {
        $statement = $this->mysqli()->prepare(
            'UPDATE oil_wells SET name = ?, depth = ?, estimated_reserves = ? WHERE id = ?'
        );

        $id = $oilWell->getId();
        $name = $oilWell->getName();
        $depth = $oilWell->getDepth();
        $estimatedReserves = $oilWell->getEstimatedReserves();

        $statement->bind_param('ssss', $name, $depth, $estimatedReserves, $id);

        if (!$statement->execute())
            throw new Exception($this->mysqli()->error);

        return $oilWell;
    }

    public function addMeasurement(OilWell $oilWell, Measurement $measurement): Measurement {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO measurements (value, time, user_id, oil_well_id) VALUES (?, ?, ?, ?)'
        );

        $value = $measurement->getValue();
        $time = $measurement->getTime();
        $userId = $measurement->getUserId();
        $oilWellId = $oilWell->getId();

        $statement->bind_param('ssss', $value, $time, $userId, $oilWellId);

        if (!$statement->execute())
            throw new Exception($this->mysqli()->error);

        $measurement->setId($this->mysqli()->insert_id);

        return $measurement;
    }

    public function deleteMeasurement(Measurement $measurement) {
        $statement = $this->mysqli()->prepare(
            'DELETE FROM measurements WHERE id = ?'
        );

        $id = $measurement->getId();

        $statement->bind_param('s', $id);

        $statement->execute();
    }

    public function editMeasurement(Measurement $measurement): Measurement {
        $statement = $this->mysqli()->prepare(
            'UPDATE measurements value = ?, time = ?, user_id = ?, oil_well_id = ? WHERE id = ?'
        );

        $id = $measurement->getId();
        $value = $measurement->getValue();
        $time = $measurement->getTime();
        $userId = $measurement->getUserId();
        $oilWellId = $measurement->getOilWellId();

        $statement->bind_param('sssss', $value, $time, $userId, $oilWellId, $id);

        $statement->execute();

        return $measurement;
    }

    public function findAllMeasurements(
        OilWell $oilWell,
        ?int $year = null,
        ?int $month = null,
        ?int $day = null
    ): array {
        $measurements = array();
        $oilWellId = $oilWell->getId();

        $sql = "SELECT id, value, time, user_id FROM "
            . "measurements WHERE oil_well_id = '{$oilWellId}'";

        if ($year && !$month) {
            $sql .= " AND DATE_FORMAT(time, '%Y' ) = '{$year}'";
        } else if ($year && $month && !$day) {
            $dateString = sprintf('%04d-%02d', $year, $month);
            $sql .= " AND DATE_FORMAT(time, '%Y-%m' ) = '{$dateString}'";
        } else if ($year && $month && $day) {
            $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $sql .= " AND DATE_FORMAT(time, '%Y-%m-%d') = '{$dateString}'";
        }

        $result = $this->mysqli()->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $measurements[] = new Measurement(
                $row['id'],
                $row['value'],
                $oilWellId,
                $row['time'],
                $row['user_id']
            );
        }

        return $measurements;
    }

    public function findMeasurementById($id) {
        $measurement = null;

        $statement = $this->mysqli()->prepare(
            'SELECT id, value, time, user_id, oil_well_id FROM measurements WHERE id = ?'
        );

        $statement->bind_param('s', $id);

        if (!$statement->execute()) {
            throw new Exception(mysqli_error($this->mysqli()));
        }

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
            $measurement = new Measurement(
                $row['id'],
                $row['value'],
                $row['oil_well_id'],
                $row['time'],
                $row['user_id']
            );
        }

        if (!$measurement) {
            throw new DomainNotFoundException();
        }

        return $measurement;
    }

    public function findMeasurementByTime(OilWell $oilWell, string $dateTime): Measurement {
        $measurement = null;
        $oilWellId = $oilWell->getId();

        $statement = $this->mysqli()->prepare(
            'SELECT id, value, time, user_id FROM measurements WHERE time = ? AND oil_well_id = ?'
        );

        $statement->bind_param('ss', $dateTime, $oilWellId);

        if (!$statement->execute()) {
            throw new Exception(mysqli_error($this->mysqli()));
        }

        $result = $statement->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
            $measurement = new Measurement(
                $row['id'],
                $row['value'],
                $oilWellId,
                $row['time'],
                $row['user_id']
            );
        }

        if (!$measurement) {
            throw new DomainNotFoundException();
        }

        return $measurement;
    }

}
