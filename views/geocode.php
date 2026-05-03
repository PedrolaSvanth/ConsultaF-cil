<?php
header('Content-Type: application/json; charset=utf-8');

// Verifica se veio o parâmetro q
if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    echo json_encode(['error' => 'Parâmetro q (endereço) é obrigatório.']);
    exit;
}

$valorBruto = trim($_GET['q']);

// Monta os parâmetros para o Nominatim
// Usamos SEMPRE 'q', sem postalcode.
// Se você mandar "Quadra SCLRN 705 Bloco F, Asa Norte, Brasília - DF, Brasil"
// ou "70730556 Brasil", o Nominatim faz o melhor que ele consegue.
$params = [
    'q'              => $valorBruto,
    'format'         => 'json',
    'addressdetails' => 1,
    'limit'          => 1
];

$url = 'https://nominatim.openstreetmap.org/search?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => [
        // User-Agent OBRIGATÓRIO pela política do Nominatim
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

// Se a API não retornou 200, repassa o erro para o front
if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode([
        'error'     => 'http_status',
        'http_code' => $httpCode,
        'body'      => $resposta
    ]);
    exit;
}

// OK – repassa o JSON original (normalmente um array)
echo $resposta;
