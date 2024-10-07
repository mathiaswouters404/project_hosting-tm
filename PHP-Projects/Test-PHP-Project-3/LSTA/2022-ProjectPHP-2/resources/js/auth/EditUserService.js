import validator from "../validators/Validator";

const EditUserService = (function () {
    function submitEditUser(e) {
        e.preventDefault();

        const form = $('#edit-user-form');

        let body = form.serialize();

        const profilePictureUrl = $('#profile-picture-img').attr('src').split("/");
        const profilePictureName = profilePictureUrl[profilePictureUrl.length - 1];
        body += "&profilePicture=" + profilePictureName;

        const response = ApiService.put("/auth/edit", body, form.data("user-id"));
        validator.validate(response);

        window.location.href = "/";
    }

    return {
        submitEditUser: submitEditUser,
    }
})();

export default EditUserService;
