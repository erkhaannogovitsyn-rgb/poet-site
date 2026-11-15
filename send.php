<?php
require_once __DIR__ . '/db.php';

function subscriptionRedirect(string $status): never
{
    header('Location: index.php?subscription=' . $status, true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Метод не поддерживается.');
}

if (!isset($_POST['privacy_agreed'])) {
    subscriptionRedirect('error');
}

$method = (string)($_POST['contact_method'] ?? '');
$contact = trim((string)($_POST[$method] ?? ''));
if (!in_array($method, ['email', 'telegram', 'max'], true) || $contact === '' || strlen($contact) > 254) {
    subscriptionRedirect('error');
}
if ($method === 'email' && !filter_var($contact, FILTER_VALIDATE_EMAIL)) {
    subscriptionRedirect('error');
}

try {
    $stmt = db()->prepare(
        'INSERT INTO leads (method, contact) VALUES (:method, :contact)'
    );
    $stmt->execute(['method' => $method, 'contact' => $contact]);
    subscriptionRedirect('success');
} catch (Throwable $e) {
    error_log('Subscription save failed: ' . $e->getMessage());
    subscriptionRedirect('error');
}
