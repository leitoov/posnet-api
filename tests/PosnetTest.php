<?php
require_once '../classes/Client.php';
require_once '../classes/Card.php';
require_once '../classes/Posnet.php';

echo "Iniciando test...\n";

$posnet = new Posnet();
$client = new Client("11111111", "Test", "User");
$card = new Card("AMEX", "Banco Test", "12345678", 15000, $client);

$posnet->registerCard($card);

try {
    $ticket = $posnet->doPayment("12345678", 5000, 3);
    echo "✅ Test OK. Ticket generado:\n";
    echo "Cliente: {$ticket->clientName}\n";
    echo "Total: {$ticket->totalAmount}\n";
    echo "Cuota: {$ticket->installmentAmount}\n";
} catch (Exception $e) {
    echo "Test fallido: " . $e->getMessage() . "\n";
}
