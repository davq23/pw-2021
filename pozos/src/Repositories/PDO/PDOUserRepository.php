<?php

namespace Repositories\PDO;

use Domains\User;
use PDO;
use Repositories\Exceptions\DomainNotFoundException;

class PDOUserRepository extends PDORepository implements \Repositories\UserRepository
{
    /**
     * @inheritDoc
     */
    public function findById($id): User
    {
        $user = null;

        $statement = $this->pdo()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $statement->bindParam(1, $id);

        $statement->execute();

        while ($userArray = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($userArray['id'], $userArray['email'], $userArray['username'], $userArray['password'], true);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    public function findByEmail(string $email): User
    {
        $user = null;

        $statement = $this->pdo()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $statement->bindParam(1, $email);

        $statement->execute();

        while ($userArray = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($userArray['id'], $userArray['email'], $userArray['username'], $userArray['password'], true);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    public function findByUsername(string $username): User
    {
        $user = null;

        $statement = $this->pdo()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $statement->bindParam(1, $username);

        $statement->execute();

        while ($userArray = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($userArray['id'], $userArray['email'], $userArray['username'], $userArray['password']);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    public function registerUser(User $user): User
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO users (email, username, password) VALUES (?, ?, ?)'
        );

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $statement->bindParam(1, $email);
        $statement->bindParam(2, $username);
        $statement->bindParam(3, $password);

        $statement->execute();

        $user->setId($this->pdo()->lastInsertId());

        return $user;

    }
}