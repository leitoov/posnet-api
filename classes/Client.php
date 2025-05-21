<?php

// Clase que representa a un cliente o titular de una tarjeta
class Client {
    private string $dni;
    private string $firstName;
    private string $lastName;

    public function __construct(string $dni, string $firstName, string $lastName) {
        // Validación de DNI: 7 u 8 dígitos
        if (!preg_match('/^\d{7,8}$/', $dni)) {
            throw new Exception("DNI inválido.");
        }

        $this->dni = $dni;
        $this->firstName = ucfirst(trim($firstName));
        $this->lastName = ucfirst(trim($lastName));
    }

    // Métodos para acceder a los atributos (getters)
    public function getDni(): string {
        return $this->dni;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getFullName(): string {
        return "{$this->firstName} {$this->lastName}";
    }
}
