<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface GetAllFlightsPort
{
    /**
     * @return FlightModel[]
     */
    public function getAll(): array;
}