
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $decks = [
            ['name' => 'Fibonacci', 'cards' => ['0','1','2','3','5','8','13','21','34']],
            ['name' => 'Modified Fibonacci', 'cards' => ['0','0.5','1','2','3','5','8','13','20','40','100','?','∞','☕']],
            ['name' => 'T-shirt', 'cards' => ['XS','S','M','L','XL','?']],
        ];
        foreach ($decks as $deck) {
            $deckId = (string) Str::uuid();
            DB::table('decks')->insert([
                'id' => $deckId,
                'room_id' => null,
                'name' => $deck['name'],
                'is_system' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $i = 0;
            foreach ($deck['cards'] as $v) {
                DB::table('deck_cards')->insert([
                    'id' => (string) Str::uuid(),
                    'deck_id' => $deckId,
                    'value' => $v,
                    'sort_order' => $i++,
                ]);
            }
        }
    }
}
