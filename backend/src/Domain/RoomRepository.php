
<?php

declare(strict_types=1);

namespace Poker\Domain;

final class RoomRepository
{
    /** @var array<string, Room> */
    private array $items = [];

    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /** @return list<Room> */
    public function all(): array
    {
        return array_values($this->items);
    }

    public function create(string $name): Room
    {
        $id = bin2hex(random_bytes(6));
        $room = new Room($id, $name, new \DateTimeImmutable());
        $this->items[$id] = $room;
        return $room;
    }
}
