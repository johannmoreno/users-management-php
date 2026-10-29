<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightPriceException.php';

class FlightPrice
{
    private $value;

    public function __construct($value)
    {
        $normalizedValue = trim((string) $value);

        if ($normalizedValue === '' || !is_numeric($normalizedValue)) {
            throw InvalidFlightPriceException::becauseValueIsNotNumeric($normalizedValue);
        }

        if ((float) $normalizedValue <= 0) {
            throw InvalidFlightPriceException::becauseValueMustBeGreaterThanZero($normalizedValue);
        }

        // Se guarda con dos decimales, igual que la columna DECIMAL(12,2).
        $this->value = number_format((float) $normalizedValue, 2, '.', '');
    }

    public function value()
    {
        return $this->value;
    }

    public function asFloat()
    {
        return (float) $this->value;
    }

    public function equals(FlightPrice $other)
    {
        return $this->value === $other->value();
    }

    public function __toString()
    {
        return $this->value;
    }
}