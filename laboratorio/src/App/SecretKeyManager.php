<?php

namespace App;

/**
 *
 * @author davido
 */
interface SecretKeyManager
{

    public function verifyKey(string $secretKey): bool;
}
