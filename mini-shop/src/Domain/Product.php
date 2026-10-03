<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class Product
{
    public function __construct(public string $id, public string $name, public Money $price) {
        if (trim($name) === "" || $price->cents <= 0) { throw new \InvalidArgumentException("Nom ou prix invalide."); }
    }
}
