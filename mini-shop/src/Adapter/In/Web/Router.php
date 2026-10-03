<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Application\NotFound;
final readonly class Router
{
    public function __construct(private ProductController $products, private CartController $cart, private OrderController $orders) {}
    public function dispatch(string $method, string $path, array $data = []): Response
    {
        try {
            if ($method === 'GET') {
                if ($path === '/' || $path === '/products') { return $this->products->index(); }
                if (preg_match('#^/products/([^/]+)$#', $path, $match)) { return $this->products->show($match[1]); }
                if ($path === '/cart') { return $this->cart->index(); }
                if ($path === '/orders') { return $this->orders->index(); }
                if (preg_match('#^/orders/([^/]+)$#', $path, $match)) { return $this->orders->show($match[1]); }
            }
            if ($method === 'POST') {
                if ($path === '/cart/add') { return $this->cart->add($data); }
                if ($path === '/cart/update') { return $this->cart->update($data); }
                if ($path === '/cart/remove') { return $this->cart->remove($data); }
                if ($path === '/orders') { return $this->orders->create(); }
            }
            return new Response('<h1>Page introuvable</h1>', 404);
        } catch (NotFound $error) {
            return new Response('<h1>' . View::escape($error->getMessage()) . '</h1>', 404);
        } catch (\InvalidArgumentException | \DomainException $error) {
            return new Response('<h1>' . View::escape($error->getMessage()) . '</h1>', 400);
        }
    }
}
