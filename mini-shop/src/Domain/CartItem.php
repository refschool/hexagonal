<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class CartItem
{
    public function __construct(public Product $product, public int $quantity) {}
    public function total(): Money { return $this->product->price->multiply($this->quantity); }
}
