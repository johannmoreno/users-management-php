<?php

declare(strict_types=1);

final class UpdateFlightWebRequest
{
    private string $id;
    private string $purchaseDate;
    private string $departureDate;
    private string $arrivalDate;
    private string $travelAgency;
    private string $airline;
    private string $flightNumber;
    private string $price;
    private string $customer;
    private string $seat;
    private string $aircraft;
    private string $departureAirport;
    private string $arrivalAirport;
    private string $pilot;
    private string $status;

    public function __construct(
        string $id,
        string $purchaseDate,
        string $departureDate,
        string $arrivalDate,
        string $travelAgency,
        string $airline,
        string $flightNumber,
        string $price,
        string $customer,
        string $seat,
        string $aircraft,
        string $departureAirport,
        string $arrivalAirport,
        string $pilot,
        string $status
    ) {
        $this->id = trim($id);
        $this->purchaseDate = trim($purchaseDate);
        $this->departureDate = trim($departureDate);
        $this->arrivalDate = trim($arrivalDate);
        $this->travelAgency = trim($travelAgency);
        $this->airline = trim($airline);
        $this->flightNumber = trim($flightNumber);
        $this->price = trim($price);
        $this->customer = trim($customer);
        $this->seat = trim($seat);
        $this->aircraft = trim($aircraft);
        $this->departureAirport = trim($departureAirport);
        $this->arrivalAirport = trim($arrivalAirport);
        $this->pilot = trim($pilot);
        $this->status = trim($status);
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

    public function getStatus(): string
    {
        return $this->status;
    }
}