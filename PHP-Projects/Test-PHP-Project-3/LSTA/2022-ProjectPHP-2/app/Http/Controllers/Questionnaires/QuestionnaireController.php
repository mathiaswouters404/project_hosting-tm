<?php

namespace App\Http\Controllers\Questionnaires;


use App\Http\Controllers\Controller;
use App\Questionnaire;
use App\Services\QuestionnaireService;
use App\Services\QuestionService;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Json;

class QuestionnaireController extends Controller
{

    private $questionnaireService;
    private $questionService;


    public function __construct(QuestionnaireService $questionnaireService, QuestionService $questionService)
    {
        $this->questionnaireService = $questionnaireService;
        $this->questionService = $questionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(int $id)
    {

        $questionnaire = Questionnaire::findById($id);

        $this->authorize('view',$questionnaire);

        return view("questionnaires.questionnaire.index", compact('id') );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Questionnaire
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $this->authorize("create",Questionnaire::class);

        // Validate questionnaire $request
        $rules = [
            'name' => 'required|min:3',
            'patient_id'=>'required',
            'questions' => 'required',
            'questions.*' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Give the questionnaire a title',
            'questions.*.required' => 'Give question a title',
            'questions.required' => 'Give the questionnaire questions'
        ];

        $this->validate($request, $rules, $customMessages);



        $questionnaire =  $this->questionnaireService->create($request);
        $this->questionService->bulkCreate($request, $questionnaire);
        return Json::createJsonResponse("success","Questionnaire has been created");

    }


    public function getQuestionnaires($id){

        $questionnaire = Questionnaire::findById($id);

        $this->authorize('view',$questionnaire);

        return $this->questionnaireService->getQuestionnaires($id);
    }

    public function getProgress(int $id,Request $request){
        $questionnaire = Questionnaire::findById($id);

        $this->authorize('view',$questionnaire);

        $progress = $this->questionnaireService->getProgressQuesionnaire($id,$request);
        return compact('progress');

    }

    public function getAnswers(int $questionnaireId){
        $questionnaire = Questionnaire::findById($questionnaireId);

        $this->authorize('view',$questionnaire);

        $questionnaires = $this->questionnaireService->sortAnswers($questionnaireId);
        return compact("questionnaires");
    }


}
