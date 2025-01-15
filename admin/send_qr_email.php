<?php
require_once '../vendor/autoload.php'; // Composer autoload

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

header('Content-Type: application/json');

try {
    if (!isset($_POST['email'])) {
        throw new Exception('Missing email.');
    }

    $email = $_POST['email'];

    // Generate the QR code with the email
    $builder = new Builder(
        writer: new PngWriter(),
        data: $email, // Use the email address as the data for the QR code
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 300,
        margin: 10
    );

    $result = $builder->build();
    $qrBase64 = $result->getDataUri();

    // Return the QR code as a base64-encoded image
    echo json_encode([
        'success' => true,
        'qr_code_base64' => $qrBase64
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}