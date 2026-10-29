<?php

class InvalidFlightNumberException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self('El número de vuelo no puede estar vacío.');
    }

    public static function becauseFormatIsInvalid($value)
    {
        return new self(
            'El número de vuelo no tiene un formato válido (ejemplo: AV8523): ' . $value
        );
    }
}