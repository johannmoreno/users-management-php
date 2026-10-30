<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../../Application/Ports/Out/SaveFlightPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/UpdateFlightPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetFlightByIdPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetFlightBySeatPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetAllFlightsPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/DeleteFlightPort.php';

require_once __DIR__ . '/../Mapper/FlightPersistenceMapper.php';
require_once __DIR__ . '/../../../../../Domain/Models/FlightModel.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightId.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightNumber.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightSchedule.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/FlightSeat.php';
require_once __DIR__ . '/../../../../../Domain/Exceptions/FlightAlreadyExistsException.php';

final class FlightRepositoryMySQL implements
    SaveFlightPort,
    UpdateFlightPort,
    GetFlightByIdPort,
    GetFlightBySeatPort,
    GetAllFlightsPort,
    DeleteFlightPort
{
    private const SELECT_COLUMNS = '
        id,
        purchase_date,
        departure_date,
        arrival_date,
        travel_agency,
        airline,
        flight_number,
        status,
        price,
        customer,
        seat,
        aircraft,
        departure_airport,
        arrival_airport,
        pilot,
        created_at,
        updated_at
    ';

    private PDO $pdo;
    private FlightPersistenceMapper $mapper;

    public function __construct(PDO $pdo, FlightPersistenceMapper $mapper)
    {
        $this->pdo = $pdo;
        $this->mapper = $mapper;
    }

    public function save(FlightModel $flight): FlightModel
    {
        $dto = $this->mapper->fromModelToDto($flight);

        $sql = '
            INSERT INTO flights (
                id,
                purchase_date,
                departure_date,
                arrival_date,
                travel_agency,
                airline,
                flight_number,
                status,
                price,
                customer,
                seat,
                aircraft,
                departure_airport,
                arrival_airport,
                pilot,
                created_at,
                updated_at
            ) VALUES (
                :id,
                :purchase_date,
                :departure_date,
                :arrival_date,
                :travel_agency,
                :airline,
                :flight_number,
                :status,
                :price,
                :customer,
                :seat,
                :aircraft,
                :departure_airport,
                :arrival_airport,
                :pilot,
                NOW(),
                NOW()
            )
        ';

        $this->execute($sql, $dto);

        $savedFlight = $this->getById(new FlightId($dto->id()));

        if ($savedFlight === null) {
            throw new RuntimeException('El vuelo no se pudo recuperar después de guardarlo.');
        }

        return $savedFlight;
    }

    public function update(FlightModel $flight): FlightModel
    {
        $dto = $this->mapper->fromModelToDto($flight);

        $sql = '
            UPDATE flights
               SET purchase_date = :purchase_date,
                   departure_date = :departure_date,
                   arrival_date = :arrival_date,
                   travel_agency = :travel_agency,
                   airline = :airline,
                   flight_number = :flight_number,
                   status = :status,
                   price = :price,
                   customer = :customer,
                   seat = :seat,
                   aircraft = :aircraft,
                   departure_airport = :departure_airport,
                   arrival_airport = :arrival_airport,
                   pilot = :pilot,
                   updated_at = NOW()
             WHERE id = :id
        ';

        $this->execute($sql, $dto);

        $updatedFlight = $this->getById(new FlightId($dto->id()));

        if ($updatedFlight === null) {
            throw new RuntimeException('El vuelo no se pudo recuperar después de actualizarlo.');
        }

        return $updatedFlight;
    }

    public function getById(FlightId $flightId): ?FlightModel
    {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . ' FROM flights WHERE id = :id LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $flightId->value(),
        ));

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    public function getBySeat(
        FlightNumber $number,
        FlightSchedule $schedule,
        FlightSeat $seat
    ): ?FlightModel {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . '
                FROM flights
                WHERE flight_number = :flight_number
                  AND departure_date = :departure_date
                  AND seat = :seat
                LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':flight_number' => $number->value(),
            ':departure_date' => $schedule->departureDate(),
            ':seat' => $seat->value(),
        ));

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    /**
     * @return FlightModel[]
     */
    public function getAll(): array
    {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . ' FROM flights ORDER BY departure_date ASC, flight_number ASC';

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll();

        return $this->mapper->fromRowsToModels($rows);
    }

    public function delete(FlightId $flightId): void
    {
        $sql = 'DELETE FROM flights WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $flightId->value(),
        ));
    }

    /**
     * Ejecuta un INSERT o UPDATE. Si la base de datos rechaza el asiento por la
     * restricción única uq_flight_seat, lo traduce a una excepción del dominio.
     */
    private function execute(string $sql, FlightPersistenceDto $dto): void
    {
        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute(array(
                ':id' => $dto->id(),
                ':purchase_date' => $dto->purchaseDate(),
                ':departure_date' => $dto->departureDate(),
                ':arrival_date' => $dto->arrivalDate(),
                ':travel_agency' => $dto->travelAgency(),
                ':airline' => $dto->airline(),
                ':flight_number' => $dto->flightNumber(),
                ':status' => $dto->status(),
                ':price' => $dto->price(),
                ':customer' => $dto->customer(),
                ':seat' => $dto->seat(),
                ':aircraft' => $dto->aircraft(),
                ':departure_airport' => $dto->departureAirport(),
                ':arrival_airport' => $dto->arrivalAirport(),
                ':pilot' => $dto->pilot(),
            ));
        } catch (PDOException $exception) {
            $isDuplicateKey = isset($exception->errorInfo[1]) && (int) $exception->errorInfo[1] === 1062;

            if ($isDuplicateKey) {
                throw FlightAlreadyExistsException::becauseSeatIsAlreadyTaken(
                    $dto->flightNumber(),
                    $dto->departureDate(),
                    $dto->seat()
                );
            }

            throw $exception;
        }
    }
}