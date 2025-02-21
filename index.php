<?php
// Funktion, um `payment.php` intern per cURL aufzurufen
function getCheckoutId() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/payment.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['checkoutId'] ?? null;
}

// Abrufen der Checkout-ID
$checkoutId = getCheckoutId();
if (!$checkoutId) {
    die("Fehler beim Abrufen der Checkout-ID");
}

// Liste aller unterstützten Zahlungsmarken (getrennt durch Leerzeichen)
$brands = implode(" ", [
      "MASTER", "SOFORTUEBERWEISUNG", "TRUSTPAY_VA" , "AMEX", "ALIA", "DISCOVER", "CARTEBANCAIRE", "SEPA"  , "BITCOIN", "RATENKAUF"
]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlung</title>
    <!-- Einbindung des Payment Widgets -->
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkoutId; ?>"></script>
</head>
<body>

    <h2>Zahlung durchführen</h2>
    <form action="status.php" class="paymentWidgets" data-brands="<?= $brands; ?>"></form>

</body>
</html>
