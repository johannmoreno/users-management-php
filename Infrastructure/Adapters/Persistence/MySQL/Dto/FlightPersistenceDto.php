<?php

declare(strict_types=1);

final class FlightPersistenceDto
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
        $this->id = trim($id);
        $this->purchaseDate = trim($purchaseDate);
        $this->departureDate = trim($departureDate);
        $this->arrivalDate = trim($arrivalDate);
        $this->travelAgency = trim($travelAgency);
        $this->airline = trim($airline);
        $this->flightNumber = trim($flightNumber);
        $this->status = trim($status);
        $this->price = trim($price);
        $this->customer = trim($customer);
        $this->seat = trim($seat);
        $this->aircraft = trim($aircraft);
        $this->departureAirport = trim($departureAirport);
        $this->arrivalAirport = trim($arrivalAirport);
        $this->pilot = trim($pilot);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function purchaseDate(): string
    {
        return $this->purchaseDate;
    }

    public function departureDate(): string
    {
        return $this->departureDate;
    }

    public function arrivalDate(): string
    {
        return $this->arrivalDate;
    }

    public function travelAgency(): string
    {
        return $this->travelAgency;
    }

    public function airline(): string
    {
        return $this->airline;
    }

    public function flightNumber(): string
    {
        return $this->flightNumber;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function price(): string
    {
        return $this->price;
    }

    public function customer(): string
    {
        return $this->customer;
    }

    public function seat(): string
    {
        return $this->seat;
    }

    public function aircraft(): string
    {
        return $this->aircraft;
    }

    public function departureAirport(): string
    {
        return $this->departureAirport;
    }

    public function arrivalAirport(): string
    {
        return $this->arrivalAirport;
    }

    public function pilot(): string
    {
        return $this->pilot;
    }
}