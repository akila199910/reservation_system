<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    if (Auth::check()) {
        if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.business');
        } elseif (auth()->user()->hasRole('business_user') || auth()->user()->hasRole('user')) {
            return redirect()->route('dashboard');
        }
    }

    return view('auth.login');
});

Route::get('/mail_view', function () {

    return view('mail.welcome.user');
});

Auth::routes(['register', false]);

//Forgot Password
Route::get('/forget_password', [App\Http\Controllers\Business\ForgotPasswordController::class, 'index'])->name('buiness.forget_password.index');
Route::post('/forget_password', [App\Http\Controllers\Business\ForgotPasswordController::class, 'emailcheck'])->name('buiness.forget_password.email.check');
Route::get('/forget_password/verify/{id}', [App\Http\Controllers\Business\ForgotPasswordController::class, 'forget_password_verify'])->name('buiness.forget_password.verify');
Route::get('/new_password/{id}', [App\Http\Controllers\Business\ForgotPasswordController::class, 'new_password'])->name('buiness.new_password.view');
Route::post('/new_password', [App\Http\Controllers\Business\ForgotPasswordController::class, 'password_create'])->name('buiness.password_create');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\Business\DashboardController::class, 'index'])->name('dashboard');
Route::post('dashboard/change_business', [App\Http\Controllers\Business\DashboardController::class, 'change_business'])->name('dashboard.change_business');
Route::get('dashboard/business_list', [App\Http\Controllers\Business\DashboardController::class, 'getBusinessList'])->name('dashboard.get_business_list');


Route::prefix('admin')->middleware(['auth'])->group(function () {
    //Business
    Route::get('/business', [App\Http\Controllers\Admin\BusinessController::class, 'index'])->name('admin.business');
    Route::get('/business/create', [App\Http\Controllers\Admin\BusinessController::class, 'create_form'])->name('admin.business.create.form');
    Route::post('/business/create', [App\Http\Controllers\Admin\BusinessController::class, 'create'])->name('admin.business.create');
    Route::get('/business/update/{id}', [App\Http\Controllers\Admin\BusinessController::class, 'update_form'])->name('admin.business.update.form');
    Route::post('/business/update', [App\Http\Controllers\Admin\BusinessController::class, 'update'])->name('admin.business.update');
    Route::post('/business/move_to_dashboard', [App\Http\Controllers\Admin\BusinessController::class, 'move_dashboard'])->name('admin.business.move_dashboard');
    Route::post('/business/update_status', [App\Http\Controllers\Admin\BusinessController::class, 'update_status'])->name('admin.business.update_status');
    Route::get('/business/view/{ref_no}', [App\Http\Controllers\Admin\BusinessController::class, 'view_details'])->name('admin.business.view_details');

    //Admin users create
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('admin.admin-users');
        Route::get('/create', [App\Http\Controllers\Admin\AdminUsersController::class, 'create_form'])->name('admin.admin-users.create.form');
        Route::post('/create', [App\Http\Controllers\Admin\AdminUsersController::class, 'create'])->name('admin.admin-users.create');
        Route::get('/update/{id}', [App\Http\Controllers\Admin\AdminUsersController::class, 'update_form'])->name('admin.admin-users.update.form');
        Route::post('/update', [App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->name('admin.admin-users.update');
        Route::post('/delete', [App\Http\Controllers\Admin\AdminUsersController::class, 'delete'])->name('admin.admin-users.delete');
        Route::post('/business/delete', [App\Http\Controllers\Admin\AdminUsersController::class, 'delete'])->name('admin.business.delete');
        Route::get('/view/{ref_no}', [App\Http\Controllers\Admin\AdminUsersController::class, 'view_details'])->name('admin.admin-user.view_details');
    });

    // Table Type
    Route::get('/element_types', [App\Http\Controllers\Admin\TableTypeController::class, 'index'])->name('admin.table_types');
    Route::post('/element_types/create', [App\Http\Controllers\Admin\TableTypeController::class, 'create'])->name('admin.table_types.create');
    Route::post('/element_types/update_view', [App\Http\Controllers\Admin\TableTypeController::class, 'update_view'])->name('admin.table_types.update_view');
    Route::post('/element_types/update', [App\Http\Controllers\Admin\TableTypeController::class, 'update'])->name('admin.table_types.update');
    Route::post('/element_types/delete', [App\Http\Controllers\Admin\TableTypeController::class, 'delete'])->name('admin.table_types.delete');

    // Table Type
    Route::get('/elements', [App\Http\Controllers\Admin\ElementsController::class, 'index'])->name('admin.elements');
    Route::get('/elements/create', [App\Http\Controllers\Admin\ElementsController::class, 'create_form'])->name('admin.elements.create.form');
    Route::post('/elements/create', [App\Http\Controllers\Admin\ElementsController::class, 'create'])->name('admin.elements.create');
    Route::get('/elements/update/{id}', [App\Http\Controllers\Admin\ElementsController::class, 'update_form'])->name('admin.elements.update.form');
    Route::post('/elements/update', [App\Http\Controllers\Admin\ElementsController::class, 'update'])->name('admin.elements.update');
    Route::post('/elements/delete', [App\Http\Controllers\Admin\ElementsController::class, 'delete'])->name('admin.elements.delete');
});

