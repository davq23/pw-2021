<?php

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

class User implements Domain
{
    public const USER_ROLE_NURSE = 'nurse';
    public const USER_ROLE_DOCTOR = 'doctor';

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    private $id;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    public function getUserRole(): string {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): void {
        $this->userRole = $userRole;
    }

    private string $username;
    private string $password;
    private string $userRole;
    private string $email;
    private bool $passwordHashed;

    public function __construct(
        $id,
        string $email,
        string $username,
        string $password,
        string $userRole = self::USER_ROLE_NURSE,
        bool $passwordHashed = false
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->passwordHashed = $passwordHashed;
        $this->username = $username;
        $this->userRole = $userRole;
    }

    /** {@inheritDoc} */
    public function validate(): void {
        if (!preg_match('/^[A-Za-z0-9_\-]{4,45}$/', $this->username)) {
            throw new InvalidDomainException('Username must contain at least 4 alphanumeric characters');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidDomainException('Invalid email');
        }

        if (
            !$this->ṕasswordHashed &&
            !preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $this->password)
        ) {
            throw new InvalidDomainException(
                    'Password must be at least 8 characters long and contain letters, numbers and special characters'
            );
        }
    }

    public static function fromArray(array $source): User {
        return new User(
            $source['id'] ?? null,
            $source['email'] ?? null,
            $source['username'] ?? null,
            $source['password'] ?? null
        );
    }

    public function hashPassword() {
        if (!$this->passwordHashed) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->passwordHashed = true;
        }
    }

    public function isPasswordHashed(): bool {
        return $this->passwordHashed;
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'username' => $this->username
        );
    }

}
