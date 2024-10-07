<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    public function events(): HasMany
    {

        // An event type is used in multiple events
        return $this->hasMany("App/Event");
    }
}
