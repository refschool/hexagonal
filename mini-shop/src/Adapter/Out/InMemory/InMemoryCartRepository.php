<?php
declare(strict_types=1);

namespace App\Adapter\Out\InMemory;

use App\Domain\Cart;
use App\Port\Out\CartRepository;
final class InMemoryCartRepository implements CartRepository
{
    private Cart $cart;
    public function __construct(?Cart $cart = null) { $this->cart = clone ($cart ?? new Cart()); }
    public function get(): Cart { return clone $this->cart; }
    public function save(Cart $cart): void { $this->cart = clone $cart; }
}
