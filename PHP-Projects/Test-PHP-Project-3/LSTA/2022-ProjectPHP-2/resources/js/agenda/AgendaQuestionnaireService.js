const AgendaQuestionnaireService = (function () {
    function showAnswerForm(event) {
        // voor jou warre
       const questionnaireId = $(event.target).closest(".event").data("questionnaire-id");
        const date = $(event.target).closest(".data-event-date").data("event_date");
        AnswerService.openQuestionnaire(questionnaireId,date);
        ProgressService.fetchProgress(questionnaireId, date);
        ProgressService.setProgress();
    }

    return {
        showAnswerForm: showAnswerForm,
    }
})();

export default AgendaQuestionnaireService;
