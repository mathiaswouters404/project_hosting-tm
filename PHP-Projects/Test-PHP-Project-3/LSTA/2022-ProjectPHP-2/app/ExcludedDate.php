<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExcludedDate extends Model
{
    protected $fillable = [
        "date",
        "event_id"
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo("App\User", "patient_id");
    }
}
