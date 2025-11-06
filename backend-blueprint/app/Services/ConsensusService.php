
<?php
namespace App\Services;

class ConsensusService
{
    public function median(array $values, array $deckNumeric): ?float
    {
        $nums = array_values(array_filter(array_map(function($v){ 
            return is_numeric($v) ? (float)$v : null; 
        }, $values), fn($v)=>$v!==null));
        if (!$nums) return null;
        sort($nums);
        $count = count($nums);
        $mid = intdiv($count, 2);
        if ($count % 2 === 0) return ($nums[$mid-1] + $nums[$mid]) / 2.0;
        return $nums[$mid];
    }

    public function mode(array $values): ?string
    {
        if (!$values) return null;
        $freq = [];
        foreach ($values as $v) { $freq[$v] = ($freq[$v] ?? 0) + 1; }
        arsort($freq);
        return array_key_first($freq);
    }

    public function roundToDeck(float $value, array $deckNumeric, string $strategy='nearest'): float
    {
        if (!$deckNumeric) return $value;
        $closest = $deckNumeric[0];
        $bestDelta = abs($value - $closest);
        foreach ($deckNumeric as $d) {
            $delta = abs($value - $d);
            if ($delta < $bestDelta) { $bestDelta = $delta; $closest = $d; }
        }
        if ($strategy==='up') {
            $higher = array_values(array_filter($deckNumeric, fn($d)=>$d >= $value));
            return $higher ? min($higher) : max($deckNumeric);
        }
        if ($strategy==='down') {
            $lower = array_values(array_filter($deckNumeric, fn($d)=>$d <= $value));
            return $lower ? max($lower) : min($deckNumeric);
        }
        return $closest;
    }
}
