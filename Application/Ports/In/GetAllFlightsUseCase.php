<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Queries/GetAllFlightsQuery.php';
require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface GetAllFlightsUseCase
{
    /**
     * @return FlightModel[]
     */
    public function execute(GetAllFlightsQuery $query): array;
}