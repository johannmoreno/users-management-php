<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface UpdateFlightPort
{
    public function update(FlightModel $flight): FlightModel;
}