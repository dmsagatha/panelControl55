<?php

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run()
    {
        factory(Team::class)->create(['name' => 'Styde']);

        factory(Team::class)->times(99)->create();
    }
}
