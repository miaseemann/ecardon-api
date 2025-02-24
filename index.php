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
      "MASTER", "SOFORTUEBERWEISUNG", "AMEX", "ALIA", "DISCOVER", "CARTEBANCAIRE", "SEPA"  , "BITCOIN", "VPAY", "PAYDIREKT"
]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
<script src="https://code.jquery.com/jquery.js" type="text/javascript"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlung</title>
    <!-- Einbindung des Payment Widgets -->
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkoutId; ?>"></script>
    <script>
          var wpwlOptions = {
    billingAddress: {
        country: "US",
        state: "NY",
        city: "New York",
        postcode: "12345",
        street1: "Suite 1234",
        street2: "Some Road"
    },
    mandatoryBillingFields:{
        country: true,
        state: true,
        city: true,
        postcode: true,
        street1: true,
        street2: false
    }
}

    </script>
</head>
<body>

    <h2>Zahlung durchführen</h2>
    <form action="status.php" class="paymentWidgets" data-brands="<?= $brands; ?>"></form>

</body>
</html>
