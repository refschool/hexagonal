<?php
declare(strict_types=1);

namespace App\Port\Out;

use App\Domain\Cart;
interface CartRepository
{
    public function get(): Cart;
    public function save(Cart $cart): void;
}
