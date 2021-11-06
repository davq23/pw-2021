<?php

namespace Repositories;

use Domains\User;
use Repositories\Exceptions\DomainNotFoundException;

interface UserRepository
{
    /**
     * Finds user by id
     *
     * @param $id
     * @return mixed
     * @throws DomainNotFoundException
     */
    public function findById($id): User;

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User
     * @throws DomainNotFoundException
     */
    public function findByEmail(string $email): User;

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     * @throws DomainNotFoundException
     */
    public function findByUsername(string $username): User;

    /**
     * Register user
     *
     * @param User $user
     * @return User
     */
    public function registerUser(User $user): User;
}