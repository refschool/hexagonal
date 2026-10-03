<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

final class Input
{
    /** @param array<string, mixed> $data */
    public static function id(array $data): string
    {
        $id = $data['productId'] ?? null;
        if (!is_string($id) || trim($id) === '') { throw new \InvalidArgumentException('Identifiant invalide.'); }
        return $id;
    }
    /** @param array<string, mixed> $data */
    public static function quantity(array $data): int
    {
        $value = $data['quantity'] ?? null;
        if (!is_string($value) && !is_int($value)) { throw new \InvalidArgumentException('Quantité invalide.'); }
        $quantity = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($quantity === false) { throw new \InvalidArgumentException('Quantité invalide.'); }
        return $quantity;
    }
}
