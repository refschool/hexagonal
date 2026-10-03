<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\ProductRepository;
use App\Domain\{Product, Cart, Order};
final readonly class GetProduct
{
    public function __construct(private ProductRepository $repository) {}
    public function execute(string $id): Product
    {
        return $this->repository->findById($id) ?? throw new NotFound('Produit absent.');
    }
}
