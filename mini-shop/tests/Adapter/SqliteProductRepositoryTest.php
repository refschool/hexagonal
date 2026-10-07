<?php
declare(strict_types=1);

use App\Adapter\Out\Sqlite\SqliteProductRepository;
use App\Application\{GetProduct, ListProducts};
use App\Domain\Product;

$sqliteDatabase = new PDO('sqlite::memory:');
$schema = file_get_contents(dirname(__DIR__, 2) . '/database/catalog.sql');
check($schema !== false, 'Schéma SQLite disponible');
$sqliteDatabase->exec($schema);

$sqliteCatalogue = new SqliteProductRepository($sqliteDatabase);
$sqliteProducts = $sqliteCatalogue->findAll();
check(count($sqliteProducts) === 4, 'Catalogue SQLite');
check(array_map(static fn (Product $product): string => $product->id, $sqliteProducts) === ['1', '2', '3', '4'], 'Ordre des produits SQLite');
check($sqliteCatalogue->findById('1')?->name === 'Clavier mécanique', 'Recherche SQLite');
check($sqliteCatalogue->findById('1')?->price->cents === 7900, 'Prix SQLite en centimes');
check($sqliteCatalogue->findById('absent') === null, 'Produit SQLite absent');

$sqliteDatabase->exec("UPDATE products SET price_cents = 8100 WHERE id = '1'");
check($sqliteCatalogue->findById('1')?->price->cents === 8100, 'Prix SQLite actualisé');
check((new ListProducts($sqliteCatalogue))->execute()[0]->price->cents === 8100, 'Liste via le port SQLite');
check((new GetProduct($sqliteCatalogue))->execute('1')->price->cents === 8100, 'Détail via le port SQLite');
