<?php
// Konfiguration
$entityId = "8a82941852cad0530152cfa454bb0a42"; // Entity-ID aus deinem Request
$accessToken = "OGE4Mjk0MTg1MmNhZDA1MzAxNTJjZmE0NTZjMjBhOGN8cHE1QG5VOXJ6WEUzRXloNXFCRzo="; // Access-Token
$amount = "92.00"; // Betrag
$currency = "EUR"; // Währung

// Daten für die Anfrage
$data = [
    'entityId' => $entityId,
    'amount' => $amount,
    'currency' => $currency,
    'paymentType' => 'DB', // 'DB' für direkte Belastung
    'integrity' => 'true' // Falls die API dies benötigt
];

// cURL-Anfrage vorbereiten
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://eu-test.oppwa.com/v1/checkouts");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Anfrage ausführen
$response = curl_exec($ch);
if ($response === false) {
    die(json_encode(['error' => curl_error($ch)]));
}
curl_close($ch);

// Antwort verarbeiten
$responseData = json_decode($response, true);
if (isset($responseData['id'])) {
    echo json_encode(['checkoutId' => $responseData['id']]);
} else {
    echo json_encode(['error' => 'Fehler beim Erstellen der Checkout-ID']);
}
