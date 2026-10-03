<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Application\{ListOrders, GetOrder, CreateOrder};
final readonly class OrderController
{
    public function __construct(private ListOrders $list, private GetOrder $get, private CreateOrder $create, private View $view) {}
    public function index(): Response { return $this->view->render('orders/index', ['orders' => $this->list->execute()]); }
    public function show(string $id): Response { return $this->view->render('orders/show', ['order' => $this->get->execute($id)]); }
    public function create(): Response { return Response::redirect('/orders/' . $this->create->execute()->id); }
}
