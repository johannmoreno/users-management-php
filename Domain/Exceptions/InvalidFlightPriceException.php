<?php

class InvalidFlightPriceException extends InvalidArgumentException
{
    public static function becauseValueIsNotNumeric($value)
    {
        return new self('El valor del vuelo debe ser un número: ' . $value);
    }

    public static function becauseValueMustBeGreaterThanZero($value)
    {
        return new self('El valor del vuelo debe ser mayor que cero: ' . $value);
    }
}