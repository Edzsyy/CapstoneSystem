<?php
// Include Composer autoload file
require_once '../vendor/autoload.php';

// Import necessary classes from the Endroid QR Code library
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve the email from the form submission
        if (!isset($_POST['email'])) {
            throw new Exception(message: 'Missing email.');
        }

        $email = $_POST['email'];

        // Create a QR Code with the email as data
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: "User's email: $email", // Data to embed in the QR code (email)
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 500,  // Size of the QR code in pixels
            margin: 10,  // Margin around the QR code
        );

        // Build the QR code
        $result = $builder->build();

        // Convert the QR code to a base64-encoded PNG
        $qr_code_base64 = $result->getDataUri();

        // Respond with the QR code in base64 format (so we can embed it directly into an <img> tag)
        echo json_encode([
            'success' => true,
            'qr_code_base64' => $qr_code_base64,
        ]);
    } catch (Exception $e) {
        // Handle any errors
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}