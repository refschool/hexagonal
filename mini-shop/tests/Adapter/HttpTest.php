<?php
declare(strict_types=1);
use App\Adapter\In\Web\{Input, Response};
check(Input::quantity(['quantity' => '2']) === 2, 'Lecture quantité');
foreach (['0', '-1', '1.5', 'abc', [], null, '9999999999999999999999999'] as $quantity) {
    rejects(fn () => Input::quantity(['quantity' => $quantity]));
}
rejects(fn () => Input::id(['productId' => []]));
check(Response::redirect('/cart')->status === 303, 'Redirection POST');
