<?php
declare(strict_types=1);
use App\Application\{ListProducts, GetProduct, AddProductToCart, UpdateCartQuantity, RemoveProductFromCart, ViewCart, CreateOrder, ListOrders, GetOrder, NotFound};
use App\Adapter\Out\InMemory\{InMemoryProductRepository, InMemoryCartRepository, InMemoryOrderRepository};
$products = new InMemoryProductRepository();
$carts = new InMemoryCartRepository();
$orders = new InMemoryOrderRepository();
check(count((new ListProducts($products))->execute()) === 4, 'Liste produits');
check((new GetProduct($products))->execute('1')->name === 'Clavier mécanique', 'Détail produit');
try { (new GetProduct($products))->execute('absent'); throw new RuntimeException('404 attendu'); } catch (NotFound $e) {}
$add = new AddProductToCart($products, $carts);
$add->execute('1', 2);
$add->execute('2');
(new UpdateCartQuantity($carts))->execute('1', 3);
check((new ViewCart($carts))->execute()->total()->cents === 27600, 'Total après modification');
rejects(fn () => $add->execute('1', 0));
check($carts->get()->total()->cents === 27600, 'Panier intact après erreur');
$order = (new CreateOrder($carts, $orders))->execute();
check($order->total->cents === 27600 && $carts->get()->isEmpty(), 'Commande et vidage');
check(count((new ListOrders($orders))->execute()) === 1, 'Liste commandes');
check((new GetOrder($orders))->execute($order->id) === $order, 'Détail commande');
$add->execute('1');
(new RemoveProductFromCart($carts))->execute('1');
rejects(fn () => (new CreateOrder($carts, $orders))->execute());
check($order->items[0]->quantity === 3 && $order->total->cents === 27600, 'Commande figée');
