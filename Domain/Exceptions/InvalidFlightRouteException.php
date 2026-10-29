<?php

class InvalidFlightRouteException extends InvalidArgumentException
{
    public static function becauseAirportIsEmpty($field)
    {
        return new self('El ' . $field . ' no puede estar vacío.');
    }

    public static function becauseAirportsAreTheSame($airport)
    {
        return new self(
            'El aeropuerto de salida y el de llegada no pueden ser el mismo: ' . $airport
        );
    }
}