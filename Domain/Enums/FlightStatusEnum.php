<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightStatusException.php';

class FlightStatusEnum
{
    const PROGRAMADO = 'PROGRAMADO';
    const RETRASADO = 'RETRASADO';
    const EN_VUELO = 'EN_VUELO';
    const ATERRIZADO = 'ATERRIZADO';
    const CANCELADO = 'CANCELADO';

    public static function values()
    {
        return array(
            self::PROGRAMADO,
            self::RETRASADO,
            self::EN_VUELO,
            self::ATERRIZADO,
            self::CANCELADO
        );
    }

    public static function isValid($value)
    {
        return in_array($value, self::values(), true);
    }

    public static function ensureIsValid($value)
    {
        if (!self::isValid($value)) {
            throw InvalidFlightStatusException::becauseValueIsInvalid($value);
        }
    }
}