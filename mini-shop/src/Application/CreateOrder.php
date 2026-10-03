<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\{CartRepository, OrderRepository};
use App\Domain\{Order, OrderItem};
final readonly class CreateOrder
{
    public function __construct(private CartRepository $carts, private OrderRepository $orders) {}
    public function execute(): Order
    {
        $cart = $this->carts->get();
        if ($cart->isEmpty()) { throw new \DomainException('Panier vide.'); }
        $items = [];
        foreach ($cart->items() as $item) {
            $items[] = new OrderItem($item->product->id, $item->product->name, $item->product->price, $item->quantity);
        }
        $order = new Order($this->orders->nextIdentity(), $items, new \DateTimeImmutable());
        $this->orders->save($order);
        $cart->clear();
        $this->carts->save($cart);
        return $order;
    }
}
