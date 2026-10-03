<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\CartRepository;
use App\Domain\{Product, Cart, Order};
final readonly class RemoveProductFromCart
{
    public function __construct(private CartRepository $repository) {}
    public function execute(string $id): Cart
    {
        $cart = $this->repository->get();
        $cart->removeProduct($id);
        $this->repository->save($cart);
        return $cart;
    }
}
