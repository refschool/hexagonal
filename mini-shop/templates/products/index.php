<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<h2>Produits</h2>
<?php foreach ($products as $product): ?>
<article><h3><a href="/products/<?= View::escape($product->id) ?>"><?= View::escape($product->name) ?></a></h3>
<p><?= View::money($product->price) ?></p>
<form method="post" action="/cart/add"><input type="hidden" name="productId" value="<?= View::escape($product->id) ?>"><input type="hidden" name="quantity" value="1"><button>Ajouter au panier</button></form>
</article>
<?php endforeach; ?>
