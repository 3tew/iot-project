<?php

use Illuminate\Database\Seeder;

class SlotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default slot
        for ($i=1; $i <= 4; $i++) { 
            $slot = new App\Slot;
            $slot->name = strval($i);
            $slot->save();
        }
    }
}
