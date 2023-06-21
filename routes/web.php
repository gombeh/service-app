<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/auth', function() {
    \Illuminate\Support\Facades\Auth::login(\App\Models\User::find(1));
});

Route::get('/league-factory', function() {
    \Illuminate\Support\Facades\Artisan::call('league:factory');
});

Route::get('/update_score', function() {
    $leagues = \App\Models\League::all();

    foreach($leagues as $league) {
        $members = $league->members()->inRandomOrder()->get();

        $score = mt_rand(100, 1000);
        foreach($members as  $index => $member){
            $member->update([
                'order' => ++$index,
                'score' => $score,
            ]);
            $score--;
        }
        //end league
        $league->update([
            'end_at' => now(),
        ]);
    }
});
