<?php

namespace App;

/**
 * Description of DefaultSecretKeyManager
 *
 * @author davido
 */
class DefaultSecretKeyManager implements SecretKeyManager
{
    protected $secretKey;

    public function __construct(string $secretKey) {
        $this->secretKey = $secretKey;
    }

    public function verifyKey(string $secretKey): bool {
        return password_verify($secretKey, $this->secretKey);
    }

}
