<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
function check(bool $condition, string $message): void {
    if (!$condition) { throw new RuntimeException($message); }
}
function rejects(callable $action): void {
    try { $action(); } catch (InvalidArgumentException|DomainException $e) { return; }
    throw new RuntimeException('Une exception était attendue.');
}
foreach (glob(__DIR__ . '/*/*Test.php') as $file) { require $file; }
echo "Tous les tests sont passés.\n";
