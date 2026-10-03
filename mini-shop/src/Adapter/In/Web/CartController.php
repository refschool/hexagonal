<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Application\{ViewCart, AddProductToCart, UpdateCartQuantity, RemoveProductFromCart};
final readonly class CartController
{
    public function __construct(private ViewCart $get, private AddProductToCart $add, private UpdateCartQuantity $update, private RemoveProductFromCart $remove, private View $view) {}
    public function index(): Response { return $this->view->render('cart/index', ['cart' => $this->get->execute()]); }
    public function add(array $data): Response
    {
        $this->add->execute(Input::id($data), Input::quantity($data));
        return Response::redirect('/cart');
    }
    public function update(array $data): Response
    {
        $this->update->execute(Input::id($data), Input::quantity($data));
        return Response::redirect('/cart');
    }
    public function remove(array $data): Response
    {
        $this->remove->execute(Input::id($data));
        return Response::redirect('/cart');
    }
}
