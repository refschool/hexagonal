<?php
declare(strict_types=1);

namespace App\Adapter\Out\InMemory;

use App\Domain\Order;
use App\Port\Out\OrderRepository;
final class InMemoryOrderRepository implements OrderRepository
{
    /** @var array<string, Order> */
    private array $orders = [];
    /** @param list<Order> $orders */
    public function __construct(array $orders = [])
    {
        foreach ($orders as $order) { $this->save($order); }
    }
    public function nextIdentity(): string { return bin2hex(random_bytes(8)); }
    public function save(Order $order): void { $this->orders[$order->id] = $order; }
    public function findAll(): array { return array_values($this->orders); }
    public function findById(string $id): ?Order { return $this->orders[$id] ?? null; }
}
