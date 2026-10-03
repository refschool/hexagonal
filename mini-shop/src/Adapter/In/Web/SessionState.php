<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Domain\{Cart, Order};
use App\Port\Out\{CartRepository, OrderRepository};
final class SessionState
{
    public function start(): void
    {
        if (!session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true])) {
            throw new \RuntimeException('Impossible de démarrer la session.');
        }
    }
    public function cart(): Cart { return $_SESSION['cart'] ?? new Cart(); }
    /** @return list<Order> */
    public function orders(): array { return $_SESSION['orders'] ?? []; }
    public function save(CartRepository $carts, OrderRepository $orders): void
    {
        $_SESSION['cart'] = $carts->get();
        $_SESSION['orders'] = $orders->findAll();
        session_write_close();
    }
}
