<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightRouteException.php';
require_once __DIR__ . '/../Exceptions/InvalidFlightTextException.php';

class FlightRoute
{
    const MAX_LENGTH = 100;

    private $departureAirport;
    private $arrivalAirport;

    public function __construct($departureAirport, $arrivalAirport)
    {
        $departure = self::normalize('aeropuerto de salida', $departureAirport);
        $arrival = self::normalize('aeropuerto de llegada', $arrivalAirport);

        if (mb_strtolower($departure) === mb_strtolower($arrival)) {
            throw InvalidFlightRouteException::becauseAirportsAreTheSame($departure);
        }

        $this->departureAirport = $departure;
        $this->arrivalAirport = $arrival;
    }

    private static function normalize($field, $value)
    {
        $normalizedValue = trim((string) $value);

        if ($normalizedValue === '') {
            throw InvalidFlightRouteException::becauseAirportIsEmpty($field);
        }

        if (mb_strlen($normalizedValue) > self::MAX_LENGTH) {
            throw InvalidFlightTextException::becauseLengthIsTooLong($field, self::MAX_LENGTH);
        }

        return $normalizedValue;
    }

    public function departureAirport()
    {
        return $this->departureAirport;
    }

    public function arrivalAirport()
    {
        return $this->arrivalAirport;
    }

    public function equals(FlightRoute $other)
    {
        return $this->departureAirport === $other->departureAirport()
            && $this->arrivalAirport === $other->arrivalAirport();
    }
}