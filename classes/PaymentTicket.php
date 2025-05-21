<?php

// Clase que representa un ticket de pago generado al abonar exitosamente
class PaymentTicket {
    public string $clientName;
    public float $totalAmount;
    public float $installmentAmount;

    public function __construct(string $clientName, float $totalAmount, float $installmentAmount) {
        $this->clientName = $clientName;
        $this->totalAmount = $totalAmount;
        $this->installmentAmount = $installmentAmount;
    }
}
