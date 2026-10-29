<?php

class FlightAlreadyExistsException extends DomainException
{
    public static function becauseSeatIsAlreadyTaken($flightNumber, $departureDate, $seat)
    {
        return new self(
            'El puesto ' . $seat . ' del vuelo ' . $flightNumber
            . ' con salida ' . $departureDate . ' ya está ocupado.'
        );
    }
}