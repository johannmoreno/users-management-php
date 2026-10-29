<?php

require_once __DIR__ . '/../Exceptions/InvalidFlightScheduleException.php';

class FlightSchedule
{
    const STORAGE_FORMAT = 'Y-m-d H:i:s';

    private $purchaseDate;
    private $departureDate;
    private $arrivalDate;

    public function __construct($purchaseDate, $departureDate, $arrivalDate)
    {
        $purchase = self::parse('compra', $purchaseDate);
        $departure = self::parse('salida', $departureDate);
        $arrival = self::parse('llegada', $arrivalDate);

        if ($arrival <= $departure) {
            throw InvalidFlightScheduleException::becauseArrivalIsNotAfterDeparture();
        }

        if ($purchase > $departure) {
            throw InvalidFlightScheduleException::becausePurchaseIsAfterDeparture();
        }

        $this->purchaseDate = $purchase->format(self::STORAGE_FORMAT);
        $this->departureDate = $departure->format(self::STORAGE_FORMAT);
        $this->arrivalDate = $arrival->format(self::STORAGE_FORMAT);
    }

    private static function parse($field, $value)
    {
        $normalizedValue = trim((string) $value);

        // Acepta el formato de la base de datos y el del campo datetime-local de HTML.
        $formats = array('Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d\TH:i:s', 'Y-m-d\TH:i');

        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat($format, $normalizedValue);

            if ($date !== false && $date->format($format) === $normalizedValue) {
                return $date;
            }
        }

        throw InvalidFlightScheduleException::becauseDateFormatIsInvalid($field, $normalizedValue);
    }

    public function purchaseDate()
    {
        return $this->purchaseDate;
    }

    public function departureDate()
    {
        return $this->departureDate;
    }

    public function arrivalDate()
    {
        return $this->arrivalDate;
    }

    public function equals(FlightSchedule $other)
    {
        return $this->purchaseDate === $other->purchaseDate()
            && $this->departureDate === $other->departureDate()
            && $this->arrivalDate === $other->arrivalDate();
    }
}