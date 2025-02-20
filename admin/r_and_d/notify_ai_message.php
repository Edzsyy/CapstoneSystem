<?php
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

$geminiApiKey = 'AIzaSyBVfiXsEQltLbGG1xFo_dlnnGKCLKHS_qU';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required data
    if (!isset($data['notify_days']) || !is_numeric($data['notify_days'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing notify_days']);
        exit;
    }

    // Extract input data from the request
    $notifyDays = intval($data['notify_days']);
    $organizationName = isset($data['organization_name']) ? $data['organization_name'] : 'UnifiedLGU';
    $contactNumber = isset($data['contact_number']) ? $data['contact_number'] : '09812345678';
    $organizationAddress = isset($data['organization_address']) ? $data['organization_address'] : 'Unknown Address';
    $websiteLink = isset($data['website_link']) ? $data['website_link'] : 'http://example.com';

    // Construct the AI prompt with the organization details
    $prompt = "Generate a professional notification message informing a business owner that their business permit will expire in $notifyDays days. The message should be clear, formal, and encourage early renewal. Include the following organization details in the message: Organization Name: $organizationName, Contact Number: $contactNumber, Address: $organizationAddress, Website: $websiteLink. The tone should be formal and polite.";

    // Call Gemini AI API to generate the message
    $aiResponse = getGeminiAiMessage($prompt, $geminiApiKey);

    if ($aiResponse['success']) {
        echo json_encode(['success' => true, 'ai_message' => $aiResponse['ai_message']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'AI message generation failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function getGeminiAiMessage($prompt, $apiKey) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

    $data = [
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ]
    ];

    $headers = ['Content-Type: application/json'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'cURL Error: ' . curl_error($ch)];
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        return [
            'success' => true,
            'ai_message' => $responseData['candidates'][0]['content']['parts'][0]['text']
        ];
    } else {
        return ['success' => false, 'message' => 'AI response structure is incorrect'];
    }
}
?>
