<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Business Routes
|--------------------------------------------------------------------------
|
| This Route related to business
|
*/

Route::middleware(['auth', 'isUserExist', 'isCompanyId'])->group(function () {

    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Business\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/graph', [App\Http\Controllers\Business\DashboardController::class, 'graph'])->name('dashboard.graph');
    Route::post('/dashboard/get_reservation', [App\Http\Controllers\Business\DashboardController::class, 'get_reservation'])->name('dashboard.get_reservation');
    Route::get('/dashboard/get_reservation_list', [App\Http\Controllers\Business\DashboardController::class, 'get_reservation_list'])->name('dashboard.get_reservation_list');

    //User Management
    Route::get('/users', [App\Http\Controllers\Business\UserManageController::class, 'index'])->name('business.users');
    Route::get('/users/create', [App\Http\Controllers\Business\UserManageController::class, 'create_form'])->name('business.users.create.form');
    Route::post('/users/create', [App\Http\Controllers\Business\UserManageController::class, 'create'])->name('business.users.create');
    Route::get('/users/update/{ref_no}', [App\Http\Controllers\Business\UserManageController::class, 'update_form'])->name('business.users.update.form');
    Route::post('/users/update', [App\Http\Controllers\Business\UserManageController::class, 'update'])->name('business.users.update');
    Route::get('/users/view/{ref_no}', [App\Http\Controllers\Business\UserManageController::class, 'view_details'])->name('business.users.view_details');
    Route::post('/users/delete', [App\Http\Controllers\Business\UserManageController::class, 'delete'])->name('business.users.delete');

    //Table section
    Route::get('/section', [App\Http\Controllers\Business\TablePreferenceController::class, 'index'])->name('business.preference');
    Route::get('/section/create', [App\Http\Controllers\Business\TablePreferenceController::class, 'create_form'])->name('business.preference.create.form');
    Route::post('/section/create', [App\Http\Controllers\Business\TablePreferenceController::class, 'create'])->name('business.preference.create');
    Route::get('/section/update/{id}', [App\Http\Controllers\Business\TablePreferenceController::class, 'update_form'])->name('business.preference.update.form');
    Route::post('/section/update', [App\Http\Controllers\Business\TablePreferenceController::class, 'update'])->name('business.preference.update');
    Route::get('/section/view/{ref_no}', [App\Http\Controllers\Business\TablePreferenceController::class, 'view_details'])->name('business.preference.view_details');
    Route::post('/section/delete', [App\Http\Controllers\Business\TablePreferenceController::class, 'delete'])->name('business.preference.delete');

    //cafes
    Route::get('/cafe_table', [App\Http\Controllers\Business\CafeTableController::class, 'index'])->name('business.cafe');
    Route::get('/cafe_table/create', [App\Http\Controllers\Business\CafeTableController::class, 'create_form'])->name('business.cafe.create.form');
    Route::post('/cafe_table/create', [App\Http\Controllers\Business\CafeTableController::class, 'create'])->name('business.cafe.create');
    Route::get('/cafe_table/update/{id}', [App\Http\Controllers\Business\CafeTableController::class, 'update_form'])->name('business.cafe.update.form');
    Route::post('/cafe_table/update', [App\Http\Controllers\Business\CafeTableController::class, 'update'])->name('business.cafe.update');
    Route::get('/cafe_table/view/{ref_no}', [App\Http\Controllers\Business\CafeTableController::class, 'view_details'])->name('business.cafe.view_details');
    Route::post('/cafe_table/delete', [App\Http\Controllers\Business\CafeTableController::class, 'delete'])->name('business.cafe.delete');
    Route::get('get-section/{sectionId}', [App\Http\Controllers\Business\CafeTableController::class, 'getSection'])->name('business.cafe.sectionid');
    Route::get('/cafe_table/get_elements', [App\Http\Controllers\Business\CafeTableController::class, 'get_elements'])->name('business.cafe.get_elements');
    Route::get('/cafe_table/filter_elements', [App\Http\Controllers\Business\CafeTableController::class, 'filter_elements'])->name('business.cafe.filter_elements');
    Route::post('/cafe_table/store_element_id', [App\Http\Controllers\Business\CafeTableController::class, 'store_element_id'])->name('business.cafe.store_element_id'); // This is for storing selected element id to session
    Route::post('/cafe_table/get_stored_element_id', [App\Http\Controllers\Business\CafeTableController::class, 'get_stored_element_id'])->name('business.cafe.get_stored_element_id');

    //reports
    Route::get('/cafe_table/reports', [App\Http\Controllers\Business\ReportController::class, 'index'])->name('cafe.reports');
    //Route::post('cafe_table/reports/download', [App\Http\Controllers\Business\ReportController::class, 'downloadReport'])->name('cafe.reports.download');
    Route::post('/reports/download', [App\Http\Controllers\Business\ReportController::class, 'report'])->name('cafe.reports.download');
    Route::get('/line-chart', [App\Http\Controllers\Business\ReportController::class, 'graph'])->name('graph');
    Route::get('/line-chart/table', [App\Http\Controllers\Business\ReportController::class, 'graphTable'])->name('graph.table');

    //Clients
    Route::get('/clients', [App\Http\Controllers\Business\ClientController::class, 'index'])->name('business.clients');
    Route::get('/clients/create', [App\Http\Controllers\Business\ClientController::class, 'create_form'])->name('business.clients.create.form');
    Route::post('/clients/create', [App\Http\Controllers\Business\ClientController::class, 'create'])->name('business.clients.create');
    Route::get('/clients/update/{id}', [App\Http\Controllers\Business\ClientController::class, 'update_form'])->name('business.clients.update.form');
    Route::post('/clients/update', [App\Http\Controllers\Business\ClientController::class, 'update'])->name('business.clients.update');
    Route::get('/clients/view/{ref_no}', [App\Http\Controllers\Business\ClientController::class, 'view_details'])->name('business.clients.view_details');
    Route::post('/clients/delete', [App\Http\Controllers\Business\ClientController::class, 'delete'])->name('business.clients.delete');

    //settings
        //notifications
    Route::get('/notifications', [App\Http\Controllers\Business\NotificationSettingController::class, 'notifications'])->name('business.settings.notifications');
    Route::post('/settings/notification_update', [App\Http\Controllers\Business\NotificationSettingController::class, 'update'])->name('settings.notification_update');

        //profile
    Route::get('/profile', [App\Http\Controllers\Business\NotificationSettingController::class, 'profile'])->name('business.settings.profile');
    Route::post('/settings/profile_update', [App\Http\Controllers\Business\NotificationSettingController::class, 'profileUpdate'])->name('settings.profile_update');
    Route::post('/settings/password_update', [App\Http\Controllers\Business\NotificationSettingController::class, 'passwordUpdate'])->name('settings.password_update');

    //Reservation Management
    //List
    Route::get('/reservation', [App\Http\Controllers\Business\ReservationController::class, 'index'])->name('business.reservation');
    Route::get('/reservation/create', [App\Http\Controllers\Business\ReservationController::class, 'create_form'])->name('business.reservation.create.form');
    Route::post('/reservation/get_available_table', [App\Http\Controllers\Business\ReservationController::class, 'get_available_table'])->name('business.reservation.get_available_table');
    Route::post('/reservation/table_details', [App\Http\Controllers\Business\ReservationController::class, 'table_details'])->name('business.reservation.table_details');
    Route::post('/reservation/get_existing_booking', [App\Http\Controllers\Business\ReservationController::class, 'get_existing_booking'])->name('business.reservation.get_existing_booking');

    Route::post('/reservation/create', [App\Http\Controllers\Business\ReservationController::class, 'create'])->name('business.reservation.create');
    Route::get('/reservation/update/{ref_no}', [App\Http\Controllers\Business\ReservationController::class, 'update_form'])->name('business.reservation.update.form');
    Route::post('/reservation/change_status', [App\Http\Controllers\Business\ReservationController::class, 'change_status'])->name('business.reservation.change_status');

    Route::post('/reservation/update', [App\Http\Controllers\Business\ReservationController::class, 'update'])->name('business.reservation.update');
    Route::get('/reservation/view/{ref_no}', [App\Http\Controllers\Business\ReservationController::class, 'view_details'])->name('business.reservation.view_details');
    Route::post('/reservation/delete', [App\Http\Controllers\Business\ReservationController::class, 'delete'])->name('business.reservation.delete');

    Route::post('/reservation/get_section', [App\Http\Controllers\Business\ReservationController::class, 'get_section'])->name('business.reservation.get_section'); //returning the section based on location
    Route::post('/reservation/get_update_data', [App\Http\Controllers\Business\ReservationController::class, 'get_update_data'])->name('business.reservation.get_update_data'); //returning the section based on location

    Route::post('/reservation/get_payment_status', [App\Http\Controllers\Business\ReservationController::class, 'get_payment_status'])->name('business.reservation.get_payment_status'); //returning the section based on location
    Route::post('/reservation/update_payment_status', [App\Http\Controllers\Business\ReservationController::class, 'update_payment_status'])->name('business.reservation.update_payment_status');

    //Calendar
    Route::get('/reservation/calendar', [App\Http\Controllers\Business\CalendarController::class, 'index'])->name('business.calendar');
    Route::get('/reservation/get_resources', [App\Http\Controllers\Business\CalendarController::class, 'get_resources'])->name('business.calendar.get_resources'); //Tables
    Route::get('/reservation/get_events', [App\Http\Controllers\Business\CalendarController::class, 'get_events'])->name('business.calendar.get_events');   //Reservation
    Route::get('/reservation/get_location_work_hours', [App\Http\Controllers\Business\CalendarController::class, 'get_location_work_hours'])->name('business.calendar.get_location_work_hours');

    // FloorPlan
    Route::get('/reservation/floorplan', [App\Http\Controllers\Business\FloorReservationController::class, 'index'])->name('business.floor');




    Route::post('/reservation/change_location', [App\Http\Controllers\Business\CalendarController::class, 'change_location'])->name('business.calendar.change_location');
    Route::post('/reservation/change_preference', [App\Http\Controllers\Business\CalendarController::class, 'change_preference'])->name('business.calendar.change_preference');
    Route::post('/reservation/get_detail', [App\Http\Controllers\Business\CalendarController::class, 'get_detail'])->name('business.calendar.get_detail');
    Route::post('/reservation/validations', [App\Http\Controllers\Business\CalendarController::class, 'validations'])->name('business.calendar.validations');
    Route::post('/reservation/update_view', [App\Http\Controllers\Business\CalendarController::class, 'update_view'])->name('business.calendar.update_view');

    //Locations
    Route::get('/location', [App\Http\Controllers\Business\LocationController::class, 'index'])->name('business.Locations');
    Route::get('/locations/create', [App\Http\Controllers\Business\LocationController::class, 'create_form'])->name('business.Locations.create.form');
    Route::post('/location/create', [App\Http\Controllers\Business\LocationController::class, 'create'])->name('business.Locations.create');
    Route::get('/location/update/{id}', [App\Http\Controllers\Business\LocationController::class, 'update_form'])->name('business.Locations.update.form');
    Route::post('/location/update', [App\Http\Controllers\Business\LocationController::class, 'update'])->name('business.Locations.update');
    Route::get('/location/view/{ref_no}', [App\Http\Controllers\Business\LocationController::class, 'view_details'])->name('business.Locations.view_details');
    Route::post('/location/delete', [App\Http\Controllers\Business\LocationController::class, 'delete'])->name('business.Locations.delete');

    //Floor Plan
    Route::get('/floor_plan', [App\Http\Controllers\Business\FloorPlanController::class, 'index'])->name('business.floor_plan');
    Route::get('/floor_plan/create', [App\Http\Controllers\Business\FloorPlanController::class, 'create_form'])->name('business.floor_plan.create.form');
    Route::get('/floor_plan/get_floor_layout', [App\Http\Controllers\Business\FloorPlanController::class, 'get_floor_layout'])->name('business.floor_plan.get_floor_layout');
    Route::post('/floor_plan/create', [App\Http\Controllers\Business\FloorPlanController::class, 'create'])->name('business.floor_plan.create');
    Route::get('/floor_plan/update/{id}', [App\Http\Controllers\Business\FloorPlanController::class, 'update_form'])->name('business.floor_plan.update.form');
    Route::post('/floor_plan/update', [App\Http\Controllers\Business\FloorPlanController::class, 'update'])->name('business.floor_plan.update');
    Route::get('/floor_plan/view/{ref_no}', [App\Http\Controllers\Business\FloorPlanController::class, 'view_details'])->name('business.floor_plan.view_details');
    Route::post('/floor_plan/delete', [App\Http\Controllers\Business\FloorPlanController::class, 'delete'])->name('business.floor_plan.delete');

    //IntakeForm
    Route::get('intake_form',[App\Http\Controllers\Business\IntakeFormController::class,'index'])->name('business.IntakeForm.index');
    Route::get('intake_form/create',[App\Http\Controllers\Business\IntakeFormController::class,'create_form'])->name('business.IntakeForm.create.form');
    Route::post('intake_form/create',[App\Http\Controllers\Business\IntakeFormController::class,'create'])->name('business.IntakeForm.create');
    Route::get('intake_form/update/{id}',[App\Http\Controllers\Business\IntakeFormController::class,'update_form'])->name('business.IntakeForm.update.form');
    Route::post('intake_form/update',[App\Http\Controllers\Business\IntakeFormController::class,'update'])->name('business.IntakeForm.update');
    Route::get('intake_form/view/{id}',[App\Http\Controllers\Business\IntakeFormController::class,'view_details'])->name('business.IntakeForm.view_details');
    Route::post('intake_form/delete',[App\Http\Controllers\Business\IntakeFormController::class,'delete'])->name('business.IntakeForm.delete');
});
