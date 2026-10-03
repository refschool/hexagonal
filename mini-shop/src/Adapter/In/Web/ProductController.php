<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Application\{ListProducts, GetProduct};
final readonly class ProductController
{
    public function __construct(private ListProducts $list, private GetProduct $get, private View $view) {}
    public function index(): Response { return $this->view->render('products/index', ['products' => $this->list->execute()]); }
    public function show(string $id): Response { return $this->view->render('products/show', ['product' => $this->get->execute($id)]); }
}
