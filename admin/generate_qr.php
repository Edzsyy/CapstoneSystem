<?php
require_once '../vendor/autoload.php'; // Composer autoload

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;


header('Content-Type: application/json');

try {
    if (!isset($_POST['application_number'])) {
        throw new Exception('Missing application number.');
    }

    $applicationNumber = $_POST['application_number'];

    // Generate the QR code with the email
    $builder = new Builder(
        writer: new PngWriter(),
        data: $applicationNumber, 
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 600,
        margin: 20,
        labelText: 'Use this QR code to claim your business permit', 
        labelFont: new OpenSans(20),
        labelAlignment: LabelAlignment::Center,
        logoPath: __DIR__.'/assets/image/logo.jpg',
        logoResizeToWidth: 100,
        logoPunchoutBackground: true,
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