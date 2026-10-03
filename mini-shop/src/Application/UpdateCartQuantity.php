<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\CartRepository;
use App\Domain\{Product, Cart, Order};
final readonly class UpdateCartQuantity
{
    public function __construct(private CartRepository $repository) {}
    public function execute(string $id, int $quantity): Cart
    {
        $cart = $this->repository->get();
        $cart->updateQuantity($id, $quantity);
        $this->repository->save($cart);
        return $cart;
    }
}
