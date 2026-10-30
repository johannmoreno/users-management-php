<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/FlightPersistenceDto.php';
require_once __DIR__ . '/../Entity/FlightEntity.php';

require_once __DIR__ . '/../../../../../Domain/Models/FlightModel.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightId.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightSchedule.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightText.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightNumber.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightPrice.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightSeat.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightRoute.php';

final class FlightPersistenceMapper
{
    public function fromModelToDto(FlightModel $flight): FlightPersistenceDto
    {
        return new FlightPersistenceDto(
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

    public function fromRowToEntity(array $row): FlightEntity
    {
        return new FlightEntity(
            (string) $row['id'],
            (string) $row['purchase_date'],
            (string) $row['departure_date'],
            (string) $row['arrival_date'],
            (string) $row['travel_agency'],
            (string) $row['airline'],
            (string) $row['flight_number'],
            (string) $row['status'],
            (string) $row['price'],
            (string) $row['customer'],
            (string) $row['seat'],
            (string) $row['aircraft'],
            (string) $row['departure_airport'],
            (string) $row['arrival_airport'],
            (string) $row['pilot'],
            isset($row['created_at']) ? (string) $row['created_at'] : null,
            isset($row['updated_at']) ? (string) $row['updated_at'] : null
        );
    }

    public function fromEntityToModel(FlightEntity $entity): FlightModel
    {
        return new FlightModel(
            new FlightId($entity->id()),
            new FlightSchedule(
                $entity->purchaseDate(),
                $entity->departureDate(),
                $entity->arrivalDate()
            ),
            new FlightText('agencia de viajes', $entity->travelAgency()),
            new FlightText('aerolínea', $entity->airline()),
            new FlightNumber($entity->flightNumber()),
            $entity->status(),
            new FlightPrice($entity->price()),
            new FlightText('cliente', $entity->customer()),
            new FlightSeat($entity->seat()),
            new FlightText('avión', $entity->aircraft(), 50),
            new FlightRoute($entity->departureAirport(), $entity->arrivalAirport()),
            new FlightText('piloto', $entity->pilot())
        );
    }

    public function fromRowToModel(array $row): FlightModel
    {
        return $this->fromEntityToModel(
            $this->fromRowToEntity($row)
        );
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return FlightModel[]
     */
    public function fromRowsToModels(array $rows): array
    {
        $models = array();

        foreach ($rows as $row) {
            $models[] = $this->fromRowToModel($row);
        }

        return $models;
    }
}