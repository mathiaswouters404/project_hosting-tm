let Validator = function (){

    function validate(response, succesCallBack = () => {}, errorCallBack = () => {}){
        if(validateStatus(response)){
            succesCallBack(response);
        } else{
            errorCallBack(response);
        }
        _buildNoty(response);
    }
    function validateStatus(response) {
        return response.status;
    }

    function _buildNoty(response){
        let noty = validateStatus(response) ? _buildSuccesNoty(response) :  _buildErrorNoty(response);
        PhpProject.toast(noty);
    }

    function _buildErrorNoty(response){
        return PhpProject.buildErrorNoty(response);
    }

    function _buildSuccesNoty(response){
        return response.data ;
    }

    return {
        validate: validate,
        validateStatus: validateStatus

    };
} ();

export default Validator;



