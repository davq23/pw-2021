<?php

namespace Repositories\PG;

use Domains\User;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;

class PGUserRepository extends PGRepository implements UserRepository
{
    /** {@inheritDoc} */
    public function findById($id): User
    {
        $statement = pg_prepare($this->pg(), '', 'SELECT username, email, password FROM users WHERE id = $1 LIMIT 1');
    
        $result = pg_execute($this->pg(), '', array($id));

        while($row = pg_fetch_assoc($result)) {
            $user = new User($id, $row['email'], $row['username'], $row['password']);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function findByEmail(string $email): User
    {
        $statement = pg_prepare($this->pg(), '', 'SELECT id, username, password FROM users WHERE email = $1 LIMIT 1');
    
        $result = pg_execute($this->pg(), '', array($email));

        while($row = pg_fetch_assoc($result)) {
            $user = new User($row['id'], $email, $row['username'], $row['password']);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function findByUsername(string $username): User
    {
        $statement = pg_prepare($this->pg(), '', 'SELECT id, email, password FROM users WHERE username = $1 LIMIT 1');
    
        $result = pg_execute($this->pg(), '', array($username));

        if (!$result) throw new Exception(pg_last_error($this->pg()));

        while($row = pg_fetch_assoc($result)) {
            $user = new User($row['id'], $row['email'], $username, $row['password']);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function registerUser(User $user): User
    {
        $statement = pg_prepare(
            $this->pg(),
            'register_user',
            'INSERT INTO users (email, username, password) VALUES ($1, $2, $3) RETURNING id'
        );

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $result = pg_execute($this->pg(), 'register_user', array($email, $username, $password));

        $user->setId(pg_fetch_assoc($result)['id']);

        return $user;
    }
}