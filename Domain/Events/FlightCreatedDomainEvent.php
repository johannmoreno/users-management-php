<?php

require_once __DIR__ . '/EventDomain.php';
require_once __DIR__ . '/../Models/FlightModel.php';

class FlightCreatedDomainEvent extends DomainEvent
{
    private $flight;

    public function __construct(FlightModel $flight)
    {
        parent::__construct('flight.created');
        $this->flight = $flight;
    }

    public function flight()
    {
        return $this->flight;
    }

    public function payload()
    {
        return array(
            'id' => $this->flight->id()->value(),
            'flight_number' => $this->flight->number()->value(),
            'seat' => $this->flight->seat()->value(),
            'customer' => $this->flight->customer()->value(),
            'departure_date' => $this->flight->schedule()->departureDate(),
            'status' => $this->flight->status()
        );
    }
}