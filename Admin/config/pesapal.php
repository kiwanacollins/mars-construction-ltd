<?php
// PesaPal API v3 integration helpers.
// Docs: https://developer.pesapal.com/how-to-integrate/api-30-json/api-reference

function pesapal_settings($pdo) {
    $settings = [];
    foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
        $settings[$row['key']] = $row['value'];
    }
    return [
        'consumer_key' => $settings['pesapal_consumer_key'] ?? '',
        'consumer_secret' => $settings['pesapal_consumer_secret'] ?? '',
        'environment' => $settings['pesapal_environment'] ?? 'sandbox',
        'ipn_id' => $settings['pesapal_ipn_id'] ?? '',
    ];
}

function pesapal_base_url($settings) {
    return ($settings['environment'] ?? 'sandbox') === 'live'
        ? 'https://pay.pesapal.com/v3'
        : 'https://cybqa.pesapal.com/pesapalv3';
}

function pesapal_is_configured($settings) {
    return !empty($settings['consumer_key']) && !empty($settings['consumer_secret']);
}

function pesapal_request($url, $method = 'GET', $body = null, $token = null) {
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => $error];
    }
    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return ['error' => 'Invalid response from PesaPal', 'raw' => $response];
    }
    $decoded['_http_code'] = $http_code;
    return $decoded;
}

function pesapal_get_token($settings) {
    if (!pesapal_is_configured($settings)) {
        return ['error' => 'PesaPal is not configured yet.'];
    }
    $result = pesapal_request(pesapal_base_url($settings) . '/api/Auth/RequestToken', 'POST', [
        'consumer_key' => $settings['consumer_key'],
        'consumer_secret' => $settings['consumer_secret'],
    ]);
    if (!empty($result['token'])) {
        return ['token' => $result['token']];
    }
    return ['error' => $result['error'] ?? ($result['message'] ?? 'Could not authenticate with PesaPal.')];
}

function pesapal_register_ipn($settings, $token, $ipn_url) {
    $result = pesapal_request(pesapal_base_url($settings) . '/api/URLSetup/RegisterIPN', 'POST', [
        'url' => $ipn_url,
        'ipn_notification_type' => 'GET',
    ], $token);
    if (!empty($result['ipn_id'])) {
        return ['ipn_id' => $result['ipn_id']];
    }
    return ['error' => $result['error'] ?? ($result['message'] ?? 'Could not register IPN URL with PesaPal.')];
}

function pesapal_submit_order($settings, $token, $order) {
    // $order: id, amount, currency, description, callback_url, notification_id,
    //         name, email, phone
    $name_parts = explode(' ', trim($order['name']), 2);
    $payload = [
        'id' => $order['merchant_reference'],
        'currency' => $order['currency'],
        'amount' => $order['amount'],
        'description' => $order['description'],
        'callback_url' => $order['callback_url'],
        'notification_id' => $settings['ipn_id'],
        'billing_address' => [
            'email_address' => $order['email'],
            'phone_number' => $order['phone'],
            'first_name' => $name_parts[0] ?? '',
            'last_name' => $name_parts[1] ?? '',
        ],
    ];
    $result = pesapal_request(pesapal_base_url($settings) . '/api/Transactions/SubmitOrderRequest', 'POST', $payload, $token);
    if (!empty($result['redirect_url'])) {
        return ['redirect_url' => $result['redirect_url'], 'order_tracking_id' => $result['order_tracking_id'] ?? null];
    }
    return ['error' => $result['error']['message'] ?? ($result['message'] ?? 'Could not create the PesaPal payment request.')];
}

function pesapal_get_transaction_status($settings, $token, $order_tracking_id) {
    $result = pesapal_request(
        pesapal_base_url($settings) . '/api/Transactions/GetTransactionStatus?orderTrackingId=' . urlencode($order_tracking_id),
        'GET', null, $token
    );
    if (isset($result['payment_status_description'])) {
        return [
            'status' => strtolower($result['payment_status_description']), // completed/failed/pending/invalid
            'confirmation_code' => $result['confirmation_code'] ?? null,
            'payment_method' => $result['payment_method'] ?? null,
        ];
    }
    return ['error' => $result['error']['message'] ?? ($result['message'] ?? 'Could not fetch PesaPal transaction status.')];
}
