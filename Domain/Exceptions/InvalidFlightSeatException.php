<?php

class InvalidFlightSeatException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self('El puesto del vuelo no puede estar vacío.');
    }

    public static function becauseFormatIsInvalid($value)
    {
        return new self(
            'El puesto no tiene un formato válido (ejemplo: 12A): ' . $value
        );
    }
}