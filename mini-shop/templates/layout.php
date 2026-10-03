<?php
declare(strict_types=1);
use App\Adapter\In\Web\View;
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>MiniShop</title>
<style>
body { font-family: system-ui, sans-serif; max-width: 1000px; margin: 2rem auto; padding: 0 1rem; color: #172434; background: #fafbfc; }
nav { display: flex; gap: 1rem; border-bottom: 1px solid #ccd4dd; padding-bottom: 1rem; }
a { color: #125da3; } table { width: 100%; border-collapse: collapse; } th, td { text-align: left; padding: .75rem; border-bottom: 1px solid #ccd4dd; }
button, input { font: inherit; padding: .4rem; } input[type=number] { width: 5rem; } button { cursor: pointer; } article { padding: 1rem; margin: 1rem 0; background: white; border: 1px solid #ccd4dd; border-radius: .4rem; }
.table-wrap { overflow-x: auto; } form { margin: .5rem 0; }
</style></head>
<body><header><h1>MiniShop</h1><nav aria-label="Navigation principale"><a href="/products">Produits</a><a href="/cart">Panier</a><a href="/orders">Commandes</a></nav></header>
<main><?= $content ?></main></body></html>
