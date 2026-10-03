<?php
declare(strict_types=1);

namespace App\Domain;

final class Cart
{
    /** @var array<string, CartItem> */
    private array $items = [];
    /** @return list<CartItem> */
    public function items(): array { return array_values($this->items); }
    public function isEmpty(): bool { return $this->items === []; }
}
