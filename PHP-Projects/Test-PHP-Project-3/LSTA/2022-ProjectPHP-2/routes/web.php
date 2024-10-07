<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', 'RegisterController@showRegistrationForm');
Route::post('/register', 'RegisterController@create');
Route::post("/uploadProfilePicture", "ImageUploadController@imageUploadPost");

Route::get('/', 'HomeController@index');

Route::get('crud', function (){
    return view('layouts.crud');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
    Route::get('auth/edit','UserController@edit');
    Route::put('auth/edit/{user}','UserController@update');
});


Route::Group(['middleware' => ['role:Mantelzorger']], function () {
    Route::get('listPatients', 'RolesMiddle\TestController@index');
});


Route::Group(['middleware' => ['role:Dokter,Mantelzorger']], function () {
    Route::get('userPatients', 'UserController@getPatients');
    Route::post('questionnaires', 'Questionnaires\QuestionnaireController@store');
    Route::get('questionnaires/{id}', 'Questionnaires\QuestionnaireController@index');
    Route::get('questionnaires/all', 'Questionnaires\QuestionnaireController@getQuestionnaires');
    Route::resource('question', 'Questionnaires\QuestionController');
});


Route::Group(['prefix'=>"questionnaires",'middleware' => ['role:Dokter,Mantelzorger,Patient']], function () {
    Route::post("/answer", 'Questionnaires\AnswerController@Create');
    Route::get('/answer/{id}', 'Questionnaires\AnswerController@Index');
    Route::post('/questions/{id}', 'Questionnaires\QuestionController@Index');
    Route::post('/progress/{id}', 'Questionnaires\QuestionnaireController@getProgress');
    Route::get('/overview/{id}','Questionnaires\QuestionnaireController@getQuestionnaires');
    Route::get('/questionnaire/{id}', 'Questionnaires\QuestionnaireController@getAnswers');


});

//Route::Group(['middleware' => ['role:Patient, MantelZorger']], function () {
//
//    Route::get('questionnaires/answer/{id}', 'Questionnaires\AnswerController@Index');
//
//});

//task management

//link to test page
Route::get('/tasktest', 'AuthPerson\TaskTestController@index');






// Agenda
Route::post("/agenda/weeklyEventsWithoutInfo/{id?}", "EventController@queryWeeklyEventsWithoutInfo");
Route::post("/agenda/weeklyEvents/{id?}", "EventController@queryWeeklyEventsWithInfo");
Route::get("/agenda/patientData/{id?}", "EventController@queryPatientData");
Route::middleware(['agenda'])->group(function () {
    Route::get('/agenda/{id}', "EventController@index");
});

// Events
Route::get("events/queryEventTypes", "EventController@queryEventTypes");
Route::get('events/queryTimeUnits', 'EventController@qryTimeUnits');
Route::get('events/queryMedications/{id}', 'EventController@qryExMedications');
Route::get('events/queryEvent/{id}', 'EventController@qryEvent');
Route::post("/events/excludeEvent", "EventController@excludeDate");
Route::get("/events/confirmEvent/{id}", "EventController@ConfirmEvent");
Route::post("/events", "EventController@store");
Route::resource('events', 'EventController');

Route::get("/user/queryCanConfirmEvents", "UserController@queryCanConfirmEvents");


Route::get('qryMedications/{id}', 'EventController@qryMedications');

Route::get('qryEvents', 'EventController@qryEvents');
Route::get('diffTest', 'EventController@diffTest');




//get all tasks
Route::get('/tasktest/qryTasks', 'EventController@qryTasks');
Route::get('qryMedication', 'PrescriptionController@qryMedicationPatient');
Route::resource('medications', 'PrescriptionController');


// test route TO DELETE
Route::get('/noty', function () {
    return view('notytest');
});



/*testing*/
Route::resource('answers', 'AnswerController');
Route::get('/debug/sendReminders', 'Debug\TaskController@sendRemindersTest');
Route::get('/debug/resetRepeat', 'Debug\TaskController@resetRepeatingTasksTest');
Route::get('/debug/repeatEx', 'Debug\TaskController@repetitionExcludedDates');
Route::get('/debug/testpol', 'Debug\TaskController@testPolicy');

/* Route to logboekBeheren */
Route::middleware(['auth'])->group(function () {
    Route::get('log/queryLogs/{search?}', 'LogController@queryLogs');
    Route::resource('log', 'LogController');
});





/*Docs api service*/
Route::get('/apiDocs', function () {
    return view('ApiDocumentation');
});
/*Send mail ==> test route*/
Route::get('/sendMail', 'MailController@SendMail');

/*test route for api service demo*/
Route::post('PostMail', 'MailController@PostMail');

// Route for the managing of patients


Route::get('patients/queryPatients', 'PatientsController@queryPatients');
Route::resource('patients', 'PatientsController');




Route::Group(['middleware' => ['role:Dokter,Mantelzorger']], function () {

    Route::get('patients/queryPatients', 'PatientsController@queryPatients');
    Route::get('patients/queryPatientRights/{id}', 'PatientsController@queryPatientRights');
    Route::post('patients/editRights/{id}', 'PatientsController@editRights');
    Route::resource('patients', 'PatientsController');
});


Route::middleware(['auth'])->group(function() {
    Route::resource('prescription', 'PrescriptionController', ['parameters' => ['prescription' => 'medicationPatient']]);
    Route::get('/qryPrescription/{id?}',  'PrescriptionController@query');
});

Route::Group(['middleware' => ['role:Dokter']], function () {
    Route::get('{id}/prescription', 'PrescriptionController@doctorIndex');
    Route::resource('/medication', 'MedicationController');
});

Route::get('/qryMedication', 'MedicationController@query');



