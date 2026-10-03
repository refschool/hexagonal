<?php
declare(strict_types=1);

use App\CompositionRoot;
use App\Adapter\In\Web\SessionState;

require dirname(__DIR__) . '/vendor/autoload.php';

$session = new SessionState();
$session->start();
$app = new CompositionRoot($session->cart(), $session->orders());
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$response = $app->router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', is_string($path) ? $path : '/', $_POST);
$session->save($app->carts, $app->orders);
$response->send();
