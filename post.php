<?php
// simple-api/index.php
// URL: http://localhost/simple-api  (POST only)

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');          // let anything test it
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);                       // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are accepted.']);
    exit;
}

/*-------------------------------------------------
| 1. Read raw JSON and decode it
|------------------------------------------------*/
$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

if (!is_array($data) || !isset($data['name'], $data['job'])) {
    http_response_code(400);                       // Bad Request
    echo json_encode(['error' => 'Expected JSON with "name" and "job".']);
    exit;
}

/*-------------------------------------------------
| 2. Build the response
|------------------------------------------------*/
$id         = str_pad(random_int(1, 99), 2, '0', STR_PAD_LEFT);   // e.g. "03"
$createdAt  = gmdate('Y-m-d\TH:i:s\Z');                           // ISO‑8601 in GMT

$response = [
    'name'      => $data['name'],
    'job'       => $data['job'],
    'id'        => $id,
    'createdAt' => $createdAt
];

/*-------------------------------------------------
| 3. Return JSON
|------------------------------------------------*/
echo json_encode($response, JSON_UNESCAPED_SLASHES);
