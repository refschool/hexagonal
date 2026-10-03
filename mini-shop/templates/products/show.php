<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<h2><?= View::escape($product->name) ?></h2><p><?= View::money($product->price) ?></p>
<form method="post" action="/cart/add"><input type="hidden" name="productId" value="<?= View::escape($product->id) ?>">
<label>Quantité <input type="number" name="quantity" min="1" value="1" required></label><button>Ajouter au panier</button></form>
