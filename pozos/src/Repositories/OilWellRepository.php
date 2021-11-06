<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Repositories;

use Domains\Measurement;
use Domains\OilWell;

/**
 * Description of OilWellRepository
 *
 * @author davido
 */
interface OilWellRepository
{

    /**
     * Fetches all oil wells
     *
     * @return array
     */
    public function findAllOilWells(): array;

    /**
     * Finds an oil well by ID
     *
     * @throw DomainNotFoundException
     * @param mixed $id
     * @return OilWell
     */
    public function findById($id): OilWell;

    /**
     * Registers an oil well
     *
     * @param OilWell $oilWell
     * @return OilWell
     */
    public function registerOilWell(OilWell $oilWell): OilWell;

    /**
     * Updates the info of an oil well
     *
     * @return OilWell
     */
    public function updateOilWell(OilWell $oilWell): OilWell;

    /**
     * Deletes oil well
     *
     * @param mixed $id
     * @return void
     */
    public function deleteOilWell($id): void;

    /**
     *
     * @param int|null $year
     * @param int|null $month
     * @param int|null $day
     * @return array
     */
    public function findAllMeasurements(
        OilWell $oilWell,
        ?int $year = null,
        ?int $month = null,
        ?int $day = null): array;

    /**
     * Finds specific measurement by time and oil well
     *
     * @param OilWell $oilWell
     * @param string $dateTime
     * @return Measurement
     */
    public function findMeasurementByTime(
        OilWell $oilWell,
        string $dateTime
    ): Measurement;

    /**
     * Finds measurement by id
     *
     * @param mixed $id
     */
    public function findMeasurementById($id);

    /**
     * Adds a pressure measurement of the oil well
     *
     * @param OilWell $oilWell
     * @param Measurement $measurement
     * @return Measurement
     */
    public function addMeasurement(OilWell $oilWell, Measurement $measurement): Measurement;

    /**
     *
     * @param Measurement $measurement
     * @return Measurement
     */
    public function editMeasurement(Measurement $measurement): Measurement;

    /**
     *
     * @param mixed $id
     */
    public function deleteMeasurement($id);
}
