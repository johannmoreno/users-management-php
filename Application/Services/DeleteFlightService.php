<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/DeleteFlightUseCase.php';
require_once __DIR__ . '/../Ports/Out/DeleteFlightPort.php';
require_once __DIR__ . '/../Ports/Out/GetFlightByIdPort.php';
require_once __DIR__ . '/Mappers/FlightApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/FlightNotFoundException.php';

final class DeleteFlightService implements DeleteFlightUseCase
{
    private DeleteFlightPort $deleteFlightPort;
    private GetFlightByIdPort $getFlightByIdPort;

    public function __construct(
        DeleteFlightPort $deleteFlightPort,
        GetFlightByIdPort $getFlightByIdPort
    ) {
        $this->deleteFlightPort = $deleteFlightPort;
        $this->getFlightByIdPort = $getFlightByIdPort;
    }

    public function execute(DeleteFlightCommand $command): void
    {
        $flightId = FlightApplicationMapper::fromDeleteCommandToFlightId($command);
        $existingFlight = $this->getFlightByIdPort->getById($flightId);

        if ($existingFlight === null) {
            throw FlightNotFoundException::becauseIdWasNotFound($flightId->value());
        }

        $this->deleteFlightPort->delete($flightId);
    }
}