
let QuestionnaireOverviewService = function (){
    let _token;
    let _user;

    const init = function () {
    };

    const setUser = function (id) {
        _user = id;
    }

    const _getQuestionnaires = function (){
        const response = ApiService.get('/questionnaires/overview',_token,_user);
        return response.data
    }

    const setToken = function(token){
        _token = token;
    }

    const loadQuestionnaires = function () {
        const questionnaires = _getQuestionnaires();
        for(let index = 0;index < questionnaires.length; index++){

            const questionnaire = questionnaires[index];
            const questionnaireElement = ` <tr class="col-12 d-flex flex-wrap justify-content-between align-items-center">
                    <td class="col-10 pl-5" style="border:none">
                     ${questionnaire.name}
                    </td>
                    <td class="col-2 d-flex flex-wrap justify-content-end">

                        <div class="btn-group btn-group-sm">

                            <button class="btn btn-outline-info info-log"
                                    data-toggle="tooltip"
                                    title="open antwoorden"
                                    data-id="${questionnaire.id}" data-name="${questionnaire.name}" onclick="OverviewAnswerService.openQuestionnaire(this)">
                                <i class="fas fa-info-circle"></i>
                                 Antwoorden
                            </button>
                        </div>
                    </td>
                </tr>`
            $("#questionnaires").append(questionnaireElement);
        }
    }



    return {
        init: init,
        setToken: setToken,
        setUser: setUser,
        loadQuestionnaires: loadQuestionnaires,


    };
} ();

export default QuestionnaireOverviewService;



