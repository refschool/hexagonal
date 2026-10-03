<?php
declare(strict_types=1);
$cookie = '';
function request(string $method, string $path, array $data = []): array
{
    global $cookie;
    $context = stream_context_create(['http' => [
        'method' => $method,
        'header' => "Content-Type: application/x-www-form-urlencoded\r\nCookie: $cookie\r\n",
        'content' => http_build_query($data), 'ignore_errors' => true,
        'follow_location' => 0, 'timeout' => 5,
    ]]);
    $body = file_get_contents('http://localhost:8000' . $path, false, $context);
    if ($body === false) { throw new RuntimeException('Démarrer le serveur à localhost:8000.'); }
    $location = null;
    foreach ($http_response_header as $header) {
        if (preg_match('/^Set-Cookie: ([^;]+)/i', $header, $match)) { $cookie = $match[1]; }
        if (str_starts_with($header, 'Location: ')) { $location = substr($header, 10); }
    }
    preg_match('/^HTTP\/\S+ (\d+)/', $http_response_header[0], $match);
    return ['status' => (int) $match[1], 'body' => $body, 'location' => $location];
}
function expect(array $response, int $status, ?string $contains = null): void
{
    if ($response['status'] !== $status || ($contains !== null && !str_contains($response['body'], $contains))) {
        throw new RuntimeException('Réponse HTTP inattendue : ' . json_encode($response));
    }
}
expect(request('GET', '/products'), 200, 'Clavier mécanique');
expect(request('GET', '/products/1'), 200, '79,00');
expect(request('POST', '/cart/add', ['productId' => '1', 'quantity' => '2']), 303);
expect(request('POST', '/cart/add', ['productId' => '2', 'quantity' => '1']), 303);
expect(request('GET', '/cart'), 200, '197,00');
expect(request('POST', '/cart/update', ['productId' => '1', 'quantity' => '3']), 303);
expect(request('GET', '/cart'), 200, '276,00');
expect(request('POST', '/cart/update', ['productId' => '1', 'quantity' => '0']), 400);
expect(request('GET', '/cart'), 200, '276,00');
$response = request('POST', '/orders');
expect($response, 303);
if ($response['location'] === null) { throw new RuntimeException('Redirection absente.'); }
expect(request('GET', '/cart'), 200, 'Votre panier est vide');
expect(request('GET', '/orders'), 200, 'CREATED');
expect(request('GET', $response['location']), 200, '276,00');
expect(request('POST', '/orders'), 400);
expect(request('GET', '/products/absent'), 404);
expect(request('GET', '/orders/absente'), 404);
expect(request('POST', '/cart/add', ['productId' => '4', 'quantity' => '1']), 303);
expect(request('POST', '/cart/remove', ['productId' => '4']), 303);
expect(request('GET', '/cart'), 200, 'Votre panier est vide');
echo "Parcours HTTP complet validé.\n";
