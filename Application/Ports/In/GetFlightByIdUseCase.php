<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Queries/GetFlightByIdQuery.php';
require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface GetFlightByIdUseCase
{
    public function execute(GetFlightByIdQuery $query): FlightModel;
}