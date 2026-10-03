<?php
declare(strict_types=1);
use App\CompositionRoot;
$app = new CompositionRoot();
foreach (['/', '/products', '/products/1', '/cart', '/orders'] as $path) {
    check($app->router->dispatch('GET', $path)->status === 200, 'GET ' . $path);
}
foreach (['/absent', '/products/absent', '/orders/absente'] as $path) {
    check($app->router->dispatch('GET', $path)->status === 404, '404 ' . $path);
}
check($app->router->dispatch('POST', '/orders')->status === 400, 'Commande vide');
check($app->router->dispatch('POST', '/cart/add', ['productId' => '1', 'quantity' => '0'])->status === 400, 'Quantité incorrecte');
check($app->router->dispatch('POST', '/cart/add', ['productId' => 'absent', 'quantity' => '1'])->status === 404, 'Ajout produit absent');
check($app->router->dispatch('POST', '/cart/add', ['productId' => '1', 'quantity' => '2'])->status === 303, 'Ajout');
$app->router->dispatch('POST', '/cart/add', ['productId' => '2', 'quantity' => '1']);
$app->router->dispatch('POST', '/cart/update', ['productId' => '1', 'quantity' => '3']);
check(str_contains($app->router->dispatch('GET', '/cart')->body, '276,00'), 'Total HTML');
$response = $app->router->dispatch('POST', '/orders');
check($response->status === 303 && str_starts_with($response->headers['Location'], '/orders/'), 'Commande redirection');
check(str_contains($app->router->dispatch('GET', '/cart')->body, 'Votre panier est vide'), 'Panier vidé HTML');
check(str_contains($app->router->dispatch('GET', $response->headers['Location'])->body, '276,00'), 'Commande détail HTML');
$restored = new CompositionRoot(unserialize(serialize($app->carts->get())), unserialize(serialize($app->orders->findAll())));
check(count($restored->orders->findAll()) === 1 && $restored->carts->get()->isEmpty(), 'Réhydratation état');
