<?php

class InvalidFlightStatusException extends InvalidArgumentException
{
    public static function becauseValueIsInvalid($status)
    {
        return new self('El estado del vuelo no es válido: ' . $status);
    }
}