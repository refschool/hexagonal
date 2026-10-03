<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class Money
{
    public function __construct(public int $cents) {
        if ($cents < 0) { throw new \InvalidArgumentException("Montant négatif."); }
    }
    public function add(self $other): self { return new self($this->cents + $other->cents); }
    public function multiply(int $quantity): self { return new self($this->cents * $quantity); }
}
