<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<h2>Commande <?= View::escape($order->id) ?></h2>
<p><?= $order->createdAt->format('d/m/Y H:i') ?> — <?= View::escape($order->status) ?></p>
<div class="table-wrap"><table><thead><tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Total ligne</th></tr></thead><tbody>
<?php foreach ($order->items as $item): ?>
<tr><td><?= View::escape($item->productName) ?></td><td><?= View::money($item->unitPrice) ?></td><td><?= $item->quantity ?></td><td><?= View::money($item->lineTotal) ?></td></tr>
<?php endforeach; ?></tbody></table></div><p><strong>Total : <?= View::money($order->total) ?></strong></p>
