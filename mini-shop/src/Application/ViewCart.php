<?php
declare(strict_types=1);

namespace App\Application;

use App\Port\Out\CartRepository;
use App\Domain\{Product, Cart, Order};
final readonly class ViewCart
{
    public function __construct(private CartRepository $repository) {}
    public function execute(): Cart
    {
        return $this->repository->get();
    }
}
