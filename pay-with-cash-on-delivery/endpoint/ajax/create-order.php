<?php
session_start();

$content = trim(file_get_contents("php://input"));
$jsondecoded = json_decode($content, true);

if (!empty($jsondecoded)) {
    $shippingTotal = $jsondecoded["shippingTotal"];
    $totalAmount = $jsondecoded["totalAmount"] + $shippingTotal;
    $name = $jsondecoded["name"];
    $email = $jsondecoded["email"];
    $phone = $jsondecoded["phone"];
    $address = $jsondecoded["address"];
    $message = $jsondecoded["message"];
    $currency = $jsondecoded["currency"];

    $itemArrayDecoded = $jsondecoded["items"];
    $customerDetailsArray = array(
        "name" => filter_var($jsondecoded["name"], FILTER_SANITIZE_STRING),
        "email" => filter_var($jsondecoded["email"], FILTER_SANITIZE_EMAIL),
        "phone" => filter_var($jsondecoded["phone"], FILTER_SANITIZE_STRING),
        "address" => filter_var($jsondecoded["address"], FILTER_SANITIZE_STRING),
        "message" => filter_var($jsondecoded["message"], FILTER_SANITIZE_STRING),
        "currency" => $jsondecoded["currency"]
    );

    $_SESSION["foodboard-cart"] = array(
        "items" => $itemArrayDecoded,
        "customerDetails" => $customerDetailsArray,
        "shippingAmount" => $shippingTotal
    );

    // ================================
    // Kirim WhatsApp Invoice via Wablas
    // ================================
    $cart = $_SESSION['foodboard-cart'];
    $items = $cart['items'];
    $buyerName = $cart['customerDetails']['name'];
    $phoneNumber = $cart['customerDetails']['phone'];
    $address = $cart['customerDetails']['address'];
    $buyerNote = $cart['customerDetails']['message'];
    $ongkir = $cart['shippingAmount'];

    // Format nomor telepon ke internasional
    $phoneIntl = preg_replace('/\D/', '', $phoneNumber);
    if (substr($phoneIntl, 0, 2) !== '62') {
        if (substr($phoneIntl, 0, 1) === '0') {
            $phoneIntl = '62' . substr($phoneIntl, 1);
        } else {
            $phoneIntl = '62' . $phoneIntl;
        }
    }

    
}
