let QuestionnaireService = function (){


    const init = function (user) {
        $('#questionnaireForm').submit(function (e){
            e.preventDefault();

        });

        EventDataService.initTimeUnits()

        $('#patient_id').val(user);
    };

    const _create = function (body, token) {
      return ApiService.post('/questionnaires',body,token);
    };

    const createQuestionnaire = function() {
        const body = $('#questionnaireForm').serialize();
        const response = _create(body,"{{ csrf_token() }}");
        Validator.validate(response, _hideModal);
        return Validator.validateStatus(response);
    }


    const submit = function (){
        if(ClientSideValidation.validateStatus("questionnaireForm")){
            createQuestionnaire();
        }
    }
    const _hideModal = function(){
        $("#modal-questionnaire").modal('hide');
        resetModal();

    }

    const resetModal = function () {
        $('#questionnaireForm').trigger("reset");
        $('#questionnaireForm').removeClass('was-validated');
        $('#questions').empty();
    }
    const closeModal = function (){
        _hideModal()
    }


    const toggleMode = function (e){
        const _mode = $(e.target).is(':checked');
        if(_mode){

            showRepetition();
        } else {
            hideRepetition();

        }
    }

    const hideRepetition = function () {
        $('#repetition').addClass("d-none");
    }
    const showRepetition = function () {
        $('#repetition').removeClass("d-none");
    }
    return {
        init: init,
        createQuestionnaire:createQuestionnaire,
        submit: submit,
        closeModal: closeModal,
        toggleMode: toggleMode,
        reset:resetModal


    };



} ();

export default QuestionnaireService;



