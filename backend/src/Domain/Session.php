<?php

declare(strict_types=1);

namespace Poker\Domain;

use Ramsey\Uuid\Uuid;

final class Session
{
    public string $id;
    public string $room;
    public string $deck;
    /** @var array<string,string> user => value */
    public array $votes = [];
    public string $status = 'open';
    public int $createdAt;

    private function __construct(string $id, string $room, string $deck, array $votes, string $status, int $createdAt)
    {
        $this->id = $id;
        $this->room = $room;
        $this->deck = $deck;
        $this->votes = $votes;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public static function create(string $room, string $deck): self
    {
        return new self(Uuid::uuid4()->toString(), $room, $deck, [], 'open', time());
    }

    public static function fromArray(array $a): self
    {
        return new self(
            $a['id'],
            $a['room'],
            $a['deck'],
            $a['votes'] ?? [],
            $a['status'] ?? 'open',
            $a['createdAt'] ?? time()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'room' => $this->room,
            'deck' => $this->deck,
            'votes' => $this->votes,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'consensus' => $this->status === 'finalized' ? $this->consensus() : null,
        ];
    }

    public function castVote(string $user, string $value): void
    {
        $this->votes[$user] = $value;
    }

    public function finalize(): void
    {
        $this->status = 'finalized';
    }

    private function consensus(): ?string
    {
        if (!$this->votes) {
            return null;
        }
        // Compute median over numeric-like values; non-numeric are ignored
        $nums = [];
        foreach ($this->votes as $v) {
            if (is_numeric($v)) {
                $nums[] = (float)$v;
            }
        }
        if (!$nums) {
            return null;
        }
        sort($nums);
        $n = count($nums);
        if ($n % 2 === 1) {
            return rtrim(rtrim(number_format($nums[intdiv($n, 2)], 2, '.', ''), '0'), '.');
        }
        $m = ($nums[$n/2 - 1] + $nums[$n/2]) / 2;
        return rtrim(rtrim(number_format($m, 2, '.', ''), '0'), '.');
    }
}
