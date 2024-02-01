<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    die("<h2>Access Denied!</h2>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate honeypot field
    if (!empty($_POST['<CUSTOM_HONEYPOT_FIELD>'])) {
        // Honeypot field is not empty, indicating a possible bot submission
        // You can log this or take other appropriate actions
        echo json_encode(['success' => false, 'message' => 'Error: Challenge response not present. Form not submitted.']);
        exit;
    }

    // Honeypot field is empty, proceed with form processing

    // Process other form fields
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Example: Validate Turnstile captcha
    $captcha = $_POST['cf-turnstile-response'];

    if (!$captcha) {
        // What happens when the CAPTCHA was entered incorrectly
        echo json_encode(['success' => false, 'message' => 'Please check the captcha form.']);
        exit;
    }

    $secretKey = "<SECRET_KEY>";
    $ip = $_SERVER['REMOTE_ADDR'];

    $url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $captcha,
        'remoteip' => $ip,
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $stream = stream_context_create($options);
    $result = file_get_contents($url_path, false, $stream);

    $response = $result;
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo json_encode(['success' => false, 'message' => 'Captcha verification failed. Please try again.']);
        exit;
    }

    // Example: Send data to Formspark
    $url = '<FORMSPARK_FORM_ID>';
    $data = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        // Add any additional fields you want to include
    ];

    // Logging the request data
    //file_put_contents('formspark_log.txt', date('Y-m-d H:i:s') . ' - Request Data: ' . print_r($data, true) . PHP_EOL, FILE_APPEND);

    $options = [
        'http' => [
            'header' => [
                "Content-type: application/x-www-form-urlencoded",
            ],
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    // Handle result from Formspark
    if ($result === FALSE) {
        // Log the error message
        error_log("Formspark submission failed: " . error_get_last()['message']);
        echo json_encode(['success' => false, 'message' => 'Formspark submission failed. Please try again.']);
    } else {
        // Log the success message
        error_log("Formspark submission successful: " . $result);
        echo json_encode(['success' => true, 'message' => 'Your form was submitted successfully!']);
    }
} else {
    // Form not submitted via POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
