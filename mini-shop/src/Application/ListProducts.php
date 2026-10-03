<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\ProductRepository;
use App\Domain\{Product, Cart, Order};
final readonly class ListProducts
{
    public function __construct(private ProductRepository $repository) {}
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
