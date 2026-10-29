<?php

class InvalidFlightIdException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self('El id del vuelo no puede estar vacío.');
    }
}