<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Commands/UpdateFlightCommand.php';
require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface UpdateFlightUseCase
{
    public function execute(UpdateFlightCommand $command): FlightModel;
}