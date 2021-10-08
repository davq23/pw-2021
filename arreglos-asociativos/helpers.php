<?php

function filterAssociativeArray(array $associativeArray): array
{
    foreach ($associativeArray as $key => $value)
    {
        if (is_null($associativeArray[$key]))
        {
            unset($associativeArray[$key]);
        }
    }

    return $associativeArray;
}

function checkFloat($float, float $min = 0): ?string
{
    $error = null;

    if (!isset($float) || !is_numeric($float))
    {
        $error = 'Campo inválido';
    } else if ($float < $min)
    {
        $error = "No puede ser menor que $min";
    }

    return $error;
}

function checkText(string $text, int $maxLength, bool $numeric = false): ?string
{
    $error = null;

    if (!isset($text) || empty($text))
    {
        $error = 'Campo requerido';
    } else if (mb_strlen($text) > $maxLength)
    {
        $error = "No puede superar los $maxLength caracteres";
    }


    if ($numeric && !is_numeric($text))
    {
        $error = 'Debe ser numérico';
    }

    return $error;
}
