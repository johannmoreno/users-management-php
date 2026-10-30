<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/GetFlightByIdUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetFlightByIdPort.php';
require_once __DIR__ . '/Mappers/FlightApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/FlightNotFoundException.php';

final class GetFlightByIdService implements GetFlightByIdUseCase
{
    private GetFlightByIdPort $getFlightByIdPort;

    public function __construct(GetFlightByIdPort $getFlightByIdPort)
    {
        $this->getFlightByIdPort = $getFlightByIdPort;
    }

    public function execute(GetFlightByIdQuery $query): FlightModel
    {
        $flightId = FlightApplicationMapper::fromGetFlightByIdQueryToFlightId($query);
        $flight = $this->getFlightByIdPort->getById($flightId);

        if ($flight === null) {
            throw FlightNotFoundException::becauseIdWasNotFound($flightId->value());
        }

        return $flight;
    }
}