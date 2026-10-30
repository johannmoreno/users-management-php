<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/CreateFlightWebRequest.php';
require_once __DIR__ . '/../Dto/UpdateFlightWebRequest.php';
require_once __DIR__ . '/../Dto/FlightResponse.php';

require_once __DIR__ . '/../../../../../Application/Services/Dto/Commands/CreateFlightCommand.php';
require_once __DIR__ . '/../../../../../Application/Services/Dto/Commands/UpdateFlightCommand.php';
require_once __DIR__ . '/../../../../../Application/Services/Dto/Commands/DeleteFlightCommand.php';
require_once __DIR__ . '/../../../../../Application/Services/Dto/Queries/GetFlightByIdQuery.php';

require_once __DIR__ . '/../../../../../Domain/Models/FlightModel.php';

final class FlightWebMapper
{
    public function fromCreateRequestToCommand(CreateFlightWebRequest $request): CreateFlightCommand
    {
        return new CreateFlightCommand(
            $request->getId(),
            $request->getPurchaseDate(),
            $request->getDepartureDate(),
            $request->getArrivalDate(),
            $request->getTravelAgency(),
            $request->getAirline(),
            $request->getFlightNumber(),
            $request->getPrice(),
            $request->getCustomer(),
            $request->getSeat(),
            $request->getAircraft(),
            $request->getDepartureAirport(),
            $request->getArrivalAirport(),
            $request->getPilot()
        );
    }

    public function fromUpdateRequestToCommand(UpdateFlightWebRequest $request): UpdateFlightCommand
    {
        return new UpdateFlightCommand(
            $request->getId(),
            $request->getPurchaseDate(),
            $request->getDepartureDate(),
            $request->getArrivalDate(),
            $request->getTravelAgency(),
            $request->getAirline(),
            $request->getFlightNumber(),
            $request->getPrice(),
            $request->getCustomer(),
            $request->getSeat(),
            $request->getAircraft(),
            $request->getDepartureAirport(),
            $request->getArrivalAirport(),
            $request->getPilot(),
            $request->getStatus()
        );
    }

    public function fromIdToGetByIdQuery(string $id): GetFlightByIdQuery
    {
        return new GetFlightByIdQuery($id);
    }

    public function fromIdToDeleteCommand(string $id): DeleteFlightCommand
    {
        return new DeleteFlightCommand($id);
    }

    public function fromModelToResponse(FlightModel $flight): FlightResponse
    {
        return new FlightResponse(
            $flight->id()->value(),
            $flight->schedule()->purchaseDate(),
            $flight->schedule()->departureDate(),
            $flight->schedule()->arrivalDate(),
            $flight->travelAgency()->value(),
            $flight->airline()->value(),
            $flight->number()->value(),
            $flight->status(),
            $flight->price()->value(),
            $flight->customer()->value(),
            $flight->seat()->value(),
            $flight->aircraft()->value(),
            $flight->route()->departureAirport(),
            $flight->route()->arrivalAirport(),
            $flight->pilot()->value()
        );
    }

    /**
     * @param FlightModel[] $flights
     * @return FlightResponse[]
     */
    public function fromModelsToResponses(array $flights): array
    {
        $responses = array();

        foreach ($flights as $flight) {
            $responses[] = $this->fromModelToResponse($flight);
        }

        return $responses;
    }
}