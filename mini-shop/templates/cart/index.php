<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<h2>Panier</h2>
<?php if ($cart->isEmpty()): ?><p>Votre panier est vide.</p><a href="/products">Voir les produits</a>
<?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Total ligne</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($cart->items() as $item): ?>
<tr><td><?= View::escape($item->product->name) ?></td><td><?= View::money($item->product->price) ?></td><td>
<form method="post" action="/cart/update"><input type="hidden" name="productId" value="<?= View::escape($item->product->id) ?>"><label>Quantité <input type="number" name="quantity" min="1" value="<?= $item->quantity ?>" required></label><button>Modifier</button></form>
</td><td><?= View::money($item->total()) ?></td><td><form method="post" action="/cart/remove"><input type="hidden" name="productId" value="<?= View::escape($item->product->id) ?>"><button>Supprimer</button></form></td></tr>
<?php endforeach; ?>
</tbody></table></div><p><strong>Total : <?= View::money($cart->total()) ?></strong></p>
<form method="post" action="/orders"><button>Créer la commande</button></form>
<?php endif; ?>
