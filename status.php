<?php
// Konfiguration
$entityId = "8a82941852cad0530152cfa454bb0a42"; // Entity-ID
$accessToken = "OGE4Mjk0MTg1MmNhZDA1MzAxNTJjZmE0NTZjMjBhOGN8cHE1QG5VOXJ6WEUzRXloNXFCRzo="; // Access-Token

// Überprüfen, ob die `id`-GET-Variable gesetzt ist
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die(json_encode(["error" => "Keine Checkout-ID angegeben."]));
}

$checkoutId = $_GET['id'];

/**
 * Sendet eine Anfrage an die eCardOn API, um den Zahlungsstatus abzurufen
 */
function request($checkoutId, $entityId, $accessToken) {
    $url = "https://eu-test.oppwa.com/v1/checkouts/{$checkoutId}/payment";
    $url .= "?entityId=" . $entityId;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $accessToken
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // In Produktion auf true setzen!
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return json_encode(["error" => curl_error($ch)]);
    }
    curl_close($ch);
    
    return $responseData;
}

// Anfrage ausführen und den Zahlungsstatus abrufen
$responseData = request($checkoutId, $entityId, $accessToken);

// Antwort als JSON ausgeben
header('Content-Type: application/json');
echo $responseData;
