<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/CreateFlightUseCase.php';
require_once __DIR__ . '/../Ports/Out/SaveFlightPort.php';
require_once __DIR__ . '/../Ports/Out/GetFlightBySeatPort.php';
require_once __DIR__ . '/Mappers/FlightApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/FlightAlreadyExistsException.php';

final class CreateFlightService implements CreateFlightUseCase
{
    private SaveFlightPort $saveFlightPort;
    private GetFlightBySeatPort $getFlightBySeatPort;

    public function __construct(
        SaveFlightPort $saveFlightPort,
        GetFlightBySeatPort $getFlightBySeatPort
    ) {
        $this->saveFlightPort = $saveFlightPort;
        $this->getFlightBySeatPort = $getFlightBySeatPort;
    }

    public function execute(CreateFlightCommand $command): FlightModel
    {
        $flight = FlightApplicationMapper::fromCreateCommandToModel($command);

        $flightWithSameSeat = $this->getFlightBySeatPort->getBySeat(
            $flight->number(),
            $flight->schedule(),
            $flight->seat()
        );

        if ($flightWithSameSeat !== null) {
            throw FlightAlreadyExistsException::becauseSeatIsAlreadyTaken(
                $flight->number()->value(),
                $flight->schedule()->departureDate(),
                $flight->seat()->value()
            );
        }

        return $this->saveFlightPort->save($flight);
    }
}