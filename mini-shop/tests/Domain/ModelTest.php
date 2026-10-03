<?php
declare(strict_types=1);
use App\Domain\{Money, Product, CartItem, Cart, OrderItem, Order};
$product = new Product('1', 'Clavier', new Money(7900));
check((new CartItem($product, 2))->total()->cents === 15800, 'Total article');
check((new Cart())->isEmpty(), 'Panier initial');
$order = new Order('1', [new OrderItem('1', 'Clavier', $product->price, 2)], new DateTimeImmutable());
check($order->total->cents === 15800 && $order->status === 'CREATED', 'Commande');
