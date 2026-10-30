<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightNumber.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightSchedule.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightSeat.php';

interface GetFlightBySeatPort
{
    /**
     * Busca un vuelo con el mismo número, la misma fecha de salida y el mismo puesto.
     */
    public function getBySeat(
        FlightNumber $number,
        FlightSchedule $schedule,
        FlightSeat $seat
    ): ?FlightModel;
}