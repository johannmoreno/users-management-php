<?php

declare(strict_types=1);

require_once __DIR__ . '/Mapper/FlightWebMapper.php';

require_once __DIR__ . '/../../../../Application/Ports/In/CreateFlightUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/UpdateFlightUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetFlightByIdUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetAllFlightsUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/DeleteFlightUseCase.php';

require_once __DIR__ . '/../../../../Application/Services/Dto/Queries/GetAllFlightsQuery.php';

final class FlightController
{
    private CreateFlightUseCase $createFlightUseCase;
    private UpdateFlightUseCase $updateFlightUseCase;
    private GetFlightByIdUseCase $getFlightByIdUseCase;
    private GetAllFlightsUseCase $getAllFlightsUseCase;
    private DeleteFlightUseCase $deleteFlightUseCase;
    private FlightWebMapper $mapper;

    public function __construct(
        CreateFlightUseCase $createFlightUseCase,
        UpdateFlightUseCase $updateFlightUseCase,
        GetFlightByIdUseCase $getFlightByIdUseCase,
        GetAllFlightsUseCase $getAllFlightsUseCase,
        DeleteFlightUseCase $deleteFlightUseCase,
        FlightWebMapper $mapper
    ) {
        $this->createFlightUseCase = $createFlightUseCase;
        $this->updateFlightUseCase = $updateFlightUseCase;
        $this->getFlightByIdUseCase = $getFlightByIdUseCase;
        $this->getAllFlightsUseCase = $getAllFlightsUseCase;
        $this->deleteFlightUseCase = $deleteFlightUseCase;
        $this->mapper = $mapper;
    }

    /**
     * @return FlightResponse[]
     */
    public function index(): array
    {
        $flights = $this->getAllFlightsUseCase->execute(new GetAllFlightsQuery());

        return $this->mapper->fromModelsToResponses($flights);
    }

    public function show(string $id): FlightResponse
    {
        $query = $this->mapper->fromIdToGetByIdQuery($id);
        $flight = $this->getFlightByIdUseCase->execute($query);

        return $this->mapper->fromModelToResponse($flight);
    }

    public function store(CreateFlightWebRequest $request): FlightResponse
    {
        $command = $this->mapper->fromCreateRequestToCommand($request);
        $flight = $this->createFlightUseCase->execute($command);

        return $this->mapper->fromModelToResponse($flight);
    }

    public function update(UpdateFlightWebRequest $request): FlightResponse
    {
        $command = $this->mapper->fromUpdateRequestToCommand($request);
        $flight = $this->updateFlightUseCase->execute($command);

        return $this->mapper->fromModelToResponse($flight);
    }

    public function delete(string $id): void
    {
        $command = $this->mapper->fromIdToDeleteCommand($id);
        $this->deleteFlightUseCase->execute($command);
    }
}