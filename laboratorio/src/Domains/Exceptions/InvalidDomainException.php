<?php
namespace Domains\Exceptions;

use Throwable;

/**
 * Exception for domains unable to be validated
 */
class InvalidDomainException extends \Exception
{
    protected string $fieldError;

    public function __construct(
        string $message = "",
        int $code = 0,
        string $fieldError = 'unknown',
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->fieldError = $fieldError;
    }

    public function getFieldError(): string
    {
        return $this->fieldError;
    }
}