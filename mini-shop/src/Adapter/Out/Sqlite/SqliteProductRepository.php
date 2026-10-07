<?php
declare(strict_types=1);

namespace App\Adapter\Out\Sqlite;

use App\Domain\{Money, Product};
use App\Port\Out\ProductRepository;
use PDO;

final class SqliteProductRepository implements ProductRepository
{
    public function __construct(private PDO $database) {}

    /** @return list<Product> */
    public function findAll(): array
    {
        $statement = $this->database->query(
            'SELECT id, name, price_cents FROM products ORDER BY id'
        );

        $products = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $products[] = $this->toProduct($row);
        }

        return $products;
    }

    public function findById(string $id): ?Product
    {
        $statement = $this->database->prepare(
            'SELECT id, name, price_cents FROM products WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $this->toProduct($row);
    }

    /** @param array{id: string, name: string, price_cents: int|string} $row */
    private function toProduct(array $row): Product
    {
        return new Product($row['id'], $row['name'], new Money((int) $row['price_cents']));
    }
}
