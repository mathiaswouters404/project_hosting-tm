const UserGuideService = (function () {

    function show(){
        $('#modal-user-guide').modal("show");
    }


    return {
        show: show,
    }
})();

export default UserGuideService;
