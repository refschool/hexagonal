<?php
declare(strict_types=1);

namespace App\Port\Out;

use App\Domain\Order;
interface OrderRepository
{
    public function nextIdentity(): string;
    public function save(Order $order): void;
    /** @return list<Order> */
    public function findAll(): array;
    public function findById(string $id): ?Order;
}
