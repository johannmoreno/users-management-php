<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/GetAllFlightsUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetAllFlightsPort.php';

final class GetAllFlightsService implements GetAllFlightsUseCase
{
    private GetAllFlightsPort $getAllFlightsPort;

    public function __construct(GetAllFlightsPort $getAllFlightsPort)
    {
        $this->getAllFlightsPort = $getAllFlightsPort;
    }

    /**
     * @return FlightModel[]
     */
    public function execute(GetAllFlightsQuery $query): array
    {
        return $this->getAllFlightsPort->getAll();
    }
}