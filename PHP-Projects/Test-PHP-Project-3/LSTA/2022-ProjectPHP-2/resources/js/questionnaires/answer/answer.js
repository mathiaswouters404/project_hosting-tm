import questionnaire from "../questionnaire/questionnaire";

let AnswerService = function (){
    let token = "";
    let questionCount = 0;
    let questionnaireId;
    let _date;


    let init = function () {
        $('#questionsForm').submit(function (e){
            e.preventDefault();
        })
    };

    let create = function (body) {
        return ApiService.post('/questionnaires/answer',body,token);
    };

    let getQuestionnaire = function(id,date){
        const body = {
            "date":date
        }
        _date = date;
        const questionnaire = ApiService.post('/questionnaires/questions',body,token,id);
        return questionnaire.data;
    }

    const loadQuestions = function(questions){
        $('#questionContainer').empty();
        questions.forEach((question,index) => {
            const answer = `
                <div class="form-group" id="questionForm${index}">
            <div class="d-flex flex-wrap">
            <div class="col-12">
            <label for="question${index}">${question.description}</label>
</div>
           <div class="col-9">
                   <input type="text" name="answer" id="question${index}"
                   class="form-control "
                   placeholder="Name"
                   minLength="3"
                   required
                   value=""
                   class="col-10"
                   >
                  <div class="invalid-feedback">
                        Het antwoord moet ingevuld zijn.
                   </div></div>
                <input type="text" hidden name="question_id" value="${question.id}" id="question_id${index}">

              <div class="col-3">
               <button class="btn-dark btn w-100" onclick="AnswerService.answer(${index})">Save</button>
            </div>
                </div>

              </div>
           `;
            $('#questionContainer').append(answer);
        })
    }

    let initQuestions = function(questionnaire){
        const questions = questionnaire.questions;
        setQuestionCount(questions.length);
        checkIfCompleted(questions)

    }

    const showCompleted = function (){
        $('#questionContainer').append(
            `<h1>Completed</h1>`
        )
    }

    const checkIfCompleted = function(questions){
        if(questions.length > 0){
            loadQuestions(questions);
        } else{
            showCompleted();
        }

    }
    const deleteQuestion = function(e){
       const count = getQuestionCount();
        setQuestionCount(count - 1);
        e.parentNode.removeChild(e);
        if(count - 1 === 0){
            PhpProject.toast(PhpProject.buildNotyObject('success','questionnaire answered'));
            closeModal();
            const selector = `#questionnaires a[data-id=${questionnaireId}]`;
            $(selector).remove();
        }
    }

    const setQuestionCount = function(number) {
      questionCount = number;
    }

    const getQuestionCount = function(){
        return questionCount ;
    }

    const answer = function(question){
        const firstSelector = `#question${question}`;
        const secondSelector = `#question_id${question}`;
        const parent = `#questionForm${question}`;
        const parentEl = $(`${parent}`)[0];
        const body = $(`${firstSelector}, ${secondSelector}`).serialize() + `&date=${_date}`;


        if(ClientSideValidation.validateInput($(firstSelector))){
            createAndValidateAnswer(body, parentEl);
        }
    }

    const createAndValidateAnswer = function (body, parentEl){

        const response = AnswerService.create(body);
        Validator.validate(response ,()=>{
            AnswerService.deleteQuestion(parentEl);
            /*register in progress service*/
            ProgressService.addAnswer();
            /*refresh progress*/
            ProgressService.setProgress();
        });
    }

    const setToken = function(token2){
        token = token2;
    }

    const openQuestionnaire = function (id,date){
        openModal();
        const questionnaire = getQuestionnaire(id,date);
        setTitle(questionnaire);
        initQuestions(questionnaire);

    }

    const setTitle = function(questionnaire){
        $('#title').text(questionnaire.questionnaire.name)
    }

    const openModal = function(){
        $("#modal-answer").modal('show');
    }

    const closeModal = function(){
        $("#modal-answer").modal('hide');

    }


    return {
        init: init,
        create: create,
        initQuestions:initQuestions,
        deleteQuestion: deleteQuestion,
        answer: answer,
        setToken: setToken,
        setQuestionCount: setQuestionCount,
        getQuestionCount: getQuestionCount,
        openQuestionnaire: openQuestionnaire,

    };
} ();

export default AnswerService;



