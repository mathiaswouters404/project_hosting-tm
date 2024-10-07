<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
//    protected $fillable = ['description', 'questionnaire_id'];
    public function questionnaire()
    {
        return $this->belongsTo('App\Questionnaire');
    }

    public function answers(){
        return $this->hasMany("App\Answer");
    }

    public static function findById($id){
        return Question::where("id","=",$id)->get()->first();
    }

}
