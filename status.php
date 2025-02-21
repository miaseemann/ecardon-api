<?php
// Abrufen der Checkout-ID aus `payment.php`
$paymentResponse = file_get_contents($_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'payment.php');
$paymentData = json_decode($paymentResponse, true);

if (!isset($_GET['id'])) {
    die("Fehler beim Abrufen der Checkout-ID");
}

$checkoutId = $$_Get['id'];
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
    <form action="status.php" class="paymentWidgets" data-brands="VISA MASTER"></form>

</body>
</html>
