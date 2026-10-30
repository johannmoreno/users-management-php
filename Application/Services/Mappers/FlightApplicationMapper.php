<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/Commands/CreateFlightCommand.php';
require_once __DIR__ . '/../Dto/Commands/UpdateFlightCommand.php';
require_once __DIR__ . '/../Dto/Commands/DeleteFlightCommand.php';
require_once __DIR__ . '/../Dto/Queries/GetFlightByIdQuery.php';

require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightId.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightSchedule.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightText.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightNumber.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightPrice.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightSeat.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/FlightRoute.php';

final class FlightApplicationMapper
{
    public static function fromCreateCommandToModel(CreateFlightCommand $command): FlightModel
    {
        return FlightModel::create(
            new FlightId($command->getId()),
            new FlightSchedule(
                $command->getPurchaseDate(),
                $command->getDepartureDate(),
                $command->getArrivalDate()
            ),
            new FlightText('agencia de viajes', $command->getTravelAgency()),
            new FlightText('aerolínea', $command->getAirline()),
            new FlightNumber($command->getFlightNumber()),
            new FlightPrice($command->getPrice()),
            new FlightText('cliente', $command->getCustomer()),
            new FlightSeat($command->getSeat()),
            new FlightText('avión', $command->getAircraft(), 50),
            new FlightRoute($command->getDepartureAirport(), $command->getArrivalAirport()),
            new FlightText('piloto', $command->getPilot())
        );
    }

    public static function fromUpdateCommandToModel(UpdateFlightCommand $command): FlightModel
    {
        return new FlightModel(
            new FlightId($command->getId()),
            new FlightSchedule(
                $command->getPurchaseDate(),
                $command->getDepartureDate(),
                $command->getArrivalDate()
            ),
            new FlightText('agencia de viajes', $command->getTravelAgency()),
            new FlightText('aerolínea', $command->getAirline()),
            new FlightNumber($command->getFlightNumber()),
            $command->getStatus(),
            new FlightPrice($command->getPrice()),
            new FlightText('cliente', $command->getCustomer()),
            new FlightSeat($command->getSeat()),
            new FlightText('avión', $command->getAircraft(), 50),
            new FlightRoute($command->getDepartureAirport(), $command->getArrivalAirport()),
            new FlightText('piloto', $command->getPilot())
        );
    }

    public static function fromGetFlightByIdQueryToFlightId(GetFlightByIdQuery $query): FlightId
    {
        return new FlightId($query->getId());
    }

    public static function fromDeleteCommandToFlightId(DeleteFlightCommand $command): FlightId
    {
        return new FlightId($command->getId());
    }

    /**
     * @return array<string, string>
     */
    public static function fromModelToArray(FlightModel $flight): array
    {
        return $flight->toArray();
    }

    /**
     * @param FlightModel[] $flights
     * @return array<int, array<string, string>>
     */
    public static function fromModelsToArray(array $flights): array
    {
        $result = array();

        foreach ($flights as $flight) {
            $result[] = self::fromModelToArray($flight);
        }

        return $result;
    }
}