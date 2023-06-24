<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\LeagueConfig;
use App\Models\User;
use Illuminate\Console\Command;

class LeagueFactory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'league:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'League factory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leagues_count = League::count();
        if ($leagues_count === 0) {
            $leagueC = League::create([
                'name' => 'league c',
                'slug' => 'league_c',
                'start_at' => now(),
                'end_at' => now()->addMonth()
            ]);

            $users = User::get('id as user_id');
            $users->map(function ($user, $index) {
                $user->order = ++$index;
            });

            $leagueC->members()->createMany($users->toArray());
        } else {
            $workingLeagues = League::where('start_at', '<', now())->where('end_at', '>', now())->count();
            if ($workingLeagues > 0) {
                $this->error('leagues are in working');
                return Command::FAILURE;
            }

            $leagueA_config = LeagueConfig::where('league', 'league_a')->first();
            $leagueB_config = LeagueConfig::where('league', 'league_b')->first();


            if ($leagues_count === 1) {
                $leagueA = League::create([
                    'name' => 'league a',
                    'slug' => 'league_a',
                    'start_at' => now(),
                    'end_at' => now()->addMonth()
                ]);

                $leagueB = League::create([
                    'name' => 'league b',
                    'slug' => 'league_b',
                    'start_at' => now(),
                    'end_at' => now()->addMonth()
                ]);

                $leagueC = League::where('slug', 'league_c')->first();

                $leagueC->update([
                    'start_at' => now(),
                    'end_at' => now()->addMonth()
                ]);


                $members = $leagueC->members()->orderBy('order')->get(['user_id', 'score', 'order']);

                $membersLeagueA = $members->splice(0, $leagueA_config->total_members);
                $membersLeagueB = $members->splice(0, $leagueB_config->total_members);
                $membersLeagueC = $members;

                $membersLeagueA = $membersLeagueA->map(function ($member, $index) {
                    $member->score = 0;
                    $member->order = ++$index;
                    return $member;
                })->toArray();

                $membersLeagueB = $membersLeagueB->map(function ($member, $index) {
                    $member->score = 0;
                    $member->order = ++$index;
                    return $member;
                })->toArray();

                $membersLeagueC = $membersLeagueC->map(function ($member, $index) {
                    $member->score = 0;
                    $member->order = ++$index;
                    return $member;
                })->toArray();

                $leagueC->members()->delete();

                $leagueA->members()->createMany($membersLeagueA);
                $leagueB->members()->createMany($membersLeagueB);
                $leagueC->members()->createMany($membersLeagueC);

            } else {
                $leagues = League::orderBy('slug', 'desc')->get();

                $upMembers = collect();
                $downMembers = collect();

                foreach ($leagues as $league) {
                    $members = $league->members()->orderBY('order', 'ASC')->get();
                    $config = LeagueConfig::with('prevConfig')->where('league', $league->slug)->orderBy(
                        'id', 'DESC'
                    )->first();

                    $next = $league->slug === 'league_c' ? 'league_b' : 'league_a';
                    $prev = $league->slug === 'league_a' ? 'league_b' : 'league_c';
                    $balance = [];


                    if ($config->prevConfig && $config->total_members !== $members->count()) {
                        $diff = $config->total_members - $config->prevConfig->total_members;
                        if ($config->league === 'league_a') {
                            if ($diff > 0) {
                                $balance['league_a'] = $diff;
                            } else {
                                $balance['league_b'] = abs($diff);
                            }
                        }else if($config->league === 'league_c') {
                            if ($diff > 0) {
                                $balance['league_c'] = $diff;
                            } else {
                                $balance['league_b'] = abs($diff);
                            }
                        }
                    }

                    if ($league->slug !== 'league_a') {
                        $lastIndex = $config->change_count;

                        if(in_array($league->slug, array_keys($balance))) {
                            $lastIndex += $balance[$league->slug];
                        }

                        dump($lastIndex, $league->slug, $balance);

                        $deletedMembers = $members->slice(0, $lastIndex);
                        $upMembers->push([
                            'league' => $leagues->where('slug', $next)->first(),
                            'members' => $deletedMembers
                        ]);
                    }

                    if ($league->slug !== 'league_c') {
                        $lastIndex = $members->count() - 1;
                        $startIndex = $lastIndex - $config->change_count + 1;

                        if(in_array($league->slug, array_keys($balance))) {
                            $startIndex -= $balance[$league->slug];
                        }

                        dump($startIndex, $league->slug, $balance);

                        $deletedMembers = $members->slice($startIndex, $lastIndex);
                        $downMembers->push([
                            'league' => $leagues->where('slug', $prev)->first(),
                            'members' => $deletedMembers
                        ]);
                    }
                }

                dd($upMembers, $downMembers);

                $upMembers->map(function ($item) {
                    $league = $item['league'];
                    $item['members']->map(function ($member) use ($league) {
                        $member->update([
                            'league_id' => $league->id
                        ]);
                    });
                });

                $downMembers->map(function ($item) {
                    $league = $item['league'];
                    $item['members']->map(function ($member) use ($league) {
                        $member->update([
                            'league_id' => $league->id
                        ]);
                    });
                });


                foreach ($leagues as $league) {
                    $league->update([
                        'start_at' => now(),
                        'end_at' => now()->addMonth()
                    ]);

                    $members = $league->members()->get();
                    $members->map(function ($member, $index) {
                        $member->update([
                            'score' => 0,
                            'order' => ++$index
                        ]);
                    });
                }


            }


        }

        $this->info('league factory complete done!');
        return Command::SUCCESS;
    }
}
