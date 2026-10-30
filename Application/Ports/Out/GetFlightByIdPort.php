<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightId.php';

interface GetFlightByIdPort
{
    public function getById(FlightId $flightId): ?FlightModel;
}