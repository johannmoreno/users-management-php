<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/UpdateFlightUseCase.php';
require_once __DIR__ . '/../Ports/Out/UpdateFlightPort.php';
require_once __DIR__ . '/../Ports/Out/GetFlightByIdPort.php';
require_once __DIR__ . '/../Ports/Out/GetFlightBySeatPort.php';
require_once __DIR__ . '/Mappers/FlightApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/FlightAlreadyExistsException.php';
require_once __DIR__ . '/../../Domain/Exceptions/FlightNotFoundException.php';

final class UpdateFlightService implements UpdateFlightUseCase
{
    private UpdateFlightPort $updateFlightPort;
    private GetFlightByIdPort $getFlightByIdPort;
    private GetFlightBySeatPort $getFlightBySeatPort;

    public function __construct(
        UpdateFlightPort $updateFlightPort,
        GetFlightByIdPort $getFlightByIdPort,
        GetFlightBySeatPort $getFlightBySeatPort
    ) {
        $this->updateFlightPort = $updateFlightPort;
        $this->getFlightByIdPort = $getFlightByIdPort;
        $this->getFlightBySeatPort = $getFlightBySeatPort;
    }

    public function execute(UpdateFlightCommand $command): FlightModel
    {
        $flightId = new FlightId($command->getId());
        $currentFlight = $this->getFlightByIdPort->getById($flightId);

        if ($currentFlight === null) {
            throw FlightNotFoundException::becauseIdWasNotFound($flightId->value());
        }

        $flightToUpdate = FlightApplicationMapper::fromUpdateCommandToModel($command);

        $flightWithSameSeat = $this->getFlightBySeatPort->getBySeat(
            $flightToUpdate->number(),
            $flightToUpdate->schedule(),
            $flightToUpdate->seat()
        );

        if ($flightWithSameSeat !== null && !$flightWithSameSeat->id()->equals($flightId)) {
            throw FlightAlreadyExistsException::becauseSeatIsAlreadyTaken(
                $flightToUpdate->number()->value(),
                $flightToUpdate->schedule()->departureDate(),
                $flightToUpdate->seat()->value()
            );
        }

        return $this->updateFlightPort->update($flightToUpdate);
    }
}