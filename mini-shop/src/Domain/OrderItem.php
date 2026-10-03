<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class OrderItem
{
    public Money $lineTotal;
    public function __construct(
        public string $productId,
        public string $productName,
        public Money $unitPrice,
        public int $quantity,
    ) {
        if ($quantity < 1) { throw new \InvalidArgumentException("Quantité invalide."); }
        $this->lineTotal = $unitPrice->multiply($quantity);
    }
}
