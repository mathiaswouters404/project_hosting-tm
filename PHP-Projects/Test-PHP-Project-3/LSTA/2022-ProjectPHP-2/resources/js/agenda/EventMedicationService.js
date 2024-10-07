const EventMedicationService = (function () {
    let _selectedMedications = [];

    function addMedication() {
        const medicationInput = $(".event__name #medication");
        const selectedMedicationId = medicationInput.val();

        if (selectedMedicationId !== null && selectedMedicationId !== undefined && selectedMedicationId !== "") {
            const medication = EventDataService.getMedicationById(selectedMedicationId);
            medicationInput.val("");
            pushMedication(medication);
        }
    }

    function pushMedication(medication) {
        const medicationId = parseInt(medication["id"], 10);

        if (!_selectedMedications.includes(medicationId)) {
            _selectedMedications.push(medicationId);

            $("#medication-list").append(`
                    <li class="row mb-3" data-medication-id="${medicationId}">
                        <div class="col-11">
                            <div class="mb-1">
                                <span class="medication-list__name">${medication["name"]}</span>:
                            </div>
                            <div>
                                ${medication["description"]}
                            </div>
                        </div>
                        <div class="col-1 medication-list__remove" onclick="EventMedicationService.removeMedication(event)">
                            &times;
                        </div>
                    </li>
                `);
        }
    }

    function removeMedication(event) {
        const id = $(event.target.closest("li")).data("medication-id");
        _selectedMedications = _selectedMedications.filter(medicationId => medicationId !== id);

        $(`li[data-medication-id="${id}"]`).remove();
    }

    function clearMedications() {
        _selectedMedications = [];
        $("#medication-list").empty();
    }

    return {
        addMedication: addMedication,
        pushMedication: pushMedication,
        removeMedication: removeMedication,
        clearMedications: clearMedications,
    }
})();

export default EventMedicationService;
