<?php
declare(strict_types=1);
namespace Poker\Tests;

use PHPUnit\Framework\TestCase;
use Poker\Domain\RoomRepository;

final class RoomRepositoryTest extends TestCase
{
    public function testCreateAndList(): void
    {
        $repo = RoomRepository::getInstance();
        $room = $repo->create('Sprint 42');
        $this->assertNotEmpty($room->id);
        $all = $repo->all();
        $this->assertGreaterThanOrEqual(1, count($all));
        $this->assertSame('Sprint 42', $all[0]->name);
    }
}
