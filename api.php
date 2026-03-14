<?php
/**
 * AutoDoc — Secure AI Proxy
 * -----------------------------------------
 * Sits between the browser and Groq (or any
 * OpenAI-compatible endpoint) so the API key
 * never leaves the server.
 *
 * Setup:
 *   1. Set GROQ_API_KEY below (or in an .env file).
 *   2. Upload to the same directory as index.html.
 *   3. Requires PHP 7.4+ with allow_url_fopen = On
 *      (or cURL, which is used here).
 */

// ── CONFIG ─────────────────────────────────────────────────────────
define('GROQ_API_KEY',  'YOUR_GROQ_API_KEY_HERE');   // 🔑 Replace this
define('GROQ_MODEL',    'qwen-qwq-32b');              // Qwen2.5-32B via Groq
define('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions');
define('MAX_TOKENS',    4096);

// Allowed origin — set to your domain in production, e.g. 'https://yourdomain.com'
define('ALLOWED_ORIGIN', '*');
// ───────────────────────────────────────────────────────────────────

/* ── CORS headers ── */
header('Access-Control-Allow-Origin: '  . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => ['message' => 'Method not allowed. Use POST.']]);
    exit;
}

/* ── Read & validate request body ── */
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

if (
    !$body ||
    !isset($body['messages']) ||
    !is_array($body['messages']) ||
    empty($body['messages'])
) {
    http_response_code(400);
    echo json_encode(['error' => ['message' => 'Invalid request: messages array is required.']]);
    exit;
}

// Basic sanity-check each message
foreach ($body['messages'] as $msg) {
    if (!isset($msg['role'], $msg['content'])) {
        http_response_code(400);
        echo json_encode(['error' => ['message' => 'Each message must have role and content.']]);
        exit;
    }
}

/* ── Build Groq payload ── */
$payload = [
    'model'       => GROQ_MODEL,
    'messages'    => $body['messages'],
    'max_tokens'  => MAX_TOKENS,
    'temperature' => 0.6,
];

/* ── Call Groq via cURL ── */
$ch = curl_init(GROQ_ENDPOINT);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY,
    ],
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response   = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError  = curl_error($ch);
curl_close($ch);

/* ── Handle cURL failure ── */
if ($curlError) {
    http_response_code(502);
    echo json_encode(['error' => ['message' => 'Could not connect to AI service: ' . $curlError]]);
    exit;
}

/* ── Relay Groq response verbatim ── */
http_response_code($httpStatus);
echo $response;
