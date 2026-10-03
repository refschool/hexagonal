<?php
declare(strict_types=1);

namespace App\Adapter\Out\InMemory;

use App\Domain\{Product, Money};
use App\Port\Out\ProductRepository;
final class InMemoryProductRepository implements ProductRepository
{
    /** @var array<string, Product> */
    private array $products = [];
    /** @param list<Product>|null $products */
    public function __construct(?array $products = null)
    {
        $products ??= [
            new Product('1', 'Clavier mécanique', new Money(7900)),
            new Product('2', 'Souris sans fil', new Money(3900)),
            new Product('3', 'Écran 27 pouces', new Money(24900)),
            new Product('4', 'Webcam HD', new Money(5900)),
        ];
        foreach ($products as $product) { $this->products[$product->id] = $product; }
    }
    public function findAll(): array { return array_values($this->products); }
    public function findById(string $id): ?Product { return $this->products[$id] ?? null; }
}
