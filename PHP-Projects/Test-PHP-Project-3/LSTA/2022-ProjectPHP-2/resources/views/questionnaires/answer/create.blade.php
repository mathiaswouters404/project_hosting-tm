<div class="modal" id="modal-answer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" onclick="AnswerService.clearQuestions()" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="question-container">
                    <h1 id="title">Title</h1>

                    <div id="questionnaireProgress">
                        <div class="progress-custom">
                            <div class="progress-bar-custom">
                                   <span class="progress-text">
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="" id="questionsForm"  novalidate id="answer-form" >
                        @method("")
                        @csrf
                        <div id="questionContainer">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
