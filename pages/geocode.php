<?php
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    echo json_encode(['error' => 'Parâmetro q (endereço) é obrigatório.']);
    exit;
}

$endereco = trim($_GET['q']);

$url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
    'q'              => $endereco,
    'format'         => 'json',
    'addressdetails' => 1,
    'limit'          => 1,
    'countrycodes'   => 'br'   // 🔒 Só resultados do Brasil
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => [
        'User-Agent: ConsultaFacil/1.0 (contato: seu-email@exemplo.com)'
    ]
]);

$resposta = curl_exec($ch);

if ($resposta === false) {
    $erro = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo json_encode([
        'error'   => 'curl_error',
        'message' => $erro
    ]);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode([
        'error'     => 'http_status',
        'http_code' => $httpCode,
        'body'      => $resposta
    ]);
    exit;
}

echo $resposta;
