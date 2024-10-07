<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeUnit extends Model
{
    public function events(): HasMany
    {

        // A time unit is used in multiple events
        return $this->hasMany("App/Event");
    }
}
