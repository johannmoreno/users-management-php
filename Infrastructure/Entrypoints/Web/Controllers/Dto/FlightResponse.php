<?php

declare(strict_types=1);

final class FlightResponse
{
    private string $id;
    private string $purchaseDate;
    private string $departureDate;
    private string $arrivalDate;
    private string $travelAgency;
    private string $airline;
    private string $flightNumber;
    private string $status;
    private string $price;
    private string $customer;
    private string $seat;
    private string $aircraft;
    private string $departureAirport;
    private string $arrivalAirport;
    private string $pilot;

    public function __construct(
        string $id,
        string $purchaseDate,
        string $departureDate,
        string $arrivalDate,
        string $travelAgency,
        string $airline,
        string $flightNumber,
        string $status,
        string $price,
        string $customer,
        string $seat,
        string $aircraft,
        string $departureAirport,
        string $arrivalAirport,
        string $pilot
    ) {
        $this->id = $id;
        $this->purchaseDate = $purchaseDate;
        $this->departureDate = $departureDate;
        $this->arrivalDate = $arrivalDate;
        $this->travelAgency = $travelAgency;
        $this->airline = $airline;
        $this->flightNumber = $flightNumber;
        $this->status = $status;
        $this->price = $price;
        $this->customer = $customer;
        $this->seat = $seat;
        $this->aircraft = $aircraft;
        $this->departureAirport = $departureAirport;
        $this->arrivalAirport = $arrivalAirport;
        $this->pilot = $pilot;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPurchaseDate(): string
    {
        return $this->purchaseDate;
    }

    public function getDepartureDate(): string
    {
        return $this->departureDate;
    }

    public function getArrivalDate(): string
    {
        return $this->arrivalDate;
    }

    public function getTravelAgency(): string
    {
        return $this->travelAgency;
    }

    public function getAirline(): string
    {
        return $this->airline;
    }

    public function getFlightNumber(): string
    {
        return $this->flightNumber;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function getSeat(): string
    {
        return $this->seat;
    }

    public function getAircraft(): string
    {
        return $this->aircraft;
    }

    public function getDepartureAirport(): string
    {
        return $this->departureAirport;
    }

    public function getArrivalAirport(): string
    {
        return $this->arrivalAirport;
    }

    public function getPilot(): string
    {
        return $this->pilot;
    }

    /**
     * Las claves coinciden con los nombres de los campos del formulario.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array(
            'id' => $this->id,
            'purchase_date' => $this->purchaseDate,
            'departure_date' => $this->departureDate,
            'arrival_date' => $this->arrivalDate,
            'travel_agency' => $this->travelAgency,
            'airline' => $this->airline,
            'flight_number' => $this->flightNumber,
            'status' => $this->status,
            'price' => $this->price,
            'customer' => $this->customer,
            'seat' => $this->seat,
            'aircraft' => $this->aircraft,
            'departure_airport' => $this->departureAirport,
            'arrival_airport' => $this->arrivalAirport,
            'pilot' => $this->pilot,
        );
    }
}