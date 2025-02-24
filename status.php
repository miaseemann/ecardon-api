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
    
    $postData = [
        "createRegistration" => "true",
        "customParameters[3DS2_enrolled]" => "true",
        "customParameters[3DS2_flow]" => "challenge",
        "testMode" => "EXTERNAL",
        "amount" => "11.12",
        "currency" => "EUR",
        "paymentType" => "DB",
        "standingInstruction.mode" => "INITIAL",
        "standingInstruction.source" => "CIT",
        "standingInstruction.type" => "UNSCHEDULED",
        "customer.givenName" => "Smith",
        "customer.surname" => "John",
        "customer.language" => "DE",
        "customer.email" => "john.smith@gmail.com",
        "customer.ip" => "192.168.0.0",
        "billing.city" => "MyCity",
        "billing.country" => "DE",
        "billing.postcode" => "712121",
        "billing.state" => "DE",
        "billing.street1" => "MyStreet"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
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
