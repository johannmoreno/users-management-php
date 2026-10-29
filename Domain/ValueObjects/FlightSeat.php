<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightSeatException.php';

class FlightSeat
{
    private $value;

    public function __construct($value)
    {
        $normalizedValue = strtoupper(trim((string) $value));

        if ($normalizedValue === '') {
            throw InvalidFlightSeatException::becauseValueIsEmpty();
        }

        // Fila de 1 o 2 dígitos seguida de una letra de la A a la K. Ej: 12A
        if (!preg_match('/^[0-9]{1,2}[A-K]$/', $normalizedValue)) {
            throw InvalidFlightSeatException::becauseFormatIsInvalid($normalizedValue);
        }

        $this->value = $normalizedValue;
    }

    public function value()
    {
        return $this->value;
    }

    public function equals(FlightSeat $other)
    {
        return $this->value === $other->value();
    }

    public function __toString()
    {
        return $this->value;
    }
}