<?php
require_once 'classes/Client.php';
require_once 'classes/Card.php';
require_once 'classes/Posnet.php';
require_once 'classes/PaymentTicket.php';

header('Content-Type: application/json');

$posnet = new Posnet();

// Leemos el JSON del cuerpo de la petición POST
$input = json_decode(file_get_contents('php://input'), true);

// Verificamos si se recibio el campo "action"
if (!isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere un parámetro "action" (registerCard o doPayment).']);
    exit;
}

try {
    switch ($input['action']) {

        case 'registerCard':
            // Validacion minima
            if (!isset($input['dni'], $input['firstName'], $input['lastName'], $input['type'], $input['bank'], $input['number'], $input['limit'])) {
                throw new Exception("Faltan datos para registrar la tarjeta.");
            }

            $client = new Client($input['dni'], $input['firstName'], $input['lastName']);
            $card = new Card($input['type'], $input['bank'], $input['number'], $input['limit'], $client);
            $posnet->registerCard($card);

            echo json_encode(['status' => 'ok', 'message' => 'Tarjeta registrada correctamente.']);
            break;

        case 'doPayment':
            if (!isset($input['cardNumber'], $input['amount'], $input['installments'])) {
                throw new Exception("Faltan datos para procesar el pago.");
            }

            $ticket = $posnet->doPayment($input['cardNumber'], $input['amount'], $input['installments']);

            echo json_encode([
                'status' => 'ok',
                'client' => $ticket->clientName,
                'total' => $ticket->totalAmount,
                'installment' => $ticket->installmentAmount
            ]);
            break;

        default:
            throw new Exception("Acción '{$input['action']}' no válida.");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
