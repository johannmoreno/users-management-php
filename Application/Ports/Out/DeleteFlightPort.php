<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/ValueObjects/FlightId.php';

interface DeleteFlightPort
{
    public function delete(FlightId $flightId): void;
}