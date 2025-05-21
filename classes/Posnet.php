<?php
require_once 'Card.php';
require_once 'PaymentTicket.php';

// Clase que representa al POSNET (terminal de pagos)
class Posnet {
    private array $cards = [];

    public function __construct() {
        $this->loadCards(); // Carga tarjetas guardadas desde el archivo JSON
    }

    // Registrar una nueva tarjeta
    public function registerCard(Card $card): void {
        $this->cards[$card->getNumber()] = $card;
        $this->saveCards();
    }

    // Ejecutar un pago
    public function doPayment(string $cardNumber, float $amount, int $installments): PaymentTicket {
        if (!isset($this->cards[$cardNumber])) {
            throw new Exception("Tarjeta no encontrada.");
        }

        if ($installments < 1 || $installments > 6) {
            throw new Exception("Cantidad de cuotas inválida.");
        }

        $card = $this->cards[$cardNumber];

        // Calculo de interés
        $interestRate = $installments > 1 ? ($installments - 1) * 0.03 : 0;
        $total = $amount * (1 + $interestRate);

        // Verifica si hay saldo suficiente
        if ($card->getLimit() < $total) {
            throw new Exception("Saldo insuficiente en la tarjeta.");
        }

        // Descuenta del límite
        $card->reduceLimit($total);
        $this->cards[$cardNumber] = $card;
        $this->saveCards();

        // Retorna un ticket con la información del pago
        return new PaymentTicket(
            $card->getClient()->getFullName(),
            round($total, 2),
            round($total / $installments, 2)
        );
    }

    // Carga tarjetas desde el archivo JSON
    private function loadCards(): void {
        $path = __DIR__ . '/../storage/cards.json';
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            foreach ($data as $cardData) {
                $client = new Client(
                    $cardData['client']['dni'],
                    $cardData['client']['firstName'],
                    $cardData['client']['lastName']
                );
                $card = new Card(
                    $cardData['type'],
                    $cardData['bank'],
                    $cardData['number'],
                    $cardData['limit'],
                    $client
                );
                $this->cards[$card->getNumber()] = $card;
            }
        }
    }

    // Guarda tarjetas en el archivo JSON
    private function saveCards(): void {
        $data = array_map(fn($card) => $card->toArray(), $this->cards);
        file_put_contents(__DIR__ . '/../storage/cards.json', json_encode($data, JSON_PRETTY_PRINT));
    }
}
