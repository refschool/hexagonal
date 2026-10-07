<?php
declare(strict_types=1);

namespace App;

use App\Adapter\Out\InMemory\{InMemoryCartRepository, InMemoryOrderRepository};
use App\Adapter\Out\Sqlite\SqliteProductRepository;
use App\Adapter\In\Web\{Router, View, ProductController, CartController, OrderController};
use App\Application\{ListProducts, GetProduct, AddProductToCart, RemoveProductFromCart, UpdateCartQuantity, ViewCart, CreateOrder, ListOrders, GetOrder};
use App\Domain\{Cart, Order};
use App\Port\Out\{CartRepository, OrderRepository, ProductRepository};
final readonly class CompositionRoot
{
    public Router $router;
    public CartRepository $carts;
    public OrderRepository $orders;
    /** @param list<Order> $orders */
    public function __construct(?Cart $cart = null, array $orders = [], ?ProductRepository $productRepository = null)
    {
        if ($productRepository === null) {
            $databasePath = dirname(__DIR__) . '/var/catalog.sqlite';
            if (!is_file($databasePath)) {
                throw new \RuntimeException('Catalogue absent. Exécutez php scripts/init-catalog.php.');
            }

            $database = new \PDO('sqlite:' . $databasePath, options: [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $productRepository = new SqliteProductRepository($database);
        }

        $products = $productRepository;
        $this->carts = new InMemoryCartRepository($cart);
        $this->orders = new InMemoryOrderRepository($orders);
        $view = new View(dirname(__DIR__) . '/templates');
        $this->router = new Router(
            new ProductController(new ListProducts($products), new GetProduct($products), $view),
            new CartController(
                new ViewCart($this->carts),
                new AddProductToCart($products, $this->carts),
                new UpdateCartQuantity($this->carts),
                new RemoveProductFromCart($this->carts),
                $view,
            ),
            new OrderController(new ListOrders($this->orders), new GetOrder($this->orders), new CreateOrder($this->carts, $this->orders), $view),
        );
    }
}
