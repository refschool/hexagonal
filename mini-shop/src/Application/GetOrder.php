<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\OrderRepository;
use App\Domain\{Product, Cart, Order};
final readonly class GetOrder
{
    public function __construct(private OrderRepository $repository) {}
    public function execute(string $id): Order
    {
        return $this->repository->findById($id) ?? throw new NotFound('Commande absente.');
    }
}
