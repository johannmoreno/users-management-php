<?php

class InvalidFlightTextException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty($field)
    {
        return new self('El campo ' . $field . ' del vuelo no puede estar vacío.');
    }

    public static function becauseLengthIsTooLong($field, $maxLength)
    {
        return new self(
            'El campo ' . $field . ' del vuelo no puede superar ' . $maxLength . ' caracteres.'
        );
    }
}