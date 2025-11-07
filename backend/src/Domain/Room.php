<?php
declare(strict_types=1);
namespace Poker\Domain;

final class Room implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public \DateTimeImmutable $createdAt
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
