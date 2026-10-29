<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightNumberException.php';

class FlightNumber
{
    private $value;

    public function __construct($value)
    {
        $normalizedValue = strtoupper(trim((string) $value));

        if ($normalizedValue === '') {
            throw InvalidFlightNumberException::becauseValueIsEmpty();
        }

        // Código de aerolínea (2 caracteres IATA o 3 letras ICAO) seguido de 1 a 4 dígitos.
        // Ejemplos: AV8523, LA1, AVA8523
        if (!preg_match('/^([A-Z0-9]{2}|[A-Z]{3})[0-9]{1,4}$/', $normalizedValue)) {
            throw InvalidFlightNumberException::becauseFormatIsInvalid($normalizedValue);
        }

        $this->value = $normalizedValue;
    }

    public function value()
    {
        return $this->value;
    }

    public function equals(FlightNumber $other)
    {
        return $this->value === $other->value();
    }

    public function __toString()
    {
        return $this->value;
    }
}