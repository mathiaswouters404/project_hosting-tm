let ClientSideValidation = function (){

    function validate(formId, succesCallBack = () => {}, errorCallBack = () => {}){
        if(validateStatus(formId)){
            succesCallBack(formId);
            /*reset form*/
            const id = '#' + formId;
            $(id).trigger("reset");
        } else{
            errorCallBack(formId);
        }
    }

    function validateStatus(formId) {
        return formIsValid(formId);
    }

    function formIsValid(formId){
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const form = document.getElementById(formId);
        // Loop over them and check  if valid
        return form.checkValidity();
    }

    function validateInput(input){
        if(validateInputValueEmpty(input)){
            invalidateInput(input);
            return false;
        } else{
            validateInputSucces(input);
            return true;
        }
    }

    function invalidateInput(input){
        $(input).addClass("is-invalid");
        $(input).parents("div").first().closest("invalid-feedback").addClass("d-block");
    }

    function validateInputSucces(input){
        $(input).removeClass("is-invalid");
        $(input).parents("div").first().closest("invalid-feedback").removeClass("d-block");
    }

    function validateInputValueEmpty(input){
        return $(input).val() === "";
    }

    return {
        validate: validate,
        validateStatus: validateStatus,
        validateInput: validateInput
    };
} ();

export default  ClientSideValidation;



