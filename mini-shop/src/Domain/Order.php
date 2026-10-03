<?php
declare(strict_types=1);

namespace App\Domain;

final readonly class Order
{
    public string $status;
    public Money $total;
    /** @param list<OrderItem> $items */
    public function __construct(public string $id, public array $items, public \DateTimeImmutable $createdAt)
    {
        if ($items === []) { throw new \DomainException("Panier vide."); }
        $this->status = 'CREATED';
        $total = new Money(0);
        foreach ($items as $item) { $total = $total->add($item->lineTotal); }
        $this->total = $total;
    }
}
