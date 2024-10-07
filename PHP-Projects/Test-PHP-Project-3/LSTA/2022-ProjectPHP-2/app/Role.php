<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users(){
        // A role has many users.
        return $this->hasMany("App\User");
    }
}
