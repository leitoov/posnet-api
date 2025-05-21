<?php
require_once 'Client.php';

// Clase que representa una tarjeta de crédito
class Card {
    private string $type;
    private string $bank;
    private string $number;
    private float $limit;
    private Client $client;

    public function __construct(string $type, string $bank, string $number, float $limit, Client $client) {
        // Validacion de tipo de tarjeta
        $allowedTypes = ['VISA', 'AMEX'];
        if (!in_array(strtoupper($type), $allowedTypes)) {
            throw new Exception("Tipo de tarjeta no válida. Solo se permite VISA o AMEX.");
        }

        // Validacion de numero de tarjeta: 8 dígitos
        if (!preg_match('/^\d{8}$/', $number)) {
            throw new Exception("Número de tarjeta inválido. Debe tener exactamente 8 dígitos.");
        }

        $this->type = strtoupper($type);
        $this->bank = $bank;
        $this->number = $number;
        $this->limit = $limit;
        $this->client = $client;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getBank(): string {
        return $this->bank;
    }

    public function getNumber(): string {
        return $this->number;
    }

    public function getLimit(): float {
        return $this->limit;
    }

    public function getClient(): Client {
        return $this->client;
    }

    // Resta un monto al límite disponible
    public function reduceLimit(float $amount): void {
        $this->limit -= $amount;
    }

    // Convierte los datos de la tarjeta a un array (para guardar en JSON)
    public function toArray(): array {
        return [
            'type' => $this->type,
            'bank' => $this->bank,
            'number' => $this->number,
            'limit' => $this->limit,
            'client' => [
                'dni' => $this->client->getDni(),
                'firstName' => $this->client->getFirstName(),
                'lastName' => $this->client->getLastName(),
            ],
        ];
    }
}
