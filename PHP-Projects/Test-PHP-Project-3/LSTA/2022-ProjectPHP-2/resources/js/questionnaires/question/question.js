let QuestionService = function () {

    let questionIndex = 0;
    let init = function () {

        addQuestion()
    };

    let addQuestion = function () {

        const question = `<div class="form-group">
            <div class="row">
                     <div class="col-9"><input type="text" name="questions[]" id="question${questionIndex}"
                                    class="form-control"
                                    placeholder="Name"
                                    minLength="3"
                                    required
                                    value=""
                                  ><div class="invalid-feedback">
                                  Vul de vraag in!
                 </div></div>

               <div class="col-3">
               <button class="btn-secondary btn w-100" onclick="QuestionService.deleteQuestion(this)">Delete</button>
               </div>
                </div>

               </div>`;
        $('#questions').append(question);
    }

    const deleteQuestion = function (e) {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }
    return {
        init: init,
        addQuestion: addQuestion,
        deleteQuestion: deleteQuestion

    };
}();

export default QuestionService;



