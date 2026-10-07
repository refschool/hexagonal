<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$databaseDirectory = $projectRoot . '/var';
$databasePath = $databaseDirectory . '/catalog.sqlite';
$schemaPath = $projectRoot . '/database/catalog.sql';

if (!extension_loaded('pdo_sqlite')) {
    throw new RuntimeException('The pdo_sqlite extension is required to initialize the catalogue.');
}

if (!is_dir($databaseDirectory) && !mkdir($databaseDirectory, 0777, true) && !is_dir($databaseDirectory)) {
    throw new RuntimeException('Could not create the catalogue database directory.');
}

$schema = file_get_contents($schemaPath);
if ($schema === false) {
    throw new RuntimeException('Could not read the catalogue schema.');
}

$database = new PDO('sqlite:' . $databasePath, options: [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$database->exec($schema);

echo "Catalogue initialized at {$databasePath}.\n";
