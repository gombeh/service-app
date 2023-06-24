<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\League;
use App\Models\LeagueConfig;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Rasoul',
            'email' => 'r.zinati1372@gmail.com',
        ]);

        \App\Models\User::factory(99)->create();

        LeagueConfig::insert([
            [
                'league' => 'league_a',
                'change_count' => 3,
                'total_members' => 40
            ],
            [
                'league' => 'league_b',
                'change_count' => 3,
                'total_members' => 30
            ],
            [
                'league' => 'league_c',
                'change_count' => 3,
                'total_members' => 30
            ]
        ]);

        $this->call(AclSeeder::class);
    }
}
