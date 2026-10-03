<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
use App\Domain\{Product, Money, Cart, Order, OrderItem};
$view = new View(dirname(__DIR__, 2) . '/templates');
$product = new Product('1', '<script>alert(1)</script>', new Money(100));
$response = $view->render('products/show', ['product' => $product]);
check(str_contains($response->body, '&lt;script&gt;') && !str_contains($response->body, '<script>'), 'Échappement HTML');
$cart = new Cart(); $cart->addProduct($product, 2);
$order = new Order('1', [new OrderItem('1', $product->name, $product->price, 2)], new DateTimeImmutable());
foreach (['products/index' => ['products' => [$product]], 'cart/index' => ['cart' => $cart], 'orders/index' => ['orders' => [$order]], 'orders/show' => ['order' => $order]] as $template => $data) {
    check($view->render($template, $data)->status === 200, 'Rendu ' . $template);
}
