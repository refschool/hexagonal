<?php
declare(strict_types=1);

namespace App\Domain;

final class Cart
{
    /** @var array<string, CartItem> */
    private array $items = [];
    public function addProduct(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) { throw new \InvalidArgumentException('Quantité invalide.'); }
        $quantity += isset($this->items[$product->id]) ? $this->items[$product->id]->quantity : 0;
        $this->items[$product->id] = new CartItem($product, $quantity);
    }
    public function removeProduct(string $id): void { unset($this->items[$id]); }
    public function updateQuantity(string $id, int $quantity): void
    {
        if ($quantity < 1) { throw new \InvalidArgumentException('Quantité invalide.'); }
        if (!isset($this->items[$id])) { throw new \DomainException('Article absent du panier.'); }
        $this->items[$id] = new CartItem($this->items[$id]->product, $quantity);
    }
    public function clear(): void { $this->items = []; }
    public function total(): Money
    {
        $total = new Money(0);
        foreach ($this->items as $item) { $total = $total->add($item->total()); }
        return $total;
    }
    /** @return list<CartItem> */
    public function items(): array { return array_values($this->items); }
    public function isEmpty(): bool { return $this->items === []; }
}
