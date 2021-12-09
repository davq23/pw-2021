<?php
namespace Repositories;

use Domains\Nurse;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;

/**
 * Encompasses all storage-related activities involving nurses
 */
interface NurseRepository
{
    /**
     * Fetches all nurses
     *
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function fetchAll(int $limit, int $offset = 0): array;

    /**
     * Fetches one nurse by id
     *
     * @param $id
     * @return Nurse|null
     * @throws DomainNotFoundException
     */
    public function findById($id): ?Nurse;

    /**
     * Fetches one nurse by user_id
     *
     * @param $userId
     * @return Nurse
     */
    public function findByUserId($userId): Nurse;

    /**
     * Search nurses by name
     *
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function searchByName(string $name): array;

    /**
     * Registers a nurse
     *
     * @param Nurse $nurse
     * @throws DomainNotFoundException
     */
    public function registerNurse(Nurse $nurse): Nurse;

    /**
     * Updates a nurse
     *
     * @param Nurse $nurse
     * @throws DomainNotFoundException
     */
    public function updateNurse(Nurse $nurse): Nurse;
}