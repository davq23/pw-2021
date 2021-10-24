<?php
namespace Repositories\MySQLi;

use Domains\User;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;

class MySQLiUserRepository extends MySQLiRepository implements UserRepository
{
    /**
     * @inheritDoc
     */
    public function findById($id): User
    {
        $user = null;
        $id = null;
        $username = null;
        $email = null;
        $password = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $statement->bind_param('s', $id);

        $statement->bind_result($id, $username, $email, $password);

        $statement->execute();

        while($statement->fetch()) {
            $user = new User($id, $email, $username, $password);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): User
    {
        $user = null;
        $id = null;
        $username = null;
        $password = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $statement->bind_param('s', $email);

        $statement->bind_result($id, $username, $email, $password);

        $statement->execute();

        while($statement->fetch()) {
            $user = new User($id, $email, $username, $password, true);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function findByUsername(string $username): User
    {
        $user = null;
        $id = null;
        $email = null;
        $password = null;

        $statement = $this->mysqli()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $statement->bind_param('s', $username);

        $statement->bind_result($id, $username, $email, $password);

        $statement->execute();

        while($statement->fetch()) {
            $user = new User($id, $email, $username, $password);
        }

        if (is_null($user)) {
            throw new DomainNotFoundException();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function registerUser(User $user): User
    {
        $statement = $this->mysqli()->prepare(
            'INSERT INTO users (email, username, password) VALUES (?, ?, ?)'
        );

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $statement->bind_param('sss', $email, $username, $password);

        $statement->execute();

        $user->setId($this->mysqli()->insert_id);

        return $user;
    }
}