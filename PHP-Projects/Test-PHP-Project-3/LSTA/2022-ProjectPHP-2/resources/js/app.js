
require('./bootstrap');

window.Noty = require('noty');
Noty.overrideDefaults({
    theme: 'bootstrap-v4',
    type: 'warning',
    layout: 'center',
    modal: true,
});

import moment from "moment";
import InitBootstrap from "./init/initBootstrap";
InitBootstrap.init();
import PhpProject from "./phpProject";
import ApiService from "./helpers/ApiService";
import AgendaService from "./agenda/AgendaService";
import EventFormService from "./agenda/EventFormService";
import EventDataService from "./agenda/EventDataService";
import NewEventService from "./agenda/NewEventService";
import ViewEventService from "./agenda/ViewEventService";
import EditEventService from "./agenda/EditEventService";
import SubmitEventService from "./agenda/SubmitEventService";
import EventMedicationService from "./agenda/EventMedicationService";
import DeleteEventService from "./agenda/DeleteEventService";
import AgendaQuestionnaireService from "./agenda/AgendaQuestionnaireService";
import ConfirmEventService from "./agenda/ConfirmEventService";
import LogService from "./agenda/LogService";
import EventService from "./agenda/EventService";
import ShowEventService from "./agenda/ShowEventService";
import UserGuideService from "./agenda/UserGuideService";
import Patients from "./patients/Patients"
import QuestionnaireService from "./questionnaires/questionnaire/questionnaire";
import QuestionService from "./questionnaires/question/question";
import AnswerService from "./questionnaires/answer/answer";
import AddLog from "./logs/AddLog";
import Validator from "./validators/Validator";
import ClientSideValidation from "./validators/ClientSideValidation";
import OverviewAnswerService from "./questionnaires/answer/overviewAnswers";
import ProgressService from "./questionnaires/questionnaire/progressService";
import PrescriptionService from "./prescription/prescription";
import QuestionnaireOverviewService from "./questionnaires/questionnaire/questionnaireOverviewService"
import MedicationService from "./medication/medicationService";
import RegisterService from "./auth/RegisterService";
import EditUserService from "./auth/EditUserService";
window.moment = moment;
window.PhpProject = PhpProject;
window.ApiService = ApiService;
window.AgendaService = AgendaService;
window.EventFormService = EventFormService;
window.EventDataService = EventDataService;
window.NewEventService = NewEventService;
window.ViewEventService = ViewEventService;
window.EditEventService = EditEventService;
window.SubmitEventService = SubmitEventService;
window.EventMedicationService = EventMedicationService;
window.DeleteEventService = DeleteEventService;
window.AgendaQuestionnaireService = AgendaQuestionnaireService;
window.ConfirmEventService = ConfirmEventService;
window.LogService = LogService;
window.EventService = EventService;
window.ShowEventService = ShowEventService;
window.Patients = Patients;
window.QuestionnaireService = QuestionnaireService;
window.QuestionService = QuestionService;
window.AnswerService = AnswerService;
window.Validator = Validator;
window.AddLog = AddLog;
window.ClientSideValidation = ClientSideValidation;
window.OverviewAnswerService = OverviewAnswerService;
window.ProgressService = ProgressService;
window.PrescriptionService = PrescriptionService;
window.QuestionnaireOverviewService = QuestionnaireOverviewService;
window.UserGuideService = UserGuideService;
window.MedicationService = MedicationService;
window.RegisterService = RegisterService;
window.EditUserService = EditUserService;
