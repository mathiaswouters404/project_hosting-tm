const RegisterService = (function () {
    let _token;

    function init(token) {
        console.log("test")
        _token = token;
    }

    function savePicture() {
        let formData = new FormData();
        formData.append("photo", document.getElementById("profile_picture").files[0])
        formData.append("_token", _token);
        fetch('/uploadProfilePicture', {method: "POST", body: formData}).then(response => response.json())
            .then(data => {
                console.log(data["path"])
                $("#profile-picture-img").attr("src", "/storage/images/" + data["path"])
            });
    }

    return {
        init: init,
        savePicture: savePicture,
    }
})();

export default RegisterService;
