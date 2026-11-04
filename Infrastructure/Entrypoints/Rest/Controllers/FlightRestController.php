<?php

declare(strict_types=1);

require_once __DIR__ . '/../ApiException.php';
require_once __DIR__ . '/../../Web/Controllers/FlightController.php';

/**
 * Adaptador REST de los vuelos. Recibe arreglos ya decodificados desde JSON,
 * revisa que lleguen los campos obligatorios y delega en FlightController,
 * que a su vez usa los casos de uso. Las reglas de negocio las valida el dominio.
 */
final class FlightRestController
{
    private const REQUIRED_FIELDS = array(
        'flight_number' => 'El número de vuelo',
        'airline' => 'La aerolínea',
        'travel_agency' => 'La agencia de viajes',
        'customer' => 'El cliente',
        'seat' => 'El puesto',
        'aircraft' => 'El avión',
        'pilot' => 'El piloto',
        'departure_airport' => 'El aeropuerto de salida',
        'arrival_airport' => 'El aeropuerto de llegada',
        'purchase_date' => 'La fecha de compra',
        'departure_date' => 'La fecha de salida',
        'arrival_date' => 'La fecha de llegada',
        'price' => 'El valor',
    );

    private FlightController $flightController;

    public function __construct(FlightController $flightController)
    {
        $this->flightController = $flightController;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function index(): array
    {
        $flights = array();

        foreach ($this->flightController->index() as $flight) {
            $flights[] = $flight->toArray();
        }

        return $flights;
    }

    public function show(string $id): FlightResponse
    {
        return $this->flightController->show($id);
    }

    /**
     * @param array<string, mixed> $body
     */
    public function store(array $body): FlightResponse
    {
        $this->assertValid($body, false);

        return $this->flightController->store(new CreateFlightWebRequest(
            self::generateUuid4(),
            $this->text($body, 'purchase_date'),
            $this->text($body, 'departure_date'),
            $this->text($body, 'arrival_date'),
            $this->text($body, 'travel_agency'),
            $this->text($body, 'airline'),
            $this->text($body, 'flight_number'),
            $this->text($body, 'price'),
            $this->text($body, 'customer'),
            $this->text($body, 'seat'),
            $this->text($body, 'aircraft'),
            $this->text($body, 'departure_airport'),
            $this->text($body, 'arrival_airport'),
            $this->text($body, 'pilot')
        ));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function update(string $id, array $body): FlightResponse
    {
        $this->assertValid($body, true);

        return $this->flightController->update(new UpdateFlightWebRequest(
            $id,
            $this->text($body, 'purchase_date'),
            $this->text($body, 'departure_date'),
            $this->text($body, 'arrival_date'),
            $this->text($body, 'travel_agency'),
            $this->text($body, 'airline'),
            $this->text($body, 'flight_number'),
            $this->text($body, 'price'),
            $this->text($body, 'customer'),
            $this->text($body, 'seat'),
            $this->text($body, 'aircraft'),
            $this->text($body, 'departure_airport'),
            $this->text($body, 'arrival_airport'),
            $this->text($body, 'pilot'),
            $this->text($body, 'status')
        ));
    }

    public function delete(string $id): void
    {
        $this->flightController->delete($id);
    }

    /**
     * @param array<string, mixed> $body
     */
    private function assertValid(array $body, bool $requireStatus): void
    {
        $fields = self::REQUIRED_FIELDS;

        if ($requireStatus) {
            $fields['status'] = 'El estado';
        }

        $errors = array();

        foreach ($fields as $field => $label) {
            if (!array_key_exists($field, $body) || $body[$field] === null) {
                $errors[$field] = $label . ' es obligatorio.';
                continue;
            }

            if (!is_scalar($body[$field])) {
                $errors[$field] = $label . ' debe ser un texto o un número.';
                continue;
            }

            if (trim((string) $body[$field]) === '') {
                $errors[$field] = $label . ' es obligatorio.';
            }
        }

        if (!isset($errors['price']) && !is_numeric((string) $body['price'])) {
            $errors['price'] = 'El valor debe ser un número.';
        }

        if ($errors !== array()) {
            throw ApiException::validation('Hay campos faltantes o inválidos.', $errors);
        }
    }

    /**
     * @param array<string, mixed> $body
     */
    private function text(array $body, string $field): string
    {
        return trim((string) $body[$field]);
    }

    private static function generateUuid4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}