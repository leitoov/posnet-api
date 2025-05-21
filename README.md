# API POSNET en PHP (Puro)

Este proyecto es una simulación de una API POSNET que procesa pagos con tarjetas de crédito, desarrollada en **PHP puro**, siguiendo programación orientada a objetos y buenas prácticas. Fue desarrollada como solución técnica a un desafío.

---

## Funcionalidades

- Registrar tarjetas de crédito (solo VISA o AMEX)
- Procesar pagos en 1 a 6 cuotas
- Calcular recargo del 3% por cada cuota adicional a la primera
- Validación completa:
  - Tipo de tarjeta
  - Número (8 dígitos)
  - Límite disponible
  - Cuotas válidas (1 a 6)
- Respuestas en formato JSON
- Excepciones controladas
- Compatible con `curl` y Postman

---

## Estructura

posnet-api/
│
├── index.php # Entrada principal simulando API
├── classes/
│ ├── Client.php # Titular de la tarjeta
│ ├── Card.php # Tarjeta de crédito
│ ├── Posnet.php # Lógica principal: registrar y pagar
│ └── PaymentTicket.php # Ticket generado al abonar
│
├── storage/
│ └── cards.json # Simula base de datos de tarjetas
│
└── tests/
└── PosnetTest.php # Test manual opcional

yaml
Copiar
Editar

---

## Cómo correr el proyecto

### Requisitos

- PHP 8.x instalado (y en el PATH)
- Terminal (`cmd`, PowerShell, Git Bash, etc.)

### Ejecutar servidor

Desde la raíz del proyecto:

php -S localhost:8000
Cómo probar con curl
1. Registrar tarjeta

curl.exe -X POST http://localhost:8000/index.php -H "Content-Type: application/json" -d "{ \"action\": \"registerCard\", \"dni\": \"12345678\", \"firstName\": \"Juan\", \"lastName\": \"Perez\", \"type\": \"VISA\", \"bank\": \"Banco Nacion\", \"number\": \"87654321\", \"limit\": 50000 }"
2. Realizar pago (ej. 5 cuotas)

curl.exe -X POST http://localhost:8000/index.php -H "Content-Type: application/json" -d "{ \"action\": \"doPayment\", \"cardNumber\": \"87654321\", \"amount\": 10000, \"installments\": 5 }"
Ejemplo de respuesta

{
  "status": "ok",
  "client": "Juan Perez",
  "total": 11200,
  "installment": 2240
}