Route::prefix('business_user')->middleware(['auth'])->group(function () {

    //Business users create
    Route::get('/', [App\Http\Controllers\Admin\BusinessUserController::class, 'index'])->name('admin.business-users');
    Route::get('/create', [App\Http\Controllers\Admin\BusinessUserController::class, 'create_form'])->name('admin.business-users.create.form');
    Route::post('/create', [App\Http\Controllers\Admin\BusinessUserController::class, 'create'])->name('admin.business-users.create');
    Route::get('/update/{id}', [App\Http\Controllers\Admin\BusinessUserController::class, 'update_form'])->name('admin.business-users.update.form');
    Route::post('/update', [App\Http\Controllers\Admin\BusinessUserController::class, 'update'])->name('admin.business-users.update');
    Route::get('/view/{ref_no}', [App\Http\Controllers\Admin\BusinessUserController::class, 'view_details'])->name('admin.business-users.view_details');
    Route::post('/delete', [App\Http\Controllers\Admin\BusinessUserController::class, 'delete'])->name('admin.business-users.delete');
});


Route::post('/business/move_to_dashboard', [App\Http\Controllers\Admin\BusinessController::class, 'move_dashboard'])->name('admin.business.move_dashboard');


//Payhere
Route::get('/reservation/paynow/{id}', [App\Http\Controllers\PayHereController::class, 'paynow_view'])->name('payhere.reservation.payment.view');

//Payhere Payment Gateway Integration
Route::post('/payment_approved', [App\Http\Controllers\PayHereController::class, 'payment_approved'])->name('payhere.reservation.payment.approved'); //Notify Url
Route::get('/payment_cancel', [App\Http\Controllers\PayHereController::class, 'payment_cancel'])->name('payhere.reservation.payment.cancel'); //Cancel Url
Route::get('/payment_success', [App\Http\Controllers\PayHereController::class, 'payment_success'])->name('payhere.reservation.payment.success'); //Return Url

//Review
Route::get('/reservation/review_us/{id}', [App\Http\Controllers\ReviewController::class, 'index'])->name('reservation.reviewus');
Route::post('/reservation/review_us', [App\Http\Controllers\ReviewController::class, 'submit_review'])->name('reservation.reviewus.submit_review');

//set password
Route::get('/set_password/{id}', [App\Http\Controllers\PasswordController::class, 'password_view'])->name('set_password.view');
Route::post('/set_password', [App\Http\Controllers\PasswordController::class, 'password_update'])->name('set_password.update');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('forget_password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

//Reminder
Route::get('/cron/reminder_mail_text', [App\Http\Controllers\CronController::class, 'reminder_mail_text'])->name('cron.reminder_mail_text');

//Profile
Route::get('/Profile_setting', [App\Http\Controllers\Admin\ProfileController::class, 'profile'])->name('admin.profile.index');
Route::post('/Profile_setting/profile_update', [App\Http\Controllers\Admin\ProfileController::class, 'profileUpdate'])->name('admin.profile.profile_update');
Route::post('/Profile_setting/password_update', [App\Http\Controllers\Admin\ProfileController::class, 'passwordUpdate'])->name('admin.profile.password_update');
