<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\{ProductRepository, CartRepository};
use App\Domain\Cart;
final readonly class AddProductToCart
{
    public function __construct(private ProductRepository $products, private CartRepository $carts) {}
    public function execute(string $id, int $quantity = 1): Cart
    {
        $product = $this->products->findById($id) ?? throw new NotFound('Produit absent.');
        $cart = $this->carts->get();
        $cart->addProduct($product, $quantity);
        $this->carts->save($cart);
        return $cart;
    }
}
