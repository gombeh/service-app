<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_configs', function (Blueprint $table) {
            $table->id();
            $table->string('league');
            $table->integer('total_members');
            $table->integer('change_count')->nullable();
            $table->foreignId('prev_config')
                ->nullable()
                ->constrained('league_configs', 'id')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_configs');
    }
};
