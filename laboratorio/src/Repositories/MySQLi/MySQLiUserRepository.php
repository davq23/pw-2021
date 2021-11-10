<?php

namespace Repositories\MySQLi;

use Domains\User;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;

class MySQLiUserRepository extends MySQLiRepository implements UserRepository
{

    /** {@inheritDoc} */
    public function findById($id): User {
        $user = null;
        $username = null;
        $email = null;
        $password = null;
        $userRole = null;

        $statement = $this->mysqli()->prepare('SELECT username, email, password, user_role FROM users WHERE id = ? LIMIT 1');
        $statement->bind_param('s', $id);

        $statement->bind_result($username, $email, $password, $userRole);

        $statement->execute();

        while ($statement->fetch()) {
            $user = new User($id, $email, $username, $password, $userRole);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function findByEmail(string $email): User {
        $user = null;
        $id = null;
        $username = null;
        $password = null;
        $userRole = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $statement->bind_param('s', $email);

        $statement->bind_result($id, $username, $email, $password, $userRole);

        $statement->execute();

        while ($statement->fetch()) {
            $user = new User($id, $email, $username, $password, $userRole, true);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function findByUsername(string $username): User {
        $user = null;
        $id = null;
        $email = null;
        $password = null;
        $userRole = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $statement->bind_param('s', $username);

        $statement->bind_result($id, $username, $email, $password, $userRole);

        $statement->execute();

        while ($statement->fetch()) {
            $user = new User($id, $email, $username, $password, $userRole);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /** {@inheritDoc} */
    public function registerUser(User $user): User {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO users (email, username, password, user_role) VALUES (?, ?, ?, ?)'
        );

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $userRole = $user->getUserRole();

        $statement->bind_param('ssss', $email, $username, $password, $userRole);

        if (!$statement->execute()) {
            throw new Exception($this->mysqli()->error);
        }

        $user->setId($this->mysqli()->insert_id);

        return $user;
    }

}
