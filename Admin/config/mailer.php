<?php
$phpmailer_autoload = __DIR__ . '/../../vendor/autoload.php';
$phpmailer_available = file_exists($phpmailer_autoload);
if ($phpmailer_available) {
    require_once $phpmailer_autoload;
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $phpmailer_available = false;
    }
}

function mail_settings($pdo) {
    $settings = [];
    foreach ($pdo->query('SELECT `key`, `value` FROM site_settings') as $row) {
        $settings[$row['key']] = $row['value'];
    }
    return [
        'enabled' => !empty($settings['smtp_enabled']),
        'host' => $settings['smtp_host'] ?? '',
        'port' => $settings['smtp_port'] ?? '587',
        'username' => $settings['smtp_username'] ?? '',
        'password' => $settings['smtp_password'] ?? '',
        'encryption' => $settings['smtp_encryption'] ?? 'tls',
        'from_email' => $settings['smtp_from_email'] ?? '',
        'from_name' => $settings['smtp_from_name'] ?? 'Mars Construction',
    ];
}

/**
 * Sends an email via SMTP if configured, otherwise falls back to PHP's mail().
 * Returns ['success' => bool, 'error' => string|null].
 */
function send_site_email($pdo, $to, $subject, $body, $reply_to = null) {
    global $phpmailer_available;
    $settings = mail_settings($pdo);

    $use_smtp = $settings['enabled'] && $settings['host'] && !empty($phpmailer_available);

    if (!$use_smtp) {
        $headers = "From: {$settings['from_name']} <" . ($settings['from_email'] ?: 'no-reply@marsconstruction.local') . ">\r\n";
        if ($reply_to) {
            $headers .= "Reply-To: {$reply_to}\r\n";
        }
        $sent = @mail($to, $subject, $body, $headers);
        $note = ($settings['enabled'] && $settings['host'] && empty($phpmailer_available))
            ? ' (SMTP is enabled but the PHPMailer library is not installed — run "composer install" on the server.)'
            : '';
        return ['success' => $sent, 'error' => $sent ? null : 'PHP mail() failed to send (SMTP is not configured).' . $note];
    }

    $mail_class = 'PHPMailer\\PHPMailer\\PHPMailer';
    $mail = new $mail_class(true);
    try {
        $mail->isSMTP();
        $mail->Host = $settings['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['username'];
        $mail->Password = $settings['password'];
        $mail->Port = (int) $settings['port'];
        if ($settings['encryption'] === 'ssl') {
            $mail->SMTPSecure = $mail_class::ENCRYPTION_SMTPS;
        } elseif ($settings['encryption'] === 'tls') {
            $mail->SMTPSecure = $mail_class::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPAutoTLS = false;
        }

        $mail->setFrom($settings['from_email'] ?: $settings['username'], $settings['from_name']);
        $mail->addAddress($to);
        if ($reply_to) {
            $mail->addReplyTo($reply_to);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);
        $mail->send();
        return ['success' => true, 'error' => null];
    } catch (\Throwable $e) {
        return ['success' => false, 'error' => $mail->ErrorInfo ?: $e->getMessage()];
    }
}
