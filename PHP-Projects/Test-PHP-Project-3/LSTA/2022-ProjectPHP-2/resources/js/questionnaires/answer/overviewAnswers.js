import questionnaire from "../questionnaire/questionnaire";
import question from "../question/question";

let OverviewAnswerService = function (){
    let _id;

   const _setQuestionnaireInfo = function(id, title){
       _id = id;
       $("#title").text(title);
   }

    const loadAnswers = function () {
        /*load the html*/
        const answers = getAnswers();
        Object.keys(answers).forEach((date,version) => {

            const parent = `<div id="version${version}" >
            <div onclick="OverviewAnswerService.toggleDropdown(this)">${date}</div>
            <div id="versionQuestions${version}" style="display:none"></div>

             </div>`;
            $("#answerOverview").append(parent);

            answers[date].forEach((object,index) => {
                const question = `<div>
                    <div><span>Vraag ${index + 1}:</span><span>${object.question.description}</span></div>
                    <div><span>Antwoord:</span> <span>${object.answer}</span></div>
                 </div>`

                const selector = "#versionQuestions" + version;
                $(selector).append(question);


            })


            console.log( $("#answerOverview"))
        });
    }
    const getAnswers = function (){
      const response = ApiService.get('/questionnaires/questionnaire',"",_id);
        return response.data.questionnaires;
    }

    const openQuestionnaire = function (el){
       const id  = $(el).data("id");
       const title = $(el).data("name");
        openModal();
        _setQuestionnaireInfo(id, title);
        loadAnswers();

    }

    const openModal = function(){
        $("#modal-answer-overview").modal('show');
    }

    const closeModal = function(){
        $("#modal-answer-overview").modal('hide');
        clear();
    }

    const clear = function () {
        $('#answerOverview').empty();
    }


    const toggleDropdown = function(el) {
        $($(el).siblings()[0]).slideToggle();

    }
    return {
        openQuestionnaire: openQuestionnaire,
        closeModal: closeModal,
        openModal: openModal,
        toggleDropdown: toggleDropdown


    };
} ();

export default OverviewAnswerService;



