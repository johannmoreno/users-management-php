<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Commands/CreateFlightCommand.php';
require_once __DIR__ . '/../../../Domain/Models/FlightModel.php';

interface CreateFlightUseCase
{
    public function execute(CreateFlightCommand $command): FlightModel;
}