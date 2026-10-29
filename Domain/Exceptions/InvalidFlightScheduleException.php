<?php

class InvalidFlightScheduleException extends InvalidArgumentException
{
    public static function becauseDateFormatIsInvalid($field, $value)
    {
        return new self(
            'La fecha de ' . $field . ' no es válida (formato esperado: AAAA-MM-DD HH:MM): ' . $value
        );
    }

    public static function becauseArrivalIsNotAfterDeparture()
    {
        return new self('La fecha de llegada debe ser posterior a la fecha de salida.');
    }

    public static function becausePurchaseIsAfterDeparture()
    {
        return new self('La fecha de compra no puede ser posterior a la fecha de salida.');
    }
}