<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightTextException.php';

/**
 * Texto obligatorio con largo máximo. Se usa para agencia de viajes,
 * aerolínea, cliente, avión y piloto.
 */
class FlightText
{
    private $value;

    public function __construct($field, $value, $maxLength = 100)
    {
        $normalizedValue = trim((string) $value);

        if ($normalizedValue === '') {
            throw InvalidFlightTextException::becauseValueIsEmpty($field);
        }

        if (mb_strlen($normalizedValue) > $maxLength) {
            throw InvalidFlightTextException::becauseLengthIsTooLong($field, $maxLength);
        }

        $this->value = $normalizedValue;
    }

    public function value()
    {
        return $this->value;
    }

    public function equals(FlightText $other)
    {
        return $this->value === $other->value();
    }

    public function __toString()
    {
        return $this->value;
    }
}