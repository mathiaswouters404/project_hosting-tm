let ProgressService = function (){
    let _token = "";
    let _questionCount = 0;
    let _answerCount = 0;

    const setToken = function (token) {
       _token = token
    };

    const fetchProgress = function(id, date){
        const body = {
            "date":date
        }
        const response = ApiService.post('/questionnaires/progress',body,_token,id);

        _questionCount = response.data.progress.amountQuestions;
        _answerCount = response.data.progress.amountAnswers;
    }

    const getProgress = function(){
        return _calculateProgress();
    }

    const _calculateProgress = function(){
        if(_questionCount !== 0){
            return _answerCount / _questionCount * 100 ;
        }
        return 0;
    }

    const _progressString = function(){
        if(_calculateProgress() % 10 === 0){
            return _calculateProgress() + '%'
        }
        return _calculateProgress().toFixed(2)  + "%";
    }
    const addAnswer = function(){
        _answerCount = _answerCount + 1;
    }

    const setProgress = function (){
        $('.progress-text').text(_progressString());
        $('.progress-bar-custom').css("width",_calculateProgress() + '%');

    }
    return {
        setToken: setToken,
        getProgress: getProgress,
        fetchProgress: fetchProgress,
        addAnswer: addAnswer,
        setProgress: setProgress



    };



} ();

export default ProgressService;



