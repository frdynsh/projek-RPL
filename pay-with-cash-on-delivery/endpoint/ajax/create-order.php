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
    $buyerName = $customerDetailsArray['name'];
    $phoneNumber = $customerDetailsArray['phone'];
    $address = $customerDetailsArray['address'];
    $ongkir = $cart['shippingAmount'];

    // Format nomor telepon ke 62
    $phoneIntl = preg_replace('/\D/', '', $phoneNumber);
    if (substr($phoneIntl, 0, 2) !== '62') {
        if (substr($phoneIntl, 0, 1) === '0') {
            $phoneIntl = '62' . substr($phoneIntl, 1);
        } else {
            $phoneIntl = '62' . $phoneIntl;
        }
    }

    // Buat isi pesan invoice
    $waMessage  = "🧾 *Invoice Pemesanan Anda*\n\n";
    $waMessage .= "👤 Nama: *$buyerName*\n";
    $waMessage .= "📍 Alamat: $address\n\n";
    $waMessage .= "📦 *Detail Pesanan:*\n";
    $subtotal = 0;
    foreach ($items as $item) {
        $productName = $item['name'];
        $qty = $item['qty'];
        $price = $item['price'];
        $lineTotal = $qty * $price;
        $subtotal += $lineTotal;
        $waMessage .= "- $productName (x$qty) = Rp " . number_format($lineTotal, 0, ',', '.') . "\n";
    }
    $waMessage .= "\n💸 *Subtotal:* Rp " . number_format($subtotal, 0, ',', '.');
    $waMessage .= "\n🚚 *Ongkir:* Rp " . number_format($ongkir, 0, ',', '.');
    $waMessage .= "\n🧾 *Total:* Rp " . number_format($subtotal + $ongkir, 0, ',', '.') . "\n\n";
    $waMessage .= "🙏 Terima kasih telah berbelanja di toko kami.";

    // Kirim lewat API Wablas
    $payload = [
        "data" => [
            [
                "phone" => $phoneIntl,
                "message" => $waMessage
            ]
        ]
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://kirim.pesan.biz.id/api/v2/send-message");
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: elGq6IWYpO5LeMxO1iuhz7lZa1IifJzgqqA9f5O8bH1xb8hrh4yyTEy"
    ]);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    // Simpan log untuk debugging (opsional)
    file_put_contents('wablas-log.txt', $response . PHP_EOL, FILE_APPEND);
}