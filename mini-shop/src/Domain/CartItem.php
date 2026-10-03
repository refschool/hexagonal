<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class CartItem
{
    public function __construct(public Product $product, public int $quantity) {
        if ($quantity < 1) { throw new \InvalidArgumentException("Quantité invalide."); }
    }
    public function total(): Money { return $this->product->price->multiply($this->quantity); }
}
