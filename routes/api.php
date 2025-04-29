<?php

use App\Http\Controllers\ArchivedSeminarController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\Auth\SocialAuthenticationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CertificateTemplateController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Middleware\JwtMiddleware; 
use Illuminate\Support\Facades\Cache;

Route::prefix('auth')->middleware([JwtMiddleware::class])->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware([JwtMiddleware::class]);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

});
// Route::middleware(['auth:api'])->group(function () {
//     Route::put('update-profile', [AuthController::class, 'updateProfile']);
// });

Route::middleware(['auth:api'])->group(function () {
    Route::put('update-profile', [RegisteredUserController::class, 'update']);
});

Route::get('seminar/{seminar}', [SeminarController::class, 'show']);
Route::get('seminars', [SeminarController::class, 'index']);
Route::post('edit-seminar/{seminar}', [SeminarController::class, 'update']);
Route::post('create-seminar', [SeminarController::class, 'store']);
Route::delete('delete-seminar/{seminar}', [SeminarController::class, 'destroy']);



// Seminar Archiving Routes
Route::post('/seminars_archive/{id}', [ArchivedSeminarController::class, 'archive']);
Route::get('/archived_seminars', [ArchivedSeminarController::class, 'archive_display']);
Route::post('/restore_seminar/{id}', [ArchivedSeminarController::class, 'restore']);
Route::delete('/delete_archived_seminar/{id}', [ArchivedSeminarController::class, 'deleteArchived']);

// Certificate Template Archiving Routes
Route::post('/template_archive/{id}', [ArchivedSeminarController::class, 'cert_archive']);
Route::get('/archived_templates', [ArchivedSeminarController::class, 'archived_certificates']);
Route::post('/restore_template/{id}', [ArchivedSeminarController::class, 'restore_cert_template']);
Route::delete('/delete_archived_template/{id}', [ArchivedSeminarController::class, 'deleteArchivedCertTemplate']);

// Participant Routes
Route::get('/participants', [ParticipantController::class, 'index']);
// Route::post('/join-seminar', [ParticipantController::class, 'store']);
Route::post('/add-participant', [ParticipantController::class, 'store']);
Route::get('/participants/{participant}', [ParticipantController::class, 'show']);
Route::delete('/leave-seminar/{participant}', [ParticipantController::class, 'destroy']);
// Guest Routes
Route::get('guests', [GuestController::class, 'index']);
Route::post('/create-guest', [GuestController::class, 'store']);
Route::post('edit-guest/{guest}', [GuestController::class, 'update']);


// Route::post('/create', [RegisteredUserController::class, 'store']);
// Route::post('/login', [RegisteredUserController::class, 'store']);

Route::controller(SocialAuthenticationController::class)->group(function () {
    Route::get('auth/redirection/{provider}', 'authProvideRedirection');
    Route::get('auth/{provider}/callback', 'socialAuthentication');
});

Route::get('/certificate/{id}', [ParticipantController::class, 'generateCertificate']);



Route::get('/templates', [CertificateTemplateController::class, 'index']);
Route::post('/templates', [CertificateTemplateController::class, 'store']);
Route::post('/templates/{id}', [CertificateTemplateController::class, 'update']);
Route::delete('/templates/{id}', [CertificateTemplateController::class, 'destroy']);


Route::put('/transactions/{id}/update', [TransactionController::class, 'update']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/create-transaction', [TransactionController::class, 'store']);
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->name('verification.verify');

Route::post('/add-payment-methods', [PaymentMethodController::class, 'store']);
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
Route::post('/edit-payment-method/{paymentMethod}', [PaymentMethodController::class, 'update']);
Route::delete('/delete-payment-method/{paymentMethod}', [PaymentMethodController::class, 'destroy']);

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
