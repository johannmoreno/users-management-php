<?php

class FlightNotFoundException extends DomainException
{
    public static function becauseIdWasNotFound($id)
    {
        return new self('No se encontró el vuelo con id ' . $id . '.');
    }
}