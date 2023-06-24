<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueConfig extends Model
{
    use HasFactory;

    public function prevConfig(): BelongsTo
    {
        return $this->belongsTo(LeagueConfig::class, 'prev_config', 'id');
    }
}
