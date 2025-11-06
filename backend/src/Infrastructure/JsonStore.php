<?php

declare(strict_types=1);

namespace Poker\Infrastructure;

use Poker\Domain\Session;

final class JsonStore
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /** @return array<string, Session> */
    private function readAll(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $raw = file_get_contents($this->path);
        $data = json_decode($raw ?: "{}", true);
        $out = [];
        foreach ($data as $id => $arr) {
            $out[$id] = Session::fromArray($arr);
        }
        return $out;
    }

    /** @param array<string, Session> $all */
    private function writeAll(array $all): void
    {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $data = [];
        foreach ($all as $id => $session) {
            $data[$id] = $session->toArray();
        }
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function upsertSession(Session $session): void
    {
        $all = $this->readAll();
        $all[$session->id] = $session;
        $this->writeAll($all);
    }

    public function getSession(string $id): ?Session
    {
        $all = $this->readAll();
        return $all[$id] ?? null;
    }
}
