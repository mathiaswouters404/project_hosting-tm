<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = ['name', 'date','creator_id','patient_id'];

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function questions(){
        return $this->hasMany("App\Question");
    }

    public function event(){
        return $this->hasMany("App\Event");
    }

    public static function findById($id){
        return Questionnaire::where("id","=",$id)->get()->first();
    }
    public static function  getQuestionnairesUncompleted(int $id){
     return DB::select('SELECT * FROM `questionnaires` q WHERE patient_id = ? and
                                    ((Select COUNT(id) from questions where questionnaire_id = q.id)
                                         >
                                     (Select COUNT(id) from answers where question_id in
                                    (SELECT id from questions WHERE questionnaire_id = q.id)))',[$id]);
    }

//    public static function getUnansweredQuestions(int $id){
//        return DB::select('SELECT * FROM `questions` q  WHERE q.id not in (select question_id from answers where question_id = q.id) and q.questionnaire_id = ?',[$id]);
//    }



    public static function getUnansweredQuestions($id,$date){
        return Question::select('*')->where("questionnaire_id", "=",$id)
            ->whereNotIn("id", self::getAnsweredQuestionIdQuestionnaireSubquery($id,$date))->get();
    }

    private static function getQuestionsIdQuestionnaire($id){
        return Question::select('id')->where("questionnaire_id","=",$id)->get();
    }

    private static function getQuestionsIdQuestionnaireSubquery($id){
        return Question::select('id')->where("questionnaire_id","=",$id);
    }


    private static function getAnswersIdQuestionnaireSubquery($id, $date){
        return Answer::select('id')
            ->wherein("question_id", self::getQuestionsIdQuestionnaire($id))
            ->whereDate('date','=',$date);
    }

    private static function getAnsweredQuestionIdQuestionnaireSubquery($id, $date){
        return Answer::select('question_id')
            ->wherein("question_id", self::getQuestionsIdQuestionnaire($id))
            ->whereDate('date','=',$date);
    }

    private static function getAnswersIdQuestionnaire($id, $date){
        return self::getAnswersIdQuestionnaireSubquery($id, $date)->get();
    }

    /*calculate amount answer for questionnaire in date*/
    public static function getAnswerCountQuestionnaire($id,$date){
            return self::getAnswersIdQuestionnaireSubquery($id, $date)->count();
        }

        /*get amount of questions questionnaire*/
    public static function getQuestionCountQuestionnaire($id){
        return self::getQuestionsIdQuestionnaireSubquery($id)->count();
    }

    /*get progress questionnaire on date*/
    public static function getProgressQuestionnaire($id,$date){
      $roughPercent =  (self::getAnswerCountQuestionnaire($id,$date)  / self::getQuestionCountQuestionnaire($id)) * 100;
        return round($roughPercent, 0);
    }

    /*get progress questionnaire on date*/
    public static function getProgressQuestionnaireObject($id,$date){
        $amountAnswers = self::getAnswerCountQuestionnaire($id,$date);
       $amountQuestions = self::getQuestionCountQuestionnaire($id);
        return compact('amountQuestions','amountAnswers');
    }



//
//    public static function getQuestionsWithAnswersByQuestionnaireId($id){
//        return Question::select("*")->from("questions")->with("answers")
//                ->where('questions.questionnaire_id', '=',$id)->get();
//    }

    public static  function getAnswersWithQuestionByQuestionnaireId($id){
       return Answer::select('*')->from('answers')->with('question')
            ->wherein("question_id", self::getQuestionsIdQuestionnaire($id))
            ->orderBY('date','DESC')
            ->get();
    }

    public static function getQuestionnairesPatient($id){
        return Questionnaire::where('patient_id','=',$id)->get();
    }

}


