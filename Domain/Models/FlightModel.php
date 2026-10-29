<?php

declare(strict_types=1);

require_once __DIR__ . '/../ValueObjects/FlightId.php';
require_once __DIR__ . '/../ValueObjects/FlightSchedule.php';
require_once __DIR__ . '/../ValueObjects/FlightText.php';
require_once __DIR__ . '/../ValueObjects/FlightNumber.php';
require_once __DIR__ . '/../ValueObjects/FlightPrice.php';
require_once __DIR__ . '/../ValueObjects/FlightSeat.php';
require_once __DIR__ . '/../ValueObjects/FlightRoute.php';
require_once __DIR__ . '/../Enums/FlightStatusEnum.php';

final class FlightModel
{
    private FlightId $id;
    private FlightSchedule $schedule;
    private FlightText $travelAgency;
    private FlightText $airline;
    private FlightNumber $number;
    private string $status;
    private FlightPrice $price;
    private FlightText $customer;
    private FlightSeat $seat;
    private FlightText $aircraft;
    private FlightRoute $route;
    private FlightText $pilot;

    public function __construct(
        FlightId $id,
        FlightSchedule $schedule,
        FlightText $travelAgency,
        FlightText $airline,
        FlightNumber $number,
        string $status,
        FlightPrice $price,
        FlightText $customer,
        FlightSeat $seat,
        FlightText $aircraft,
        FlightRoute $route,
        FlightText $pilot
    ) {
        FlightStatusEnum::ensureIsValid($status);

        $this->id = $id;
        $this->schedule = $schedule;
        $this->travelAgency = $travelAgency;
        $this->airline = $airline;
        $this->number = $number;
        $this->status = $status;
        $this->price = $price;
        $this->customer = $customer;
        $this->seat = $seat;
        $this->aircraft = $aircraft;
        $this->route = $route;
        $this->pilot = $pilot;
    }

    /**
     * Un vuelo nuevo siempre nace en estado PROGRAMADO.
     */
    public static function create(
        FlightId $id,
        FlightSchedule $schedule,
        FlightText $travelAgency,
        FlightText $airline,
        FlightNumber $number,
        FlightPrice $price,
        FlightText $customer,
        FlightSeat $seat,
        FlightText $aircraft,
        FlightRoute $route,
        FlightText $pilot
    ): self {
        return new self(
            $id,
            $schedule,
            $travelAgency,
            $airline,
            $number,
            FlightStatusEnum::PROGRAMADO,
            $price,
            $customer,
            $seat,
            $aircraft,
            $route,
            $pilot
        );
    }

    public function id(): FlightId
    {
        return $this->id;
    }

    public function schedule(): FlightSchedule
    {
        return $this->schedule;
    }

    public function travelAgency(): FlightText
    {
        return $this->travelAgency;
    }

    public function airline(): FlightText
    {
        return $this->airline;
    }

    public function number(): FlightNumber
    {
        return $this->number;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function price(): FlightPrice
    {
        return $this->price;
    }

    public function customer(): FlightText
    {
        return $this->customer;
    }

    public function seat(): FlightSeat
    {
        return $this->seat;
    }

    public function aircraft(): FlightText
    {
        return $this->aircraft;
    }

    public function route(): FlightRoute
    {
        return $this->route;
    }

    public function pilot(): FlightText
    {
        return $this->pilot;
    }

    public function changeStatus(string $status): self
    {
        return new self(
            $this->id,
            $this->schedule,
            $this->travelAgency,
            $this->airline,
            $this->number,
            $status,
            $this->price,
            $this->customer,
            $this->seat,
            $this->aircraft,
            $this->route,
            $this->pilot
        );
    }

    /**
     * Reemplaza todos los datos editables conservando el mismo id.
     */
    public function update(
        FlightSchedule $schedule,
        FlightText $travelAgency,
        FlightText $airline,
        FlightNumber $number,
        string $status,
        FlightPrice $price,
        FlightText $customer,
        FlightSeat $seat,
        FlightText $aircraft,
        FlightRoute $route,
        FlightText $pilot
    ): self {
        return new self(
            $this->id,
            $schedule,
            $travelAgency,
            $airline,
            $number,
            $status,
            $price,
            $customer,
            $seat,
            $aircraft,
            $route,
            $pilot
        );
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->id->value(),
            'purchase_date' => $this->schedule->purchaseDate(),
            'departure_date' => $this->schedule->departureDate(),
            'arrival_date' => $this->schedule->arrivalDate(),
            'travel_agency' => $this->travelAgency->value(),
            'airline' => $this->airline->value(),
            'flight_number' => $this->number->value(),
            'status' => $this->status,
            'price' => $this->price->value(),
            'customer' => $this->customer->value(),
            'seat' => $this->seat->value(),
            'aircraft' => $this->aircraft->value(),
            'departure_airport' => $this->route->departureAirport(),
            'arrival_airport' => $this->route->arrivalAirport(),
            'pilot' => $this->pilot->value()
        );
    }
}