<?php
declare(strict_types=1);

namespace App\Port\Out;

use App\Domain\Product;
interface ProductRepository
{
    /** @return list<Product> */
    public function findAll(): array;
    public function findById(string $id): ?Product;
}
