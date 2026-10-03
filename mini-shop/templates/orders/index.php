<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<h2>Commandes</h2>
<?php if ($orders === []): ?><p>Aucune commande.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Identifiant</th><th>Date</th><th>Statut</th><th>Total</th><th>Détail</th></tr></thead><tbody>
<?php foreach ($orders as $order): ?>
<tr><td><?= View::escape($order->id) ?></td><td><?= $order->createdAt->format('d/m/Y H:i') ?></td><td><?= View::escape($order->status) ?></td><td><?= View::money($order->total) ?></td><td><a href="/orders/<?= View::escape($order->id) ?>">Voir</a></td></tr>
<?php endforeach; ?></tbody></table></div><?php endif; ?>
